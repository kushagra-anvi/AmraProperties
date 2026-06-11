<?php

namespace App\Http\Controllers;

use App\Helpers\DateFilterHelper;
use App\Models\Partner;
use App\Models\B2CLeadShare;
use App\Models\SalesPerson;
use App\Services\PackageExpiryNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerController extends Controller
{

    /**
     * Display a listing of Partners.
     */
    public function index(Request $request): View
    {
        app(PackageExpiryNotifier::class)->notifyAdmins();

        $query = Partner::with('assignedSalesPerson');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('package')) {
            $query->where('package', $request->input('package'));
        }

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        // Subquery to calculate leads received by partner, filtered by date
        $filter = $request->input('date_filter', 'last_30_days'); // default to last 30 days
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $sharesSubquery = B2CLeadShare::whereColumn('partner_id', 'partners.id');
        DateFilterHelper::apply($sharesSubquery, $filter, 'shared_at', $startDate, $endDate);
        
        $query->select('partners.*')
              ->selectSub($sharesSubquery->selectRaw('count(*)'), 'leads_received_count');

        $partners = $query->orderByDesc('package_purchase_date')->paginate(15)->withQueryString();
        
        // Count package expiry alerts in the next 7 days
        $expiringCount = Partner::where('is_active', true)
            ->whereNotNull('renewal_date')
            ->whereBetween('renewal_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
            ->count();

        return view('crm.partners.index', [
            'partners' => $partners,
            'expiringCount' => $expiringCount,
            'activeFilters' => [
                'search' => $request->input('search'),
                'type' => $request->input('type'),
                'package' => $request->input('package'),
                'city' => $request->input('city'),
                'date_filter' => $filter,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        ]);
    }

    /**
     * Show the form for creating a new Partner.
     */
    public function create(): View
    {
        if (Auth::user()->role === 'analyst') {
            abort(403);
        }

        $salesPeople = SalesPerson::where('is_active', true)->orderBy('name')->get();
        return view('crm.partners.create', compact('salesPeople'));
    }

    /**
     * Store a newly created Partner in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403);
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:agent,developer,affiliate'],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'office_address' => ['nullable', 'string'],
            'service_areas' => ['nullable', 'array'],
            'city' => ['required', 'string', 'max:100'],
            'package' => ['required', 'string', 'in:free,starter,growth,premium,customise'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'package_purchase_date' => ['nullable', 'date'],
            'renewal_date' => ['nullable', 'date', 'after_or_equal:package_purchase_date'],
            'assigned_sales_person_id' => ['nullable', 'exists:sales_people,id'],
            'lead_source' => ['nullable', 'string', 'max:100'],
            'remark' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = true;
        Partner::create($validated);

        return redirect()->route('crm.partners.index')
            ->with('success', 'Partner account successfully created!');
    }

    /**
     * Display the specified Partner.
     */
    public function show(Partner $partner, Request $request): View
    {
        $partner->load('assignedSalesPerson');

        // Dynamic leads received list with date filter
        $filter = $request->input('date_filter', 'last_30_days');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $sharesQuery = B2CLeadShare::with(['lead', 'sharedBy'])
            ->where('partner_id', $partner->id);

        DateFilterHelper::apply($sharesQuery, $filter, 'shared_at', $startDate, $endDate);
        
        $shares = $sharesQuery->orderByDesc('shared_at')->get();

        return view('crm.partners.show', [
            'partner' => $partner,
            'shares' => $shares,
            'activeFilters' => [
                'date_filter' => $filter,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified Partner.
     */
    public function edit(Partner $partner): View
    {
        if (Auth::user()->role === 'analyst') {
            abort(403);
        }

        $salesPeople = SalesPerson::where('is_active', true)->orderBy('name')->get();
        return view('crm.partners.edit', compact('partner', 'salesPeople'));
    }

    /**
     * Update the specified Partner in storage.
     */
    public function update(Request $request, Partner $partner): RedirectResponse
    {
        if (Auth::user()->role === 'analyst') {
            abort(403);
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:agent,developer,affiliate'],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'office_address' => ['nullable', 'string'],
            'service_areas' => ['nullable', 'array'],
            'city' => ['required', 'string', 'max:100'],
            'package' => ['required', 'string', 'in:free,starter,growth,premium,customise'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'package_purchase_date' => ['nullable', 'date'],
            'renewal_date' => ['nullable', 'date', 'after_or_equal:package_purchase_date'],
            'assigned_sales_person_id' => ['nullable', 'exists:sales_people,id'],
            'lead_source' => ['nullable', 'string', 'max:100'],
            'remark' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $partner->update($validated);

        return redirect()->route('crm.partners.show', $partner->id)
            ->with('success', 'Partner account successfully updated.');
    }
}
