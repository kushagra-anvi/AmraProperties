<?php

namespace App\Http\Controllers;

use App\Helpers\DateFilterHelper;
use App\Models\SalesPerson;
use App\Models\B2BLead;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SalesTeamController extends Controller
{

    /**
     * Display a listing of Sales Team members and their metrics.
     */
    public function index(Request $request): View
    {
        $filter = $request->input('date_filter');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $salesPeopleQuery = SalesPerson::where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $salesPeopleQuery->where('name', 'like', "%{$search}%");
        }

        $salesPeople = $salesPeopleQuery->orderBy('name')->get();

        // Calculate KPIs dynamically for each sales representative
        $salesPeople->transform(function ($person) use ($filter, $startDate, $endDate) {
            $leadsQuery = B2BLead::where('assigned_sales_person_id', $person->id);
            DateFilterHelper::apply($leadsQuery, $filter, 'lead_created_at', $startDate, $endDate);
            
            $person->assigned_count = $leadsQuery->count();
            $person->contacted_count = (clone $leadsQuery)->where('status', 'contacted')->count();
            $person->qualified_count = (clone $leadsQuery)->where('status', 'qualified')->count();
            $person->not_interested_count = (clone $leadsQuery)->where('status', 'not_interested')->count();
            
            $person->free_listing_count = (clone $leadsQuery)->where('status', 'free_listing')->count();
            $person->paid_listing_count = (clone $leadsQuery)->where('status', 'paid_listing')->count();
            $person->converted_count = (clone $leadsQuery)->where('status', 'converted')->count();

            // Calculate total sale based on converted partners subscription packages:
            // Starter = 15,000 INR, Growth = 45,000 INR, Free = 0
            $partnersQuery = Partner::where('assigned_sales_person_id', $person->id);
            DateFilterHelper::apply($partnersQuery, $filter, 'package_purchase_date', $startDate, $endDate);
            $totalSale = 0;
            foreach ($partnersQuery->get() as $partner) {
                if ($partner->paid_amount !== null) {
                    $totalSale += $partner->paid_amount;
                } elseif ($partner->package === 'starter') {
                    $totalSale += 15000;
                } elseif ($partner->package === 'growth') {
                    $totalSale += 45000;
                }
            }
            $person->total_sale = $totalSale;

            // Conversion Ratios
            $person->free_ratio = $person->assigned_count > 0 
                ? round(($person->free_listing_count / $person->assigned_count) * 100, 1) 
                : 0;

            $person->paid_ratio = $person->assigned_count > 0 
                ? round(($person->paid_listing_count / $person->assigned_count) * 100, 1) 
                : 0;

            return $person;
        });

        return view('crm.sales.index', [
            'salesPeople' => $salesPeople,
            'search' => $request->input('search'),
            'activeFilters' => [
                'date_filter' => $filter,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    /**
     * Display the specified Sales Team member's performance profile.
     */
    public function show(Request $request, SalesPerson $salesPerson): View
    {
        $filter = $request->input('date_filter');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $leadsQuery = B2BLead::where('assigned_sales_person_id', $salesPerson->id);
        DateFilterHelper::apply($leadsQuery, $filter, 'lead_created_at', $startDate, $endDate);
        
        $stats = [
            'assigned' => $leadsQuery->count(),
            'contacted' => (clone $leadsQuery)->where('status', 'contacted')->count(),
            'qualified' => (clone $leadsQuery)->where('status', 'qualified')->count(),
            'not_interested' => (clone $leadsQuery)->where('status', 'not_interested')->count(),
            'free_listing' => (clone $leadsQuery)->where('status', 'free_listing')->count(),
            'paid_listing' => (clone $leadsQuery)->where('status', 'paid_listing')->count(),
            'converted' => (clone $leadsQuery)->where('status', 'converted')->count(),
        ];

        // Total Sale Calculation
        $partnersQuery = Partner::where('assigned_sales_person_id', $salesPerson->id);
        DateFilterHelper::apply($partnersQuery, $filter, 'package_purchase_date', $startDate, $endDate);
        $partners = $partnersQuery->get();
        $totalSale = 0;
        foreach ($partners as $partner) {
            if ($partner->paid_amount !== null) {
                $totalSale += $partner->paid_amount;
            } elseif ($partner->package === 'starter') {
                $totalSale += 15000;
            } elseif ($partner->package === 'growth') {
                $totalSale += 45000;
            }
        }
        $stats['total_sale'] = $totalSale;

        // Conversion ratios
        $stats['free_ratio'] = $stats['assigned'] > 0 ? round(($stats['free_listing'] / $stats['assigned']) * 100, 1) : 0;
        $stats['paid_ratio'] = $stats['assigned'] > 0 ? round(($stats['paid_listing'] / $stats['assigned']) * 100, 1) : 0;

        // Load the assigned B2B leads list
        $assignedLeads = $leadsQuery->orderByDesc('lead_created_at')->get();

        // Follow-up calls analytics queries
        $followUpsQuery = \App\Models\FollowUp::where('sales_person_id', $salesPerson->id)->with('followable');

        // Completed calls count in the filtered range
        $completedQuery = (clone $followUpsQuery)->whereNotNull('completed_at');
        DateFilterHelper::apply($completedQuery, $filter, 'completed_at', $startDate, $endDate);
        $stats['completed_followups'] = $completedQuery->count();

        // Pending scheduled follow-ups in the filtered range
        $pendingQuery = (clone $followUpsQuery)->whereNull('completed_at');
        DateFilterHelper::apply($pendingQuery, $filter, 'due_at', $startDate, $endDate);
        $stats['pending_followups'] = $pendingQuery->count();

        // Day-wise completed calls aggregation
        $dayWiseCalls = $completedQuery
            ->selectRaw('DATE(completed_at) as call_date, count(*) as call_count')
            ->groupBy('call_date')
            ->orderBy('call_date', 'desc')
            ->get();

        // Detailed individual recent follow-up activity logs
        $recentFollowUps = (clone $followUpsQuery)
            ->orderByRaw('CASE WHEN completed_at IS NOT NULL THEN completed_at ELSE due_at END DESC')
            ->limit(50)
            ->get();

        $activeFilters = [
            'date_filter' => $filter,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        return view('crm.sales.show', compact(
            'salesPerson', 
            'stats', 
            'assignedLeads', 
            'partners', 
            'activeFilters',
            'dayWiseCalls',
            'recentFollowUps'
        ));
    }
}
