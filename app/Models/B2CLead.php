<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class B2CLead extends Model
{
    protected $fillable = [
        'source_platform',
        'lead_created_at',
        'name',
        'phone',
        'email',
        'city',
        'pincode',
        'budget_min',
        'budget_max',
        'preferred_locations',
        'property_type',
        'configuration',
        'status',
        'remark',
        'assigned_sales_person_id',
    ];

    protected function casts(): array
    {
        return [
            'lead_created_at' => 'datetime',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'preferred_locations' => 'array',
        ];
    }

    public function shares(): HasMany
    {
        return $this->hasMany(B2CLeadShare::class);
    }

    public function followUps(): MorphMany
    {
        return $this->morphMany(FollowUp::class, 'followable');
    }

    public function statusLogs(): MorphMany
    {
        return $this->morphMany(LeadStatusLog::class, 'lead');
    }

    public function assignedSalesPerson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesPerson::class, 'assigned_sales_person_id');
    }
}
