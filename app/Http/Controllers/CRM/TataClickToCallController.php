<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TataClickToCallController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $user = auth()->user();
        $agentNumber = preg_replace('/\D/', '', $user->phone ?? '');
        $destinationNumber = preg_replace('/\D/', '', $request->phone);

        if (empty($agentNumber)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please set your phone number in your profile settings to use Click-to-Call.'
            ], 422);
        }

        // Normalize numbers to include country prefix 91 if it's a 10-digit number
        if (strlen($agentNumber) === 10) {
            $agentNumber = '91' . $agentNumber;
        }
        if (strlen($destinationNumber) === 10) {
            $destinationNumber = '91' . $destinationNumber;
        }

        $apiKey = config('services.smartflo.click_to_call_api_key');
        $callerId = config('services.smartflo.did_number') ?: '7965806170'; // fallback to synced TFN

        if (empty($apiKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'TTBS_CLICK_TO_CALL_API_KEY is not configured in .env'
            ], 500);
        }

        // Trigger Tata Smartflo click-to-call POST request
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $apiKey
            ])->post('https://api-smartflo.tatateleservices.com/v1/click_to_call', [
                'agent_number' => $agentNumber,
                'destination_number' => $destinationNumber,
                'caller_id' => $callerId,
                'async' => 1
            ]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Call initiated successfully! Your phone will ring first.'
                ]);
            }

            Log::error('Tata Click-to-Call API failed:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            $errorMsg = $response->json('message') ?: 'Failed to initiate call via Tata Smartflo.';
            return response()->json([
                'status' => 'error',
                'message' => $errorMsg
            ], 400);

        } catch (\Exception $e) {
            Log::error('Tata Click-to-Call Exception:', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while connecting to the telephony server.'
            ], 500);
        }
    }
}
