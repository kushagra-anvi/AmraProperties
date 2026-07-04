@extends('layouts.crm')

@section('title', 'Buyer Profile - ' . $lead->name)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs & actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('crm.partner.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-amra-dark transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Partner Dashboard
        </a>
    </div>

    <!-- Client Header Banner -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full filter blur-3xl pointer-events-none"></div>

        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center shrink-0">
                    <i data-lucide="user" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-serif font-extrabold text-slate-800">{{ $lead->name }}</h1>
                    <p class="text-sm text-slate-500 mt-1 flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i> Lead Received: <strong class="text-slate-600 font-semibold">{{ $share->shared_at->format('d M Y, h:i A') }}</strong>
                    </p>
                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                        <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $lead->city }}</span>
                    </div>
                </div>
            </div>

            <!-- Lead Shared Status Pill -->
            <div class="flex flex-col items-start md:items-end gap-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lead Status</span>
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-teal-50 border border-teal-200 text-teal-700">
                    ● {{ ['new' => 'New', 'shared' => 'Shared', 'contacted' => 'Contacted', 'follow_up' => 'Follow-up', 'site_visit_scheduled' => 'Site Visit Scheduled', 'interested' => 'Interested', 'closed_won' => 'Closed/Won', 'closed_lost' => 'Closed/Lost', 'filtered' => 'New'][$lead->status] ?? ucfirst(str_replace('_', ' ', $lead->status)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Details Split Grid -->
    <div class="grid gap-6 lg:grid-cols-12 items-start">
        <!-- Left Column: Preferences & Info (8/12 width) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Buyer Requirement Profile Card -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2.5">Buyer Preferences</h3>
                
                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Property Type</span>
                        <div class="text-sm font-semibold text-slate-800 capitalize mt-0.5">{{ $lead->property_type }}</div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Room Configuration</span>
                        <div class="text-sm font-bold text-teal-600 mt-0.5">{{ $lead->configuration }}</div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Budget Bracket</span>
                        <div class="text-sm font-bold text-slate-800 mt-0.5">
                            @if ($lead->budget_min || $lead->budget_max)
                                ₹{{ number_format($lead->budget_min ?? 0) }} - ₹{{ number_format($lead->budget_max ?? 0) }}
                            @else
                                <span class="text-slate-400 font-normal italic">Open Budget</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Area Pincode</span>
                        <div class="text-sm font-semibold text-slate-800 mt-0.5">{{ $lead->pincode ?? 'Not Specified' }}</div>
                    </div>

                    <div class="space-y-1 sm:col-span-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Preferred Locations</span>
                        @if (!empty($lead->preferred_locations))
                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                @foreach ($lead->preferred_locations as $loc)
                                    <span class="px-2.5 py-1 rounded bg-slate-50 text-[10px] font-semibold text-slate-600 border border-slate-200 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i> {{ $loc }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-slate-400 italic block mt-0.5">All of {{ $lead->city }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes & Remarks Panel -->
            @if ($lead->remark)
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-md space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2.5">Received Remarks</h3>
                    
                    <div class="bg-teal-50/20 border border-teal-100 p-4 rounded-xl">
                        <span class="text-[9px] font-bold text-teal-600 uppercase tracking-widest">Additional Discovery Remarks</span>
                        <p class="text-xs text-slate-700 mt-1 leading-relaxed">{{ $lead->remark }}</p>
                    </div>
                </div>
            @endif

            <!-- My Remark History -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2.5">My Remark History</h3>
                <div class="space-y-4">
                    @forelse ($share->remarks as $hist)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i>
                                    {{ $hist->user?->name ?? 'You' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-400"></i>
                                    {{ $hist->created_at->format('d M Y, h:i A') }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                {{ $hist->remark }}
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic pl-1">No remarks logged yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Buyer Contact Details (4/12 width) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2.5">Contact Profile</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 shrink-0">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Contact Number</span>
                            <a href="tel:{{ $lead->phone }}" class="text-sm font-semibold text-slate-800 block hover:text-teal-500 transition-colors mt-0.5">{{ $lead->phone }}</a>
                        </div>
                    </div>

                    @if ($lead->email)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 shrink-0">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Email Address</span>
                                <a href="mailto:{{ $lead->email }}" class="text-sm font-semibold text-slate-800 block hover:text-teal-500 transition-colors mt-0.5">{{ $lead->email }}</a>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $lead->phone) }}" target="_blank" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs py-3 rounded-xl flex items-center justify-center gap-2 shadow-md transition-all active:scale-[0.99]">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg>
                            WhatsApp Buyer
                        </a>
                        <a href="tel:{{ $lead->phone }}" class="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs py-3 rounded-xl flex items-center justify-center gap-2 shadow-md transition-all active:scale-[0.99]">
                            <i data-lucide="phone" class="w-4 h-4"></i> Call Buyer
                        </a>
                    </div>
                </div>
            </div>

            <!-- Add a Remark -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-md space-y-4 text-left">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2.5">Add a Remark</h3>
                <form action="{{ route('crm.partner.leads.remark', $lead->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <textarea name="remark" rows="3" placeholder="Enter private follow-up remarks..." required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:border-teal-500 transition-all text-xs font-medium text-slate-800">{{ old('remark') }}</textarea>
                    </div>
                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs py-2.5 rounded-xl transition-all shadow-sm">
                        Submit Remark
                    </button>
                </form>
            </div>

            <!-- Follow-up Tasks -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2.5">Follow-up Tasks</h3>
                <div class="space-y-3">
                    @forelse ($lead->followUps as $fu)
                        <div class="p-3 rounded-xl border {{ $fu->completed_at ? 'border-slate-100 bg-slate-50/50 text-slate-500' : 'border-rose-100 bg-rose-50/50 text-slate-700' }}">
                            <div class="flex items-center justify-between text-[9px] font-bold uppercase tracking-wider mb-1.5">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3 text-slate-400"></i>
                                    {{ $fu->due_at ? $fu->due_at->format('d M Y, h:i A') : 'No date' }}
                                </span>
                                @if($fu->completed_at)
                                    <span class="text-teal-600">✓ Completed</span>
                                @else
                                    <span class="text-rose-600">● Due Pending</span>
                                @endif
                            </div>
                            <p class="text-xs leading-normal font-medium">"{{ $fu->notes }}"</p>
                            @if(!$fu->completed_at)
                                <form action="{{ route('crm.partner.leads.followup.complete', $fu->id) }}" method="POST" class="mt-2.5 flex gap-2">
                                    @csrf
                                    <input type="text" name="outcome" placeholder="Outcome (e.g. Spoke to buyer)..." required
                                        class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-[10px] text-slate-800 outline-none focus:border-teal-500 transition-colors">
                                    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-2.5 py-1.5 rounded-lg text-[10px] font-bold uppercase shrink-0">
                                        Complete
                                    </button>
                                </form>
                            @else
                                <div class="mt-2 text-[10px] italic text-slate-400">
                                    Outcome: {{ $fu->outcome ?? 'Done' }} (at {{ $fu->completed_at->format('d M, h:i A') }})
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-450 italic pl-1">No follow-up reminders scheduled.</p>
                    @endforelse
                </div>
            </div>

            <!-- Schedule Followup -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-md space-y-4 text-left">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2.5">Schedule Follow-up</h3>
                <form action="{{ route('crm.partner.leads.followup', $lead->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Due Date & Time</label>
                        <input type="datetime-local" name="due_at" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:border-teal-500 transition-all text-xs font-medium text-slate-800">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Follow-up Notes</label>
                        <textarea name="notes" rows="2" placeholder="e.g. Call to discuss pricing"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:border-teal-500 transition-all text-xs font-medium text-slate-800"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs py-2.5 rounded-xl transition-all shadow-sm">
                        Schedule Reminder
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
