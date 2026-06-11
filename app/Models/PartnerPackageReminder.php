<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerPackageReminder extends Model
{
    protected $fillable = [
        'partner_id',
        'remind_on',
        'type',
        'notified_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'remind_on' => 'date',
            'notified_at' => 'datetime',
        ];
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
