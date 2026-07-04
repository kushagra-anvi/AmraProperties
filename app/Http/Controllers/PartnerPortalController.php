<?php

namespace App\Http\Controllers;

use App\Models\B2CLead;
use App\Models\B2CLeadShare;
use App\Models\Property;
use App\Models\PropertyEnquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PartnerPortalController extends Controller
{
    /**
     * Display the partner portal dashboard.
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        $partner = $user->partner;

        if (!$partner) {
            abort(403, 'No partner profile associated with this user account.');
        }

        // Load shared B2C leads
        $shares = B2CLeadShare::with('lead')
            ->where('partner_id', $partner->id)
            ->orderByDesc('shared_at')
            ->paginate(15);

        $totalLeadsCount = B2CLeadShare::where('partner_id', $partner->id)->count();
        $propertyEnquiriesCount = PropertyEnquiry::where(function ($query) use ($partner) {
            $query->where('partner_id', $partner->id)
                ->orWhere('seller_partner_id', $partner->id);
        })->count();
        $propertiesCount = Property::where('partner_id', $partner->id)->count();

        // Load pending partner followups on B2C leads shared with them
        $pendingFollowups = \App\Models\FollowUp::where('followable_type', B2CLead::class)
            ->whereNull('completed_at')
            ->where('user_id', auth()->id())
            ->whereIn('followable_id', function ($sub) use ($partner) {
                $sub->select('b2_c_lead_id')
                    ->from('b2_c_lead_shares')
                    ->where('partner_id', $partner->id);
            })
            ->with('followable')
            ->orderBy('due_at')
            ->get();

        return view('partner.dashboard', compact('partner', 'shares', 'totalLeadsCount', 'propertyEnquiriesCount', 'propertiesCount', 'pendingFollowups'));
    }

    /**
     * Display the specified B2C lead details.
     */
    public function showLead(B2CLead $lead): View
    {
        $user = Auth::user();
        $partner = $user->partner;

        if (!$partner) {
            abort(403, 'No partner profile associated with this user account.');
        }

        // Verify that this lead has indeed been shared with this partner
        $share = B2CLeadShare::with(['remarks.user'])
            ->where('b2_c_lead_id', $lead->id)
            ->where('partner_id', $partner->id)
            ->first();

        if (!$share) {
            abort(403, 'Unauthorized access. This lead is not shared with you.');
        }

        // Load only follow-ups created by this partner user
        $lead->load(['followUps' => function ($q) use ($user) {
            $q->where('user_id', $user->id)->orderBy('due_at');
        }]);

        return view('partner.show', compact('lead', 'share', 'partner'));
    }

    public function properties(): View
    {
        $partner = Auth::user()->partner;
        abort_unless($partner, 403);

        $properties = Property::where('partner_id', $partner->id)
            ->latest()
            ->paginate(15);
        $enquiries = PropertyEnquiry::with('property')
            ->where(function ($query) use ($partner) {
                $query->where('partner_id', $partner->id)
                    ->orWhere('seller_partner_id', $partner->id);
            })
            ->latest()
            ->limit(20)
            ->get();

        return view('partner.properties.index', compact('partner', 'properties', 'enquiries'));
    }

    public function createProperty(): View
    {
        $partner = Auth::user()->partner;
        abort_unless($partner, 403);

        return view('partner.properties.create', compact('partner'));
    }

    public function storeProperty(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $partner = $user->partner;
        abort_unless($partner, 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'integer', 'min:0'],
            'avg_price_per_sqft' => ['nullable', 'numeric', 'min:0'],
            'possession_date' => ['nullable', 'date'],
            'possession_status' => ['nullable', 'string', 'max:255'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'area' => ['nullable', 'integer', 'min:0'],
            'area_unit' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'configuration' => ['nullable', 'string', 'max:255'],
            'developer_name' => ['nullable', 'string', 'max:255'],
            'rera_number' => ['nullable', 'string', 'max:255'],
            'listing_type' => ['required', 'string', 'in:sale,rent'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'featured_image_url' => ['nullable', 'string', 'max:2048'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:5120'],
            'gallery_urls' => ['nullable', 'string'],
            'amenities' => ['nullable', 'array'],
            'video_urls' => ['nullable', 'string'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'contact_whatsapp' => ['nullable', 'string', 'max:20'],
            'configurations' => ['nullable', 'array'],
            'configurations.*.name' => ['required', 'string', 'max:255'],
            'configurations.*.price' => ['nullable', 'integer', 'min:0'],
            'configurations.*.area' => ['nullable', 'integer', 'min:0'],
            'configurations.*.area_unit' => ['required', 'string'],
            'configurations.*.bedrooms' => ['nullable', 'integer', 'min:0'],
            'configurations.*.bathrooms' => ['nullable', 'integer', 'min:0'],
            'configurations.*.status' => ['required', 'string'],
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . random_int(1000, 9999);
        $validated['partner_id'] = $partner->id;
        $validated['submitted_by_user_id'] = $user->id;
        $validated['status'] = 'draft';
        $validated['is_featured'] = false;
        $validated['is_rera_approved'] = filled($validated['rera_number'] ?? null);

        // Handle Featured Image Upload or URL
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('properties', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        } elseif ($request->filled('featured_image_url')) {
            $validated['featured_image'] = $request->input('featured_image_url');
        }

        // Handle Gallery Uploads and URLs combined
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('properties/gallery', 'public');
                $galleryPaths[] = '/storage/' . $path;
            }
        }
        if ($request->filled('gallery_urls')) {
            $urlLines = collect(preg_split('/\r\n|\r|\n/', $request->input('gallery_urls')))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values()
                ->all();
            $galleryPaths = array_merge($galleryPaths, $urlLines);
        }
        $validated['gallery'] = $galleryPaths;

        $validated['video_urls'] = collect(preg_split('/\r\n|\r|\n/', $validated['video_urls'] ?? ''))
            ->map(fn ($url) => trim($url))
            ->filter()
            ->values()
            ->all();

        $property = Property::create($validated);

        if ($request->has('configurations')) {
            foreach ($request->input('configurations') as $config) {
                if (!empty($config['name'])) {
                    $property->configurations()->create($config);
                }
            }
        }

        return redirect()->route('crm.partner.properties.index')
            ->with('success', 'Property submitted for admin review.');
    }

    public function updateLeadRemark(B2CLead $lead, Request $request): RedirectResponse
    {
        $partner = Auth::user()->partner;
        abort_unless($partner, 403);

        $share = B2CLeadShare::where('b2_c_lead_id', $lead->id)
            ->where('partner_id', $partner->id)
            ->firstOrFail();

        $validated = $request->validate([
            'remark' => ['required', 'string'],
        ]);

        \App\Models\B2CLeadShareRemark::create([
            'b2_c_lead_share_id' => $share->id,
            'user_id' => Auth::id(),
            'remark' => $validated['remark'],
        ]);

        $share->update([
            'remark' => $validated['remark']
        ]);

        return redirect()->back()->with('success', 'Lead remark updated successfully.');
    }

    public function createLead(): View
    {
        $partner = Auth::user()->partner;
        abort_unless($partner, 403);

        return view('partner.leads.create', compact('partner'));
    }

    public function storeLead(Request $request): RedirectResponse
    {
        $partner = Auth::user()->partner;
        abort_unless($partner, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'budget_min' => ['nullable', 'integer', 'min:0'],
            'budget_max' => ['nullable', 'integer', 'min:0'],
            'property_type' => ['required', 'string'],
            'configuration' => ['nullable', 'string'],
            'remark' => ['nullable', 'string'],
        ]);

        $lead = B2CLead::create(array_merge($validated, [
            'source_platform' => 'manual',
            'lead_created_at' => now(),
            'status' => 'shared',
            'preferred_locations' => [$partner->city],
        ]));

        $share = B2CLeadShare::create([
            'b2_c_lead_id' => $lead->id,
            'partner_id' => $partner->id,
            'shared_by_user_id' => Auth::id(),
            'shared_at' => now(),
            'remark' => 'Lead created manually by partner.',
        ]);

        \App\Models\B2CLeadShareRemark::create([
            'b2_c_lead_share_id' => $share->id,
            'user_id' => Auth::id(),
            'remark' => 'Lead created manually by partner.',
        ]);

        return redirect()->route('crm.partner.dashboard')
            ->with('success', 'Lead manually created and assigned to your portal.');
    }

    public function scheduleFollowup(B2CLead $lead, Request $request): RedirectResponse
    {
        $partner = Auth::user()->partner;
        abort_unless($partner, 403);

        $share = B2CLeadShare::where('b2_c_lead_id', $lead->id)
            ->where('partner_id', $partner->id)
            ->firstOrFail();

        $validated = $request->validate([
            'due_at' => ['required', 'date', 'after:now'],
            'notes' => ['nullable', 'string'],
        ]);

        // Complete any existing pending followups for this lead/user combo
        \App\Models\FollowUp::where('followable_type', B2CLead::class)
            ->where('followable_id', $lead->id)
            ->where('user_id', Auth::id())
            ->whereNull('completed_at')
            ->update(['completed_at' => now()]);

        // Create new follow up
        \App\Models\FollowUp::create([
            'followable_type' => B2CLead::class,
            'followable_id' => $lead->id,
            'sales_person_id' => null, // null since it's a partner followup
            'user_id' => Auth::id(),
            'due_at' => $validated['due_at'],
            'notes' => $validated['notes'] ?? 'Scheduled partner followup reminder.',
        ]);

        return redirect()->back()->with('success', 'Follow-up reminder scheduled successfully.');
    }

    public function completeFollowup(\App\Models\FollowUp $followUp, Request $request): RedirectResponse
    {
        abort_unless($followUp->user_id === Auth::id(), 403);

        $followUp->update([
            'completed_at' => now(),
            'outcome' => $request->input('outcome', 'Completed'),
        ]);

        return back()->with('success', 'Follow-up successfully completed.');
    }
}
