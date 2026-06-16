<?php

namespace App\Http\Controllers;

use App\Helpers\DateFilterHelper;
use App\Models\B2CLead;
use App\Models\B2CLeadShare;
use App\Models\Partner;
use App\Models\LeadStatusLog;
use App\Models\SalesPerson;
use App\Services\B2CLeadAutoDistributor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class B2CLeadController extends Controller
{

    /**
     * Display a listing of B2C Buyer Leads.
     */
    public function index(Request $request): View
    {
        $query = B2CLead::with('assignedSalesPerson');

        // Search name, phone, email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply filters
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        if ($request->filled('preferred_location')) {
            $location = $request->input('preferred_location');
            $query->where('preferred_locations', 'like', "%{$location}%");
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', $request->input('property_type'));
        }

        if ($request->filled('configuration')) {
            $query->where('configuration', $request->input('configuration'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('source_platform')) {
            $query->where('source_platform', $request->input('source_platform'));
        }

        if ($request->filled('sales_person_id')) {
            $query->where('assigned_sales_person_id', $request->input('sales_person_id'));
        }

        // Budget filters
        if ($request->filled('budget_min')) {
            $query->where('budget_min', '>=', $request->input('budget_min'));
        }
        if ($request->filled('budget_max')) {
            $query->where('budget_max', '<=', $request->input('budget_max'));
        }

        // Apply Date range
        $filter = $request->input('date_filter');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        DateFilterHelper::apply($query, $filter, 'lead_created_at', $startDate, $endDate);

        $leads = $query->orderByDesc('lead_created_at')->paginate(15)->withQueryString();
        $salesPeople = SalesPerson::where('is_active', true)->orderBy('name')->get();

        return view('crm.b2c.index', [
            'leads' => $leads,
            'salesPeople' => $salesPeople,
            'activeFilters' => [
                'search' => $request->input('search'),
                'city' => $request->input('city'),
                'preferred_location' => $request->input('preferred_location'),
                'property_type' => $request->input('property_type'),
                'configuration' => $request->input('configuration'),
                'status' => $request->input('status'),
                'source_platform' => $request->input('source_platform'),
                'sales_person_id' => $request->input('sales_person_id'),
                'budget_min' => $request->input('budget_min'),
                'budget_max' => $request->input('budget_max'),
                'date_filter' => $filter,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        ]);
    }

    public function markFiltered(Request $request, B2CLead $lead): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403);
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        if ($lead->status !== 'filtered') {
            $oldStatus = $lead->status;
            $lead->update(['status' => 'filtered']);

            LeadStatusLog::create([
                'lead_type' => B2CLead::class,
                'lead_id' => $lead->id,
                'from_status' => $oldStatus,
                'to_status' => 'filtered',
                'changed_by_user_id' => Auth::id(),
                'notes' => $validated['notes'] ?? 'Lead reviewed and marked as admin filtered.',
            ]);
        }

        $autoShares = app(B2CLeadAutoDistributor::class)
            ->distribute($lead->fresh(), Auth::id(), 'admin filter');

        $message = 'Buyer lead marked as admin filtered.';
        if ($autoShares->isNotEmpty()) {
            $message .= ' Auto-shared to ' . $autoShares->count() . ' package-eligible partner(s).';
        }

        return back()->with('success', $message);
    }

    public function create(): View
    {
        // Enforce RBAC: Analysts are read-only
        if (Auth::user()->role === 'analyst') {
            abort(403, 'Unauthorized. Analysts cannot create records.');
        }

        $salesPeople = SalesPerson::where('is_active', true)->orderBy('name')->get();
        return view('crm.b2c.create', compact('salesPeople'));
    }

    /**
     * Store a newly created B2C Lead in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0'],
            'preferred_locations' => ['nullable', 'array'],
            'property_type' => ['required', 'string', 'in:plot,flat,villa,commercial'],
            'configuration' => ['required', 'string', 'in:1BHK,2BHK,3BHK,4BHK,Plot,Studio'],
            'source_platform' => ['required', 'string', 'in:meta,google,website,manual'],
            'assigned_sales_person_id' => ['nullable', 'exists:sales_people,id'],
            'remark' => ['nullable', 'string'],
        ]);

        $validated['status'] = 'new';
        $validated['lead_created_at'] = now();

        $lead = B2CLead::create($validated);

        // Initial log
        LeadStatusLog::create([
            'lead_type' => B2CLead::class,
            'lead_id' => $lead->id,
            'from_status' => null,
            'to_status' => 'new',
            'changed_by_user_id' => Auth::id(),
            'notes' => 'B2C Buyer Lead created manually.',
        ]);

        $autoShares = app(B2CLeadAutoDistributor::class)
            ->distribute($lead, Auth::id(), 'manual lead creation');

        $message = 'B2C Buyer Lead created successfully!';
        if ($autoShares->isNotEmpty()) {
            $message .= ' Auto-shared to ' . $autoShares->count() . ' package-eligible partner(s).';
        }

        return redirect()->route('crm.b2c.index')
            ->with('success', $message);
    }

    /**
     * Display the specified B2C Lead.
     */
    public function show(B2CLead $lead): View
    {
        $lead->load(['shares.partner', 'shares.sharedBy', 'statusLogs.changedByUser', 'assignedSalesPerson']);
        
        // Load active partners that are agents/developers for the Lead Sharing list
        $partners = Partner::where('is_active', true)->orderBy('company_name')->get();
        $salesPeople = SalesPerson::where('is_active', true)->orderBy('name')->get();

        return view('crm.b2c.show', compact('lead', 'partners', 'salesPeople'));
    }

    /**
     * Show the form for editing the specified B2C Lead.
     */
    public function edit(B2CLead $lead): View
    {
        if (Auth::user()->role === 'analyst') {
            abort(403);
        }

        $salesPeople = SalesPerson::where('is_active', true)->orderBy('name')->get();
        return view('crm.b2c.edit', compact('lead', 'salesPeople'));
    }

    /**
     * Update the specified B2C Lead in storage.
     */
    public function update(Request $request, B2CLead $lead): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0'],
            'preferred_locations' => ['nullable', 'array'],
            'property_type' => ['required', 'string', 'in:plot,flat,villa,commercial'],
            'configuration' => ['required', 'string', 'in:1BHK,2BHK,3BHK,4BHK,Plot,Studio'],
            'assigned_sales_person_id' => ['nullable', 'exists:sales_people,id'],
            'remark' => ['nullable', 'string'],
        ]);

        $lead->update($validated);

        return redirect()->route('crm.b2c.show', $lead->id)
            ->with('success', 'Buyer profile updated successfully.');
    }

    /**
     * Share B2C Lead with selected Partners.
     */
    public function share(Request $request, B2CLead $lead): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403, 'Analysts cannot distribute leads.');
        }

        $request->validate([
            'partner_ids' => ['required', 'array'],
            'partner_ids.*' => ['exists:partners,id'],
            'remark' => ['nullable', 'string'],
        ]);

        $partnerIds = $request->input('partner_ids');
        $remark = $request->input('remark');
        $shareCount = 0;

        foreach ($partnerIds as $partnerId) {
            // Check if already shared to avoid duplicates
            $exists = B2CLeadShare::where('b2_c_lead_id', $lead->id)
                ->where('partner_id', $partnerId)
                ->exists();

            if (!$exists) {
                B2CLeadShare::create([
                    'b2_c_lead_id' => $lead->id,
                    'partner_id' => $partnerId,
                    'shared_by_user_id' => Auth::id(),
                    'shared_at' => now(),
                    'remark' => $remark,
                ]);
                $shareCount++;
            }
        }

        if ($shareCount > 0) {
            // Update lead status to shared once distribution starts.
            if (in_array($lead->status, ['new', 'filtered'], true)) {
                $oldStatus = $lead->status;
                $lead->update(['status' => 'shared']);

                LeadStatusLog::create([
                    'lead_type' => B2CLead::class,
                    'lead_id' => $lead->id,
                    'from_status' => $oldStatus,
                    'to_status' => 'shared',
                    'changed_by_user_id' => Auth::id(),
                    'notes' => "Lead distributed to {$shareCount} partner(s). Status bumped to Shared.",
                ]);
            }
        }

        return back()->with('success', "Lead shared successfully with {$shareCount} partner(s).");
    }

    /**
     * Manually trigger package-based auto distribution for an existing lead.
     */
    public function autoDistribute(B2CLead $lead): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403, 'Analysts cannot distribute leads.');
        }

        $autoShares = app(B2CLeadAutoDistributor::class)
            ->distribute($lead, Auth::id(), 'manual auto-distribution');

        if ($autoShares->isEmpty()) {
            return back()->with('success', 'No new package-eligible partners found for auto assignment.');
        }

        return back()->with('success', 'Auto-assigned lead to ' . $autoShares->count() . ' package-eligible partner(s).');
    }

    /**
     * Assign B2C lead to sales person (tele-caller).
     */
    public function assign(Request $request, B2CLead $lead): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403);
        }

        $request->validate([
            'assigned_sales_person_id' => ['nullable', 'exists:sales_people,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $oldSalesPerson = $lead->assignedSalesPerson;
        $newSalesPersonId = $request->input('assigned_sales_person_id');
        $lead->update(['assigned_sales_person_id' => $newSalesPersonId]);

        $newSalesPerson = SalesPerson::find($newSalesPersonId);
        $notes = 'Reassigned B2C Lead from ' . ($oldSalesPerson ? $oldSalesPerson->name : 'Unassigned') . 
                 ' to ' . ($newSalesPerson ? $newSalesPerson->name : 'Unassigned') . '.';
        
        if ($request->filled('notes')) {
            $notes .= ' Note: ' . $request->input('notes');
        }

        LeadStatusLog::create([
            'lead_type' => B2CLead::class,
            'lead_id' => $lead->id,
            'from_status' => $lead->status,
            'to_status' => $lead->status,
            'changed_by_user_id' => Auth::id(),
            'notes' => $notes,
        ]);

        return back()->with('success', 'B2C Buyer lead successfully assigned to representative.');
    }

    /**
     * Assign multiple B2C buyer leads to one tele-caller.
     */
    public function bulkAssign(Request $request): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403);
        }

        $validated = $request->validate([
            'lead_ids' => ['required', 'array', 'min:1'],
            'lead_ids.*' => ['integer', 'exists:b2_c_leads,id'],
            'assigned_sales_person_id' => ['nullable', 'exists:sales_people,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $newSalesPerson = SalesPerson::find($validated['assigned_sales_person_id'] ?? null);
        $targetName = $newSalesPerson ? $newSalesPerson->name : 'Unassigned';
        $leads = B2CLead::with('assignedSalesPerson')
            ->whereIn('id', $validated['lead_ids'])
            ->get();

        foreach ($leads as $lead) {
            $oldSalesPerson = $lead->assignedSalesPerson;
            $lead->update([
                'assigned_sales_person_id' => $validated['assigned_sales_person_id'] ?? null,
            ]);

            $notes = 'Bulk reassigned B2C Lead from '
                . ($oldSalesPerson ? $oldSalesPerson->name : 'Unassigned')
                . ' to ' . $targetName . '.';

            if (!empty($validated['notes'])) {
                $notes .= ' Note: ' . $validated['notes'];
            }

            LeadStatusLog::create([
                'lead_type' => B2CLead::class,
                'lead_id' => $lead->id,
                'from_status' => $lead->status,
                'to_status' => $lead->status,
                'changed_by_user_id' => Auth::id(),
                'notes' => $notes,
            ]);
        }

        return back()->with('success', $leads->count() . ' B2C buyer lead(s) assigned to ' . $targetName . '.');
    }
}
