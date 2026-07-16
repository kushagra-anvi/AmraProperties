<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\TataCallLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TataWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // 1. Verify webhook security token
        $secureToken = config('services.smartflo.webhook_secret', 'smartflo_secure_token_123');
        if ($request->query('token') !== $secureToken) {
            Log::warning('Unauthorized Smartflo webhook attempt.', [
                'ip' => $request->ip(),
                'query_params' => $request->query()
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rawBody = $request->getContent();
        $payload = json_decode($rawBody, true) ?? $request->all();

        // 2. Log payload for auditing
        Log::channel('single')->info('Incoming Tata Smartflo Webhook Payload:', [
            'received_at' => now()->toIso8601String(),
            'payload' => $payload
        ]);

        $callId = $payload['call_id'] ?? $payload['uuid'] ?? null;
        if (!$callId) {
            return response()->json(['error' => 'Missing unique call_id'], 400);
        }

        // 3. Extract and normalize phone numbers (stripping country prefix 91 if present)
        $rawCaller = $payload['caller_id_number'] ?? $payload['customer_no_with_prefix'] ?? $payload['customer_no_with_prefix '] ?? '';
        $rawTfn = $payload['call_to_number'] ?? '';

        $callerNumber = preg_replace('/\D/', '', $rawCaller);
        $tfnNumber = preg_replace('/\D/', '', $rawTfn);

        if (str_starts_with($callerNumber, '91') && strlen($callerNumber) === 12) {
            $callerNumber = substr($callerNumber, 2);
        }
        if (str_starts_with($tfnNumber, '91') && strlen($tfnNumber) === 12) {
            $tfnNumber = substr($tfnNumber, 2);
        }

        // 4. Parse durations & status
        $duration = (int) ($payload['duration'] ?? $payload['billsec'] ?? 0);
        $rawStatus = $payload['call_status'] ?? 'Unknown';

        // Standardize status
        $status = 'Unknown';
        if (stripos($rawStatus, 'answer') !== false || stripos($rawStatus, 'success') !== false) {
            $status = 'Answered';
        } elseif (stripos($rawStatus, 'miss') !== false) {
            $status = 'Missed';
        } elseif (stripos($rawStatus, 'abandon') !== false) {
            $status = 'Abandoned';
        } elseif (stripos($rawStatus, 'busy') !== false) {
            $status = 'Busy';
        } else {
            $status = ucfirst(strtolower(trim($rawStatus)));
        }

        // 5. Parse start & end timestamps
        $startTimeStr = $payload['start_stamp'] ?? null;
        $endTimeStr = $payload['end_stamp'] ?? null;

        $startTime = $startTimeStr ? date('Y-m-d H:i:s', strtotime($startTimeStr)) : now()->toDateTimeString();
        $endTime = $endTimeStr ? date('Y-m-d H:i:s', strtotime($endTimeStr)) : null;

        if (!$endTime && $startTime && $duration > 0) {
            $endTime = date('Y-m-d H:i:s', strtotime($startTime) + $duration);
        }

        // 6. Record call log in the database
        TataCallLog::updateOrCreate([
            'call_id' => $callId,
        ], [
            'caller_number' => $callerNumber,
            'tfn_number' => $tfnNumber,
            'duration_seconds' => $duration,
            'status' => $status,
            'disposition' => $payload['reason_key'] ?? $payload['hangup_cause_description'] ?? null,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'recording_url' => $payload['recording_url'] ?? null,
            'raw_data' => $payload,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook received and saved.'
        ]);
    }
}
