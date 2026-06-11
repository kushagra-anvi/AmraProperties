@extends('layouts.crm')

@section('title', 'B2B Leads Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <span class="inline-flex items-center gap-1 bg-teal-500/10 border border-teal-500/20 text-teal-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <i data-lucide="building-2" class="w-3.5 h-3.5"></i> B2B Pipeline
            </span>
            <h1 class="text-3xl font-serif font-extrabold text-white">B2B Leads Directory</h1>
            <p class="text-sm text-slate-400">Manage agents, developers, single property owner pipelines, and sales assignments</p>
        </div>
        
        <div class="flex items-center gap-3">
            @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
                <button onclick="toggleModal('csv-modal')" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 px-4 py-3 rounded-xl font-bold text-xs flex items-center gap-2 active:scale-[0.99] transition-all">
                    <i data-lucide="upload" class="w-4 h-4 text-slate-400"></i> Bulk Upload CSV
                </button>
            @endif
            <a href="{{ route('crm.b2b.create') }}" class="bg-amra-primary hover:bg-teal-400 text-slate-950 px-4 py-3 rounded-xl font-bold text-xs flex items-center gap-2 active:scale-[0.99] transition-all shadow-lg shadow-teal-500/10">
                <i data-lucide="plus" class="w-4 h-4"></i> Add B2B Lead
            </a>
        </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
        <form action="{{ route('crm.b2b.index') }}" method="GET" class="grid gap-4 md:grid-cols-12 items-end">
            <!-- Search field -->
            <div class="md:col-span-4 space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Search Leads</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ $activeFilters['search'] }}" placeholder="Company, contact person, phone..." 
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl pl-10 pr-4 py-3.5 outline-none font-medium text-xs text-slate-200 transition-all">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="md:col-span-2 space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Category</label>
                <select name="category" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3.5 outline-none font-medium text-xs text-slate-300 transition-all">
                    <option value="">All Categories</option>
                    <option value="agent" {{ $activeFilters['category'] === 'agent' ? 'selected' : '' }}>Agent</option>
                    <option value="developer" {{ $activeFilters['category'] === 'developer' ? 'selected' : '' }}>Developer</option>
                    <option value="single_owner" {{ $activeFilters['category'] === 'single_owner' ? 'selected' : '' }}>Single Owner</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="md:col-span-2 space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</label>
                <select name="status" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3.5 outline-none font-medium text-xs text-slate-300 transition-all">
                    <option value="">All Statuses</option>
                    <option value="conversions" {{ $activeFilters['status'] === 'conversions' ? 'selected' : '' }}>All Conversions</option>
                    <option value="conversions_free" {{ $activeFilters['status'] === 'conversions_free' ? 'selected' : '' }}>Free Listing Conversions</option>
                    <option value="conversions_paid" {{ $activeFilters['status'] === 'conversions_paid' ? 'selected' : '' }}>Paid Listing Conversions</option>
                    <option value="new" {{ $activeFilters['status'] === 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ $activeFilters['status'] === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="qualified" {{ $activeFilters['status'] === 'qualified' ? 'selected' : '' }}>Qualified</option>
                    <option value="not_interested" {{ $activeFilters['status'] === 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                    <option value="follow_up" {{ $activeFilters['status'] === 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div class="md:col-span-2 space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Created Date</label>
                <select name="date_filter" id="date-filter-select" onchange="toggleCustomDates()" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3.5 outline-none font-medium text-xs text-slate-300 transition-all">
                    <option value="">All Time</option>
                    <option value="today" {{ $activeFilters['date_filter'] === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="yesterday" {{ $activeFilters['date_filter'] === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                    <option value="last_7_days" {{ $activeFilters['date_filter'] === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="last_30_days" {{ $activeFilters['date_filter'] === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="custom" {{ $activeFilters['date_filter'] === 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>

            <!-- Submit Buttons -->
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs py-3.5 rounded-xl transition-all flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i> Apply
                </button>
                <a href="{{ route('crm.b2b.index') }}" class="p-3.5 bg-slate-950 hover:bg-slate-900 border border-slate-850 rounded-xl text-slate-500 hover:text-slate-300 transition-all flex items-center justify-center shrink-0">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <!-- Custom date range inputs -->
            <div id="custom-date-container" class="md:col-span-12 grid grid-cols-2 gap-4 mt-2 hidden">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Start Date</label>
                    <input type="date" name="start_date" value="{{ $activeFilters['start_date'] }}" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none font-medium text-xs text-slate-300">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">End Date</label>
                    <input type="date" name="end_date" value="{{ $activeFilters['end_date'] }}" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none font-medium text-xs text-slate-300">
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-400"></i>
                                Company & Client
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                Location & Area
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="radio-tower" class="w-3.5 h-3.5 text-slate-400"></i>
                                Source
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="user-check" class="w-3.5 h-3.5 text-slate-400"></i>
                                Sales Person
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="activity" class="w-3.5 h-3.5 text-slate-400"></i>
                                Status
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="calendar-days" class="w-3.5 h-3.5 text-slate-400"></i>
                                Created Date
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
                    @forelse ($leads as $lead)
                        <tr class="hover:bg-slate-850/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-sm hover:text-amra-primary transition-colors">
                                    <a href="{{ route('crm.b2b.show', $lead->id) }}">{{ $lead->company_name }}</a>
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-slate-800 text-slate-300 border border-slate-700">
                                        {{ str_replace('_', ' ', $lead->category) }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $lead->contact_person_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold text-slate-200">{{ $lead->city }}</span>
                                @if(!empty($lead->service_areas))
                                    <div class="text-[10px] text-slate-400 mt-0.5 truncate max-w-xs">
                                        {{ implode(', ', $lead->service_areas) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-xl text-[10px] font-bold uppercase tracking-wide
                                    {{ $lead->source_platform === 'meta' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                    {{ $lead->source_platform === 'google' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                                    {{ $lead->source_platform === 'website' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : '' }}
                                    {{ $lead->source_platform === 'manual' ? 'bg-slate-500/10 text-slate-400 border border-slate-500/20' : '' }}
                                    {{ $lead->source_platform === 'csv' ? 'bg-violet-500/10 text-violet-500 border border-violet-500/20' : '' }}">
                                    {{ $lead->source_platform }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-300">
                                @if ($lead->assignedSalesPerson)
                                    <span class="flex items-center gap-1.5 text-teal-400">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                        {{ $lead->assignedSalesPerson->name }}
                                    </span>
                                @else
                                    <span class="text-slate-600 italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold uppercase tracking-wider
                                    {{ $lead->status === 'new' ? 'bg-blue-500/15 text-blue-400' : '' }}
                                    {{ $lead->status === 'contacted' ? 'bg-purple-500/15 text-purple-400' : '' }}
                                    {{ $lead->status === 'qualified' ? 'bg-emerald-500/15 text-emerald-450' : '' }}
                                    {{ $lead->status === 'not_interested' ? 'bg-rose-500/15 text-rose-400' : '' }}
                                    {{ $lead->status === 'follow_up' ? 'bg-amber-500/15 text-amber-400' : '' }}
                                    {{ in_array($lead->status, ['free_listing', 'paid_listing', 'converted']) ? 'bg-teal-500/15 text-teal-400' : '' }}">
                                    • {{ str_replace('_', ' ', $lead->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ $lead->lead_created_at ? $lead->lead_created_at->format('d M Y, h:i A') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('crm.b2b.show', $lead->id) }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-350 hover:text-white rounded-xl transition-all" title="View details">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('crm.b2b.edit', $lead->id) }}" class="p-2 bg-slate-850 hover:bg-slate-800 text-slate-450 hover:text-teal-400 rounded-xl transition-all" title="Edit lead">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i data-lucide="folder-open" class="w-10 h-10 text-slate-650"></i>
                                    <p class="text-sm">No B2B leads match the filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if ($leads->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</div>

<!-- CSV Upload Modal -->
@if (in_array(auth()->user()->role, ['super_admin', 'admin']))
<div id="csv-modal" class="fixed inset-0 z-50 overflow-y-auto flex items-start justify-center p-4 hidden">
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black/75 backdrop-blur-sm transition-opacity" onclick="toggleModal('csv-modal')"></div>

    <!-- Modal Content Box -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl max-w-md w-full relative z-10 p-6 space-y-6 my-8">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-serif font-extrabold text-white flex items-center gap-2">
                <i data-lucide="upload-cloud" class="w-5 h-5 text-amra-primary"></i> Bulk CSV Lead Uploader
            </h3>
            <button onclick="toggleModal('csv-modal')" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('crm.b2b.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="border-2 border-dashed border-slate-800 rounded-2xl p-6 text-center bg-slate-950/50 flex flex-col items-center justify-center gap-3 hover:border-amra-primary/30 transition-colors">
                <i data-lucide="file-spreadsheet" class="w-10 h-10 text-slate-550"></i>
                <div class="text-xs">
                    <span class="text-slate-400">Click to choose or drag & drop</span>
                    <p class="text-slate-600 mt-1">Accepts only standard CSV files (Max 5MB)</p>
                </div>
                <input type="file" name="csv_file" accept=".csv" required class="w-full text-slate-400 font-medium text-xs mt-2 px-3 py-2 border border-slate-800 rounded-xl bg-slate-900 focus:outline-none">
            </div>

            <!-- CSV Requirements Specs Info -->
            <div class="bg-slate-950 border border-slate-850 p-5 rounded-xl space-y-3">
                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">B2B CSV Schema Details:</p>
                <div class="space-y-2 text-[10px] leading-relaxed">
                    <div class="text-slate-400 font-semibold border-b border-slate-900 pb-1.5">Required Fields:</div>
                    <div class="grid grid-cols-2 gap-2 text-slate-500 font-mono">
                        <div>• <strong class="text-slate-350">company_name</strong> (text)</div>
                        <div>• <strong class="text-slate-350">contact_person_name</strong> (text)</div>
                        <div>• <strong class="text-slate-350">contact_number</strong> (number)</div>
                        <div>• <strong class="text-slate-350">city</strong> (text e.g. Mumbai)</div>
                    </div>
                    <div class="text-slate-400 font-semibold border-b border-slate-900 pt-1 pb-1.5">Optional Fields:</div>
                    <div class="space-y-1 text-slate-500">
                        <div>• <code class="text-slate-350 font-mono text-[9px]">category</code>: Options are <code class="bg-slate-900 px-1 py-0.5 rounded text-amber-500">agent</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-amber-500">developer</code>, or <code class="bg-slate-900 px-1 py-0.5 rounded text-amber-500">single_owner</code></div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">whatsapp_number</code>: Text (defaults to contact number if left empty)</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">email</code>: Valid email address</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">office_address</code>: Plain text physical office address</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">service_areas</code>: Comma separated coverage localities (e.g. <code class="text-slate-400">Powai, Hiranandani</code>)</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">project_ticket_size_min</code> / <code class="text-slate-350 font-mono text-[9px]">max</code>: Decimal/Numbers in INR</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">source_platform</code>: Options are <code class="bg-slate-900 px-1 py-0.5 rounded text-amber-500">meta</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-amber-500">google</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-amber-500">website</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-amber-500">manual</code></div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">remark</code>: Discovery notes</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-amra-primary text-slate-950 font-bold text-xs py-3.5 rounded-xl active:scale-[0.99] transition-all shadow-lg shadow-teal-500/10">
                Start Bulk Lead Processing
            </button>
        </form>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    function toggleCustomDates() {
        const select = document.getElementById('date-filter-select');
        const container = document.getElementById('custom-date-container');
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

    // Trigger on page load to check if custom range is preselected
    document.addEventListener('DOMContentLoaded', () => {
        toggleCustomDates();
    });
</script>
@endsection
