<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\B2BLead;
use App\Models\B2CLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TataDialplanController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // 1. Verify webhook security token
        $secureToken = config('services.smartflo.webhook_secret', 'smartflo_secure_token_123');
        if ($request->query('token') !== $secureToken) {
            Log::warning('Unauthorized Smartflo dialplan attempt.', [
                'ip' => $request->ip(),
                'query_params' => $request->query()
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rawBody = $request->getContent();
        $payload = json_decode($rawBody, true) ?? $request->all();

        Log::info('Incoming Smartflo Dialplan Request:', [
            'payload' => $payload
        ]);

        // 2. Extract caller's phone number
        $rawCaller = $payload['caller_id_number'] ?? $payload['customer_no_with_prefix'] ?? '';
        $callerNumber = preg_replace('/\D/', '', $rawCaller);

        if (str_starts_with($callerNumber, '91') && strlen($callerNumber) === 12) {
            $callerNumber = substr($callerNumber, 2);
        }

        $targetAgentPhone = null;

        if (!empty($callerNumber)) {
            // 3. Search B2C Lead first
            $b2cLead = B2CLead::where('phone', 'like', "%{$callerNumber}%")
                ->whereNotNull('assigned_sales_person_id')
                ->first();

            if ($b2cLead && $b2cLead->assignedSalesPerson && $b2cLead->assignedSalesPerson->is_active) {
                $targetAgentPhone = $b2cLead->assignedSalesPerson->phone;
            } else {
                // 4. Search B2B Lead
                $b2bLead = B2BLead::where(function ($query) use ($callerNumber) {
                        $query->where('contact_number', 'like', "%{$callerNumber}%")
                              ->orWhere('whatsapp_number', 'like', "%{$callerNumber}%");
                    })
                    ->whereNotNull('assigned_sales_person_id')
                    ->first();

                if ($b2bLead && $b2bLead->assignedSalesPerson && $b2bLead->assignedSalesPerson->is_active) {
                    $targetAgentPhone = $b2bLead->assignedSalesPerson->phone;
                }
            }
        }

        // 5. Fallback number if no assigned salesperson is found
        // Use default admin agent number from .env or fallback
        if (empty($targetAgentPhone)) {
            $targetAgentPhone = env('ADMIN_AGENT_NUMBER', '917965806170');
        }

        // Ensure number has country prefix if required by Smartflo routing
        $normalizedAgentPhone = preg_replace('/\D/', '', $targetAgentPhone);
        if (!str_starts_with($normalizedAgentPhone, '91') && strlen($normalizedAgentPhone) === 10) {
            $normalizedAgentPhone = '91' . $normalizedAgentPhone;
        }

        // 6. Return Smartflo routing JSON response
        $routingResponse = [
            [
                'transfer' => [
                    'type' => 'number',
                    'data' => [$normalizedAgentPhone],
                    'ring_type' => 'order_by',
                    'skip_active' => true
                ]
            ]
        ];

        Log::info('Smartflo Dialplan Routing Decision:', [
            'caller' => $callerNumber,
            'routed_to' => $normalizedAgentPhone,
            'response' => $routingResponse
        ]);

        return response()->json($routingResponse);
    }
}
