<?php

namespace App\Http\Controllers;

use App\Helpers\DateFilterHelper;
use App\Models\B2BLead;
use App\Models\LeadStatusLog;
use App\Models\Partner;
use App\Models\SalesPerson;
use App\Models\FollowUp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class B2BLeadController extends Controller
{
    /**
     * Display a listing of B2B Leads.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = B2BLead::with('assignedSalesPerson');

        // RBAC: Sales Team can only see their own assigned B2B Leads
        if ($user->role === 'sales_team') {
            $salesPerson = $user->salesPerson;
            if ($salesPerson) {
                $query->where('assigned_sales_person_id', $salesPerson->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Apply filters
        if ($request->boolean('due_only')) {
            $query->whereHas('followUps', function ($q) use ($user) {
                $q->whereNull('completed_at')
                  ->where('due_at', '<=', now()->endOfDay());
                if ($user->role === 'sales_team') {
                    $q->where('sales_person_id', $user->salesPerson?->id);
                }
            });
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person_name', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'conversions') {
                $query->whereIn('status', ['free_listing', 'paid_listing', 'converted']);
            } elseif ($status === 'conversions_free') {
                $query->where('status', 'free_listing');
            } elseif ($status === 'conversions_paid') {
                $query->whereIn('status', ['paid_listing', 'converted']);
            } else {
                $query->where('status', $status);
            }
        }

        if ($request->filled('sales_person_id') && $user->role !== 'sales_team') {
            $query->where('assigned_sales_person_id', $request->input('sales_person_id'));
        }

        // Apply Date Filters
        $filter = $request->input('date_filter');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        DateFilterHelper::apply($query, $filter, 'lead_created_at', $startDate, $endDate);

        $leads = $query->orderByDesc('lead_created_at')->paginate(15)->withQueryString();
        $salesPeople = SalesPerson::where('is_active', true)->orderBy('name')->get();

        return view('crm.b2b.index', [
            'leads' => $leads,
            'salesPeople' => $salesPeople,
            'activeFilters' => [
                'search' => $request->input('search'),
                'category' => $request->input('category'),
                'status' => $request->input('status'),
                'sales_person_id' => $request->input('sales_person_id'),
                'date_filter' => $filter,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        ]);
    }

    /**
     * Show the form for creating a new B2B Lead.
     */
    public function create(): View
    {
        $salesPeople = SalesPerson::where('is_active', true)->orderBy('name')->get();
        return view('crm.b2b.create', compact('salesPeople'));
    }

    /**
     * Store a newly created B2B Lead in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'in:agent,developer,single_owner'],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'office_address' => ['nullable', 'string'],
            'service_areas' => ['nullable', 'array'],
            'city' => ['required', 'string', 'max:100'],
            'project_ticket_size_min' => ['nullable', 'numeric', 'min:0'],
            'project_ticket_size_max' => ['nullable', 'numeric', 'min:0'],
            'source_platform' => ['required', 'string', 'in:meta,google,website,manual,csv'],
            'assigned_sales_person_id' => ['nullable', 'exists:sales_people,id'],
            'remark' => ['nullable', 'string'],
        ]);

        // Sales Team users can only create and assign leads to themselves
        if (Auth::user()->role === 'sales_team') {
            $salesPerson = Auth::user()->salesPerson;
            $validated['assigned_sales_person_id'] = $salesPerson ? $salesPerson->id : null;
        }

        $validated['status'] = 'new';
        $validated['lead_created_at'] = now();

        $lead = B2BLead::create($validated);

        // Log the initial status creation
        LeadStatusLog::create([
            'lead_type' => B2BLead::class,
            'lead_id' => $lead->id,
            'from_status' => null,
            'to_status' => 'new',
            'changed_by_user_id' => Auth::id(),
            'notes' => 'Lead created manually.',
        ]);

        return redirect()->route('crm.b2b.index')
            ->with('success', 'B2B Lead created successfully!');
    }

    /**
     * Display the specified B2B Lead.
     */
    public function show(B2BLead $lead): View
    {
        $user = Auth::user();
        
        // RBAC Check
        if ($user->role === 'sales_team') {
            $salesPerson = $user->salesPerson;
            if (!$salesPerson || $lead->assigned_sales_person_id !== $salesPerson->id) {
                abort(403, 'Unauthorized access to this B2B Lead.');
            }
        }

        $lead->load(['assignedSalesPerson', 'convertedPartner', 'statusLogs.changedByUser', 'followUps.salesPerson']);
        $salesPeople = SalesPerson::where('is_active', true)->orderBy('name')->get();

        return view('crm.b2b.show', compact('lead', 'salesPeople'));
    }

    /**
     * Show the form for editing the specified B2B Lead.
     */
    public function edit(B2BLead $lead): View
    {
        $user = Auth::user();
        
        // RBAC Check
        if ($user->role === 'sales_team') {
            $salesPerson = $user->salesPerson;
            if (!$salesPerson || $lead->assigned_sales_person_id !== $salesPerson->id) {
                abort(403, 'Unauthorized access to this B2B Lead.');
            }
        }

        $salesPeople = SalesPerson::where('is_active', true)->orderBy('name')->get();
        return view('crm.b2b.edit', compact('lead', 'salesPeople'));
    }

    /**
     * Update the specified B2B Lead in storage.
     */
    public function update(Request $request, B2BLead $lead): RedirectResponse
    {
        $user = Auth::user();
        
        // RBAC Check
        if ($user->role === 'sales_team') {
            $salesPerson = $user->salesPerson;
            if (!$salesPerson || $lead->assigned_sales_person_id !== $salesPerson->id) {
                abort(403, 'Unauthorized access to this B2B Lead.');
            }
        }

        $validated = $request->validate([
            'category' => ['required', 'string', 'in:agent,developer,single_owner'],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'office_address' => ['nullable', 'string'],
            'service_areas' => ['nullable', 'array'],
            'city' => ['required', 'string', 'max:100'],
            'project_ticket_size_min' => ['nullable', 'numeric', 'min:0'],
            'project_ticket_size_max' => ['nullable', 'numeric', 'min:0'],
            'assigned_sales_person_id' => ['nullable', 'exists:sales_people,id'],
            'remark' => ['nullable', 'string'],
        ]);

        if ($user->role === 'sales_team') {
            unset($validated['assigned_sales_person_id']); // Cannot reassign
        }

        $lead->update($validated);

        return redirect()->route('crm.b2b.show', $lead->id)
            ->with('success', 'B2B Lead updated successfully.');
    }

    /**
     * Update the status of a B2B Lead.
     */
    public function updateStatus(Request $request, B2BLead $lead): RedirectResponse
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
            'status' => ['required', 'string', 'in:new,contacted,qualified,not_interested,follow_up,free_listing,paid_listing,converted'],
            'conversion_package' => ['nullable', 'string', 'in:free,starter,growth,premium,customise'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $oldStatus = $lead->status;
        $newStatus = $request->input('status');

        if ($oldStatus !== $newStatus) {
            $lead->update(['status' => $newStatus]);

            $conversionNote = $this->syncConvertedPartner($lead, $request);

            LeadStatusLog::create([
                'lead_type' => B2BLead::class,
                'lead_id' => $lead->id,
                'from_status' => $oldStatus,
                'to_status' => $newStatus,
                'changed_by_user_id' => Auth::id(),
                'notes' => trim(($request->input('notes') ?? '') . ' ' . $conversionNote),
            ]);
        }

        return back()->with('success', 'Status updated successfully.');
    }

    private function syncConvertedPartner(B2BLead $lead, Request $request): string
    {
        if (!in_array($lead->status, ['free_listing', 'paid_listing', 'converted'], true)) {
            return '';
        }

        if (!in_array($lead->category, ['agent', 'developer'], true)) {
            return 'Single property owner conversion kept in B2B pipeline; no partner record created.';
        }

        $package = $lead->status === 'free_listing'
            ? 'free'
            : ($request->input('conversion_package') ?: 'starter');

        $defaultPaidAmount = match ($package) {
            'starter' => 15000,
            'growth' => 45000,
            'premium' => 90000,
            default => 0,
        };

        $paidAmount = $request->filled('paid_amount')
            ? $request->input('paid_amount')
            : $defaultPaidAmount;

        $partner = $lead->convertedPartner;

        if (!$partner) {
            $partner = Partner::where('company_name', $lead->company_name)
                ->where(function ($query) use ($lead) {
                    $query->where('phone', $lead->contact_number);

                    if ($lead->email) {
                        $query->orWhere('email', $lead->email);
                    }
                })
                ->first();
        }

        $partnerData = [
            'type' => $lead->category,
            'company_name' => $lead->company_name,
            'contact_person' => $lead->contact_person_name,
            'phone' => $lead->contact_number,
            'email' => $lead->email,
            'office_address' => $lead->office_address,
            'service_areas' => $lead->service_areas,
            'city' => $lead->city,
            'package' => $package,
            'paid_amount' => $paidAmount,
            'package_purchase_date' => now()->toDateString(),
            'renewal_date' => now()->addYear()->toDateString(),
            'assigned_sales_person_id' => $lead->assigned_sales_person_id,
            'lead_source' => $lead->source_platform,
            'remark' => $lead->remark,
            'is_active' => true,
        ];

        if ($partner) {
            $partner->update($partnerData);
        } else {
            $partner = Partner::create($partnerData);
        }

        if ($lead->converted_partner_id !== $partner->id) {
            $lead->update(['converted_partner_id' => $partner->id]);
        }

        return "Partner database synced: {$partner->company_name} ({$package}).";
    }

    /**
     * Reassign a B2B Lead.
     */
    public function assign(Request $request, B2BLead $lead): RedirectResponse
    {
        // Enforce RBAC: Sales Team cannot assign
        if (Auth::user()->role === 'sales_team') {
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
        $notes = 'Reassigned from ' . ($oldSalesPerson ? $oldSalesPerson->name : 'Unassigned') . 
                 ' to ' . ($newSalesPerson ? $newSalesPerson->name : 'Unassigned') . '.';
        
        if ($request->filled('notes')) {
            $notes .= ' Note: ' . $request->input('notes');
        }

        LeadStatusLog::create([
            'lead_type' => B2BLead::class,
            'lead_id' => $lead->id,
            'from_status' => $lead->status,
            'to_status' => $lead->status,
            'changed_by_user_id' => Auth::id(),
            'notes' => $notes,
        ]);

        return back()->with('success', 'Lead successfully reassigned.');
    }

    /**
     * Add a follow up to a B2B Lead.
     */
    public function logFollowUp(Request $request, B2BLead $lead): RedirectResponse
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

        FollowUp::create([
            'followable_type' => B2BLead::class,
            'followable_id' => $lead->id,
            'sales_person_id' => $salesPerson ? $salesPerson->id : null,
            'user_id' => Auth::id(),
            'due_at' => $request->input('due_at'),
            'completed_at' => $request->boolean('completed') ? now() : null,
            'outcome' => $request->input('outcome'),
            'notes' => $request->input('notes'),
        ]);

        return back()->with('success', 'Follow-up log successfully added.');
    }
}
