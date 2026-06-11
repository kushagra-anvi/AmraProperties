<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesPerson extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'location',
        'service_areas',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'service_areas' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function b2bLeads(): HasMany
    {
        return $this->hasMany(B2BLead::class, 'assigned_sales_person_id');
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class, 'assigned_sales_person_id');
    }
}
