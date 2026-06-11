<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FollowUp extends Model
{
    protected $fillable = [
        'followable_type',
        'followable_id',
        'sales_person_id',
        'due_at',
        'completed_at',
        'outcome',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function followable(): MorphTo
    {
        return $this->morphTo();
    }

    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(SalesPerson::class);
    }
}
