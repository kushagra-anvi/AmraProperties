<?php

namespace App\Notifications;

use App\Models\Partner;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PartnerPackageExpiringNotification extends Notification
{
    use Queueable;

    public function __construct(public Partner $partner)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'partner_id' => $this->partner->id,
            'partner_name' => $this->partner->company_name,
            'package' => $this->partner->package,
            'renewal_date' => optional($this->partner->renewal_date)->toDateString(),
            'message' => "{$this->partner->company_name} package expires within 7 days.",
            'url' => route('crm.partners.show', $this->partner->id),
        ];
    }
}
