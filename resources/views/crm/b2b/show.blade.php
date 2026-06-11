@extends('layouts.crm')

@section('title', $lead->company_name)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb back button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('crm.b2b.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to B2B Directory
        </a>
        <a href="{{ route('crm.b2b.edit', $lead->id) }}" class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 active:scale-[0.99] transition-all">
            <i data-lucide="edit-3" class="w-4 h-4 text-slate-400"></i> Edit Profile
        </a>
    </div>

    <!-- Client Banner Block -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-teal-500/5 rounded-full filter blur-3xl pointer-events-none"></div>

        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center shrink-0">
                    <i data-lucide="building-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-2xl md:text-3xl font-serif font-extrabold text-white">{{ $lead->company_name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-800 text-slate-350 border border-slate-700">
                            {{ str_replace('_', ' ', $lead->category) }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-400 mt-1 flex items-center gap-1.5">
                        <i data-lucide="user" class="w-4 h-4 text-slate-500"></i> Primary Contact: <strong class="text-slate-300 font-semibold">{{ $lead->contact_person_name }}</strong>
                    </p>
                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                        <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $lead->city }}</span>
                        <span class="flex items-center gap-1"><i data-lucide="globe" class="w-3.5 h-3.5"></i> Platform: <strong class="text-slate-400 uppercase font-semibold">{{ $lead->source_platform }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Big Status Pill -->
            <div class="flex flex-col items-start md:items-end gap-2">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Active Status</span>
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $lead->status === 'new' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : '' }}
                    {{ $lead->status === 'contacted' ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : '' }}
                    {{ $lead->status === 'qualified' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : '' }}
                    {{ $lead->status === 'not_interested' ? 'bg-rose-500/20 text-rose-400 border border-rose-500/30' : '' }}
                    {{ $lead->status === 'follow_up' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : '' }}
                    {{ in_array($lead->status, ['free_listing', 'paid_listing', 'converted']) ? 'bg-teal-500/20 text-teal-400 border border-teal-500/30' : '' }}">
                    ● {{ str_replace('_', ' ', $lead->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Details split block -->
    <div class="grid gap-6 lg:grid-cols-12 items-start">
        
        <!-- Left Column: Core Lead Stats & Info (8/12 width) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Contact Card Details -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Contact Profile</h3>
                
                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Contact Number</span>
                        <a href="tel:{{ $lead->contact_number }}" class="text-sm font-semibold text-white block hover:text-amra-primary">{{ $lead->contact_number }}</a>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">WhatsApp Number</span>
                        @if ($lead->whatsapp_number)
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $lead->whatsapp_number) }}" target="_blank" class="text-sm font-semibold text-emerald-400 hover:underline flex items-center gap-1.5">
                                <i data-lucide="message-square" class="w-4 h-4"></i> {{ $lead->whatsapp_number }}
                            </a>
                        @else
                            <span class="text-xs text-slate-650 italic">None Provided</span>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Email Address</span>
                        @if ($lead->email)
                            <a href="mailto:{{ $lead->email }}" class="text-sm font-semibold text-white block hover:text-amra-primary">{{ $lead->email }}</a>
                        @else
                            <span class="text-xs text-slate-650 italic">None Provided</span>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Operational Localities</span>
                        @if(!empty($lead->service_areas))
                            <div class="flex flex-wrap gap-1.5 mt-1">
                                @foreach ($lead->service_areas as $area)
                                    <span class="px-2 py-0.5 rounded-md bg-slate-800 text-[10px] font-semibold text-slate-350 border border-slate-750">{{ $area }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-slate-650 italic block">All of {{ $lead->city }}</span>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Ticket Size Range</span>
                        <div class="text-sm font-semibold text-white mt-0.5">
                            @if ($lead->project_ticket_size_min || $lead->project_ticket_size_max)
                                ₹{{ number_format($lead->project_ticket_size_min ?? 0) }} - ₹{{ number_format($lead->project_ticket_size_max ?? 0) }}
                            @else
                                <span class="text-slate-600 italic">Not Specified</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Physical Office Address</span>
                        <p class="text-xs text-slate-300 leading-relaxed font-medium mt-0.5">{{ $lead->office_address ?? 'Not Specified' }}</p>
                    </div>
                </div>

                @if ($lead->remark)
                    <div class="bg-slate-950/60 p-4 border border-slate-850 rounded-xl mt-4">
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Discovery Remark Notes</span>
                        <p class="text-xs text-slate-350 mt-1 leading-relaxed">{{ $lead->remark }}</p>
                    </div>
                @endif
            </div>

            <!-- Follow-up Activity Logger Panel -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5 mb-4">Log Follow-up Call / Interaction</h3>
                
                <form action="{{ route('crm.b2b.followup', $lead->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Interaction Outcome</label>
                            <input type="text" name="outcome" placeholder="e.g. Call Scheduled, Met Client" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-200">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Next Follow-up Due</label>
                            <input type="datetime-local" name="due_at" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-200">
                        </div>

                        <div class="space-y-1.5 flex flex-col justify-end">
                            <label class="flex items-center gap-2 cursor-pointer select-none py-2">
                                <input type="checkbox" name="completed" value="1" checked class="w-4 h-4 rounded border-slate-800 bg-slate-950 text-amra-primary focus:ring-teal-500/20">
                                <span class="text-xs font-semibold text-slate-350">Mark completed instantly</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Follow-up Notes <span class="text-rose-500">*</span></label>
                        <textarea name="notes" rows="3" required placeholder="Detail the client conversation, property requirements discussed..." class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2.5 text-xs text-slate-200"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-amra-primary hover:bg-teal-400 text-slate-950 font-bold text-xs px-6 py-2.5 rounded-xl transition-all shadow-md shadow-teal-500/10">
                            Log Follow-up Activity
                        </button>
                    </div>
                </form>
            </div>

            <!-- Follow-up Activity History Timeline -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Interaction History</h3>
                
                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                    @forelse ($lead->followUps as $activity)
                        <div class="bg-slate-950/40 p-4 border border-slate-850 rounded-2xl space-y-2 relative">
                            <div class="flex items-center justify-between text-[10px] font-bold">
                                <span class="text-teal-400 flex items-center gap-1.5">
                                    <i data-lucide="phone-call" class="w-3.5 h-3.5"></i>
                                    {{ $activity->outcome ?? 'Call / Activity Logged' }}
                                </span>
                                <span class="text-slate-550">{{ $activity->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed font-medium">{{ $activity->notes }}</p>
                            @if ($activity->due_at)
                                <div class="pt-1.5 flex items-center gap-3 border-t border-slate-850 text-[10px] text-slate-500 font-semibold">
                                    <span class="flex items-center gap-1"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> Next Due: {{ $activity->due_at->format('d M Y') }}</span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5 {{ $activity->completed_at ? 'text-emerald-450' : 'text-slate-650' }}"></i> 
                                        {{ $activity->completed_at ? 'Completed on ' . $activity->completed_at->format('d M') : 'Pending Followup' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-550 text-center py-6">No interactions logged for this lead yet.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Column: Status Log & Assignment (4/12 width) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Quick Status Workflow Trigger -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Update Pipeline Status</h3>
                
                <form action="{{ route('crm.b2b.status', $lead->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-1.5">
                        <label for="status-select" class="text-[10px] font-bold text-slate-500 uppercase">Select Target Status</label>
                        <select id="status-select" name="status" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-300">
                            <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="qualified" {{ $lead->status === 'qualified' ? 'selected' : '' }}>Qualified</option>
                            <option value="not_interested" {{ $lead->status === 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                            <option value="follow_up" {{ $lead->status === 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                            <option value="free_listing" {{ $lead->status === 'free_listing' ? 'selected' : '' }}>Free Listing</option>
                            <option value="paid_listing" {{ $lead->status === 'paid_listing' ? 'selected' : '' }}>Paid Listing</option>
                            <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Converted</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label for="notes" class="text-[10px] font-bold text-slate-500 uppercase">Transition Notes</label>
                        <textarea id="notes" name="notes" rows="2" placeholder="e.g. Client agreed to pay for starter package." class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-200"></textarea>
                    </div>

                    @if (in_array($lead->category, ['agent', 'developer'], true))
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label for="conversion_package" class="text-[10px] font-bold text-slate-500 uppercase">Partner Package</label>
                                <select id="conversion_package" name="conversion_package" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-300">
                                    <option value="free">Free</option>
                                    <option value="starter" selected>Starter</option>
                                    <option value="growth">Growth</option>
                                    <option value="premium">Premium</option>
                                    <option value="customise">Customise</option>
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label for="paid_amount" class="text-[10px] font-bold text-slate-500 uppercase">Paid Amount</label>
                                <input id="paid_amount" name="paid_amount" type="number" min="0" step="0.01" placeholder="15000"
                                    class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-200">
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-750 text-teal-400 border border-slate-700 font-bold text-xs py-3 rounded-xl transition-all">
                        Transition Status
                    </button>
                </form>
            </div>

            @if ($lead->convertedPartner)
                <div class="bg-teal-500/10 border border-teal-500/20 rounded-3xl p-5 shadow-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-teal-500">Partner Database Linked</p>
                    <a href="{{ route('crm.partners.show', $lead->convertedPartner->id) }}" class="text-sm font-bold text-slate-900 hover:text-teal-600 mt-1 block">
                        {{ $lead->convertedPartner->company_name }}
                    </a>
                    <p class="text-[10px] text-slate-500 mt-1 capitalize">Package: {{ $lead->convertedPartner->package }} · Paid: ₹{{ number_format($lead->convertedPartner->paid_amount ?? 0) }}</p>
                </div>
            @endif

            <!-- Sales Person Assignment -->
            @if (auth()->user()->role !== 'sales_team')
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Lead Assignment</h3>
                    
                    <form action="{{ route('crm.b2b.assign', $lead->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="space-y-1.5">
                            <label for="assignee-select" class="text-[10px] font-bold text-slate-500 uppercase">Assignee Representative</label>
                            <select id="assignee-select" name="assigned_sales_person_id" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-300">
                                <option value="">Leave Unassigned</option>
                                @foreach ($salesPeople as $person)
                                    <option value="{{ $person->id }}" {{ $lead->assigned_sales_person_id == $person->id ? 'selected' : '' }}>{{ $person->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="assignee-note" class="text-[10px] font-bold text-slate-500 uppercase">Assignment Note</label>
                            <textarea id="assignee-note" name="notes" rows="2" placeholder="Instructions for representative..." class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-2 text-xs text-slate-200"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-amra-primary hover:bg-teal-400 text-slate-950 font-bold text-xs py-3 rounded-xl transition-all shadow-md shadow-teal-500/10">
                            Update Representative
                        </button>
                    </form>
                </div>
            @endif

            <!-- Chronological Status Logs Timeline -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2.5">Timeline & Status Logs</h3>
                
                <div class="relative pl-4 border-l border-slate-800 space-y-6 max-h-96 overflow-y-auto pr-2 pt-2">
                    @forelse ($lead->statusLogs as $log)
                        <div class="relative space-y-1">
                            <!-- Bullet marker -->
                            <span class="absolute -left-[20.5px] top-1 w-2.5 h-2.5 rounded-full bg-teal-500 ring-4 ring-slate-900"></span>
                            
                            <div class="flex items-center justify-between text-[9px] font-bold uppercase tracking-wider text-slate-500">
                                <span>{{ $log->created_at->format('d M, h:i A') }}</span>
                                <span class="text-slate-400">{{ $log->changedByUser ? $log->changedByUser->name : 'System' }}</span>
                            </div>
                            
                            <div class="text-xs">
                                <span class="font-bold text-slate-400">
                                    {{ $log->from_status ? str_replace('_', ' ', $log->from_status) : 'Created' }}
                                </span> 
                                <span class="text-slate-650">→</span> 
                                <span class="font-bold text-teal-400">
                                    {{ str_replace('_', ' ', $log->to_status) }}
                                </span>
                            </div>
                            @if ($log->notes)
                                <p class="text-[11px] text-slate-400 italic leading-snug mt-1 font-medium bg-slate-950/40 p-2 rounded-lg border border-slate-850">
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
