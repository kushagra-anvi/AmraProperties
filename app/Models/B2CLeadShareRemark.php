<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class B2CLeadShareRemark extends Model
{
    protected $fillable = [
        'b2_c_lead_share_id',
        'user_id',
        'remark',
    ];

    public function leadShare(): BelongsTo
    {
        return $this->belongsTo(B2CLeadShare::class, 'b2_c_lead_share_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
