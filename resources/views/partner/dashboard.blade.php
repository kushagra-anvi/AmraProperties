@extends('layouts.crm')

@section('title', 'Partner Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <span class="inline-flex items-center gap-1 bg-teal-500/10 border border-teal-500/20 text-teal-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <i data-lucide="handshake" class="w-3.5 h-3.5"></i> Partner Workspace
            </span>
            <h1 class="text-3xl font-serif font-extrabold text-amra-dark">Welcome back, {{ $partner->contact_person }}</h1>
            <p class="text-sm text-slate-400">View and manage your assigned B2C buyer leads and subscription package details</p>
        </div>
        <div>
            <a href="{{ route('crm.partner.leads.create') }}" class="bg-amra-primary text-slate-950 hover:bg-teal-300 px-5 py-3 rounded-xl font-extrabold text-xs transition-all shadow-md flex items-center gap-2 active:scale-95">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Add Lead manually
            </a>
        </div>
    </div>

    <!-- Partner Info & Subscription KPI Cards -->
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <!-- Package Level -->
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Active Subscription</p>
            <p class="text-2xl font-bold text-slate-800 capitalize">{{ $partner->package }} Tier Plan</p>
            <p class="text-[10px] text-slate-500 mt-1">
                @if ($partner->paid_amount)
                    Paid Amount: <strong>₹{{ number_format($partner->paid_amount) }}</strong>
                @else
                    Free Tier Access
                @endif
            </p>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Submitted Properties</p>
            <p class="text-2xl font-bold text-slate-800">{{ $propertiesCount ?? 0 }}</p>
            <a href="{{ route('crm.partner.properties.index') }}" class="text-[10px] text-teal-600 font-bold mt-1 inline-flex">Manage listings</a>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Property Enquiries</p>
            <p class="text-2xl font-bold text-teal-500">{{ $propertyEnquiriesCount ?? 0 }}</p>
            <a href="{{ route('crm.partner.properties.index') }}" class="text-[10px] text-teal-600 font-bold mt-1 inline-flex">View enquiries</a>
        </article>

        <!-- Renewal Countdown -->
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Package Expiry Date</p>
            @if ($partner->renewal_date)
                @php
                    $days = now()->startOfDay()->diffInDays($partner->renewal_date, false);
                @endphp
                @if ($days < 0)
                    <p class="text-2xl font-bold text-rose-500">Expired ({{ abs($days) }} days ago)</p>
                @elseif ($days <= 7)
                    <p class="text-2xl font-bold text-amber-500 animate-pulse">Expiring in {{ $days }} days</p>
                @else
                    <p class="text-2xl font-bold text-slate-800">{{ $partner->renewal_date->format('d M Y') }}</p>
                @endif
                <p class="text-[10px] text-slate-500 mt-1">Renewal countdown alert active</p>
            @else
                <p class="text-2xl font-bold text-slate-800">No Expiry</p>
                <p class="text-[10px] text-slate-500 mt-1">Continuous lifetime access</p>
            @endif
        </article>

        <!-- Leads Count -->
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Leads Received</p>
            <p class="text-2xl font-bold text-teal-500">{{ $totalLeadsCount }}</p>
            <p class="text-[10px] text-slate-500 mt-1">Buyer matches received from administration</p>
        </article>
    </section>

    <!-- Follow-up Alerts/Reminders -->
    @if(isset($pendingFollowups) && $pendingFollowups->isNotEmpty())
        <div class="bg-rose-50/50 border border-rose-100 rounded-3xl p-6 shadow-sm mt-6 text-left">
            <h3 class="text-xs font-bold text-rose-700 uppercase tracking-widest mb-4 flex items-center gap-2">
                <i data-lucide="clock" class="w-4 h-4 text-rose-600 animate-pulse"></i> Scheduled Lead Follow-up Reminders
            </h3>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($pendingFollowups as $followup)
                    <div class="bg-white border border-rose-100 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-slate-800 text-sm">
                                    <a href="{{ route('crm.partner.leads.show', $followup->followable_id) }}" class="hover:text-teal-600">
                                        {{ $followup->followable->name }}
                                    </a>
                                </h4>
                                <span class="text-[9px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">
                                    {{ $followup->due_at->format('d M Y') }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                {{ $followup->notes ?: 'No notes added.' }}
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-50 flex justify-between items-center">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Status: Pending</span>
                            <a href="{{ route('crm.partner.leads.show', $followup->followable_id) }}" class="text-[10px] font-bold text-teal-600 hover:text-teal-700 flex items-center gap-0.5">
                                View Lead <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Leads Shared Table -->
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-md mt-6">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Assigned B2C Buyer Leads</h3>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20">
                {{ $shares->total() }} matches
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                        <th class="px-6 py-4">Buyer Info</th>
                        <th class="px-6 py-4">Property Type</th>
                        <th class="px-6 py-4">Configuration</th>
                        <th class="px-6 py-4">Budget Range</th>
                        <th class="px-6 py-4">Received Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($shares as $share)
                        @php
                            $lead = $share->lead;
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <!-- Buyer Details -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-sm hover:text-teal-500 transition-colors">
                                    <a href="{{ route('crm.partner.leads.show', $lead->id) }}">{{ $lead->name }}</a>
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $lead->city }}
                                </div>
                            </td>

                            <!-- Property Type -->
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-600">
                                    {{ $lead->property_type }}
                                </span>
                            </td>

                            <!-- Configuration -->
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-md bg-teal-50 border border-teal-200 text-[10px] font-extrabold text-teal-700 uppercase tracking-wider">
                                    {{ $lead->configuration }}
                                </span>
                            </td>

                            <!-- Budget -->
                            <td class="px-6 py-4 text-xs font-bold text-slate-800">
                                @if ($lead->budget_min || $lead->budget_max)
                                    ₹{{ number_format($lead->budget_min ?? 0) }} - ₹{{ number_format($lead->budget_max ?? 0) }}
                                @else
                                    <span class="text-slate-400 font-normal italic">Open Budget</span>
                                @endif
                            </td>

                            <!-- Shared Date -->
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $share->shared_at->format('d M Y, h:i A') }}
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $statusLabels = ['new' => 'New', 'shared' => 'Shared', 'contacted' => 'Contacted', 'follow_up' => 'Follow-up', 'site_visit_scheduled' => 'Site Visit Scheduled', 'interested' => 'Interested', 'closed_won' => 'Closed/Won', 'closed_lost' => 'Closed/Lost', 'filtered' => 'New'];
                                @endphp
                                <span class="inline-flex rounded-full border border-teal-200 bg-teal-50 px-2.5 py-1 text-[10px] font-extrabold text-teal-700">
                                    {{ $statusLabels[$lead->status] ?? ucfirst(str_replace('_', ' ', $lead->status)) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('crm.partner.leads.show', $lead->id) }}" class="inline-flex items-center gap-1 bg-slate-100 hover:bg-teal-500 text-slate-600 hover:text-white border border-slate-200 px-3 py-1.5 rounded-lg font-bold text-xs transition-colors" title="View details">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i data-lucide="folder-open" class="w-10 h-10 text-slate-300"></i>
                                    <p class="text-sm">No leads have been received by your account yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($shares->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $shares->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
