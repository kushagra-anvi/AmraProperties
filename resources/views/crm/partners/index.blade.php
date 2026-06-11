@extends('layouts.crm')

@section('title', 'Agents & Developers')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <span class="inline-flex items-center gap-1 bg-violet-500/10 border border-violet-500/20 text-violet-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <i data-lucide="handshake" class="w-3.5 h-3.5"></i> Partner ecosystem
            </span>
            <h1 class="text-3xl font-serif font-extrabold text-white">Agents & Developers</h1>
            <p class="text-sm text-slate-400">Track affiliate partners, subscription packages, and lead distribution analytics</p>
        </div>
        
        @if (auth()->user()->role !== 'analyst')
            <div class="flex items-center gap-3">
                <button onclick="toggleModal('partner-csv-modal')" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 px-4 py-3 rounded-xl font-bold text-xs flex items-center gap-2 active:scale-[0.99] transition-all">
                    <i data-lucide="upload" class="w-4 h-4 text-slate-400"></i> Bulk Upload CSV
                </button>
                <a href="{{ route('crm.partners.create') }}" class="bg-amra-primary hover:bg-teal-400 text-slate-950 px-4 py-3 rounded-xl font-bold text-xs flex items-center gap-2 active:scale-[0.99] transition-all shadow-lg shadow-teal-500/10">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Partner Account
                </a>
            </div>
        @endif
    </div>

    <!-- Alert Cards Bar -->
    @if ($expiringCount > 0)
        <div class="bg-amber-500/10 border border-amber-500/20 rounded-3xl p-5 flex items-start gap-4 shadow-sm animate-pulse">
            <div class="w-10 h-10 rounded-xl bg-amber-500/15 text-amber-400 flex items-center justify-center shrink-0">
                <i data-lucide="bell-ring" class="w-5 h-5"></i>
            </div>
            <div class="text-xs">
                <h4 class="font-bold text-amber-300 uppercase tracking-wider">Package Expiry Warning</h4>
                <p class="text-slate-400 mt-1">There are currently <strong class="text-amber-400 font-bold">{{ $expiringCount }} partner account(s)</strong> whose growth/starter packages are expiring within the next 7 days. Action may be required to secure renewals.</p>
            </div>
        </div>
    @endif

    <!-- Filters and Date Filter for Leads Received -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
        <form action="{{ route('crm.partners.index') }}" method="GET" class="space-y-4">
            <div class="grid gap-4 md:grid-cols-12 items-end">
                <!-- Search -->
                <div class="md:col-span-3 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Search Partners</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                        <input type="text" name="search" value="{{ $activeFilters['search'] }}" placeholder="Brand, person, email, phone..." 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl pl-10 pr-4 py-3 outline-none font-medium text-xs text-slate-200 transition-all">
                    </div>
                </div>

                <!-- Type -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Partner Type</label>
                    <select name="type" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none font-medium text-xs text-slate-350 transition-all">
                        <option value="">All Types</option>
                        <option value="agent" {{ $activeFilters['type'] === 'agent' ? 'selected' : '' }}>Agent</option>
                        <option value="developer" {{ $activeFilters['type'] === 'developer' ? 'selected' : '' }}>Developer</option>
                        <option value="affiliate" {{ $activeFilters['type'] === 'affiliate' ? 'selected' : '' }}>Affiliate Partner</option>
                    </select>
                </div>

                <!-- Package -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active Package</label>
                    <select name="package" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none font-medium text-xs text-slate-350 transition-all">
                        <option value="">All Packages</option>
                        <option value="free" {{ $activeFilters['package'] === 'free' ? 'selected' : '' }}>Free</option>
                        <option value="starter" {{ $activeFilters['package'] === 'starter' ? 'selected' : '' }}>Starter</option>
                        <option value="growth" {{ $activeFilters['package'] === 'growth' ? 'selected' : '' }}>Growth</option>
                        <option value="premium" {{ $activeFilters['package'] === 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="customise" {{ $activeFilters['package'] === 'customise' ? 'selected' : '' }}>Customise</option>
                    </select>
                </div>

                <!-- City -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Operating City</label>
                    <select name="city" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none font-medium text-xs text-slate-350 transition-all">
                        <option value="">All Cities</option>
                        <option value="Mumbai" {{ $activeFilters['city'] === 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                        <option value="Lucknow" {{ $activeFilters['city'] === 'Lucknow' ? 'selected' : '' }}>Lucknow</option>
                        <option value="Pune" {{ $activeFilters['city'] === 'Pune' ? 'selected' : '' }}>Pune</option>
                        <option value="Delhi" {{ $activeFilters['city'] === 'Delhi' ? 'selected' : '' }}>Delhi</option>
                        <option value="Bangalore" {{ $activeFilters['city'] === 'Bangalore' ? 'selected' : '' }}>Bangalore</option>
                    </select>
                </div>

                <!-- Leads Received Date range filter -->
                <div class="md:col-span-3 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider text-teal-400 font-serif">Leads Distribution Date range</label>
                    <select name="date_filter" id="p-date-filter" onchange="toggleCustomDates()" class="w-full bg-slate-950 border border-teal-900/40 focus:border-amra-primary rounded-xl px-4 py-3 outline-none font-medium text-xs text-teal-300 transition-all">
                        <option value="today" {{ $activeFilters['date_filter'] === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ $activeFilters['date_filter'] === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="last_7_days" {{ $activeFilters['date_filter'] === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="last_30_days" {{ $activeFilters['date_filter'] === 'last_30_days' ? 'selected' : '' }}>Last 30 Days (Default)</option>
                        <option value="custom" {{ $activeFilters['date_filter'] === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
            </div>

            <!-- Custom date range input fields -->
            <div id="p-custom-dates" class="grid grid-cols-2 gap-4 mt-2 hidden">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Start Date</label>
                    <input type="date" name="start_date" value="{{ $activeFilters['start_date'] }}" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none font-medium text-xs text-slate-355">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">End Date</label>
                    <input type="date" name="end_date" value="{{ $activeFilters['end_date'] }}" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none font-medium text-xs text-slate-355">
                </div>
            </div>

            <!-- Apply & Reset Buttons -->
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-800/40">
                <a href="{{ route('crm.partners.index') }}" class="px-5 py-2.5 bg-slate-955 hover:bg-slate-900 border border-slate-850 rounded-xl text-slate-500 hover:text-slate-300 font-bold text-xs transition-all flex items-center justify-center shrink-0">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 mr-1.5"></i> Reset Filters
                </a>
                <button type="submit" class="bg-violet-600 hover:bg-violet-550 text-white font-bold text-xs px-6 py-2.5 rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-md shadow-violet-500/10">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i> Apply & Sync Leads received
                </button>
            </div>
        </form>
    </div>

    <!-- Directory Table Grid -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="handshake" class="w-3.5 h-3.5 text-slate-400"></i>
                                Company & Contact
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                City & Areas
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="badge-check" class="w-3.5 h-3.5 text-slate-400"></i>
                                Package
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="calendar-clock" class="w-3.5 h-3.5 text-slate-400"></i>
                                Renewal Status
                            </span>
                        </th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center justify-center gap-2">
                                <i data-lucide="send" class="w-3.5 h-3.5 text-slate-400"></i>
                                Leads Distributed
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="user-check" class="w-3.5 h-3.5 text-slate-400"></i>
                                Representative
                            </span>
                        </th>
                        <th class="px-6 py-4 text-right whitespace-nowrap">
                            <span class="inline-flex items-center justify-end gap-2">
                                Actions
                                <i data-lucide="settings-2" class="w-3.5 h-3.5 text-slate-400"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse ($partners as $partner)
                        <tr class="hover:bg-slate-850/20 transition-colors">
                            <!-- Company -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-sm hover:text-violet-400 transition-colors">
                                    <a href="{{ route('crm.partners.show', $partner->id) }}">{{ $partner->company_name }}</a>
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-slate-800 text-slate-350 border border-slate-700">
                                        {{ $partner->type }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $partner->contact_person }}</span>
                                </div>
                            </td>

                            <!-- City -->
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold text-slate-200">{{ $partner->city }}</span>
                                @if (!empty($partner->service_areas))
                                    <div class="text-[10px] text-slate-500 mt-0.5 max-w-xs truncate">
                                        {{ implode(', ', $partner->service_areas) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Package level -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wide
                                    {{ $partner->package === 'free' ? 'bg-slate-500/10 text-slate-400 border border-slate-500/20' : '' }}
                                    {{ $partner->package === 'starter' ? 'bg-teal-500/10 text-teal-400 border border-teal-500/20' : '' }}
                                    {{ $partner->package === 'growth' ? 'bg-violet-500/10 text-violet-400 border border-violet-500/20' : '' }}
                                    {{ $partner->package === 'premium' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                    {{ $partner->package === 'customise' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : '' }}">
                                    {{ $partner->package }}
                                </span>
                            </td>

                            <!-- Renewal Status -->
                            <td class="px-6 py-4">
                                @if ($partner->renewal_date)
                                    @php 
                                        $days = now()->startOfDay()->diffInDays($partner->renewal_date, false);
                                    @endphp
                                    
                                    @if ($days < 0)
                                        <span class="text-xs font-bold text-rose-500">Expired ({{ abs($days) }} days ago)</span>
                                    @elseif ($days <= 7)
                                        <span class="text-xs font-bold text-amber-400 animate-pulse flex items-center gap-1">
                                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                            Expiring ({{ $days }} days left)
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-300 font-semibold">{{ $partner->renewal_date->format('d M Y') }} ({{ $days }} days left)</span>
                                    @endif
                                @else
                                    <span class="text-slate-655 italic text-xs">No Expiry</span>
                                @endif
                            </td>

                            <!-- Leads Received dynamic aggregation count -->
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm bg-slate-950 border border-slate-800 text-teal-450">
                                    {{ $partner->leads_received_count }}
                                </span>
                            </td>

                            <!-- Sales rep assignee -->
                            <td class="px-6 py-4 text-xs font-semibold text-slate-350">
                                @if ($partner->assignedSalesPerson)
                                    {{ $partner->assignedSalesPerson->name }}
                                @else
                                    <span class="text-slate-655 italic">Unassigned</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('crm.partners.show', $partner->id) }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-350 hover:text-white rounded-xl transition-all" title="View partner profile">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    @if (auth()->user()->role !== 'analyst')
                                        <a href="{{ route('crm.partners.edit', $partner->id) }}" class="p-2 bg-slate-850 hover:bg-slate-800 text-slate-450 hover:text-teal-400 rounded-xl transition-all" title="Edit partner plan">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i data-lucide="folder-open" class="w-10 h-10 text-slate-655"></i>
                                    <p class="text-sm">No affiliate partners registered yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($partners->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-955/20">
                {{ $partners->links() }}
            </div>
        @endif
    </div>
</div>

@if (auth()->user()->role !== 'analyst')
<!-- Bulk CSV Import Modal -->
<div id="partner-csv-modal" class="fixed inset-0 z-50 overflow-y-auto flex items-start justify-center p-4 hidden">
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black/75 backdrop-blur-sm transition-opacity" onclick="toggleModal('partner-csv-modal')"></div>

    <!-- Modal Content Box -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl max-w-md w-full relative z-10 p-6 space-y-6 my-8">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-serif font-extrabold text-white flex items-center gap-2">
                <i data-lucide="upload-cloud" class="w-5 h-5 text-violet-400"></i> Bulk Partner CSV Uploader
            </h3>
            <button onclick="toggleModal('partner-csv-modal')" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('crm.partners.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="border-2 border-dashed border-slate-800 rounded-2xl p-6 text-center bg-slate-950/50 flex flex-col items-center justify-center gap-3 hover:border-violet-500/30 transition-colors">
                <i data-lucide="file-spreadsheet" class="w-10 h-10 text-slate-550"></i>
                <div class="text-xs">
                    <span class="text-slate-400">Click to choose or drag & drop</span>
                    <p class="text-slate-655 mt-1">Accepts only standard CSV files (Max 5MB)</p>
                </div>
                <input type="file" name="csv_file" accept=".csv" required class="w-full text-slate-400 font-medium text-xs mt-2 px-3 py-2 border border-slate-800 rounded-xl bg-slate-900 focus:outline-none">
            </div>

            <!-- CSV Requirements Specs Info -->
            <div class="bg-slate-950 border border-slate-850 p-5 rounded-xl space-y-3">
                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">Partner CSV Schema Details:</p>
                <div class="space-y-2 text-[10px] leading-relaxed">
                    <div class="text-slate-400 font-semibold border-b border-slate-900 pb-1.5">Required Fields:</div>
                    <div class="grid grid-cols-2 gap-2 text-slate-500 font-mono">
                        <div>• <strong class="text-slate-350">company_name</strong> (text)</div>
                        <div>• <strong class="text-slate-350">contact_person</strong> (text)</div>
                        <div>• <strong class="text-slate-350">phone</strong> (number)</div>
                        <div>• <strong class="text-slate-350">city</strong> (text e.g. Mumbai)</div>
                        <div>• <strong class="text-slate-350">type</strong> (text)</div>
                    </div>
                    <div class="text-[9px] text-slate-555 mt-1 italic leading-snug">
                        Allowed values for <strong class="text-slate-400">type</strong>: agent, developer, affiliate.
                    </div>
                    
                    <div class="text-slate-400 font-semibold border-b border-slate-900 pt-2 pb-1.5">Optional Fields:</div>
                    <div class="space-y-1 text-slate-500">
                        <div>• <code class="text-slate-350 font-mono text-[9px]">email</code>: Valid email address</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">office_address</code>: Plain text physical address</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">service_areas</code>: Comma separated coverage localities (e.g. <code class="text-slate-400">Powai, Bandra</code>)</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">package</code>: Options are <code class="bg-slate-900 px-1 py-0.5 rounded text-violet-400">free</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-violet-400">starter</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-violet-400">growth</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-violet-400">premium</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-violet-400">customise</code></div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">paid_amount</code>: Decimal/Number in INR</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">package_purchase_date</code> / <code class="text-slate-350 font-mono text-[9px]">renewal_date</code>: Dates (YYYY-MM-DD)</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">lead_source</code>: Text</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">remark</code>: Discovery notes</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-violet-600 hover:bg-violet-550 text-white font-bold text-xs py-3.5 rounded-xl active:scale-[0.99] transition-all shadow-lg shadow-violet-500/10">
                Start Bulk Partner Processing
            </button>
        </form>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    function toggleCustomDates() {
        const select = document.getElementById('p-date-filter');
        const container = document.getElementById('p-custom-dates');
        if (select.value === 'custom') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleCustomDates();
    });
</script>
@endsection
