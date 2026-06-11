<?php

namespace App\Http\Controllers;

use App\Models\B2CLead;
use App\Models\LeadStatusLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactFormController extends Controller
{
    /**
     * Handle incoming AJAX submissions from the contact form.
     */
    public function submit(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:120'],
                'last_name' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['nullable', 'string', 'max:20'], // Some forms might pass phone, make it optional
                'message' => ['required', 'string'],
            ]);

            $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);
            $message = $validated['message'];

            // Smart Property Preference Extractor
            $propertyType = 'flat'; // Default fallback
            $messageLower = strtolower($message);

            if (str_contains($messageLower, 'villa') || str_contains($messageLower, 'house') || str_contains($messageLower, 'independent')) {
                $propertyType = 'villa';
            } elseif (str_contains($messageLower, 'plot') || str_contains($messageLower, 'land')) {
                $propertyType = 'plot';
            } elseif (str_contains($messageLower, 'commercial') || str_contains($messageLower, 'office') || str_contains($messageLower, 'shop')) {
                $propertyType = 'commercial';
            }

            // Smart Configuration Extractor
            $configuration = '2BHK'; // Default fallback
            if (str_contains($messageLower, '1bhk') || str_contains($messageLower, '1 bhk')) {
                $configuration = '1BHK';
            } elseif (str_contains($messageLower, '2bhk') || str_contains($messageLower, '2 bhk')) {
                $configuration = '2BHK';
            } elseif (str_contains($messageLower, '3bhk') || str_contains($messageLower, '3 bhk')) {
                $configuration = '3BHK';
            } elseif (str_contains($messageLower, '4bhk') || str_contains($messageLower, '4 bhk')) {
                $configuration = '4BHK';
            } elseif ($propertyType === 'plot') {
                $configuration = 'Plot';
            }

            // Smart Budget Extractor
            $budgetMin = null;
            $budgetMax = null;
            
            // Extract numbers followed by cr or lakhs (e.g. "1cr", "50 lakhs")
            preg_match('/(\d+)\s*(?:cr|crore)/i', $message, $crMatch);
            preg_match('/(\d+)\s*(?:lakh|lakhs|l)/i', $message, $lakhMatch);

            if (!empty($crMatch)) {
                $budgetMax = floatval($crMatch[1]) * 10000000;
                $budgetMin = $budgetMax * 0.75; // Estimate min as 75% of max
            } elseif (!empty($lakhMatch)) {
                $budgetMax = floatval($lakhMatch[1]) * 100000;
                $budgetMin = $budgetMax * 0.75;
            }

            // Extract city
            $city = 'Mumbai'; // Default fallback
            if (str_contains($messageLower, 'lucknow')) {
                $city = 'Lucknow';
            } elseif (str_contains($messageLower, 'pune')) {
                $city = 'Pune';
            } elseif (str_contains($messageLower, 'delhi')) {
                $city = 'Delhi';
            } elseif (str_contains($messageLower, 'bangalore')) {
                $city = 'Bangalore';
            }

            // Preferred locations (extract from comma separation or use city)
            $locations = [$city];
            
            // Create B2C Lead
            $lead = B2CLead::create([
                'source_platform' => 'website',
                'lead_created_at' => now(),
                'name' => $fullName,
                'phone' => $validated['phone'] ?? '+91 99999 88888', // Fallback phone for website leads
                'email' => $validated['email'],
                'city' => $city,
                'budget_min' => $budgetMin,
                'budget_max' => $budgetMax,
                'preferred_locations' => $locations,
                'property_type' => $propertyType,
                'configuration' => $configuration,
                'status' => 'new',
                'remark' => 'Website Inquiry message: ' . $message,
            ]);

            // Initial status audit log
            LeadStatusLog::create([
                'lead_type' => B2CLead::class,
                'lead_id' => $lead->id,
                'from_status' => null,
                'to_status' => 'new',
                'changed_by_user_id' => null, // Organic system created
                'notes' => 'New buyer lead acquired organically via website contact form.',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your inquiry has been successfully captured as a website lead.',
                'lead_id' => $lead->id
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your message. Please try again later.'
            ], 500);
        }
    }
}
