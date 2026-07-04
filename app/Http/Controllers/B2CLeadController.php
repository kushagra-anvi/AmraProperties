<?php

namespace App\Http\Controllers;

use App\Helpers\DateFilterHelper;
use App\Models\B2CLead;
use App\Models\B2CLeadShare;
use App\Models\Partner;
use App\Models\LeadStatusLog;
use App\Models\SalesPerson;
use App\Services\B2CLeadAutoDistributor;
use App\Services\B2CLeadSharingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class B2CLeadController extends Controller
{

    /**
     * Display a listing of B2C Buyer Leads.
     */
    public function index(Request $request): View
    {
        $query = B2CLead::with('assignedSalesPerson')->withCount('shares');

        if ($request->boolean('due_only')) {
            $query->whereHas('followUps', function ($q) {
                $q->whereNull('completed_at')
                  ->where('due_at', '<=', now()->endOfDay());
                if (auth()->user()->role === 'sales_team') {
                    $q->where('sales_person_id', auth()->user()->salesPerson?->id);
                }
            });
        }

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
        $partners = Partner::where('is_active', true)->orderBy('company_name')->get();

        $shareFilter = $request->input('share_date_filter');
        $shareStartDate = $request->input('share_start_date');
        $shareEndDate = $request->input('share_end_date');
        $shareActivityQuery = B2CLeadShare::query();
        DateFilterHelper::apply($shareActivityQuery, $shareFilter, 'shared_at', $shareStartDate, $shareEndDate);
        $shareActivityStats = [
            'total' => (clone $shareActivityQuery)->count(),
            'sales_team' => (clone $shareActivityQuery)->where('recipient_type', 'sales_team')->count(),
            'builders' => (clone $shareActivityQuery)->where('recipient_type', 'builder')->count(),
            'agents' => (clone $shareActivityQuery)->where('recipient_type', 'agent')->count(),
        ];
        $recentShareActivity = (clone $shareActivityQuery)
            ->with(['lead', 'partner', 'salesPerson', 'sharedBy'])
            ->orderByDesc('shared_at')
            ->limit(10)
            ->get();

        return view('crm.b2c.index', [
            'leads' => $leads,
            'salesPeople' => $salesPeople,
            'partners' => $partners,
            'shareActivityStats' => $shareActivityStats,
            'recentShareActivity' => $recentShareActivity,
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
                'share_date_filter' => $shareFilter,
                'share_start_date' => $shareStartDate,
                'share_end_date' => $shareEndDate,
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
        $lead->load(['shares.partner', 'shares.salesPerson', 'shares.sharedBy', 'statusLogs.changedByUser', 'assignedSalesPerson', 'followUps.salesPerson']);
        
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
     * Share a B2C lead with selected sales team members, builders, or agents.
     */
    public function share(Request $request, B2CLead $lead): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403, 'Analysts cannot distribute leads.');
        }

        $validated = $request->validate([
            'partner_ids' => ['nullable', 'array'],
            'partner_ids.*' => ['exists:partners,id'],
            'sales_person_ids' => ['nullable', 'array'],
            'sales_person_ids.*' => ['exists:sales_people,id'],
            'remark' => ['nullable', 'string'],
        ]);

        $this->ensureRecipientsSelected($validated);
        $shareCount = app(B2CLeadSharingService::class)->share(
            collect([$lead]),
            $validated['partner_ids'] ?? [],
            $validated['sales_person_ids'] ?? [],
            Auth::id(),
            $validated['remark'] ?? null
        );

        return back()->with('success', "Lead shared successfully in {$shareCount} recipient delivery(s).");
    }

    public function bulkShare(Request $request): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403, 'Analysts cannot share leads.');
        }

        $validated = $request->validate([
            'lead_ids' => ['required', 'array', 'min:1'],
            'lead_ids.*' => ['integer', 'exists:b2_c_leads,id'],
            'partner_ids' => ['nullable', 'array'],
            'partner_ids.*' => ['integer', 'exists:partners,id'],
            'sales_person_ids' => ['nullable', 'array'],
            'sales_person_ids.*' => ['integer', 'exists:sales_people,id'],
            'remark' => ['nullable', 'string'],
        ]);
        $this->ensureRecipientsSelected($validated);

        $leads = B2CLead::whereIn('id', $validated['lead_ids'])->get();
        $shareCount = app(B2CLeadSharingService::class)->share(
            $leads,
            $validated['partner_ids'] ?? [],
            $validated['sales_person_ids'] ?? [],
            Auth::id(),
            $validated['remark'] ?? null
        );

        return back()->with('success', $leads->count() . " lead(s) shared in {$shareCount} recipient delivery(s).");
    }

    public function updateStatus(Request $request, B2CLead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,shared,contacted,follow_up,site_visit_scheduled,interested,closed_won,closed_lost'],
            'notes' => ['nullable', 'string'],
        ]);

        $oldStatus = $lead->status;
        if ($oldStatus !== $validated['status']) {
            $lead->update(['status' => $validated['status']]);
            LeadStatusLog::create([
                'lead_type' => B2CLead::class,
                'lead_id' => $lead->id,
                'from_status' => $oldStatus,
                'to_status' => $validated['status'],
                'changed_by_user_id' => Auth::id(),
                'notes' => $validated['notes'] ?? 'Lead stage updated.',
            ]);
        }

        return back()->with('success', 'Lead status updated successfully.');
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

    private function ensureRecipientsSelected(array $validated): void
    {
        if (empty($validated['partner_ids']) && empty($validated['sales_person_ids'])) {
            throw ValidationException::withMessages([
                'recipients' => 'Select at least one Sales Team member, Builder, or Agent.',
            ]);
        }
    }

    /**
     * Add a follow up to a B2C Lead.
     */
    public function logFollowUp(Request $request, B2CLead $lead): RedirectResponse
    {
        $user = Auth::user();

        // RBAC Check
        if ($user->role === 'sales_team') {
            $salesPerson = $user->salesPerson;
            if (!$salesPerson || $lead->assigned_sales_person_id !== $salesPerson->id) {
                abort(403);
            }
        }

        $request->validate([
            'notes' => ['required', 'string'],
            'due_at' => ['nullable', 'date'],
            'outcome' => ['nullable', 'string', 'max:255'],
            'completed' => ['boolean'],
        ]);

        $salesPerson = $lead->assignedSalesPerson;

        \App\Models\FollowUp::create([
            'followable_type' => B2CLead::class,
            'followable_id' => $lead->id,
            'sales_person_id' => $salesPerson ? $salesPerson->id : null,
            'user_id' => Auth::id(),
            'due_at' => $request->input('due_at'),
            'completed_at' => $request->boolean('completed') ? now() : null,
            'outcome' => $request->input('outcome'),
            'notes' => $request->input('notes'),
        ]);

        return back()->with('success', 'Follow-up reminder successfully scheduled.');
    }

    /**
     * Mark a follow up as completed.
     */
    public function completeFollowUp(Request $request, \App\Models\FollowUp $followUp): RedirectResponse
    {
        $followUp->update([
            'completed_at' => now(),
            'outcome' => $request->input('outcome', 'Completed'),
        ]);

        return back()->with('success', 'Follow-up successfully completed.');
    }
}
