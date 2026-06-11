<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class B2BLead extends Model
{
    protected $fillable = [
        'category',
        'company_name',
        'contact_person_name',
        'contact_number',
        'whatsapp_number',
        'email',
        'office_address',
        'service_areas',
        'city',
        'project_ticket_size_min',
        'project_ticket_size_max',
        'source_platform',
        'lead_created_at',
        'assigned_sales_person_id',
        'converted_partner_id',
        'status',
        'remark',
    ];

    protected function casts(): array
    {
        return [
            'service_areas' => 'array',
            'project_ticket_size_min' => 'decimal:2',
            'project_ticket_size_max' => 'decimal:2',
            'lead_created_at' => 'datetime',
        ];
    }

    public function assignedSalesPerson(): BelongsTo
    {
        return $this->belongsTo(SalesPerson::class, 'assigned_sales_person_id');
    }

    public function convertedPartner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'converted_partner_id');
    }

    public function followUps(): MorphMany
    {
        return $this->morphMany(FollowUp::class, 'followable');
    }

    public function statusLogs(): MorphMany
    {
        return $this->morphMany(LeadStatusLog::class, 'lead');
    }
}
