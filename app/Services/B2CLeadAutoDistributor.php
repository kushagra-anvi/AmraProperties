<?php

namespace App\Services;

use App\Models\B2CLead;
use App\Models\B2CLeadShare;
use App\Models\LeadStatusLog;
use App\Models\Partner;
use Illuminate\Support\Collection;

class B2CLeadAutoDistributor
{
    /**
     * Auto-share a B2C lead to package-eligible partners.
     */
    public function distribute(B2CLead $lead, ?int $userId = null, string $trigger = 'auto'): Collection
    {
        if (!config('crm.auto_distribution.enabled', true)) {
            return collect();
        }

        $partners = $this->eligiblePartners($lead);
        $selectedPartners = $this->selectPartnersByPackage($partners);

        if ($selectedPartners->isEmpty()) {
            return collect();
        }

        foreach ($selectedPartners as $partner) {
            B2CLeadShare::create([
                'b2_c_lead_id' => $lead->id,
                'partner_id' => $partner->id,
                'shared_by_user_id' => $userId,
                'shared_at' => now(),
                'remark' => 'Auto distributed by package rules: ' . ucfirst($partner->package) . ' package. Trigger: ' . $trigger . '.',
            ]);
        }

        if (in_array($lead->status, ['new', 'filtered'], true)) {
            $oldStatus = $lead->status;
            $lead->update(['status' => 'shared']);

            LeadStatusLog::create([
                'lead_type' => B2CLead::class,
                'lead_id' => $lead->id,
                'from_status' => $oldStatus,
                'to_status' => 'shared',
                'changed_by_user_id' => $userId,
                'notes' => 'Auto-distributed to ' . $selectedPartners->count() . ' package-eligible partner(s): ' . $selectedPartners->pluck('company_name')->implode(', ') . '.',
            ]);
        }

        return $selectedPartners->values();
    }

    private function eligiblePartners(B2CLead $lead): Collection
    {
        $lookbackDays = (int) config('crm.auto_distribution.lookback_days', 30);
        $packageLimits = config('crm.auto_distribution.package_limits', []);
        $packagePriority = config('crm.auto_distribution.package_priority', []);

        return Partner::query()
            ->where('is_active', true)
            ->whereIn('type', array_keys(config('crm.partner_types', [])))
            ->whereNotIn('package', ['free'])
            ->whereDoesntHave('b2cLeadShares', function ($query) use ($lead) {
                $query->where('b2_c_lead_id', $lead->id);
            })
            ->withCount([
                'b2cLeadShares as recent_share_count' => function ($query) use ($lookbackDays) {
                    $query->where('shared_at', '>=', now()->subDays($lookbackDays));
                },
            ])
            ->get()
            ->filter(function (Partner $partner) use ($lead, $packageLimits) {
                return ($packageLimits[$partner->package] ?? 0) > 0
                    && $this->matchesLeadLocation($partner, $lead);
            })
            ->sortBy([
                fn (Partner $a, Partner $b) => ($packagePriority[$b->package] ?? 0) <=> ($packagePriority[$a->package] ?? 0),
                fn (Partner $a, Partner $b) => ($a->recent_share_count ?? 0) <=> ($b->recent_share_count ?? 0),
                fn (Partner $a, Partner $b) => strcmp($a->company_name, $b->company_name),
            ])
            ->values();
    }

    private function selectPartnersByPackage(Collection $partners): Collection
    {
        $packageLimits = config('crm.auto_distribution.package_limits', []);

        return $partners
            ->groupBy('package')
            ->flatMap(function (Collection $group, string $package) use ($packageLimits) {
                return $group->take((int) ($packageLimits[$package] ?? 0));
            })
            ->sortBy([
                fn (Partner $a, Partner $b) => ($a->recent_share_count ?? 0) <=> ($b->recent_share_count ?? 0),
                fn (Partner $a, Partner $b) => strcmp($a->company_name, $b->company_name),
            ])
            ->values();
    }

    private function matchesLeadLocation(Partner $partner, B2CLead $lead): bool
    {
        $needles = collect([$lead->city])
            ->merge($lead->preferred_locations ?? [])
            ->filter()
            ->map(fn (string $value) => strtolower(trim($value)))
            ->unique();

        if ($needles->isEmpty()) {
            return true;
        }

        $partnerLocations = collect([$partner->city])
            ->merge($partner->service_areas ?? [])
            ->filter()
            ->map(fn (string $value) => strtolower(trim($value)));

        return $needles->contains(function (string $needle) use ($partnerLocations) {
            return $partnerLocations->contains(function (string $location) use ($needle) {
                return $location === $needle
                    || str_contains($location, $needle)
                    || str_contains($needle, $location);
            });
        });
    }
}
