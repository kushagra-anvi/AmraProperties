<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyEnquiry extends Model
{
    protected $fillable = [
        'property_id',
        'partner_id',
        'seller_partner_id',
        'assigned_sales_person_id',
        'name',
        'phone',
        'email',
        'message',
        'source',
        'intent',
        'status',
        'revealed_at',
    ];

    protected function casts(): array
    {
        return [
            'revealed_at' => 'datetime',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function sellerPartner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'seller_partner_id');
    }

    public function assignedSalesPerson(): BelongsTo
    {
        return $this->belongsTo(SalesPerson::class, 'assigned_sales_person_id');
    }
}
