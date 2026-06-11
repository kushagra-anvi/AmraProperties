@extends('layouts.crm')

@section('title', 'Add Partner')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Link -->
    <a href="{{ route('crm.partners.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Partner Directory
    </a>

    <!-- Header Block -->
    <div>
        <h1 class="text-3xl font-serif font-extrabold text-white">Add Affiliate Partner</h1>
        <p class="text-sm text-slate-400 mt-1">Register a certified Agent or Developer account and assign subscription packages</p>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md">
        <form action="{{ route('crm.partners.store') }}" method="POST" class="space-y-6">
            @csrf

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

            <!-- Section 1: Brand Info -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Partner Details</h3>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Partner Type -->
                    <div class="space-y-1.5">
                        <label for="type" class="text-xs font-bold text-slate-400">Partner Type <span class="text-rose-500">*</span></label>
                        <select id="type" name="type" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-205">
                            <option value="agent" {{ old('type') === 'agent' ? 'selected' : '' }}>Agent / Broker</option>
                            <option value="developer" {{ old('type') === 'developer' ? 'selected' : '' }}>Real Estate Developer</option>
                            <option value="affiliate" {{ old('type') === 'affiliate' ? 'selected' : '' }}>Affiliate Partner</option>
                        </select>
                    </div>

                    <!-- Company Name -->
                    <div class="space-y-1.5">
                        <label for="company_name" class="text-xs font-bold text-slate-400">Brand / Company Name <span class="text-rose-500">*</span></label>
                        <input id="company_name" name="company_name" type="text" required value="{{ old('company_name') }}" placeholder="e.g. Acme Developers"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Contact Person -->
                    <div class="space-y-1.5">
                        <label for="contact_person" class="text-xs font-bold text-slate-400">Contact Representative <span class="text-rose-500">*</span></label>
                        <input id="contact_person" name="contact_person" type="text" required value="{{ old('contact_person') }}" placeholder="Representative name"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold text-slate-400">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="office@acme.com"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Contact Phone -->
                    <div class="space-y-1.5">
                        <label for="phone" class="text-xs font-bold text-slate-400">Primary Phone Number <span class="text-rose-500">*</span></label>
                        <input id="phone" name="phone" type="text" required value="{{ old('phone') }}" placeholder="e.g. +91 9999999999"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Operating City -->
                    <div class="space-y-1.5">
                        <label for="city" class="text-xs font-bold text-slate-400">Primary Operating City <span class="text-rose-500">*</span></label>
                        <select id="city" name="city" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-205">
                            <option value="Mumbai" {{ old('city') === 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                            <option value="Lucknow" {{ old('city') === 'Lucknow' ? 'selected' : '' }}>Lucknow</option>
                            <option value="Pune" {{ old('city') === 'Pune' ? 'selected' : '' }}>Pune</option>
                            <option value="Delhi" {{ old('city') === 'Delhi' ? 'selected' : '' }}>Delhi</option>
                            <option value="Bangalore" {{ old('city') === 'Bangalore' ? 'selected' : '' }}>Bangalore</option>
                        </select>
                    </div>
                </div>

                <!-- Locality Service Areas comma inputs -->
                <div class="space-y-1.5 mt-4">
                    <label for="service_areas_input" class="text-xs font-bold text-slate-400">Coverage Localities (Comma separated)</label>
                    <input id="service_areas_input" name="service_areas_raw" type="text" placeholder="e.g. Powai, Hiranandani, Hazratganj"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    <!-- Hidden array fields container -->
                    <div id="service-areas-container"></div>
                </div>

                <!-- Office Address -->
                <div class="space-y-1.5 mt-4">
                    <label for="office_address" class="text-xs font-bold text-slate-400">Physical Office Address</label>
                    <textarea id="office_address" name="office_address" rows="3" placeholder="Suite, business plaza, street address..."
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">{{ old('office_address') }}</textarea>
                </div>
            </div>

            <!-- Section 2: Subscription Package Details -->
            <div class="space-y-4 pt-4 border-t border-slate-800/40">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Subscription & Package</h3>
                
                <div class="grid gap-6 md:grid-cols-4">
                    <!-- Package select -->
                    <div class="space-y-1.5">
                        <label for="package" class="text-xs font-bold text-slate-400">Affiliate Package plan <span class="text-rose-500">*</span></label>
                        <select id="package" name="package" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-205">
                            <option value="free" {{ old('package') === 'free' ? 'selected' : '' }}>Free Listing Package</option>
                            <option value="starter" {{ old('package') === 'starter' ? 'selected' : '' }}>Starter Tier Plan</option>
                            <option value="growth" {{ old('package') === 'growth' ? 'selected' : '' }}>Growth Tier Plan</option>
                            <option value="premium" {{ old('package') === 'premium' ? 'selected' : '' }}>Premium Tier Plan</option>
                            <option value="customise" {{ old('package') === 'customise' ? 'selected' : '' }}>Customise Tier Plan</option>
                        </select>
                    </div>

                    <!-- Package Purchase Date -->
                    <div class="space-y-1.5">
                        <label for="paid_amount" class="text-xs font-bold text-slate-400">Paid Amount (INR)</label>
                        <input id="paid_amount" name="paid_amount" type="number" min="0" step="0.01" value="{{ old('paid_amount') }}"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <!-- Package Purchase Date -->
                    <div class="space-y-1.5">
                        <label for="package_purchase_date" class="text-xs font-bold text-slate-400">Purchase Date</label>
                        <input id="package_purchase_date" name="package_purchase_date" type="date" value="{{ old('package_purchase_date') }}"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <!-- Renewal Date -->
                    <div class="space-y-1.5">
                        <label for="renewal_date" class="text-xs font-bold text-slate-400">Renewal Expiry Date</label>
                        <input id="renewal_date" name="renewal_date" type="date" value="{{ old('renewal_date') }}"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>
                </div>
            </div>

            <!-- Section 3: Internal Assigns -->
            <div class="space-y-4 pt-4 border-t border-slate-800/40">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Sales Assignment & Channel</h3>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Assigned Sales Person -->
                    <div class="space-y-1.5">
                        <label for="assigned_sales_person_id" class="text-xs font-bold text-slate-400">Assigned Internal Rep</label>
                        <select id="assigned_sales_person_id" name="assigned_sales_person_id"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-205">
                            <option value="">Leave Unassigned</option>
                            @foreach ($salesPeople as $person)
                                <option value="{{ $person->id }}" {{ old('assigned_sales_person_id') == $person->id ? 'selected' : '' }}>{{ $person->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lead Source -->
                    <div class="space-y-1.5">
                        <label for="lead_source" class="text-xs font-bold text-slate-400">Lead Origin / Channel</label>
                        <input id="lead_source" name="lead_source" type="text" value="{{ old('lead_source') }}" placeholder="e.g. Broker Reference, Powai Expo"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>
                </div>

                <!-- Remark Notes -->
                <div class="space-y-1.5 mt-4">
                    <label for="remark" class="text-xs font-bold text-slate-400">Partner Remark / Memo Notes</label>
                    <textarea id="remark" name="remark" rows="3" placeholder="Context details, primary listing types..."
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">{{ old('remark') }}</textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('crm.partners.index') }}" class="bg-slate-800 hover:bg-slate-755 text-slate-350 font-bold text-xs px-6 py-3.5 rounded-xl transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-amra-primary hover:bg-teal-400 text-slate-955 font-bold text-xs px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-teal-500/10">
                    Register Partner
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
