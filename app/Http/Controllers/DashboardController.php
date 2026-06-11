<?php

namespace App\Http\Controllers;

use App\Helpers\DateFilterHelper;
use App\Models\B2BLead;
use App\Models\B2CLead;
use App\Models\B2CLeadShare;
use App\Models\Partner;
use App\Models\PlatformSpend;
use App\Models\SalesPerson;
use App\Models\FollowUp;
use App\Services\PackageExpiryNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        app(PackageExpiryNotifier::class)->notifyAdmins();

        $driver = DB::connection()->getDriverName();
        $dailyDateExpression = $driver === 'sqlite'
            ? 'strftime("%Y-%m-%d", lead_created_at)'
            : 'DATE_FORMAT(lead_created_at, "%Y-%m-%d")';
        $monthlyDateExpression = $driver === 'sqlite'
            ? 'strftime("%Y-%m", lead_created_at)'
            : 'DATE_FORMAT(lead_created_at, "%Y-%m")';

        $filter = $request->input('date_filter', 'last_30_days'); // default to 30 days
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // B2B & B2C Queries with Date Filters
        $b2bQuery = B2BLead::query();
        $b2cQuery = B2CLead::query();
        $sharesQuery = B2CLeadShare::query();
        $spendsQuery = PlatformSpend::query();

        DateFilterHelper::apply($b2bQuery, $filter, 'lead_created_at', $startDate, $endDate);
        DateFilterHelper::apply($b2cQuery, $filter, 'lead_created_at', $startDate, $endDate);
        DateFilterHelper::apply($sharesQuery, $filter, 'shared_at', $startDate, $endDate);
        DateFilterHelper::apply($spendsQuery, $filter, 'spent_on', $startDate, $endDate);

        // Core Counts
        $stats = [
            'total_b2b_leads' => $b2bQuery->count(),
            'total_b2c_leads' => $b2cQuery->count(),
            'active_partners' => Partner::where('is_active', true)->count(),
            'leads_shared' => $sharesQuery->count(),
            'package_expiry_alerts' => Partner::where('is_active', true)
                ->whereNotNull('renewal_date')
                ->whereBetween('renewal_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
                ->count(),
        ];

        // Specific Partner Analytics counts
        $totalActiveAgents = Partner::where('is_active', true)->where('type', 'agent')->count();
        $totalActiveDevelopers = Partner::where('is_active', true)->where('type', 'developer')->count();

        // Platform-wise lead distributions
        $platforms = ['meta', 'google', 'website', 'manual', 'csv'];

        // MODULE 1 - B2B Platform Conversion Analytics
        $b2bPlatformStats = [];
        foreach ($platforms as $p) {
            $total = (clone $b2bQuery)->where('source_platform', $p)->count();
            $paid = (clone $b2bQuery)->where('source_platform', $p)->whereIn('status', ['paid_listing', 'converted'])->count();
            $ratio = $total > 0 ? round(($paid / $total) * 100, 1) : 0;
            $b2bPlatformStats[$p] = [
                'total' => $total,
                'paid' => $paid,
                'ratio' => $ratio
            ];
        }

        // MODULE 1 - B2C Platform Performance Analytics
        $b2cPlatformStats = [];
        foreach ($platforms as $p) {
            $total = (clone $b2cQuery)->where('source_platform', $p)->count();
            $shared = (clone $b2cQuery)->where('source_platform', $p)->whereHas('shares')->count();
            $ratio = $total > 0 ? round(($shared / $total) * 100, 1) : 0;
            $b2cPlatformStats[$p] = [
                'total' => $total,
                'shared' => $shared,
                'ratio' => $ratio
            ];
        }

        // Overall Conversion Ratios
        $convertedB2B = (clone $b2bQuery)->whereIn('status', ['free_listing', 'paid_listing', 'converted'])->count();
        $b2bConversionRatio = $stats['total_b2b_leads'] > 0 
            ? round(($convertedB2B / $stats['total_b2b_leads']) * 100, 1) 
            : 0;

        $sharedB2C = (clone $b2cQuery)->whereHas('shares')->count();
        $b2cConversionRatio = $stats['total_b2c_leads'] > 0 
            ? round(($sharedB2C / $stats['total_b2c_leads']) * 100, 1) 
            : 0;

        // Merge platforms for charts.
        $platformChartData = [];
        $platformSpendStats = [];
        foreach ($platforms as $p) {
            $platformChartData[$p] = ($b2bPlatformStats[$p]['total'] ?? 0) + ($b2cPlatformStats[$p]['total'] ?? 0);
            $spend = (clone $spendsQuery)->where('source_platform', $p)->sum('amount');
            $leads = $platformChartData[$p] ?: 0;
            $platformSpendStats[$p] = [
                'spend' => $spend,
                'cpl' => $leads > 0 ? round($spend / $leads, 2) : 0,
            ];
        }

        // Daily Leads Graphs Categories
        $dailyTrendsB2B = (clone $b2bQuery)
            ->selectRaw($dailyDateExpression . ' as date, count(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $dailyTrendsB2C = (clone $b2cQuery)
            ->selectRaw($dailyDateExpression . ' as date, count(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $allDates = array_unique(array_merge(array_keys($dailyTrendsB2B), array_keys($dailyTrendsB2C)));
        sort($allDates);

        if (count($allDates) > 15) {
            $allDates = array_slice($allDates, -15);
        }

        $chartDailyCategories = [];
        $chartDailyB2B = [];
        $chartDailyB2C = [];

        foreach ($allDates as $d) {
            if (empty($d)) continue;
            $chartDailyCategories[] = Carbon::parse($d)->format('d M');
            $chartDailyB2B[] = $dailyTrendsB2B[$d] ?? 0;
            $chartDailyB2C[] = $dailyTrendsB2C[$d] ?? 0;
        }

        // Monthly Leads & Conversion Trends (ApexCharts)
        $monthlyB2B = (clone $b2bQuery)
            ->selectRaw($monthlyDateExpression . ' as month, count(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyB2C = (clone $b2cQuery)
            ->selectRaw($monthlyDateExpression . ' as month, count(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyConversions = (clone $b2bQuery)
            ->whereIn('status', ['free_listing', 'paid_listing', 'converted'])
            ->selectRaw($monthlyDateExpression . ' as month, count(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $allMonths = array_unique(array_merge(array_keys($monthlyB2B), array_keys($monthlyB2C)));
        sort($allMonths);

        $chartMonthlyCategories = [];
        $chartMonthlyB2B = [];
        $chartMonthlyB2C = [];
        $chartMonthlyConversions = [];

        foreach ($allMonths as $m) {
            if (empty($m)) continue;
            $chartMonthlyCategories[] = Carbon::parse($m . '-01')->format('M Y');
            $chartMonthlyB2B[] = $monthlyB2B[$m] ?? 0;
            $chartMonthlyB2C[] = $monthlyB2C[$m] ?? 0;
            $chartMonthlyConversions[] = $monthlyConversions[$m] ?? 0;
        }

        // Best Sales Representatives Performance Ranking
        $salesPeople = SalesPerson::where('is_active', true)->get();
        $salesPeople->transform(function ($person) {
            $leadsQuery = B2BLead::where('assigned_sales_person_id', $person->id);
            $person->assigned_count = $leadsQuery->count();
            $person->conversions = (clone $leadsQuery)->whereIn('status', ['free_listing', 'paid_listing', 'converted'])->count();
            $person->calls_count = FollowUp::where('sales_person_id', $person->id)->count();
            
            // Total pricing mapping: Starter = 15k, Growth = 45k
            $partners = Partner::where('assigned_sales_person_id', $person->id)->get();
            $person->sales_value = 0;
            foreach ($partners as $partner) {
                if ($partner->paid_amount !== null) {
                    $person->sales_value += $partner->paid_amount;
                } elseif ($partner->package === 'starter') {
                    $person->sales_value += 15000;
                } elseif ($partner->package === 'growth') {
                    $person->sales_value += 45000;
                }
            }
            return $person;
        });

        // Sort descending by sales value
        $salesLeaderboard = $salesPeople->sortByDesc('sales_value')->values()->take(5);

        // Sales KPI Aggregates
        $bestSalesPerson = $salesLeaderboard->first() ? $salesLeaderboard->first()->name : 'N/A';
        $totalCalls = FollowUp::count();
        $paidConversionsCount = (clone $b2bQuery)->whereIn('status', ['paid_listing', 'converted'])->count();
        $freeConversionsCount = (clone $b2bQuery)->where('status', 'free_listing')->count();

        // Active Expiry Alerts details list
        $expiryAlerts = Partner::where('is_active', true)
            ->whereNotNull('renewal_date')
            ->whereBetween('renewal_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
            ->orderBy('renewal_date')
            ->get();

        return view('dashboard', [
            'stats' => $stats,
            'totalActiveAgents' => $totalActiveAgents,
            'totalActiveDevelopers' => $totalActiveDevelopers,
            
            'b2bPlatformStats' => $b2bPlatformStats,
            'b2cPlatformStats' => $b2cPlatformStats,
            'b2bConversionRatio' => $b2bConversionRatio,
            'b2cConversionRatio' => $b2cConversionRatio,
            
            'platformChartData' => $platformChartData,
            'platformSpendStats' => $platformSpendStats,
            
            'chartDailyCategories' => $chartDailyCategories,
            'chartDailyB2B' => $chartDailyB2B,
            'chartDailyB2C' => $chartDailyB2C,
            
            'chartMonthlyCategories' => $chartMonthlyCategories,
            'chartMonthlyB2B' => $chartMonthlyB2B,
            'chartMonthlyB2C' => $chartMonthlyB2C,
            'chartMonthlyConversions' => $chartMonthlyConversions,
            
            'salesLeaderboard' => $salesLeaderboard,
            'bestSalesPerson' => $bestSalesPerson,
            'totalCalls' => $totalCalls,
            'paidConversionsCount' => $paidConversionsCount,
            'freeConversionsCount' => $freeConversionsCount,
            
            'expiryAlerts' => $expiryAlerts,
            'activeFilters' => [
                'date_filter' => $filter,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        ]);
    }
}
