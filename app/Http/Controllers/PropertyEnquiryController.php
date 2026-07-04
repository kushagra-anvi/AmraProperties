<?php

namespace App\Http\Controllers;

use App\Models\B2CLead;
use App\Models\LeadStatusLog;
use App\Models\Partner;
use App\Models\Property;
use App\Models\PropertyEnquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PropertyEnquiryController extends Controller
{
    public function index(Request $request): View
    {
        $query = PropertyEnquiry::with(['property', 'partner', 'sellerPartner'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('property', fn ($propertyQuery) => $propertyQuery->where('title', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $enquiries = $query->paginate(20)->withQueryString();

        return view('crm.property-enquiries.index', compact('enquiries'));
    }

    public function store(Request $request, Property $property): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string'],
            'source' => ['nullable', 'string', 'max:80'],
            'intent' => ['nullable', 'string', 'max:80'],
            'partner_id' => ['nullable', 'exists:partners,id'],
        ]);

        $targetPartner = null;
        if (isset($validated['partner_id'])) {
            $targetPartner = Partner::find($validated['partner_id']);
        } else {
            $assignedPartners = $property->partners;
            if ($assignedPartners->isNotEmpty()) {
                $targetPartner = $assignedPartners->random();
            } else {
                $targetPartner = $property->partner;
            }
        }

        $enquiry = PropertyEnquiry::create([
            'property_id' => $property->id,
            'partner_id' => $targetPartner?->id,
            'seller_partner_id' => $property->partner_id,
            'assigned_sales_person_id' => $targetPartner?->assigned_sales_person_id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'message' => $validated['message'] ?? 'Interested in ' . $property->title,
            'source' => $validated['source'] ?? 'property_form',
            'intent' => $validated['intent'] ?? 'details',
            'revealed_at' => now(),
        ]);

        $lead = B2CLead::create([
            'source_platform' => 'website',
            'lead_created_at' => now(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'city' => $property->city,
            'budget_min' => null,
            'budget_max' => $property->price,
            'preferred_locations' => array_filter([$property->city, $property->address]),
            'property_type' => $property->type_category ?: 'flat',
            'configuration' => $property->configuration ?: '2BHK',
            'status' => 'new',
            'remark' => 'Property enquiry for ' . $property->title . '. Enquiry #' . $enquiry->id . '. ' . ($validated['message'] ?? ''),
        ]);

        if ($targetPartner) {
            \App\Models\B2CLeadShare::create([
                'b2_c_lead_id' => $lead->id,
                'partner_id' => $targetPartner->id,
                'shared_by_user_id' => null, // system auto
                'shared_at' => now(),
                'remark' => 'Auto-assigned via property enquiry on ' . $property->title,
            ]);
            $lead->update(['status' => 'shared']);
        }

        LeadStatusLog::create([
            'lead_type' => B2CLead::class,
            'lead_id' => $lead->id,
            'from_status' => null,
            'to_status' => $targetPartner ? 'shared' : 'new',
            'changed_by_user_id' => Auth::id(),
            'notes' => 'Property-specific enquiry captured from ' . $enquiry->source . '.',
        ]);

        $phone = $targetPartner?->phone ?: $property->contact_phone ?: config('crm.default_contact_phone', '+919559992958');
        $whatsapp = $targetPartner?->phone ?: $property->contact_whatsapp ?: $phone;

        return response()->json([
            'success' => true,
            'message' => 'Enquiry saved. Contact details unlocked.',
            'enquiry_id' => $enquiry->id,
            'phone' => $phone,
            'whatsapp_url' => 'https://wa.me/' . preg_replace('/\D+/', '', $whatsapp) . '?text=' . rawurlencode('Hi, I am interested in ' . $property->title),
        ]);
    }

    public function storeSellerContact(Request $request, Partner $partner): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string'],
            'intent' => ['nullable', 'string', 'max:80'],
        ]);

        $lead = B2CLead::create([
            'source_platform' => 'website',
            'lead_created_at' => now(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'city' => $partner->city,
            'preferred_locations' => array_filter($partner->service_areas ?? [$partner->city]),
            'property_type' => 'property',
            'configuration' => 'seller_contact',
            'status' => 'new',
            'remark' => 'Recommended seller contact request for ' . ($partner->company_name ?: $partner->contact_person ?: 'Partner #' . $partner->id) . '. ' . ($validated['message'] ?? ''),
        ]);

        LeadStatusLog::create([
            'lead_type' => B2CLead::class,
            'lead_id' => $lead->id,
            'from_status' => null,
            'to_status' => 'new',
            'changed_by_user_id' => Auth::id(),
            'notes' => 'Homepage recommended seller contact captured.',
        ]);

        $phone = $partner->phone ?: config('crm.default_contact_phone', '+919559992958');

        return response()->json([
            'success' => true,
            'message' => 'Enquiry saved. Seller contact unlocked.',
            'lead_id' => $lead->id,
            'phone' => $phone,
            'whatsapp_url' => 'https://wa.me/' . preg_replace('/\D+/', '', $phone) . '?text=' . rawurlencode('Hi, I found you on Amra Property and want to discuss properties.'),
        ]);
    }
}
