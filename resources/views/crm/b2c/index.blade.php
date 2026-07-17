@extends('layouts.crm')

@section('title', 'B2C Buyer Leads')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <span class="inline-flex items-center gap-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <i data-lucide="users" class="w-3.5 h-3.5"></i> B2C Pipeline
            </span>
            <h1 class="text-3xl font-serif font-extrabold text-white">B2C Buyer Leads</h1>
            <p class="text-sm text-slate-400">Track property buyers, preferences, and partner distribution streams</p>
        </div>
        
        @if (auth()->user()->role !== 'analyst')
            <div class="flex items-center gap-3">
                <button onclick="toggleModal('b2c-csv-modal')" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 px-4 py-3 rounded-xl font-bold text-xs flex items-center gap-2 active:scale-[0.99] transition-all">
                    <i data-lucide="upload" class="w-4 h-4 text-slate-400"></i> Bulk Upload CSV
                </button>
                <a href="{{ route('crm.b2c.create') }}" class="bg-amra-primary hover:bg-teal-400 text-slate-950 px-4 py-3 rounded-xl font-bold text-xs flex items-center gap-2 active:scale-[0.99] transition-all shadow-lg shadow-teal-500/10">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Buyer Lead
                </a>
            </div>
        @endif
    </div>

    <!-- Filter Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
        <form action="{{ route('crm.b2c.index') }}" method="GET" class="space-y-4">
            <div class="grid gap-4 md:grid-cols-12 items-end">
                <!-- Search -->
                <div class="md:col-span-4 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Search Buyers</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                        <input type="text" name="search" value="{{ $activeFilters['search'] }}" placeholder="Name, phone number, email..." 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl pl-10 pr-4 py-3.5 outline-none font-medium text-xs text-slate-200 transition-all">
                    </div>
                </div>

                <!-- City -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">City</label>
                    <select name="city" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3.5 outline-none font-medium text-xs text-slate-350 transition-all">
                        <option value="">All Cities</option>
                        <option value="Mumbai" {{ $activeFilters['city'] === 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                        <option value="Lucknow" {{ $activeFilters['city'] === 'Lucknow' ? 'selected' : '' }}>Lucknow</option>
                        <option value="Pune" {{ $activeFilters['city'] === 'Pune' ? 'selected' : '' }}>Pune</option>
                        <option value="Delhi" {{ $activeFilters['city'] === 'Delhi' ? 'selected' : '' }}>Delhi</option>
                        <option value="Bangalore" {{ $activeFilters['city'] === 'Bangalore' ? 'selected' : '' }}>Bangalore</option>
                    </select>
                </div>

                <!-- Property Type -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Property Type</label>
                    <select name="property_type" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3.5 outline-none font-medium text-xs text-slate-350 transition-all">
                        <option value="">All Types</option>
                        <option value="flat" {{ $activeFilters['property_type'] === 'flat' ? 'selected' : '' }}>Flat</option>
                        <option value="plot" {{ $activeFilters['property_type'] === 'plot' ? 'selected' : '' }}>Plot</option>
                        <option value="villa" {{ $activeFilters['property_type'] === 'villa' ? 'selected' : '' }}>Villa</option>
                        <option value="commercial" {{ $activeFilters['property_type'] === 'commercial' ? 'selected' : '' }}>Commercial</option>
                    </select>
                </div>

                <!-- Preferred Location -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Preferred Location</label>
                    <input type="text" name="preferred_location" value="{{ $activeFilters['preferred_location'] }}" placeholder="Powai, Bandra..."
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3.5 outline-none font-medium text-xs text-slate-200 transition-all">
                </div>

                <!-- Configuration -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Configuration</label>
                    <select name="configuration" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3.5 outline-none font-medium text-xs text-slate-350 transition-all">
                        <option value="">All Configurations</option>
                        <option value="1BHK" {{ $activeFilters['configuration'] === '1BHK' ? 'selected' : '' }}>1 BHK</option>
                        <option value="2BHK" {{ $activeFilters['configuration'] === '2BHK' ? 'selected' : '' }}>2 BHK</option>
                        <option value="3BHK" {{ $activeFilters['configuration'] === '3BHK' ? 'selected' : '' }}>3 BHK</option>
                        <option value="4BHK" {{ $activeFilters['configuration'] === '4BHK' ? 'selected' : '' }}>4 BHK</option>
                        <option value="Plot" {{ $activeFilters['configuration'] === 'Plot' ? 'selected' : '' }}>Plot</option>
                        <option value="Studio" {{ $activeFilters['configuration'] === 'Studio' ? 'selected' : '' }}>Studio</option>
                    </select>
                </div>

                <!-- Date range trigger -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date Created</label>
                    <select name="date_filter" id="b2c-date-filter" onchange="toggleCustomDates()" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3.5 outline-none font-medium text-xs text-slate-350 transition-all">
                        <option value="">All Time</option>
                        <option value="today" {{ $activeFilters['date_filter'] === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ $activeFilters['date_filter'] === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="last_7_days" {{ $activeFilters['date_filter'] === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="last_30_days" {{ $activeFilters['date_filter'] === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="custom" {{ $activeFilters['date_filter'] === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
            </div>

            <!-- Advanced Row: Budgets & Source & Actions -->
            <div class="grid gap-4 md:grid-cols-12 items-end pt-2 border-t border-slate-800/40">
                <!-- Budget Min -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Min Budget (INR)</label>
                    <input type="number" name="budget_min" value="{{ $activeFilters['budget_min'] }}" placeholder="e.g. 50,00,000" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-300">
                </div>

                <!-- Budget Max -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Max Budget (INR)</label>
                    <input type="number" name="budget_max" value="{{ $activeFilters['budget_max'] }}" placeholder="e.g. 2,00,00,000" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-300">
                </div>

                <!-- Source Platform -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lead Source</label>
                    <select name="source_platform" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3.5 outline-none font-medium text-xs text-slate-355 transition-all">
                        <option value="">All Sources</option>
                        <option value="website" {{ $activeFilters['source_platform'] === 'website' ? 'selected' : '' }}>Website</option>
                        <option value="meta" {{ $activeFilters['source_platform'] === 'meta' ? 'selected' : '' }}>Meta Ads</option>
                        <option value="google" {{ $activeFilters['source_platform'] === 'google' ? 'selected' : '' }}>Google Ads</option>
                        <option value="manual" {{ $activeFilters['source_platform'] === 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>

                <!-- Assigned Sales Person / Tele-caller -->
                <div class="md:col-span-3 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Assigned Representative</label>
                    <select name="sales_person_id" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3.5 outline-none font-medium text-xs text-slate-355 transition-all">
                        <option value="">All Representatives</option>
                        @foreach ($salesPeople as $sp)
                            <option value="{{ $sp->id }}" {{ $activeFilters['sales_person_id'] == $sp->id ? 'selected' : '' }}>
                                {{ $sp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="md:col-span-3 flex gap-2">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-3.5 rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-md shadow-emerald-500/10">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i> Apply Filters
                    </button>
                    <a href="{{ route('crm.b2c.index') }}" class="p-3.5 bg-slate-950 hover:bg-slate-900 border border-slate-850 rounded-xl text-slate-500 hover:text-slate-300 transition-all flex items-center justify-center shrink-0">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            <!-- Custom date range inputs -->
            <div id="custom-date-fields" class="grid grid-cols-2 gap-4 mt-2 hidden">
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

    <!-- Sharing activity dashboard -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-xs font-bold text-slate-300 uppercase tracking-widest">Lead Sharing Activity</h2>
                <p class="mt-1 text-[10px] text-slate-500">Recipient deliveries recorded within the selected period.</p>
            </div>
            <form method="GET" action="{{ route('crm.b2c.index') }}" class="flex flex-wrap items-end gap-2">
                <select name="share_date_filter" id="share-date-filter" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-slate-300">
                    <option value="">All Time</option>
                    <option value="today" {{ $activeFilters['share_date_filter'] === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="last_7_days" {{ $activeFilters['share_date_filter'] === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="last_30_days" {{ $activeFilters['share_date_filter'] === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="custom" {{ $activeFilters['share_date_filter'] === 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
                <input type="date" name="share_start_date" value="{{ $activeFilters['share_start_date'] }}" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-300">
                <input type="date" name="share_end_date" value="{{ $activeFilters['share_end_date'] }}" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-300">
                <button class="bg-teal-500 hover:bg-teal-400 text-slate-950 rounded-xl px-4 py-2.5 text-xs font-bold">Apply Period</button>
            </form>
        </div>
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach (['total' => 'Total Shares', 'sales_team' => 'Sales Team', 'builders' => 'Builders', 'agents' => 'Agents'] as $key => $label)
                <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4">
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500">{{ $label }}</p>
                    <p class="mt-1 text-2xl font-extrabold text-white">{{ $shareActivityStats[$key] }}</p>
                </div>
            @endforeach
        </div>
        <div class="overflow-x-auto rounded-xl border border-slate-800">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-[9px] uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Shared At</th><th class="px-4 py-3">Lead</th><th class="px-4 py-3">Recipient</th><th class="px-4 py-3">Type</th><th class="px-4 py-3">Shared By</th></tr></thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($recentShareActivity as $activity)
                        <tr><td class="px-4 py-3 text-slate-400 whitespace-nowrap">{{ $activity->shared_at->format('d M Y, h:i A') }}</td><td class="px-4 py-3"><a class="font-bold text-white hover:text-teal-400" href="{{ route('crm.b2c.show', $activity->b2_c_lead_id) }}">{{ $activity->lead?->name ?? 'Deleted lead' }}</a></td><td class="px-4 py-3 text-slate-300">{{ $activity->recipient_display_name }}</td><td class="px-4 py-3 uppercase text-[9px] font-bold text-teal-400">{{ str_replace('_', ' ', $activity->recipient_type) }}</td><td class="px-4 py-3 text-slate-500">{{ $activity->sharedBy?->name ?? 'System' }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">No sharing activity in this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-md">
        @if (auth()->user()->role !== 'analyst')
            <form id="bulk-assign-form" action="{{ route('crm.b2c.bulk-assign') }}" method="POST">
                @csrf

                <div class="border-b border-slate-800 bg-slate-950/50 px-6 py-4">
                    <div class="grid gap-3 lg:grid-cols-[minmax(130px,auto)_minmax(180px,240px)_1fr_auto_auto] lg:items-end">
                        <div class="rounded-xl border border-slate-800 bg-slate-950 px-4 py-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Selected Leads</p>
                            <p class="mt-1 text-sm font-extrabold text-white"><span id="bulk-selected-count">0</span> selected</p>
                        </div>

                        <div class="space-y-1.5">
                            <label for="bulk-assignee" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tele-caller</label>
                            <select id="bulk-assignee" name="assigned_sales_person_id" class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-xs font-medium text-slate-300 outline-none transition-all focus:border-amra-primary">
                                <option value="">Set Unassigned</option>
                                @foreach ($salesPeople as $sp)
                                    <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="bulk-assignment-note" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Assignment Note</label>
                            <input id="bulk-assignment-note" type="text" name="notes" placeholder="Optional note for selected buyer leads..."
                                class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-xs font-medium text-slate-200 outline-none transition-all focus:border-amra-primary">
                        </div>

                        <button id="bulk-assign-submit" type="submit" disabled class="inline-flex h-[42px] items-center justify-center gap-2 rounded-xl bg-amra-primary px-4 text-xs font-extrabold text-slate-950 shadow-lg shadow-teal-500/10 transition-all hover:bg-teal-400 disabled:cursor-not-allowed disabled:opacity-40 disabled:shadow-none">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                            Assign Selected
                        </button>
                        <button id="bulk-share-open" type="button" disabled onclick="toggleModal('b2c-bulk-share-modal')" class="inline-flex h-[42px] items-center justify-center gap-2 rounded-xl bg-indigo-500 px-4 text-xs font-extrabold text-white transition-all hover:bg-indigo-400 disabled:cursor-not-allowed disabled:opacity-40">
                            <i data-lucide="share-2" class="w-4 h-4"></i> Share Selected
                        </button>
                    </div>
                </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                        @if (auth()->user()->role !== 'analyst')
                            <th class="px-6 py-4 whitespace-nowrap">
                                <input id="select-all-leads" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-amra-primary focus:ring-amra-primary">
                            </th>
                        @endif
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="user-round" class="w-3.5 h-3.5 text-slate-400"></i>
                                Buyer Profile
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                City & Preference
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="home" class="w-3.5 h-3.5 text-slate-400"></i>
                                Property Preferences
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="indian-rupee" class="w-3.5 h-3.5 text-slate-400"></i>
                                Budget Range
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="radio-tower" class="w-3.5 h-3.5 text-slate-400"></i>
                                Platform
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="user-check" class="w-3.5 h-3.5 text-slate-400"></i>
                                Representative
                            </span>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <i data-lucide="share-2" class="w-3.5 h-3.5 text-slate-400"></i>
                                Shared Status
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
                            @if (auth()->user()->role !== 'analyst')
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" class="lead-checkbox h-4 w-4 rounded border-slate-700 bg-slate-950 text-amra-primary focus:ring-amra-primary">
                                </td>
                            @endif
                            <!-- Buyer profile info -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-sm hover:text-emerald-400 transition-colors">
                                    <a href="{{ route('crm.b2c.show', $lead->id) }}">{{ $lead->name }}</a>
                                </div>
                                <div class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                                    <span>{{ $lead->phone }}</span>
                                    <button onclick="initiateClickToCall('{{ $lead->phone }}')" class="text-teal-400 hover:text-teal-350 transition-colors p-0.5" title="Initiate Call">
                                        <i data-lucide="phone-call" class="w-3.5 h-3.5"></i>
                                    </button>
                                    @if ($lead->email)
                                        <span class="text-slate-600">•</span>
                                        <span class="truncate max-w-xs">{{ $lead->email }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- City & preference locations -->
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold text-slate-200">{{ $lead->city }}</span>
                                @if (!empty($lead->preferred_locations))
                                    <div class="text-[10px] text-slate-500 mt-0.5 max-w-xs truncate">
                                        {{ implode(', ', $lead->preferred_locations) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Property details -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-50 border border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-700">
                                        {{ $lead->property_type }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md bg-teal-50 border border-teal-200 text-[10px] font-extrabold text-teal-700 uppercase tracking-wider">
                                        {{ $lead->configuration }}
                                    </span>
                                </div>
                            </td>

                            <!-- Budget -->
                            <td class="px-6 py-4 text-xs font-bold text-white">
                                @if ($lead->budget_min || $lead->budget_max)
                                    ₹{{ number_format($lead->budget_min ?? 0) }} - ₹{{ number_format($lead->budget_max ?? 0) }}
                                @else
                                    <span class="text-slate-655 font-normal italic">Open Budget</span>
                                @endif
                            </td>

                            <!-- Source -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wide
                                    {{ $lead->source_platform === 'website' ? 'bg-teal-500/10 text-teal-400 border border-teal-500/20' : '' }}
                                    {{ $lead->source_platform === 'meta' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                    {{ $lead->source_platform === 'google' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                                    {{ $lead->source_platform === 'manual' ? 'bg-slate-500/10 text-slate-400 border border-slate-500/20' : '' }}">
                                    {{ $lead->source_platform }}
                                </span>
                            </td>

                            <!-- Assigned Sales Person -->
                            <td class="px-6 py-4">
                                @if ($lead->assignedSalesPerson)
                                    <div class="flex items-center gap-1.5 text-white font-semibold text-xs">
                                        <div class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></div>
                                        <span>{{ $lead->assignedSalesPerson->name }}</span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Tele-caller</div>
                                @else
                                    <span class="text-slate-600 font-normal italic text-xs">Unassigned</span>
                                @endif
                            </td>

                            <!-- Shared Status -->
                            <td class="px-6 py-4">
                                @php $shareCount = $lead->shares_count; @endphp
                                @if ($shareCount > 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20">
                                        <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                                        Shared to {{ $shareCount }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-800 text-slate-500 border border-slate-750">
                                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                        Not Shared
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('crm.b2c.show', $lead->id) }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-350 hover:text-white rounded-xl transition-all" title="View details & Share">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    @if (auth()->user()->role !== 'analyst')
                                        <a href="{{ route('crm.b2c.edit', $lead->id) }}" class="p-2 bg-slate-850 hover:bg-slate-800 text-slate-450 hover:text-teal-400 rounded-xl transition-all" title="Edit buyer profile">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role !== 'analyst' ? 9 : 8 }}" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i data-lucide="folder-open" class="w-10 h-10 text-slate-650"></i>
                                    <p class="text-sm">No buyer leads match the current filters.</p>
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

        @if (auth()->user()->role !== 'analyst')
            </form>
        @endif
    </div>
</div>

@if (auth()->user()->role !== 'analyst')
<!-- Bulk lead sharing modal -->
<div id="b2c-bulk-share-modal" class="fixed inset-0 z-50 overflow-y-auto flex items-start justify-center p-4 hidden">
    <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" onclick="toggleModal('b2c-bulk-share-modal')"></div>
    <div class="relative z-10 my-8 w-full max-w-2xl rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-2xl">
        <div class="mb-5 flex items-center justify-between">
            <div><h3 class="text-lg font-serif font-extrabold text-white">Bulk Lead Sharing</h3><p class="text-[10px] text-slate-500">Each selected lead will be shared with every selected recipient.</p></div>
            <button type="button" onclick="toggleModal('b2c-bulk-share-modal')" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="bulk-share-form" action="{{ route('crm.b2c.bulk-share') }}" method="POST" class="space-y-5">
            @csrf
            <div id="bulk-share-lead-inputs"></div>
            <div class="grid gap-5 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sales Team</label>
                    <div class="max-h-56 space-y-2 overflow-y-auto rounded-xl border border-slate-800 bg-slate-950 p-3">
                        @foreach ($salesPeople as $person)
                            <label class="flex items-center gap-2 rounded-lg border border-slate-800 p-2 text-xs text-slate-200"><input type="checkbox" name="sales_person_ids[]" value="{{ $person->id }}" class="rounded bg-slate-900 text-teal-500"> {{ $person->name }}</label>
                        @endforeach
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Builders & Agents</label>
                    <div class="max-h-56 space-y-2 overflow-y-auto rounded-xl border border-slate-800 bg-slate-950 p-3">
                        @foreach ($partners as $partner)
                            <label class="flex items-center gap-2 rounded-lg border border-slate-800 p-2 text-xs text-slate-200"><input type="checkbox" name="partner_ids[]" value="{{ $partner->id }}" class="rounded bg-slate-900 text-teal-500"><span class="truncate">{{ $partner->company_name }}</span><span class="ml-auto text-[8px] font-bold uppercase text-slate-500">{{ $partner->type === 'developer' ? 'Builder' : 'Agent' }}</span></label>
                        @endforeach
                    </div>
                </div>
            </div>
            <textarea name="remark" rows="3" placeholder="Optional sharing note..." class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-xs text-slate-200 focus:border-amra-primary"></textarea>
            @error('recipients')<p class="text-xs font-bold text-rose-400">{{ $message }}</p>@enderror
            <button type="submit" class="w-full rounded-xl bg-indigo-500 py-3.5 text-xs font-bold text-white hover:bg-indigo-400">Share Selected Leads</button>
        </form>
    </div>
</div>

<!-- Bulk CSV Import Modal -->
<div id="b2c-csv-modal" class="fixed inset-0 z-50 overflow-y-auto flex items-start justify-center p-4 hidden">
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black/75 backdrop-blur-sm transition-opacity" onclick="toggleModal('b2c-csv-modal')"></div>

    <!-- Modal Content Box -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl max-w-md w-full relative z-10 p-6 space-y-6 my-8">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-serif font-extrabold text-white flex items-center gap-2">
                <i data-lucide="upload-cloud" class="w-5 h-5 text-emerald-400"></i> Bulk B2C CSV Uploader
            </h3>
            <button onclick="toggleModal('b2c-csv-modal')" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('crm.b2c.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="border-2 border-dashed border-slate-800 rounded-2xl p-6 text-center bg-slate-950/50 flex flex-col items-center justify-center gap-3 hover:border-emerald-500/30 transition-colors">
                <i data-lucide="file-spreadsheet" class="w-10 h-10 text-slate-550"></i>
                <div class="text-xs">
                    <span class="text-slate-400">Click to choose or drag & drop</span>
                    <p class="text-slate-655 mt-1">Accepts only standard CSV files (Max 5MB)</p>
                </div>
                <input type="file" name="csv_file" accept=".csv" required class="w-full text-slate-400 font-medium text-xs mt-2 px-3 py-2 border border-slate-800 rounded-xl bg-slate-900 focus:outline-none">
            </div>

            <!-- CSV Requirements Specs Info -->
            <div class="bg-slate-950 border border-slate-850 p-5 rounded-xl space-y-3">
                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">B2C CSV Schema Details:</p>
                <div class="space-y-2 text-[10px] leading-relaxed">
                    <div class="text-slate-400 font-semibold border-b border-slate-900 pb-1.5">Required Fields:</div>
                    <div class="grid grid-cols-2 gap-2 text-slate-500 font-mono">
                        <div>• <strong class="text-slate-350">name</strong> (text)</div>
                        <div>• <strong class="text-slate-350">phone</strong> (number)</div>
                        <div>• <strong class="text-slate-350">city</strong> (text e.g. Mumbai)</div>
                        <div>• <strong class="text-slate-350">property_type</strong> (text)</div>
                        <div>• <strong class="text-slate-350">configuration</strong> (text)</div>
                    </div>
                    <div class="text-[9px] text-slate-550 mt-1 italic leading-snug">
                        Allowed values for <strong class="text-slate-400">property_type</strong>: plot, flat, villa, commercial.<br>
                        Allowed values for <strong class="text-slate-400">configuration</strong>: 1BHK, 2BHK, 3BHK, 4BHK, Plot, Studio.
                    </div>
                    
                    <div class="text-slate-400 font-semibold border-b border-slate-900 pt-2 pb-1.5">Optional Fields:</div>
                    <div class="space-y-1 text-slate-500">
                        <div>• <code class="text-slate-350 font-mono text-[9px]">email</code>: Valid email address</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">pincode</code>: Code number</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">budget_min</code> / <code class="text-slate-350 font-mono text-[9px]">budget_max</code>: Numbers in INR</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">preferred_locations</code>: Comma separated coverage localities (e.g. <code class="text-slate-400">Powai, Bandra</code>)</div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">source_platform</code>: Options are <code class="bg-slate-900 px-1 py-0.5 rounded text-emerald-400">meta</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-emerald-400">google</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-emerald-400">website</code>, <code class="bg-slate-900 px-1 py-0.5 rounded text-emerald-400">manual</code></div>
                        <div>• <code class="text-slate-350 font-mono text-[9px]">remark</code>: Additional comments</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-3.5 rounded-xl active:scale-[0.99] transition-all shadow-lg shadow-emerald-500/10">
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
        const select = document.getElementById('b2c-date-filter');
        const container = document.getElementById('custom-date-fields');
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
        @if ($errors->has('recipients'))
            toggleModal('b2c-bulk-share-modal');
        @endif

        const selectAll = document.getElementById('select-all-leads');
        const leadCheckboxes = Array.from(document.querySelectorAll('.lead-checkbox'));
        const selectedCount = document.getElementById('bulk-selected-count');
        const submitButton = document.getElementById('bulk-assign-submit');
        const bulkShareButton = document.getElementById('bulk-share-open');
        const bulkShareForm = document.getElementById('bulk-share-form');
        const bulkForm = document.getElementById('bulk-assign-form');

        function updateBulkControls() {
            const checkedCount = leadCheckboxes.filter((checkbox) => checkbox.checked).length;

            if (selectedCount) {
                selectedCount.textContent = checkedCount;
            }

            if (submitButton) {
                submitButton.disabled = checkedCount === 0;
            }
            if (bulkShareButton) {
                bulkShareButton.disabled = checkedCount === 0;
            }

            if (selectAll) {
                selectAll.checked = checkedCount > 0 && checkedCount === leadCheckboxes.length;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < leadCheckboxes.length;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                leadCheckboxes.forEach((checkbox) => {
                    checkbox.checked = selectAll.checked;
                });
                updateBulkControls();
            });
        }

        leadCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updateBulkControls);
        });

        if (bulkForm) {
            bulkForm.addEventListener('submit', (event) => {
                const checkedCount = leadCheckboxes.filter((checkbox) => checkbox.checked).length;
                if (checkedCount === 0) {
                    event.preventDefault();
                    updateBulkControls();
                }
            });
        }

        if (bulkShareForm) {
            bulkShareForm.addEventListener('submit', (event) => {
                const selected = leadCheckboxes.filter((checkbox) => checkbox.checked);
                if (selected.length === 0) {
                    event.preventDefault();
                    return;
                }
                const inputContainer = document.getElementById('bulk-share-lead-inputs');
                inputContainer.innerHTML = '';
                selected.forEach((checkbox) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'lead_ids[]';
                    input.value = checkbox.value;
                    inputContainer.appendChild(input);
                });
            });
        }

        updateBulkControls();
    });
</script>
@endsection
