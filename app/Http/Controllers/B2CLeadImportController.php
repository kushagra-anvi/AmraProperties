<?php

namespace App\Http\Controllers;

use App\Models\B2CLead;
use App\Models\LeadStatusLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class B2CLeadImportController extends Controller
{
    /**
     * Import B2C Buyer Leads from a CSV file.
     */
    public function import(Request $request): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403, 'Unauthorized to import leads.');
        }

        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // Max 5MB
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        if (($handle = fopen($path, 'r')) === false) {
            return back()->with('error', 'Unable to open the uploaded file.');
        }

        // Parse Header Row
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return back()->with('error', 'The uploaded file is empty.');
        }

        // Clean headers
        $headers = array_map(function ($h) {
            $h = preg_replace('/[\x{FEFF}\x{200B}]/u', '', $h);
            return strtolower(trim($h));
        }, $headers);

        // Required headers validation
        $required = ['name', 'phone', 'city', 'property_type', 'configuration'];
        $missing = array_diff($required, $headers);

        if (!empty($missing)) {
            fclose($handle);
            return back()->with('error', 'Missing required B2C CSV columns: ' . implode(', ', $missing));
        }

        $rowCount = 0;
        $importCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowCount++;

            // Map row data using headers
            $data = [];
            foreach ($headers as $index => $header) {
                if (isset($row[$index])) {
                    $data[$header] = trim($row[$index]);
                }
            }

            if (empty($data['name'])) {
                continue; // Skip blank rows
            }

            // Map and Validate Fields
            $propertyType = 'flat';
            if (isset($data['property_type']) && in_array(strtolower($data['property_type']), ['plot', 'flat', 'villa', 'commercial'])) {
                $propertyType = strtolower($data['property_type']);
            }

            $configuration = '2BHK';
            if (isset($data['configuration']) && in_array(strtoupper($data['configuration']), ['1BHK', '2BHK', '3BHK', '4BHK', 'PLOT', 'STUDIO'])) {
                $configuration = strtoupper($data['configuration']);
            }

            $preferredLocations = [];
            if (isset($data['preferred_locations']) && !empty($data['preferred_locations'])) {
                $preferredLocations = array_map('trim', explode(',', $data['preferred_locations']));
            }

            $sourcePlatform = 'website';
            if (isset($data['source_platform']) && in_array(strtolower($data['source_platform']), ['meta', 'google', 'website', 'manual'])) {
                $sourcePlatform = strtolower($data['source_platform']);
            }

            // Create lead
            $lead = B2CLead::create([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'city' => $data['city'] ?? 'Unknown',
                'pincode' => $data['pincode'] ?? null,
                'budget_min' => isset($data['budget_min']) && is_numeric($data['budget_min']) ? $data['budget_min'] : null,
                'budget_max' => isset($data['budget_max']) && is_numeric($data['budget_max']) ? $data['budget_max'] : null,
                'preferred_locations' => $preferredLocations,
                'property_type' => $propertyType,
                'configuration' => $configuration,
                'source_platform' => $sourcePlatform,
                'lead_created_at' => now(),
                'status' => 'new',
                'remark' => $data['remark'] ?? 'Imported via CSV',
            ]);

            // Log Initial Status
            LeadStatusLog::create([
                'lead_type' => B2CLead::class,
                'lead_id' => $lead->id,
                'from_status' => null,
                'to_status' => 'new',
                'changed_by_user_id' => Auth::id(),
                'notes' => 'Buyer lead imported via bulk CSV upload.',
            ]);

            $importCount++;
        }

        fclose($handle);

        return back()->with('success', "Successfully imported {$importCount} B2C Leads out of {$rowCount} rows parsed.");
    }
}
