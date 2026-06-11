<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSpend extends Model
{
    protected $fillable = [
        'source_platform',
        'amount',
        'spent_on',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'spent_on' => 'date',
        ];
    }
}
