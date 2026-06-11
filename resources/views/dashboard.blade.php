@extends('layouts.crm')

@section('title', 'CRM Executive Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Block with Global Filters -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-3xl font-serif font-extrabold text-white">CRM Executive Dashboard</h1>
            <p class="text-sm text-slate-400 mt-1 font-sans">Real-time oversight of lead acquisition, conversions, partner renewals, and sales statistics</p>
        </div>

        <!-- Global Dashboard Date Filters -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-sm shrink-0">
            <form action="{{ route('crm.dashboard') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="space-y-1">
                    <label for="date-filter-select" class="text-[9px] font-bold text-slate-550 uppercase tracking-wider block">Global Date Filter</label>
                    <select name="date_filter" id="date-filter-select" onchange="toggleDashboardDates()" class="bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-2 outline-none text-xs text-slate-350 transition-all font-semibold">
                        <option value="today" {{ $activeFilters['date_filter'] === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ $activeFilters['date_filter'] === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="last_7_days" {{ $activeFilters['date_filter'] === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="last_30_days" {{ $activeFilters['date_filter'] === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="custom" {{ $activeFilters['date_filter'] === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>

                <div id="dashboard-custom-dates" class="grid grid-cols-2 gap-2 hidden">
                    <div class="space-y-1">
                        <label class="text-[9px] font-bold text-slate-550 uppercase block">Start</label>
                        <input type="date" name="start_date" value="{{ $activeFilters['start_date'] }}" class="bg-slate-955 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-300">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-bold text-slate-550 uppercase block">End</label>
                        <input type="date" name="end_date" value="{{ $activeFilters['end_date'] }}" class="bg-slate-955 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-300">
                    </div>
                </div>

                <button type="submit" class="bg-amra-primary hover:bg-teal-400 text-slate-955 font-bold text-xs px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-teal-500/10 shrink-0">
                    Sync Analytics
                </button>
            </form>
        </div>
    </div>

    <!-- Active Expiry Warnings Alarms -->
    @if ($stats['package_expiry_alerts'] > 0)
        <div class="bg-rose-500/10 border border-rose-500/20 rounded-3xl p-5 flex items-start gap-4 shadow-sm animate-pulse">
            <div class="w-10 h-10 rounded-xl bg-rose-500/15 text-rose-455 flex items-center justify-center shrink-0 border border-rose-500/20">
                <i data-lucide="alert-octagon" class="w-5 h-5"></i>
            </div>
            <div class="text-xs">
                <h4 class="font-bold text-rose-300 uppercase tracking-wider flex items-center gap-1.5 font-serif">Critical Expiry Warning</h4>
                <p class="text-slate-400 mt-1">There are <strong class="text-rose-400 font-bold">{{ $stats['package_expiry_alerts'] }} certified partner accounts</strong> expiring in the next 7 days. Action must be taken to secure renewal conversions.</p>
                <div class="mt-2.5 flex flex-wrap gap-2">
                    @foreach ($expiryAlerts as $alert)
                        <a href="{{ route('crm.partners.show', $alert->id) }}" class="px-2.5 py-1 rounded bg-rose-950/40 text-[9px] font-bold text-rose-350 hover:bg-rose-900 border border-rose-800/30 flex items-center gap-1.5 transition-colors">
                            <i data-lucide="handshake" class="w-3 h-3 text-rose-455"></i> {{ $alert->company_name }} (Expires {{ $alert->renewal_date->format('d M') }})
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Core Lead & Partner Analytics Metric Grid -->
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
        <!-- Total B2B Leads -->
        <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1 hover:border-slate-700 transition-colors">
            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Total B2B Leads</p>
            <p class="text-3xl font-bold text-white font-serif">{{ $stats['total_b2b_leads'] }}</p>
            <span class="text-[9px] font-bold text-slate-500 uppercase">Conversion: {{ $b2bConversionRatio }}%</span>
        </article>

        <!-- Total B2C Leads -->
        <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1 hover:border-slate-700 transition-colors">
            <p class="text-[9px] font-bold uppercase tracking-wider text-emerald-400">Total B2C Leads</p>
            <p class="text-3xl font-bold text-white font-serif">{{ $stats['total_b2c_leads'] }}</p>
            <span class="text-[9px] font-bold text-slate-500 uppercase">Conversion: {{ $b2cConversionRatio }}%</span>
        </article>

        <!-- Total Active Agents -->
        <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1 hover:border-slate-700 transition-colors">
            <p class="text-[9px] font-bold uppercase tracking-wider text-violet-400">Active Agents</p>
            <p class="text-3xl font-bold text-white font-serif">{{ $totalActiveAgents }}</p>
            <span class="text-[9px] font-bold text-slate-500 uppercase">Brokers Directory</span>
        </article>

        <!-- Total Developers -->
        <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1 hover:border-slate-700 transition-colors">
            <p class="text-[9px] font-bold uppercase tracking-wider text-violet-450">Active Developers</p>
            <p class="text-3xl font-bold text-white font-serif">{{ $totalActiveDevelopers }}</p>
            <span class="text-[9px] font-bold text-slate-500 uppercase">Builders Portfolio</span>
        </article>

        <!-- Leads Distributed -->
        <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1 hover:border-slate-700 transition-colors">
            <p class="text-[9px] font-bold uppercase tracking-wider text-teal-400">Leads Distributed</p>
            <p class="text-3xl font-bold text-white font-serif">{{ $stats['leads_shared'] }}</p>
            <span class="text-[9px] font-bold text-slate-500 uppercase">Shared Transactions</span>
        </article>

        <!-- Expiry Warning count -->
        <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1 hover:border-slate-700 transition-colors">
            <p class="text-[9px] font-bold uppercase tracking-wider text-rose-400">Expiry Alerts (7d)</p>
            <p class="text-3xl font-bold text-white font-serif">{{ $stats['package_expiry_alerts'] }}</p>
            <span class="text-[9px] font-bold text-slate-550 uppercase">Immediate Renewals</span>
        </article>
    </section>

    <!-- Specific Sales Performance Analytics Block -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3.5 mb-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="trending-up" class="w-4 h-4 text-amber-500"></i> Sales Performance Operations
            </h3>
            <span class="text-[10px] text-slate-550 font-bold uppercase tracking-wider">Internal Employees metrics</span>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Best sales rep -->
            <div class="bg-slate-950/40 p-4 border border-slate-850 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center shrink-0">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Top Representative</span>
                    <strong class="block text-sm text-slate-200 mt-0.5">{{ $bestSalesPerson }}</strong>
                </div>
            </div>

            <!-- Total interaction calls -->
            <div class="bg-slate-950/40 p-4 border border-slate-850 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20 flex items-center justify-center shrink-0">
                    <i data-lucide="phone-call" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Total Interactions</span>
                    <strong class="block text-sm text-slate-200 mt-0.5">{{ $totalCalls }} Logs</strong>
                </div>
            </div>

            <!-- Paid Conversions -->
            <div class="bg-slate-950/40 p-4 border border-slate-850 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-400 border border-teal-500/20 flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Paid Listings</span>
                    <strong class="block text-sm text-slate-200 mt-0.5">{{ $paidConversionsCount }} Converted</strong>
                </div>
            </div>

            <!-- Free Conversions -->
            <div class="bg-slate-950/40 p-4 border border-slate-850 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20 flex items-center justify-center shrink-0">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Free Listings</span>
                    <strong class="block text-sm text-slate-200 mt-0.5">{{ $freeConversionsCount }} Converted</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Visual Charts Split Grid -->
    <div class="grid gap-6 lg:grid-cols-12">
        <!-- Daily Leads Trend Line Graph (8/12 width) -->
        <div class="lg:col-span-8 bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest font-serif">Daily Acquisition Curves</h3>
                <span class="text-[10px] text-slate-550 font-bold uppercase">B2B vs B2C Leads</span>
            </div>
            
            @if (!empty($chartDailyCategories))
                <div id="daily-leads-chart" class="w-full h-80"></div>
            @else
                <div class="h-80 flex flex-col items-center justify-center text-slate-655 space-y-2">
                    <i data-lucide="trending-up" class="w-8 h-8"></i>
                    <p class="text-xs">No daily acquisition data for the selected range.</p>
                </div>
            @endif
        </div>

        <!-- Leads By Source Donut Graph (4/12 width) -->
        <div class="lg:col-span-4 bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest font-serif">Leads Share by Source</h3>
                <span class="text-[10px] text-slate-550 font-bold uppercase">Source Share</span>
            </div>
            
            <div id="platform-donut-chart" class="w-full h-80 flex items-center justify-center"></div>
        </div>
    </div>

    <!-- Monthly Lead Trends & Platform Conversion Matrices -->
    <div class="grid gap-6 lg:grid-cols-12">
        <!-- B2B Leads Platform Conversion Table (6/12 width) -->
        <div class="lg:col-span-6 bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">B2B Leads Platform Conversion</h3>
                <span class="text-[10px] text-slate-550 font-bold uppercase">Acquisition ROI</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-50">
                        <tr class="border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                            <th class="py-4 whitespace-nowrap">Platform</th>
                            <th class="py-4 text-center whitespace-nowrap">Total Leads</th>
                            <th class="py-4 text-center whitespace-nowrap">Paid Listings</th>
                            <th class="py-4 text-center whitespace-nowrap">Ad Spend</th>
                            <th class="py-4 text-center whitespace-nowrap">CPL</th>
                            <th class="py-4 text-right whitespace-nowrap">Conversion %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850 font-medium">
                        @foreach ($b2bPlatformStats as $platform => $pstats)
                            <tr class="hover:bg-slate-850/10">
                                <td class="py-3 font-bold text-white capitalize flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full 
                                        {{ $platform === 'meta' ? 'bg-blue-500' : '' }}
                                        {{ $platform === 'google' ? 'bg-red-500' : '' }}
                                        {{ $platform === 'website' ? 'bg-emerald-500' : '' }}
                                        {{ $platform === 'manual' ? 'bg-slate-500' : '' }}
                                        {{ $platform === 'csv' ? 'bg-violet-500' : '' }}"></span>
                                    {{ $platform }}
                                </td>
                                <td class="py-3 text-center text-slate-350">
                                    {{ $pstats['total'] }}
                                </td>
                                <td class="py-3 text-center text-teal-400 font-bold">
                                    {{ $pstats['paid'] }}
                                </td>
                                <td class="py-3 text-center text-slate-350">
                                    ₹{{ number_format($platformSpendStats[$platform]['spend'] ?? 0) }}
                                </td>
                                <td class="py-3 text-center text-slate-350">
                                    ₹{{ number_format($platformSpendStats[$platform]['cpl'] ?? 0) }}
                                </td>
                                <td class="py-3 text-right text-amber-400 font-extrabold">
                                    {{ $pstats['ratio'] }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- B2C Leads Platform Performance Table (6/12 width) -->
        <div class="lg:col-span-6 bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">B2C Leads Platform Performance</h3>
                <span class="text-[10px] text-slate-550 font-bold uppercase">Distribution ROI</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-50">
                        <tr class="border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                            <th class="py-4 whitespace-nowrap">Platform</th>
                            <th class="py-4 text-center whitespace-nowrap">Leads</th>
                            <th class="py-4 text-center whitespace-nowrap">Distributed Leads</th>
                            <th class="py-4 text-right whitespace-nowrap">Conversion Ratio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850 font-medium">
                        @foreach ($b2cPlatformStats as $platform => $pstats)
                            <tr class="hover:bg-slate-850/10">
                                <td class="py-3 font-bold text-white capitalize flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full 
                                        {{ $platform === 'meta' ? 'bg-blue-500' : '' }}
                                        {{ $platform === 'google' ? 'bg-red-500' : '' }}
                                        {{ $platform === 'website' ? 'bg-emerald-500' : '' }}
                                        {{ $platform === 'manual' ? 'bg-slate-500' : '' }}
                                        {{ $platform === 'csv' ? 'bg-violet-500' : '' }}"></span>
                                    {{ $platform }}
                                </td>
                                <td class="py-3 text-center text-slate-350">
                                    {{ $pstats['total'] }}
                                </td>
                                <td class="py-3 text-center text-teal-400 font-bold">
                                    {{ $pstats['shared'] }}
                                </td>
                                <td class="py-3 text-right text-violet-400 font-extrabold">
                                    {{ $pstats['ratio'] }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Monthly Lead Growth & Meta vs Google ROI Analysis Graphs -->
    <div class="grid gap-6 lg:grid-cols-12">
        <!-- Monthly Lead and Conversion Graph (8/12 width) -->
        <div class="lg:col-span-8 bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest font-serif">Monthly Conversions Trend</h3>
                <span class="text-[10px] text-slate-550 font-bold uppercase">Monthly Growth Chart</span>
            </div>
            
            @if (!empty($chartMonthlyCategories))
                <div id="monthly-conversion-chart" class="w-full h-80"></div>
            @else
                <div class="h-80 flex flex-col items-center justify-center text-slate-655 space-y-2">
                    <i data-lucide="bar-chart-3" class="w-8 h-8"></i>
                    <p class="text-xs">No monthly conversion historical trends logged.</p>
                </div>
            @endif
        </div>

        <!-- Meta vs Google ROI Analysis details (4/12 width) -->
        <div class="lg:col-span-4 bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Meta vs Google ROI Comparison</h3>
                <span class="text-[10px] text-slate-550 font-bold uppercase">ROI Efficiency</span>
            </div>
            
            <div id="roi-donut-chart" class="w-full h-80 flex items-center justify-center"></div>

            @if (auth()->user()->role !== 'analyst')
                <form action="{{ route('crm.dashboard.platform-spend') }}" method="POST" class="grid gap-3 border-t border-slate-800 pt-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <select name="source_platform" required class="bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 outline-none text-xs text-slate-300">
                            <option value="meta">Meta Ads</option>
                            <option value="google">Google Ads</option>
                            <option value="website">Website</option>
                            <option value="manual">Manual</option>
                            <option value="csv">CSV</option>
                        </select>
                        <input type="date" name="spent_on" value="{{ now()->toDateString() }}" required class="bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 outline-none text-xs text-slate-300">
                    </div>
                    <input type="number" name="amount" min="0" step="0.01" required placeholder="Ad spend / campaign cost"
                        class="bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 outline-none text-xs text-slate-300">
                    <input type="text" name="notes" placeholder="Notes, campaign name..."
                        class="bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 outline-none text-xs text-slate-300">
                    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-teal-500 border border-slate-700 font-bold text-xs py-2.5 rounded-xl transition-all">
                        Record Platform Spend
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Bottom Representative Leaderboard -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-3">Top sales representatives leaderboard</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-50">
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                        <th class="py-4 whitespace-nowrap">Representative</th>
                        <th class="py-4 text-center whitespace-nowrap">Assigned Leads</th>
                        <th class="py-4 text-center whitespace-nowrap">Total Interactions</th>
                        <th class="py-4 text-center whitespace-nowrap">Conversions</th>
                        <th class="py-4 text-right whitespace-nowrap">Total sales</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse ($salesLeaderboard as $rep)
                        <tr class="hover:bg-slate-850/10">
                            <td class="py-3 font-bold text-white flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-[10px]">
                                    {{ strtoupper(substr($rep->name, 0, 2)) }}
                                </div>
                                <div>
                                    <span class="block font-bold">{{ $rep->name }}</span>
                                    <span class="text-[9px] text-slate-550 block font-semibold">{{ $rep->location ?? 'Headquarters' }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-center font-semibold text-slate-300">
                                {{ $rep->assigned_count }}
                            </td>
                            <td class="py-3 text-center text-purple-400 font-bold">
                                {{ $rep->calls_count }}
                            </td>
                            <td class="py-3 text-center text-emerald-450 font-bold">
                                {{ $rep->conversions }}
                            </td>
                            <td class="py-3 text-right font-extrabold text-teal-400">
                                ₹{{ number_format($rep->sales_value) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-555 italic">No representative data recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Load ApexCharts library -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    function toggleDashboardDates() {
        const select = document.getElementById('date-filter-select');
        const container = document.getElementById('dashboard-custom-dates');
        if (select.value === 'custom') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleDashboardDates();

        // 1. Daily Leads curves area chart
        @if (!empty($chartDailyCategories))
            const dailyOptions = {
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: { show: false },
                    background: 'transparent',
                    foreColor: '#94a3b8'
                },
                series: [{
                    name: 'B2B Leads',
                    data: {!! json_encode($chartDailyB2B) !!},
                    color: '#0bc1b2'
                }, {
                    name: 'B2C Buyer Leads',
                    data: {!! json_encode($chartDailyB2C) !!},
                    color: '#10b981'
                }],
                xaxis: {
                    categories: {!! json_encode($chartDailyCategories) !!},
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                grid: {
                    borderColor: '#1e293b',
                    strokeDashArray: 4
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                tooltip: { theme: 'dark' }
            };

            const dailyChart = new ApexCharts(document.querySelector("#daily-leads-chart"), dailyOptions);
            dailyChart.render();
        @endif

        // 2. Donut Chart: Leads By Source
        const platformOptions = {
            chart: {
                type: 'donut',
                height: 320,
                background: 'transparent',
                foreColor: '#94a3b8'
            },
            series: [
                {{ $platformChartData['meta'] ?? 0 }},
                {{ $platformChartData['google'] ?? 0 }},
                {{ $platformChartData['website'] ?? 0 }},
                {{ $platformChartData['manual'] ?? 0 }},
                {{ $platformChartData['csv'] ?? 0 }}
            ],
            labels: ['Meta Ads', 'Google Ads', 'Website Forms', 'Manual entry', 'CSV Upload'],
            colors: ['#3b82f6', '#ef4444', '#10b981', '#64748b', '#8b5cf6'],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                labels: { colors: '#94a3b8' }
            },
            dataLabels: { enabled: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        background: 'transparent',
                        labels: {
                            show: true,
                            name: { show: true },
                            value: {
                                show: true,
                                color: '#ffffff',
                                formatter: function (val) { return val }
                            },
                            total: {
                                show: true,
                                color: '#94a3b8',
                                label: 'Total Leads',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            stroke: { show: false },
            tooltip: { theme: 'dark' }
        };

        const platformChart = new ApexCharts(document.querySelector("#platform-donut-chart"), platformOptions);
        platformChart.render();

        // 3. Monthly leads and conversions bar chart
        @if (!empty($chartMonthlyCategories))
            const monthlyOptions = {
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: { show: false },
                    background: 'transparent',
                    foreColor: '#94a3b8'
                },
                series: [{
                    name: 'B2B Leads',
                    data: {!! json_encode($chartMonthlyB2B) !!},
                    color: '#3b82f6'
                }, {
                    name: 'B2C Buyer Leads',
                    data: {!! json_encode($chartMonthlyB2C) !!},
                    color: '#a855f7'
                }, {
                    name: 'Listing Conversions',
                    data: {!! json_encode($chartMonthlyConversions) !!},
                    color: '#0bc1b2'
                }],
                xaxis: {
                    categories: {!! json_encode($chartMonthlyCategories) !!},
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                grid: {
                    borderColor: '#1e293b',
                    strokeDashArray: 4
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 6
                    }
                },
                dataLabels: { enabled: false },
                tooltip: { theme: 'dark' }
            };

            const monthlyChart = new ApexCharts(document.querySelector("#monthly-conversion-chart"), monthlyOptions);
            monthlyChart.render();
        @endif

        // 4. Meta vs Google ROI comparison chart
        const roiOptions = {
            chart: {
                type: 'pie',
                height: 320,
                background: 'transparent',
                foreColor: '#94a3b8'
            },
            // Compare overall conversion rates of Meta vs Google
            series: [
                {{ $b2bPlatformStats['meta']['ratio'] ?? 0 }},
                {{ $b2bPlatformStats['google']['ratio'] ?? 0 }}
            ],
            labels: ['Meta Paid Conv %', 'Google Paid Conv %'],
            colors: ['#2563eb', '#dc2626'],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                labels: { colors: '#94a3b8' }
            },
            stroke: { show: false },
            tooltip: { theme: 'dark' }
        };

        const roiChart = new ApexCharts(document.querySelector("#roi-donut-chart"), roiOptions);
        roiChart.render();
    });
</script>
@endsection
