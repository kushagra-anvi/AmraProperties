<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerImportController extends Controller
{
    /**
     * Import Partners from a CSV file.
     */
    public function import(Request $request): RedirectResponse
    {
        // Enforce RBAC
        if (!in_array(Auth::user()->role, ['super_admin', 'admin'], true)) {
            abort(403, 'Unauthorized to import partners.');
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

        // Clean headers (remove BOM, trim, lowercase)
        $headers = array_map(function ($h) {
            $h = preg_replace('/[\x{FEFF}\x{200B}]/u', '', $h);
            return strtolower(trim($h));
        }, $headers);

        // Required headers validation
        $required = ['company_name', 'contact_person', 'phone', 'city', 'type'];
        $missing = array_diff($required, $headers);

        if (!empty($missing)) {
            fclose($handle);
            return back()->with('error', 'Missing required Partner CSV columns: ' . implode(', ', $missing));
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

            // Map and Validate Partner Type
            $type = 'agent';
            if (isset($data['type'])) {
                $rawType = strtolower($data['type']);
                if (in_array($rawType, ['agent', 'broker', 'agent / broker'])) {
                    $type = 'agent';
                } elseif (in_array($rawType, ['developer', 'real estate developer'])) {
                    $type = 'developer';
                } elseif (in_array($rawType, ['affiliate', 'affiliate partner', 'affiliate_partner'])) {
                    $type = 'affiliate';
                }
            }

            // Map and Validate Package
            $package = 'free';
            if (isset($data['package'])) {
                $rawPackage = strtolower($data['package']);
                if (in_array($rawPackage, ['free', 'starter', 'growth', 'premium', 'customise'])) {
                    $package = $rawPackage;
                }
            }

            // Map Service Areas (comma separated)
            $serviceAreas = [];
            if (isset($data['service_areas']) && !empty($data['service_areas'])) {
                $serviceAreas = array_map('trim', explode(',', $data['service_areas']));
            }

            // Parse Dates
            $packagePurchaseDate = null;
            if (isset($data['package_purchase_date']) && !empty($data['package_purchase_date'])) {
                $time = strtotime($data['package_purchase_date']);
                if ($time !== false) {
                    $packagePurchaseDate = date('Y-m-d', $time);
                }
            }

            $renewalDate = null;
            if (isset($data['renewal_date']) && !empty($data['renewal_date'])) {
                $time = strtotime($data['renewal_date']);
                if ($time !== false) {
                    $renewalDate = date('Y-m-d', $time);
                }
            }

            // Parse Paid Amount
            $paidAmount = null;
            if (isset($data['paid_amount']) && is_numeric($data['paid_amount'])) {
                $paidAmount = floatval($data['paid_amount']);
            }

            // Create Partner Account
            Partner::create([
                'type' => $type,
                'company_name' => $data['company_name'],
                'contact_person' => $data['contact_person'] ?? 'Unknown',
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'office_address' => $data['office_address'] ?? null,
                'service_areas' => $serviceAreas,
                'city' => $data['city'] ?? 'Unknown',
                'package' => $package,
                'paid_amount' => $paidAmount,
                'package_purchase_date' => $packagePurchaseDate,
                'renewal_date' => $renewalDate,
                'lead_source' => $data['lead_source'] ?? 'CSV Bulk Upload',
                'remark' => $data['remark'] ?? 'Imported via CSV',
                'is_active' => true,
            ]);

            $importCount++;
        }

        fclose($handle);

        return back()->with('success', "Successfully imported {$importCount} Partners out of {$rowCount} rows parsed.");
    }
}
