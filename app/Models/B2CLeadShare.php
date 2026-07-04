<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class B2CLeadShare extends Model
{
    protected $fillable = [
        'b2_c_lead_id',
        'partner_id',
        'sales_person_id',
        'recipient_type',
        'recipient_name',
        'recipient_contact',
        'shared_by_user_id',
        'shared_at',
        'remark',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'shared_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (B2CLeadShare $share) {
            if ($share->sales_person_id && !$share->recipient_name) {
                $salesPerson = SalesPerson::find($share->sales_person_id);
                $share->recipient_type = 'sales_team';
                $share->recipient_name = $salesPerson?->name;
                $share->recipient_contact = $salesPerson?->phone;
            } elseif ($share->partner_id && !$share->recipient_name) {
                $partner = Partner::find($share->partner_id);
                $share->recipient_type = $partner?->type === 'developer' ? 'builder' : 'agent';
                $share->recipient_name = $partner?->company_name;
                $share->recipient_contact = $partner?->phone;
            }
        });
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(B2CLead::class, 'b2_c_lead_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(SalesPerson::class);
    }

    public function getRecipientDisplayNameAttribute(): string
    {
        return $this->recipient_name
            ?? $this->partner?->company_name
            ?? $this->salesPerson?->name
            ?? 'Unknown recipient';
    }

    public function sharedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by_user_id');
    }

    public function remarks(): HasMany
    {
        return $this->hasMany(B2CLeadShareRemark::class, 'b2_c_lead_share_id')->latest();
    }
}
