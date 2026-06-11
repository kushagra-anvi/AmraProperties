@extends('layouts.crm')

@section('title', 'Edit B2B Lead')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button -->
    <a href="{{ route('crm.b2b.show', $lead->id) }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Lead Details
    </a>

    <!-- Header Block -->
    <div>
        <h1 class="text-3xl font-serif font-extrabold text-white">Modify B2B Lead</h1>
        <p class="text-sm text-slate-400 mt-1">Update profile and operating details for {{ $lead->company_name }}</p>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md">
        <form action="{{ route('crm.b2b.update', $lead->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Form Error alert -->
            @if ($errors->any())
                <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs rounded-2xl flex flex-col gap-1.5">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-start gap-2">
                            <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Section 1: Client Details -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Client Details</h3>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Category Select -->
                    <div class="space-y-1.5">
                        <label for="category" class="text-xs font-bold text-slate-400">Lead Category <span class="text-rose-500">*</span></label>
                        <select id="category" name="category" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-200">
                            <option value="agent" {{ (old('category') ?? $lead->category) === 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="developer" {{ (old('category') ?? $lead->category) === 'developer' ? 'selected' : '' }}>Developer</option>
                            <option value="single_owner" {{ (old('category') ?? $lead->category) === 'single_owner' ? 'selected' : '' }}>Single Property Owner</option>
                        </select>
                    </div>

                    <!-- Company Name -->
                    <div class="space-y-1.5">
                        <label for="company_name" class="text-xs font-bold text-slate-400">Company Name / Brand <span class="text-rose-500">*</span></label>
                        <input id="company_name" name="company_name" type="text" required value="{{ old('company_name') ?? $lead->company_name }}" placeholder="e.g. ABC Realty Group"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Contact Person -->
                    <div class="space-y-1.5">
                        <label for="contact_person_name" class="text-xs font-bold text-slate-400">Contact Person Name <span class="text-rose-500">*</span></label>
                        <input id="contact_person_name" name="contact_person_name" type="text" required value="{{ old('contact_person_name') ?? $lead->contact_person_name }}" placeholder="John Doe"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold text-slate-400">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') ?? $lead->email }}" placeholder="partner@example.com"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Contact Phone -->
                    <div class="space-y-1.5">
                        <label for="contact_number" class="text-xs font-bold text-slate-400">Contact Phone Number <span class="text-rose-500">*</span></label>
                        <input id="contact_number" name="contact_number" type="text" required value="{{ old('contact_number') ?? $lead->contact_number }}" placeholder="e.g. +91 9876543210"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- WhatsApp Phone -->
                    <div class="space-y-1.5">
                        <label for="whatsapp_number" class="text-xs font-bold text-slate-400">WhatsApp Number</label>
                        <input id="whatsapp_number" name="whatsapp_number" type="text" value="{{ old('whatsapp_number') ?? $lead->whatsapp_number }}" placeholder="Same as phone if empty"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>
                </div>
            </div>

            <!-- Section 2: Coverage & Operations -->
            <div class="space-y-4 pt-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Location & Coverage</h3>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- City Dropdown -->
                    <div class="space-y-1.5">
                        <label for="city" class="text-xs font-bold text-slate-400">Primary Operating City <span class="text-rose-500">*</span></label>
                        <select id="city" name="city" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-200">
                            <option value="Mumbai" {{ (old('city') ?? $lead->city) === 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                            <option value="Lucknow" {{ (old('city') ?? $lead->city) === 'Lucknow' ? 'selected' : '' }}>Lucknow</option>
                            <option value="Pune" {{ (old('city') ?? $lead->city) === 'Pune' ? 'selected' : '' }}>Pune</option>
                            <option value="Delhi" {{ (old('city') ?? $lead->city) === 'Delhi' ? 'selected' : '' }}>Delhi</option>
                            <option value="Bangalore" {{ (old('city') ?? $lead->city) === 'Bangalore' ? 'selected' : '' }}>Bangalore</option>
                        </select>
                    </div>

                    <!-- Service Areas (Multi-select parsed as string) -->
                    <div class="space-y-1.5">
                        <label for="service_areas" class="text-xs font-bold text-slate-400">Service Area Localities (Comma separated)</label>
                        <input id="service_areas_input" name="service_areas_raw" type="text" value="{{ old('service_areas_raw') ?? (is_array($lead->service_areas) ? implode(', ', $lead->service_areas) : '') }}" placeholder="Powai, Hiranandani, Hazratganj"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                        <p class="text-[10px] text-slate-500">Provide comma-separated localities to auto-create listing coverage tags.</p>
                        <!-- Hidden array field -->
                        <div id="service-areas-container"></div>
                    </div>

                    <!-- Project Ticket Size Min -->
                    <div class="space-y-1.5">
                        <label for="project_ticket_size_min" class="text-xs font-bold text-slate-400">Minimum Ticket Size (INR)</label>
                        <input id="project_ticket_size_min" name="project_ticket_size_min" type="number" min="0" value="{{ old('project_ticket_size_min') ?? $lead->project_ticket_size_min }}" placeholder="e.g. 5000000"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Project Ticket Size Max -->
                    <div class="space-y-1.5">
                        <label for="project_ticket_size_max" class="text-xs font-bold text-slate-400">Maximum Ticket Size (INR)</label>
                        <input id="project_ticket_size_max" name="project_ticket_size_max" type="number" min="0" value="{{ old('project_ticket_size_max') ?? $lead->project_ticket_size_max }}" placeholder="e.g. 20000000"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>
                </div>

                <!-- Office Address -->
                <div class="space-y-1.5 mt-4">
                    <label for="office_address" class="text-xs font-bold text-slate-400">Office / Physical Address</label>
                    <textarea id="office_address" name="office_address" rows="3" placeholder="Suite, business center, street address..."
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">{{ old('office_address') ?? $lead->office_address }}</textarea>
                </div>
            </div>

            <!-- Section 3: CRM Assignment -->
            @if (auth()->user()->role !== 'sales_team')
                <div class="space-y-4 pt-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Sales Assignment</h3>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Assigned Sales Person -->
                        <div class="space-y-1.5">
                            <label for="assigned_sales_person_id" class="text-xs font-bold text-slate-400">Assign to Sales Representative</label>
                            <select id="assigned_sales_person_id" name="assigned_sales_person_id"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-200">
                                <option value="">Leave Unassigned</option>
                                @foreach ($salesPeople as $person)
                                    <option value="{{ $person->id }}" {{ (old('assigned_sales_person_id') ?? $lead->assigned_sales_person_id) == $person->id ? 'selected' : '' }}>{{ $person->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Remark Notes -->
            <div class="space-y-1.5 pt-4">
                <label for="remark" class="text-xs font-bold text-slate-400">Discovery Remarks / Status Log Note</label>
                <textarea id="remark" name="remark" rows="3" placeholder="Context details, primary listing types..."
                    class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">{{ old('remark') ?? $lead->remark }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('crm.b2b.show', $lead->id) }}" class="bg-slate-800 hover:bg-slate-750 text-slate-300 font-bold text-xs px-6 py-3.5 rounded-xl transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-amra-primary hover:bg-teal-400 text-slate-950 font-bold text-xs px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-teal-500/10">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rawInput = document.getElementById('service_areas_input');
        const container = document.getElementById('service-areas-container');
        
        if (rawInput && container) {
            const form = rawInput.closest('form');
            form.addEventListener('submit', () => {
                const values = rawInput.value.split(',')
                    .map(v => v.trim())
                    .filter(v => v.length > 0);

                container.innerHTML = '';
                values.forEach((value, index) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `service_areas[${index}]`;
                    input.value = value;
                    container.appendChild(input);
                });
            });
        }
    });
</script>
@endsection
