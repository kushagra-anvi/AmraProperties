@extends('layouts.crm')

@section('title', 'Buyer Profile - ' . $lead->name)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs & actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('crm.b2c.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Buyer Pipeline
        </a>
        @if (auth()->user()->role !== 'analyst')
            <a href="{{ route('crm.b2c.edit', $lead->id) }}" class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 active:scale-[0.99] transition-all">
                <i data-lucide="edit-3" class="w-4 h-4 text-slate-400"></i> Edit Profile
            </a>
        @endif
    </div>

    <!-- Client Header Banner -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full filter blur-3xl pointer-events-none"></div>

        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-450 flex items-center justify-center shrink-0">
                    <i data-lucide="user" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-serif font-extrabold text-white">{{ $lead->name }}</h1>
                    <p class="text-sm text-slate-400 mt-1 flex items-center gap-1.5">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-500"></i> Phone Contact: <strong class="text-slate-350 font-semibold">{{ $lead->phone }}</strong>
                        <button onclick="initiateClickToCall('{{ $lead->phone }}')" class="inline-flex items-center justify-center p-1 rounded-md bg-teal-500/10 text-teal-400 hover:bg-teal-500 hover:text-white transition-all ml-1.5" title="Click to Call">
                            <i data-lucide="phone-call" class="w-3.5 h-3.5"></i>
                        </button>
                    </p>
                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                        <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $lead->city }}</span>
                        <span class="flex items-center gap-1"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> Generated: {{ $lead->lead_created_at ? $lead->lead_created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Pipeline Status Pill -->
            <div class="flex flex-col items-start md:items-end gap-2">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pipeline Status</span>
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $lead->status === 'new' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : '' }}
                    {{ $lead->status === 'contacted' ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : '' }}
                    {{ $lead->status === 'filtered' ? 'bg-amber-500/20 text-amber-500 border border-amber-500/30' : '' }}
                    {{ $lead->status === 'shared' ? 'bg-teal-500/20 text-teal-500 border border-teal-500/30' : '' }}
                    {{ $lead->status === 'closed' ? 'bg-emerald-500/20 text-emerald-500 border border-emerald-500/30' : '' }}
                    {{ $lead->status === 'qualified' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : '' }}
                    {{ $lead->status === 'not_interested' ? 'bg-rose-500/20 text-rose-400 border border-rose-500/30' : '' }}">
                    ● {{ str_replace('_', ' ', $lead->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid gap-6 lg:grid-cols-12 items-start">
        
        <!-- Left Column: Preferences & Shared Partners History (7/12 width) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Buyer Requirement Profile Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Buyer Requirements</h3>
                
                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Property Type</span>
                        <div class="text-sm font-semibold text-white capitalize mt-0.5">{{ $lead->property_type }}</div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Room Configuration</span>
                        <div class="text-sm font-semibold text-emerald-450 mt-0.5">{{ $lead->configuration }}</div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Budget Bracket</span>
                        <div class="text-sm font-bold text-white mt-0.5">
                            @if ($lead->budget_min || $lead->budget_max)
                                ₹{{ number_format($lead->budget_min ?? 0) }} - ₹{{ number_format($lead->budget_max ?? 0) }}
                            @else
                                <span class="text-slate-655 font-normal italic">Open Budget</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Area Pincode</span>
                        <div class="text-sm font-semibold text-white mt-0.5">{{ $lead->pincode ?? 'Not Specified' }}</div>
                    </div>

                    <div class="space-y-1 sm:col-span-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Preferred Locations</span>
                        @if (!empty($lead->preferred_locations))
                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                @foreach ($lead->preferred_locations as $loc)
                                    <span class="px-2.5 py-1 rounded bg-slate-850 text-[10px] font-semibold text-slate-300 border border-slate-750 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-slate-500"></i> {{ $loc }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-slate-650 italic block mt-0.5">All of {{ $lead->city }}</span>
                        @endif
                    </div>
                </div>

                @if ($lead->remark)
                    <div class="bg-slate-950/60 p-4 border border-slate-850 rounded-xl mt-4">
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Buyer Notes & Remarks</span>
                        <p class="text-xs text-slate-350 mt-1 leading-relaxed">{{ $lead->remark }}</p>
                    </div>
                @endif
            </div>

            <!-- Distribution / Sharing Log -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Lead Sharing History</h3>
                    <span class="rounded-full border border-teal-500/20 bg-teal-500/10 px-2.5 py-1 text-[10px] font-bold text-teal-400">{{ $lead->shares->count() }} total shares</span>
                </div>
                
                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                    @forelse ($lead->shares as $share)
                        <div class="bg-slate-950/40 p-4 border border-slate-850 rounded-2xl relative">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-850/60 pb-2 mb-2 text-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-teal-500/10 text-teal-400 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                        {{ substr($share->recipient_display_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong class="text-white text-xs">{{ $share->recipient_display_name }}</strong>
                                        <span class="text-[9px] font-bold text-slate-550 uppercase tracking-wider block">{{ str_replace('_', ' ', $share->recipient_type) }}</span>
                                    </div>
                                </div>
                                <div class="text-[9px] font-bold text-slate-500 uppercase text-left sm:text-right shrink-0">
                                    Shared: {{ $share->shared_at->format('d M Y, h:i A') }}
                                </div>
                            </div>
                            @if ($share->admin_notes)
                                <div class="mt-2 text-xs p-2.5 rounded-lg bg-slate-900 border border-slate-800">
                                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest block mb-1">Admin Share Notes</span>
                                    <span class="text-slate-350 leading-relaxed italic">"{{ $share->admin_notes }}"</span>
                                </div>
                            @endif

                            @if ($share->remark)
                                <div class="mt-2 text-xs p-2.5 rounded-lg bg-teal-950/20 border border-teal-900/30">
                                    <span class="text-[9px] font-bold text-teal-400 uppercase tracking-widest block mb-1">Partner Feedback Remarks</span>
                                    <span class="text-slate-200 leading-relaxed italic">"{{ $share->remark }}"</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between text-[10px] text-slate-500 font-semibold mt-2.5 pt-2 border-t border-slate-850/40">
                                <span class="flex items-center gap-1"><i data-lucide="user-check" class="w-3.5 h-3.5"></i> Distributed by: {{ $share->sharedBy ? $share->sharedBy->name : 'System' }}</span>
                                @if ($share->recipient_contact)
                                    <span class="flex items-center gap-1 text-teal-400"><i data-lucide="smartphone" class="w-3.5 h-3.5"></i> {{ $share->recipient_contact }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 space-y-2">
                            <i data-lucide="alert-circle" class="w-8 h-8 text-slate-650 mx-auto"></i>
                            <p class="text-xs text-slate-550">This buyer lead has not been shared yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Column: Interactive Share Form (5/12 width) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Lead Sharing Dashboard Panel -->
            @if (auth()->user()->role !== 'analyst')
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Current Lead Status</h3>
                    <form action="{{ route('crm.b2c.status', $lead->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <select name="status" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-300">
                            @foreach (['new' => 'New', 'shared' => 'Shared', 'contacted' => 'Contacted', 'follow_up' => 'Follow-up', 'site_visit_scheduled' => 'Site Visit Scheduled', 'interested' => 'Interested', 'closed_won' => 'Closed/Won', 'closed_lost' => 'Closed/Lost'] as $value => $label)
                                <option value="{{ $value }}" {{ $lead->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="notes" placeholder="Optional status update note..." class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-200">
                        <button class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-3 rounded-xl">Update Status</button>
                    </form>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Admin Filter</h3>
                    <form action="{{ route('crm.b2c.filter', $lead->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <textarea name="notes" rows="2" placeholder="Filter notes, budget fit, location match..." class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-200"></textarea>
                        <button type="submit" class="w-full bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 border border-amber-500/20 font-bold text-xs py-3 rounded-xl transition-all">
                            Mark As Admin Filtered
                        </button>
                    </form>
                </div>

                <!-- Sales Person / Tele-caller Assignment -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Lead Assignment</h3>
                    
                    <form action="{{ route('crm.b2c.assign', $lead->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="space-y-1.5">
                            <label for="assignee-select" class="text-[10px] font-bold text-slate-500 uppercase">Tele-caller Representative</label>
                            <select id="assignee-select" name="assigned_sales_person_id" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-300">
                                <option value="">Leave Unassigned</option>
                                @foreach ($salesPeople as $person)
                                    <option value="{{ $person->id }}" {{ $lead->assigned_sales_person_id == $person->id ? 'selected' : '' }}>{{ $person->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="assignee-note" class="text-[10px] font-bold text-slate-500 uppercase">Assignment Note</label>
                            <textarea id="assignee-note" name="notes" rows="2" placeholder="e.g. Assigned to follow up on configuration/budget match..." class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-200"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-3 rounded-xl transition-all shadow-md shadow-emerald-500/10">
                            Update Representative
                        </button>
                    </form>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between border-b border-slate-800 pb-3">
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Share Lead</h3>
                            <p class="text-[10px] text-slate-500 mt-1.5 leading-relaxed">Share directly with Sales Team members, Builders, or Agents. Every delivery is recorded in the history.</p>
                        </div>
                        <form action="{{ route('crm.b2c.auto-distribute', $lead->id) }}" method="POST" class="shrink-0">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 px-3 py-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-200 transition-all hover:bg-slate-700">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amra-primary"></i>
                                Auto Assign by Package
                            </button>
                        </form>
                    </div>

                    <form action="{{ route('crm.b2c.share', $lead->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Sales Team</label>
                            <div class="max-h-40 overflow-y-auto border border-slate-800 rounded-xl bg-slate-950 p-3 space-y-2">
                                @foreach ($salesPeople as $person)
                                    <label class="flex items-center gap-3 p-2 rounded-lg bg-slate-900/60 border border-slate-850 cursor-pointer">
                                        <input type="checkbox" name="sales_person_ids[]" value="{{ $person->id }}" class="w-4 h-4 rounded border-slate-800 bg-slate-950 text-amra-primary">
                                        <span class="text-xs font-bold text-slate-200">{{ $person->name }}</span>
                                        <span class="ml-auto text-[9px] text-slate-500">{{ $person->location }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Partner Searchable Checkboxes -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Available Certified Partners</label>
                            
                            <!-- Search box for partners filter -->
                            <div class="relative mb-2">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 pointer-events-none">
                                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                                </span>
                                <input type="text" id="partner-search-input" placeholder="Search partners by name or city..." 
                                    class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-9 pr-3 py-2 outline-none text-xs text-slate-300">
                            </div>

                            <!-- List Box Container -->
                            <div class="max-h-60 overflow-y-auto border border-slate-800 rounded-xl bg-slate-950 p-3 space-y-2 pr-2">
                                @php 
                                    $sharedPartnerIds = [];
                                @endphp

                                @foreach ($partners as $partner)
                                    @php $alreadyShared = in_array($partner->id, $sharedPartnerIds); @endphp
                                    
                                    <label class="flex items-start gap-3 p-2 rounded-lg bg-slate-900/60 border border-slate-850 hover:border-slate-700 cursor-pointer select-none transition-colors partner-item" data-name="{{ strtolower($partner->company_name) }}" data-city="{{ strtolower($partner->city) }}">
                                        <input type="checkbox" name="partner_ids[]" value="{{ $partner->id }}" 
                                            class="w-4 h-4 rounded border-slate-800 bg-slate-950 text-amra-primary focus:ring-teal-500/20 mt-0.5">
                                        
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-xs text-slate-200 truncate">{{ $partner->company_name }}</span>
                                                <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider bg-slate-800 text-slate-400 border border-slate-750 shrink-0">
                                                    {{ $partner->city }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2 mt-0.5 text-[9px] font-semibold text-slate-500">
                                                <span>{{ $partner->type }}</span>
                                                <span>•</span>
                                                <span class="text-slate-400 capitalize">Package: {{ $partner->package }}</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Distribution Remark Notes -->
                        <div class="space-y-1.5">
                            <label for="share-remark" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Distribution Memo / Notes</label>
                            <textarea id="share-remark" name="remark" rows="3" placeholder="Context memo for partners e.g. 'Looking to close this flat in Powai within 30 days.'" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-200"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-amra-primary hover:bg-teal-400 text-slate-950 font-bold text-xs py-3.5 rounded-xl transition-all shadow-md shadow-teal-500/10">
                            Share Lead with Selected Recipients
                        </button>
                    </form>
                </div>
            @endif
            <!-- Schedule B2C Follow-up -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4 text-left">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Schedule Follow-up</h3>
                <form action="{{ route('crm.b2c.followup', $lead->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Due Date & Time</label>
                        <input type="datetime-local" name="due_at" required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-200">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Follow-up Notes</label>
                        <textarea name="notes" rows="2" placeholder="e.g. Call to discuss budget fit..." required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-200"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-2.5 rounded-xl transition-all shadow-sm">
                        Schedule Reminder
                    </button>
                </form>
            </div>

            <!-- Pending Follow-ups list -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Follow-up Tasks</h3>
                <div class="space-y-3">
                    @forelse ($lead->followUps as $fu)
                        <div class="p-3 rounded-xl border {{ $fu->completed_at ? 'border-slate-800 bg-slate-950/20 text-slate-500' : 'border-rose-950/40 bg-rose-950/10 text-slate-300' }}">
                            <div class="flex items-center justify-between text-[9px] font-bold uppercase tracking-wider mb-1.5">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    {{ $fu->due_at ? $fu->due_at->format('d M Y, h:i A') : 'No date' }}
                                </span>
                                @if($fu->completed_at)
                                    <span class="text-emerald-400">✓ Completed</span>
                                @else
                                    <span class="text-rose-400">● Due Pending</span>
                                @endif
                            </div>
                            <p class="text-xs leading-normal font-medium">"{{ $fu->notes }}"</p>
                            @if(!$fu->completed_at)
                                <form action="{{ route('crm.b2c.followup.complete', $fu->id) }}" method="POST" class="mt-2.5 flex gap-2">
                                    @csrf
                                    <input type="text" name="outcome" placeholder="Outcome (e.g. Call outcome)..." required
                                        class="flex-1 bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-lg px-2.5 py-1.5 text-[10px] text-slate-200">
                                    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-slate-700 px-2.5 py-1.5 rounded-lg text-[10px] font-extrabold uppercase shrink-0">
                                        Complete
                                    </button>
                                </form>
                            @else
                                <div class="mt-2 text-[10px] italic text-slate-500">
                                    Outcome: {{ $fu->outcome ?? 'Done' }} (at {{ $fu->completed_at->format('d M, h:i A') }})
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 italic pl-2">No follow-up reminders scheduled for this lead.</p>
                    @endforelse
                </div>
            </div>

            <!-- Chronological Timeline Logs -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Buyer Logs Timeline</h3>
                
                <div class="relative pl-4 border-l border-slate-800 space-y-6 max-h-90 overflow-y-auto pr-2 pt-2">
                    @forelse ($lead->statusLogs as $log)
                        <div class="relative space-y-1">
                            <span class="absolute -left-[20.5px] top-1 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-slate-900"></span>
                            
                            <div class="flex items-center justify-between text-[9px] font-bold uppercase tracking-wider text-slate-500">
                                <span>{{ $log->created_at->format('d M, h:i A') }}</span>
                                <span class="text-slate-400">{{ $log->changedByUser ? $log->changedByUser->name : 'System' }}</span>
                            </div>
                            
                            <div class="text-xs">
                                <span class="font-bold text-slate-400">
                                    {{ $log->from_status ? str_replace('_', ' ', $log->from_status) : 'Created' }}
                                </span> 
                                <span class="text-slate-650">→</span> 
                                <span class="font-bold text-emerald-400">
                                    {{ str_replace('_', ' ', $log->to_status) }}
                                </span>
                            </div>
                            @if ($log->notes)
                                <p class="text-[11px] text-slate-450 italic leading-snug mt-1 font-medium bg-slate-950/40 p-2 rounded-lg border border-slate-850">
                                    {{ $log->notes }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-600 italic pl-2 py-4">No audit logs found.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Search filter mechanism for available partners check-list
        const searchInput = document.getElementById('partner-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                const items = document.querySelectorAll('.partner-item');
                
                items.forEach(item => {
                    const name = item.getAttribute('data-name');
                    const city = item.getAttribute('data-city');
                    if (name.includes(query) || city.includes(query)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        }
    });
</script>
@endsection
