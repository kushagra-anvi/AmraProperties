<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TataCallLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'call_id',
        'caller_number',
        'tfn_number',
        'duration_seconds',
        'status',
        'disposition',
        'start_time',
        'end_time',
        'recording_url',
        'notes',
        'raw_data',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'raw_data' => 'array',
    ];

    /**
     * Get the call direction (inbound / outbound).
     */
    public function getDirectionAttribute(): string
    {
        return $this->raw_data['direction'] ?? 'inbound';
    }

    /**
     * Get the name of the agent who answered.
     */
    public function getAgentNameAttribute(): ?string
    {
        return $this->raw_data['answered_agent_name'] ?? null;
    }
}
