@extends('layouts.crm')

@section('title', 'Partner - ' . $partner->company_name)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & edit -->
    <div class="flex items-center justify-between">
        <a href="{{ route('crm.partners.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Partner Directory
        </a>
        @if (auth()->user()->role !== 'analyst')
            <a href="{{ route('crm.partners.edit', $partner->id) }}" class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 active:scale-[0.99] transition-all">
                <i data-lucide="edit-3" class="w-4 h-4 text-slate-400"></i> Edit Package / Profile
            </a>
        @endif
    </div>

    <!-- Partner Banner Block -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-violet-500/5 rounded-full filter blur-3xl pointer-events-none"></div>

        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-violet-500/10 border border-violet-500/20 text-violet-400 flex items-center justify-center shrink-0">
                    <i data-lucide="handshake" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-2xl md:text-3xl font-serif font-extrabold text-white">{{ $partner->company_name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-800 text-slate-350 border border-slate-700">
                            {{ $partner->type }}
                        </span>
                        @if ($partner->user_id && $partner->user)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/20 text-teal-400 border border-teal-500/30 flex items-center gap-1">
                                <i data-lucide="key-round" class="w-3.5 h-3.5"></i> Portal Access Enabled ({{ $partner->user->email }})
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-750 flex items-center gap-1">
                                <i data-lucide="key-round" class="w-3.5 h-3.5"></i> Portal Access Disabled
                            </span>
                        @endif
                        @if (!$partner->is_active)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500/20 text-rose-455 border border-rose-500/30">
                                Inactive
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-400 mt-1 flex items-center gap-1.5">
                        <i data-lucide="user" class="w-4 h-4 text-slate-500"></i> Primary Contact: <strong class="text-slate-300 font-semibold">{{ $partner->contact_person }}</strong>
                    </p>
                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                        <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $partner->city }}</span>
                        <span class="flex items-center gap-1"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> Joined: {{ $partner->created_at ? $partner->created_at->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Package Plan Badge -->
            <div class="flex flex-col items-start md:items-end gap-2">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Active Plan</span>
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $partner->package === 'free' ? 'bg-slate-500/20 text-slate-400 border border-slate-500/30' : '' }}
                    {{ $partner->package === 'starter' ? 'bg-teal-500/20 text-teal-400 border border-teal-500/30' : '' }}
                    {{ $partner->package === 'growth' ? 'bg-violet-500/20 text-violet-400 border border-violet-500/30' : '' }}
                    {{ $partner->package === 'premium' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : '' }}
                    {{ $partner->package === 'customise' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : '' }}">
                    ★ {{ $partner->package }} Tier
                </span>
            </div>
        </div>
    </div>

    <!-- Main Detail Content Grid -->
    <div class="grid gap-6 lg:grid-cols-12 items-start">
        
        <!-- Left: Core Profile & Subscription Info (4/12 width) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Contact Profiles card -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Contact Profile</h3>
                
                <div class="space-y-4 text-xs font-semibold text-slate-300">
                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-550 uppercase block">Phone Number</span>
                        <a href="tel:{{ $partner->phone }}" class="text-sm font-semibold text-white hover:text-violet-400">{{ $partner->phone }}</a>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-550 uppercase block">Email Address</span>
                        @if ($partner->email)
                            <a href="mailto:{{ $partner->email }}" class="text-sm font-semibold text-white hover:text-violet-400 block truncate">{{ $partner->email }}</a>
                        @else
                            <span class="text-slate-655 italic">Not Specified</span>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-550 uppercase block">Locality Coverage Areas</span>
                        @if (!empty($partner->service_areas))
                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                @foreach ($partner->service_areas as $area)
                                    <span class="px-2 py-0.5 rounded bg-slate-800 text-[10px] text-slate-350 border border-slate-750">{{ $area }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-slate-655 italic">All of {{ $partner->city }}</span>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-550 uppercase block">Physical Office Address</span>
                        <p class="text-xs font-medium text-slate-350 leading-relaxed mt-0.5">{{ $partner->office_address ?? 'Not Specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Subscription Lifecycle Info Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Subscription Lifecycle</h3>
                
                <div class="space-y-4 text-xs font-semibold text-slate-300">
                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-550 uppercase block">Package Plan Level</span>
                        <span class="text-sm font-bold text-white capitalize">{{ $partner->package }} Tier</span>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-550 uppercase block">Paid Amount</span>
                        <span class="text-sm font-bold text-teal-500">₹{{ number_format($partner->paid_amount ?? 0) }}</span>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-550 uppercase block">Purchase Date</span>
                        <span class="text-sm text-slate-200">{{ $partner->package_purchase_date ? $partner->package_purchase_date->format('d M Y') : 'N/A' }}</span>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[9px] font-bold text-slate-550 uppercase block">Renewal / Expiry Date</span>
                        @if ($partner->renewal_date)
                            @php 
                                $daysLeft = now()->startOfDay()->diffInDays($partner->renewal_date, false);
                            @endphp
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-sm font-bold text-white">{{ $partner->renewal_date->format('d M Y') }}</span>
                                @if ($daysLeft < 0)
                                    <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-400 border border-rose-500/30 text-[9px] font-bold uppercase">Expired</span>
                                @elseif ($daysLeft <= 7)
                                    <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-400 border border-amber-500/30 text-[9px] font-bold uppercase animate-pulse">Expires Soon</span>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-500 font-bold block mt-0.5">({{ $daysLeft < 0 ? abs($daysLeft) . ' days past expiry' : $daysLeft . ' days remaining' }})</span>
                        @else
                            <span class="text-slate-655 italic">Lifetime / Free Account</span>
                        @endif
                    </div>

                    <div class="space-y-1 pt-2 border-t border-slate-800">
                        <span class="text-[9px] font-bold text-slate-550 uppercase block">Account Representative</span>
                        <span class="text-xs text-slate-350">{{ $partner->assignedSalesPerson ? $partner->assignedSalesPerson->name : 'Unassigned' }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes & Remarks Card -->
            @if ($partner->remark)
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-2">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Internal Remarks</h3>
                    <p class="text-xs text-slate-450 leading-relaxed font-medium mt-2">{{ $partner->remark }}</p>
                </div>
            @endif

        </div>

        <!-- Right: Leads Received Timeline with Date range Filters (8/12 width) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Date filter control toolbar -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
                <form action="{{ route('crm.partners.show', $partner->id) }}" method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-end justify-between">
                    <div class="space-y-1.5 flex-grow">
                        <label for="lead-date-filter" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Leads Distribution Date range Filter</label>
                        <select name="date_filter" id="lead-date-filter" onchange="toggleCustomDates()" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-300">
                            <option value="today" {{ $activeFilters['date_filter'] === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ $activeFilters['date_filter'] === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="last_7_days" {{ $activeFilters['date_filter'] === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="last_30_days" {{ $activeFilters['date_filter'] === 'last_30_days' ? 'selected' : '' }}>Last 30 Days (Default)</option>
                            <option value="custom" {{ $activeFilters['date_filter'] === 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>

                    <div id="custom-dates" class="grid grid-cols-2 gap-4 flex-grow hidden">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Start Date</label>
                            <input type="date" name="start_date" value="{{ $activeFilters['start_date'] }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-300">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">End Date</label>
                            <input type="date" name="end_date" value="{{ $activeFilters['end_date'] }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-300">
                        </div>
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <button type="submit" class="bg-violet-600 hover:bg-violet-550 text-white font-bold text-xs px-6 py-3 rounded-xl transition-all shadow-md shadow-violet-500/10">
                            Sync Metrics
                        </button>
                    </div>
                </form>
            </div>

            <!-- Leads received list display -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Leads Distributed Timeline</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20">
                        Total: {{ count($shares) }} Lead(s) Received
                    </span>
                </div>

                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                    @forelse ($shares as $share)
                        <div class="bg-slate-955/40 p-4 border border-slate-850 rounded-2xl space-y-3 hover:border-slate-800 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs">
                                <div>
                                    <h4 class="font-bold text-white text-sm">
                                        <span class="text-slate-500 hover:text-slate-300 cursor-pointer">{{ $share->lead->name }}</span>
                                    </h4>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <span class="px-1.5 py-0.5 rounded-md bg-teal-50 text-[9px] font-extrabold text-teal-700 border border-teal-200 uppercase tracking-wider">{{ $share->lead->configuration }}</span>
                                        <span class="px-1.5 py-0.5 rounded-md bg-slate-50 text-[9px] font-bold uppercase text-slate-700 border border-slate-200">{{ $share->lead->property_type }}</span>
                                        <span class="text-[10px] text-slate-500 font-semibold">{{ $share->lead->city }}</span>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right">
                                    <span class="text-[9px] font-bold text-slate-550 uppercase block">Received on</span>
                                    <span class="text-xs font-semibold text-slate-400">{{ $share->shared_at->format('d M Y, h:i A') }}</span>
                                </div>
                            </div>

                            @if ($share->remark)
                                <p class="text-xs text-slate-350 leading-relaxed italic bg-slate-950/50 p-2.5 rounded-xl border border-slate-850">
                                    "{{ $share->remark }}"
                                </p>
                            @endif

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-[10px] text-slate-500 font-semibold pt-2.5 border-t border-slate-850/40 gap-2">
                                <span class="flex items-center gap-1"><i data-lucide="user-check" class="w-3.5 h-3.5"></i> Distributed by: {{ $share->sharedBy ? $share->sharedBy->name : 'System' }}</span>
                                <span class="flex items-center gap-1 text-emerald-400"><i data-lucide="phone" class="w-3.5 h-3.5"></i> Contact Phone: {{ $share->lead->phone }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 space-y-3">
                            <i data-lucide="inbox" class="w-10 h-10 text-slate-655 mx-auto"></i>
                            <p class="text-xs text-slate-550">No leads distributed to this partner within the selected date range.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleCustomDates() {
        const select = document.getElementById('lead-date-filter');
        const container = document.getElementById('custom-dates');
        if (select.value === 'custom') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleCustomDates();
    });
</script>
@endsection
