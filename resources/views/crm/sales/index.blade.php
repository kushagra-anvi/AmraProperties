@extends('layouts.crm')

@section('title', 'Sales Performance Tracking')

@section('content')
<div class="space-y-6">
    <!-- Header Block -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> Sales Operations
            </span>
            <h1 class="text-3xl font-serif font-extrabold text-white">Sales Performance Tracking</h1>
            <p class="text-sm text-slate-400">Evaluate sales team lead engagement counts, subscription conversions, and pipeline KPIs</p>
        </div>
        <div>
            <a href="{{ route('crm.sales.create') }}" class="bg-amra-primary hover:bg-teal-600 text-white font-bold text-xs px-5 py-3.5 rounded-xl transition-all shadow-md flex items-center gap-1.5 active:scale-95">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Add Sales Rep
            </a>
        </div>
    </div>

    <!-- Toolbar Filters -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
        <form action="{{ route('crm.sales.index') }}" method="GET" class="grid gap-4 lg:grid-cols-[1fr_180px_160px_160px_auto] items-end">
            <div class="relative w-full">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Search Sales</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search sales person..." 
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl pl-10 pr-4 py-3 outline-none text-xs text-slate-200">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Date Range</label>
                <select name="date_filter" id="sales-date-filter" onchange="toggleSalesDates()" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-200">
                    <option value="">All Time</option>
                    @foreach (config('crm.date_filters') as $value => $label)
                        <option value="{{ $value }}" {{ ($activeFilters['date_filter'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sales-custom-date {{ ($activeFilters['date_filter'] ?? '') === 'custom' ? '' : 'hidden' }}">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Start</label>
                <input type="date" name="start_date" value="{{ $activeFilters['start_date'] ?? '' }}" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-200">
            </div>

            <div class="sales-custom-date {{ ($activeFilters['date_filter'] ?? '') === 'custom' ? '' : 'hidden' }}">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">End</label>
                <input type="date" name="end_date" value="{{ $activeFilters['end_date'] ?? '' }}" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-200">
            </div>

            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-5 py-3 rounded-xl transition-all">
                Apply
            </button>
        </form>
    </div>

    <!-- Leader Board Ranks Grid -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="user-check" class="w-3.5 h-3.5 text-slate-400"></i>
                                Sales Representative
                            </span>
                        </th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Assigned Leads</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Contacted</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Qualified</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Free Listing</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Paid Listing</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Total Sales (INR)</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Paid Ratio</th>
                        <th class="px-6 py-4 text-right whitespace-nowrap">
                            <span class="inline-flex items-center justify-end gap-2">
                                Actions
                                <i data-lucide="settings-2" class="w-3.5 h-3.5 text-slate-400"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse ($salesPeople as $person)
                        <tr class="hover:bg-slate-850/20 transition-colors">
                            <!-- Representative name -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-sm hover:text-amra-primary transition-colors">
                                    <a href="{{ route('crm.sales.show', $person->id) }}">{{ $person->name }}</a>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500 uppercase mt-0.5 block flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-slate-600"></i>
                                    {{ $person->location ?? 'Headquarters' }}
                                </span>
                            </td>

                            <!-- Assigned B2B Leads -->
                            <td class="px-6 py-4 text-center font-bold text-slate-200">
                                {{ $person->assigned_count }}
                            </td>

                            <!-- Contacted leads -->
                            <td class="px-6 py-4 text-center text-purple-400 font-semibold">
                                {{ $person->contacted_count }}
                            </td>

                            <!-- Qualified leads -->
                            <td class="px-6 py-4 text-center text-emerald-400 font-semibold">
                                {{ $person->qualified_count }}
                            </td>

                            <!-- Free Listings Converted -->
                            <td class="px-6 py-4 text-center text-teal-400 font-semibold">
                                {{ $person->free_listing_count }}
                            </td>

                            <!-- Paid Listings Converted -->
                            <td class="px-6 py-4 text-center text-amber-400 font-semibold">
                                {{ $person->paid_listing_count }}
                            </td>

                            <!-- Total Sales amount -->
                            <td class="px-6 py-4 text-center font-bold text-white">
                                ₹{{ number_format($person->total_sale) }}
                            </td>

                            <!-- Conversion Ratio -->
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    {{ $person->paid_ratio }}%
                                </span>
                            </td>

                            <!-- View profile -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('crm.sales.edit', $person->id) }}" class="p-2.5 bg-slate-800 hover:bg-slate-700 text-slate-350 hover:text-white rounded-xl transition-all inline-flex items-center gap-1" title="Edit account">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('crm.sales.show', $person->id) }}" class="p-2.5 bg-slate-800 hover:bg-slate-700 text-slate-350 hover:text-white rounded-xl transition-all inline-flex items-center gap-1" title="View details">
                                        <i data-lucide="trending-up" class="w-4 h-4"></i> Performance Profile
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i data-lucide="folder-open" class="w-10 h-10 text-slate-655"></i>
                                    <p class="text-sm">No sales representative profiles found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleSalesDates() {
        const select = document.getElementById('sales-date-filter');
        document.querySelectorAll('.sales-custom-date').forEach((field) => {
            field.classList.toggle('hidden', select.value !== 'custom');
        });
    }

    document.addEventListener('DOMContentLoaded', toggleSalesDates);
</script>
@endsection
