<?php

namespace App\Services;

use App\Models\B2CLead;
use App\Models\B2CLeadShare;
use App\Models\LeadStatusLog;
use App\Models\Partner;
use App\Models\SalesPerson;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class B2CLeadSharingService
{
    public function share(
        Collection $leads,
        array $partnerIds,
        array $salesPersonIds,
        ?int $sharedByUserId,
        ?string $remark = null
    ): int {
        $partners = Partner::where('is_active', true)->whereIn('id', $partnerIds)->get();
        $salesPeople = SalesPerson::where('is_active', true)->whereIn('id', $salesPersonIds)->get();

        return DB::transaction(function () use ($leads, $partners, $salesPeople, $sharedByUserId, $remark) {
            $count = 0;

            foreach ($leads as $lead) {
                foreach ($partners as $partner) {
                    B2CLeadShare::create([
                        'b2_c_lead_id' => $lead->id,
                        'partner_id' => $partner->id,
                        'recipient_type' => $partner->type === 'developer' ? 'builder' : 'agent',
                        'recipient_name' => $partner->company_name,
                        'recipient_contact' => $partner->phone,
                        'shared_by_user_id' => $sharedByUserId,
                        'shared_at' => now(),
                        'admin_notes' => $remark,
                    ]);
                    $count++;
                }

                foreach ($salesPeople as $salesPerson) {
                    B2CLeadShare::create([
                        'b2_c_lead_id' => $lead->id,
                        'sales_person_id' => $salesPerson->id,
                        'recipient_type' => 'sales_team',
                        'recipient_name' => $salesPerson->name,
                        'recipient_contact' => $salesPerson->phone,
                        'shared_by_user_id' => $sharedByUserId,
                        'shared_at' => now(),
                        'admin_notes' => $remark,
                    ]);
                    $count++;
                }

                if (($partners->isNotEmpty() || $salesPeople->isNotEmpty()) && in_array($lead->status, ['new', 'filtered'], true)) {
                    $oldStatus = $lead->status;
                    $lead->update(['status' => 'shared']);
                    LeadStatusLog::create([
                        'lead_type' => B2CLead::class,
                        'lead_id' => $lead->id,
                        'from_status' => $oldStatus,
                        'to_status' => 'shared',
                        'changed_by_user_id' => $sharedByUserId,
                        'notes' => 'Lead shared with ' . ($partners->count() + $salesPeople->count()) . ' recipient(s).',
                    ]);
                }
            }

            return $count;
        });
    }
}
