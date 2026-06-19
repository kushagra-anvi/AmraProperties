<?php

namespace App\Http\Controllers;

use App\Models\B2BLead;
use App\Models\LeadStatusLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class B2BLeadImportController extends Controller
{
    /**
     * Import B2B Leads from a CSV file.
     */
    public function import(Request $request): RedirectResponse
    {
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

        // Clean headers (remove BOM if present, trim whitespace, convert to lowercase)
        $headers = array_map(function ($h) {
            $h = preg_replace('/[\x{FEFF}\x{200B}]/u', '', $h);
            return strtolower(trim($h));
        }, $headers);

        // Required headers validation
        $required = ['company_name', 'contact_person_name', 'contact_number', 'city'];
        $missing = array_diff($required, $headers);

        if (!empty($missing)) {
            fclose($handle);
            return back()->with('error', 'Missing required CSV columns: ' . implode(', ', $missing));
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

            if (empty($data['company_name'])) {
                continue; // Skip blank rows
            }

            // Map and Validate Fields
            $category = 'agent';
            if (isset($data['category']) && in_array(strtolower($data['category']), ['agent', 'developer', 'single_owner', 'single owner'])) {
                $category = strtolower($data['category']);
                if ($category === 'single owner') {
                    $category = 'single_owner';
                }
            }

            $serviceAreas = [];
            if (isset($data['service_areas']) && !empty($data['service_areas'])) {
                $serviceAreas = array_map('trim', explode(',', $data['service_areas']));
            }

            $sourcePlatform = 'csv';
            if (isset($data['source_platform']) && in_array(strtolower($data['source_platform']), ['meta', 'google', 'website', 'manual', 'csv'])) {
                $sourcePlatform = strtolower($data['source_platform']);
            }

            // Create lead
            $lead = B2BLead::create([
                'category' => $category,
                'company_name' => $data['company_name'],
                'contact_person_name' => $data['contact_person_name'] ?? 'Unknown',
                'contact_number' => $data['contact_number'] ?? null,
                'whatsapp_number' => $data['whatsapp_number'] ?? ($data['contact_number'] ?? null),
                'email' => $data['email'] ?? null,
                'office_address' => $data['office_address'] ?? null,
                'service_areas' => $serviceAreas,
                'city' => $data['city'] ?? 'Unknown',
                'project_ticket_size_min' => isset($data['project_ticket_size_min']) && is_numeric($data['project_ticket_size_min']) ? $data['project_ticket_size_min'] : null,
                'project_ticket_size_max' => isset($data['project_ticket_size_max']) && is_numeric($data['project_ticket_size_max']) ? $data['project_ticket_size_max'] : null,
                'source_platform' => $sourcePlatform,
                'lead_created_at' => now(),
                'status' => 'new',
                'remark' => $data['remark'] ?? 'Imported via CSV',
            ]);

            // Log Initial Status
            LeadStatusLog::create([
                'lead_type' => B2BLead::class,
                'lead_id' => $lead->id,
                'from_status' => null,
                'to_status' => 'new',
                'changed_by_user_id' => Auth::id(),
                'notes' => 'Lead imported via bulk CSV upload.',
            ]);

            $importCount++;
        }

        fclose($handle);

        return back()->with('success', "Successfully imported {$importCount} B2B Leads out of {$rowCount} rows parsed.");
    }
}
