<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LeadStatusLog extends Model
{
    protected $fillable = [
        'lead_type',
        'lead_id',
        'from_status',
        'to_status',
        'changed_by_user_id',
        'notes',
    ];

    public function lead(): MorphTo
    {
        return $this->morphTo();
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    public function changedByUser(): BelongsTo
    {
        return $this->changedBy();
    }
}
