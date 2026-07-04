<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'company_name',
        'contact_person',
        'phone',
        'email',
        'office_address',
        'service_areas',
        'city',
        'package',
        'paid_amount',
        'package_purchase_date',
        'renewal_date',
        'assigned_sales_person_id',
        'lead_source',
        'remark',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'service_areas' => 'array',
            'paid_amount' => 'decimal:2',
            'package_purchase_date' => 'date',
            'renewal_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedSalesPerson(): BelongsTo
    {
        return $this->belongsTo(SalesPerson::class, 'assigned_sales_person_id');
    }

    public function b2cLeadShares(): HasMany
    {
        return $this->hasMany(B2CLeadShare::class);
    }

    public function packageReminders(): HasMany
    {
        return $this->hasMany(PartnerPackageReminder::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function assignedProperties(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'partner_property');
    }

    public function propertyEnquiries(): HasMany
    {
        return $this->hasMany(PropertyEnquiry::class);
    }

    public function sellerEnquiries(): HasMany
    {
        return $this->hasMany(PropertyEnquiry::class, 'seller_partner_id');
    }
}
