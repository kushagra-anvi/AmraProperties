<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class B2CLeadShare extends Model
{
    protected $fillable = [
        'b2_c_lead_id',
        'partner_id',
        'shared_by_user_id',
        'shared_at',
        'remark',
    ];

    protected function casts(): array
    {
        return [
            'shared_at' => 'datetime',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(B2CLead::class, 'b2_c_lead_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function sharedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by_user_id');
    }
}
