@extends('layouts.crm')

@section('title', 'Performance Profile - ' . $salesPerson->name)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb back link -->
    <a href="{{ route('crm.sales.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Sales Performance Table
    </a>

    <!-- Profile Header Banner -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full filter blur-3xl pointer-events-none"></div>

        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                    <i data-lucide="user" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-serif font-extrabold text-white">{{ $salesPerson->name }}</h1>
                    <p class="text-sm text-slate-400 mt-1 flex items-center gap-1.5">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-500"></i> Contact phone: <strong class="text-slate-350 font-semibold">{{ $salesPerson->phone ?? 'N/A' }}</strong>
                    </p>
                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                        <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $salesPerson->location ?? 'Headquarters' }}</span>
                        <span class="flex items-center gap-1"><i data-lucide="shield" class="w-3.5 h-3.5"></i> Role: Internal Representative</span>
                    </div>
                </div>
            </div>

            <!-- Total Sales Block -->
            <div class="flex flex-col items-start md:items-end gap-1.5 bg-slate-950/40 p-4 border border-slate-850 rounded-2xl shrink-0">
                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Total Sales Generated</span>
                <span class="text-2xl font-serif font-extrabold text-white">₹{{ number_format($stats['total_sale']) }}</span>
            </div>
        </div>
    </div>

    <!-- Performance Date Filters -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
        <form action="{{ route('crm.sales.show', $salesPerson->id) }}" method="GET" class="grid gap-4 md:grid-cols-[180px_160px_160px_auto] items-end">
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Date Range</label>
                <select name="date_filter" id="sales-profile-date-filter" onchange="toggleSalesProfileDates()" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-200">
                    <option value="">All Time</option>
                    @foreach (config('crm.date_filters') as $value => $label)
                        <option value="{{ $value }}" {{ ($activeFilters['date_filter'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sales-profile-custom-date {{ ($activeFilters['date_filter'] ?? '') === 'custom' ? '' : 'hidden' }}">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Start</label>
                <input type="date" name="start_date" value="{{ $activeFilters['start_date'] ?? '' }}" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-200">
            </div>

            <div class="sales-profile-custom-date {{ ($activeFilters['date_filter'] ?? '') === 'custom' ? '' : 'hidden' }}">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">End</label>
                <input type="date" name="end_date" value="{{ $activeFilters['end_date'] ?? '' }}" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-200">
            </div>

            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-5 py-3 rounded-xl transition-all">
                Apply
            </button>
        </form>
    </div>    <!-- Tab Navigation Bar -->
    <div class="border-b border-slate-800 flex items-center gap-6 text-sm mb-6 mt-2">
        <button id="tab-btn-pipeline" onclick="switchSalesTab('pipeline')" class="pb-3 text-amra-primary border-b-2 border-amra-primary font-bold transition-all px-1">
            B2B Pipeline & Leads
        </button>
        <button id="tab-btn-calls" onclick="switchSalesTab('calls')" class="pb-3 text-slate-400 border-b-2 border-transparent hover:text-slate-200 transition-all px-1">
            Call Performance & Logs
        </button>
    </div>

    <!-- Tab 1: B2B Pipeline & Leads -->
    <div id="sales-tab-pipeline" class="space-y-6">
        <!-- Performance KPIs metrics Grid -->
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <!-- Assigned leads -->
            <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Assigned Leads</p>
                <p class="text-3xl font-bold text-white">{{ $stats['assigned'] }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Total leads managed</p>
            </article>

            <!-- Free listing conversions -->
            <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-teal-400">Free Listings</p>
                <p class="text-3xl font-bold text-white">{{ $stats['free_listing'] }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Free conversion ratio: <strong class="text-teal-400">{{ $stats['free_ratio'] }}%</strong></p>
            </article>

            <!-- Paid listing conversions -->
            <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-amber-400">Paid Listings</p>
                <p class="text-3xl font-bold text-white">{{ $stats['paid_listing'] }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Paid conversion ratio: <strong class="text-amber-400">{{ $stats['paid_ratio'] }}%</strong></p>
            </article>

            <!-- Contacted leads -->
            <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-purple-400">Leads Contacted</p>
                <p class="text-3xl font-bold text-white">{{ $stats['contacted'] }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Engagement: {{ $stats['assigned'] > 0 ? round(($stats['contacted'] / $stats['assigned']) * 100) : 0 }}%</p>
            </article>

            <!-- Qualified leads -->
            <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-450">Leads Interested</p>
                <p class="text-3xl font-bold text-white">{{ $stats['qualified'] }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Interest: {{ $stats['assigned'] > 0 ? round(($stats['qualified'] / $stats['assigned']) * 100) : 0 }}%</p>
            </article>
        </section>

        <!-- Converted certified partners list (full-width) -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
            <div class="border-b border-slate-850 pb-3 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Converted Certified Partners</h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-500/10 text-violet-400 border border-violet-500/20">
                    {{ count($partners) }} Partner(s)
                </span>
            </div>

            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                @forelse ($partners as $partner)
                    <div class="bg-slate-955/40 p-4 border border-slate-850 rounded-2xl flex items-center justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-white text-xs">{{ $partner->company_name }}</h4>
                            <div class="flex items-center gap-1.5 mt-1 text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                                <span>{{ $partner->city }}</span>
                                <span>•</span>
                                <span>{{ $partner->type }}</span>
                            </div>
                        </div>
                        
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl text-[10px] font-bold uppercase tracking-wide
                            {{ $partner->package === 'free' ? 'bg-slate-550/15 text-slate-400 border border-slate-750' : '' }}
                            {{ $partner->package === 'starter' ? 'bg-teal-500/10 text-teal-400 border border-teal-500/20' : '' }}
                            {{ $partner->package === 'growth' ? 'bg-violet-500/10 text-violet-400 border border-violet-500/20' : '' }}">
                            {{ $partner->package }}
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-slate-655 italic text-center py-8">No partners converted yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Assigned leads list (full-width) -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-3">Assigned B2B Lead List</h3>
            
            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                @forelse ($assignedLeads as $lead)
                    <div class="bg-slate-955/40 p-4 border border-slate-855 rounded-2xl flex items-center justify-between gap-4 hover:border-slate-800 transition-colors">
                        <div>
                            <h4 class="font-bold text-white text-xs hover:text-amra-primary transition-colors">
                                <a href="{{ route('crm.b2b.show', $lead->id) }}">{{ $lead->company_name }}</a>
                            </h4>
                            <div class="flex items-center gap-1.5 mt-1 text-[9px] font-bold text-slate-500 uppercase tracking-wider flex-wrap">
                                <span>{{ $lead->city }}</span>
                                <span>•</span>
                                <span>{{ str_replace('_', ' ', $lead->category) }}</span>
                                <span>•</span>
                                <span class="text-slate-400 capitalize">Source: {{ $lead->source_platform }}</span>
                            </div>
                        </div>

                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl text-[10px] font-bold uppercase tracking-wider shrink-0
                            {{ $lead->status === 'new' ? 'bg-blue-500/15 text-blue-400' : '' }}
                            {{ $lead->status === 'contacted' ? 'bg-purple-500/15 text-purple-400' : '' }}
                            {{ $lead->status === 'qualified' ? 'bg-emerald-500/15 text-emerald-450' : '' }}
                            {{ $lead->status === 'not_interested' ? 'bg-rose-500/15 text-rose-400' : '' }}
                            {{ $lead->status === 'follow_up' ? 'bg-amber-500/15 text-amber-400' : '' }}
                            {{ in_array($lead->status, ['free_listing', 'paid_listing', 'converted']) ? 'bg-teal-500/15 text-teal-400' : '' }}">
                            {{ str_replace('_', ' ', $lead->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-slate-655 italic text-center py-8">No leads currently assigned to this representative.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tab 2: Call Performance & Logs -->
    <div id="sales-tab-calls" class="space-y-6 hidden">
        <!-- Follow-up Activity KPIs -->
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Completed Calls / Follow-ups -->
            <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Completed Calls / Follow-ups</p>
                <p class="text-3xl font-bold text-white">{{ $stats['completed_followups'] }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Total interactions completed</p>
            </article>

            <!-- Pending Scheduled Follow-ups -->
            <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-amber-400">Pending Follow-ups</p>
                <p class="text-3xl font-bold text-white">{{ $stats['pending_followups'] }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Calls scheduled / in pipeline</p>
            </article>

            <!-- Total Activity Logs -->
            <article class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-sm space-y-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-violet-400">Total Follow-ups Registered</p>
                <p class="text-3xl font-bold text-white">{{ $stats['completed_followups'] + $stats['pending_followups'] }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Historical database actions</p>
            </article>
        </section>

        <!-- Day-wise Call Performance (full-width) -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-3">Day-Wise Calls Completed</h3>
            <div class="overflow-x-auto max-h-[350px] overflow-y-auto pr-2">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-855 text-slate-500 font-bold uppercase">
                            <th class="py-2">Date</th>
                            <th class="py-2 text-right">Calls Completed</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850">
                        @forelse ($dayWiseCalls as $day)
                            <tr>
                                <td class="py-3 text-slate-200 font-medium">{{ \Carbon\Carbon::parse($day->call_date)->format('d M Y') }}</td>
                                <td class="py-3 text-right text-teal-400 font-extrabold text-sm">{{ $day->call_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-6 text-center text-slate-550 italic">No calls logged.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Follow-up logs timeline (full-width) -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-3">Follow-up Call Activity Logs</h3>
            <div class="space-y-4 max-h-[350px] overflow-y-auto pr-2">
                @forelse ($recentFollowUps as $log)
                    <div class="bg-slate-955/40 p-4 border border-slate-850 rounded-2xl space-y-2.5">
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <span class="flex items-center gap-1.5 {{ $log->completed_at ? 'text-teal-400' : 'text-amber-500' }}">
                                <i data-lucide="{{ $log->completed_at ? 'phone-call' : 'calendar-days' }}" class="w-3.5 h-3.5"></i>
                                {{ $log->completed_at ? 'Completed Call' : 'Pending Follow-up' }}
                            </span>
                            <span class="text-slate-550">
                                {{ $log->completed_at ? 'Completed: ' . $log->completed_at->format('d M Y, h:i A') : 'Due: ' . $log->due_at->format('d M Y') }}
                            </span>
                        </div>
                        
                        <p class="text-xs text-slate-350 leading-relaxed font-medium">"{{ $log->notes }}"</p>
                        
                        <div class="flex items-center justify-between text-[10px] text-slate-550 border-t border-slate-850/60 pt-2 flex-wrap gap-2">
                            <span class="font-semibold">
                                Lead: 
                                @if ($log->followable)
                                    <span class="text-slate-300">{{ $log->followable->company_name ?? ($log->followable->name ?? 'Unknown') }}</span>
                                @else
                                    <span class="text-slate-655 italic">Unlinked</span>
                                @endif
                            </span>
                            @if ($log->outcome)
                                <span class="bg-slate-800 px-2 py-0.5 rounded text-slate-300 font-bold uppercase tracking-wider">Outcome: {{ $log->outcome }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-655 italic text-center py-10">No detailed follow-up activity logged.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleSalesProfileDates() {
        const select = document.getElementById('sales-profile-date-filter');
        document.querySelectorAll('.sales-profile-custom-date').forEach((field) => {
            field.classList.toggle('hidden', select.value !== 'custom');
        });
    }

    function switchSalesTab(tabName) {
        const pipelineTab = document.getElementById('sales-tab-pipeline');
        const callsTab = document.getElementById('sales-tab-calls');
        
        const btnPipeline = document.getElementById('tab-btn-pipeline');
        const btnCalls = document.getElementById('tab-btn-calls');
        
        if (tabName === 'pipeline') {
            pipelineTab.classList.remove('hidden');
            callsTab.classList.add('hidden');
            
            btnPipeline.className = "pb-3 text-amra-primary border-b-2 border-amra-primary font-bold transition-all px-1";
            btnCalls.className = "pb-3 text-slate-400 border-b-2 border-transparent hover:text-slate-200 transition-all px-1";
        } else {
            pipelineTab.classList.add('hidden');
            callsTab.classList.remove('hidden');
            
            btnPipeline.className = "pb-3 text-slate-400 border-b-2 border-transparent hover:text-slate-200 transition-all px-1";
            btnCalls.className = "pb-3 text-amra-primary border-b-2 border-amra-primary font-bold transition-all px-1";
        }
    }

    document.addEventListener('DOMContentLoaded', toggleSalesProfileDates);
</script>
@endsection
