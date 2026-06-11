<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\PartnerPackageReminder;
use App\Models\User;
use App\Notifications\PartnerPackageExpiringNotification;

class PackageExpiryNotifier
{
    public function notifyAdmins(): int
    {
        $notified = 0;

        $partners = Partner::where('is_active', true)
            ->whereNotNull('renewal_date')
            ->whereBetween('renewal_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
            ->get();

        $admins = User::whereIn('role', ['super_admin', 'admin'])
            ->where('is_active', true)
            ->get();

        foreach ($partners as $partner) {
            $reminder = PartnerPackageReminder::firstOrCreate(
                [
                    'partner_id' => $partner->id,
                    'remind_on' => $partner->renewal_date->toDateString(),
                    'type' => 'expires_in_7_days',
                ],
                [
                    'notes' => "{$partner->company_name} package expires on {$partner->renewal_date->toDateString()}.",
                ]
            );

            if ($reminder->notified_at) {
                continue;
            }

            foreach ($admins as $admin) {
                $admin->notify(new PartnerPackageExpiringNotification($partner));
            }

            $reminder->update(['notified_at' => now()]);
            $notified++;
        }

        return $notified;
    }
}
