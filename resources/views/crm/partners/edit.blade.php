@extends('layouts.crm')

@section('title', 'Edit Partner')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Link -->
    <a href="{{ route('crm.partners.show', $partner->id) }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Partner Profile
    </a>

    <!-- Header Block -->
    <div>
        <h1 class="text-3xl font-serif font-extrabold text-white">Modify Partner Profile</h1>
        <p class="text-sm text-slate-400 mt-1">Update brand information, locations, and package details for {{ $partner->company_name }}</p>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md">
        <form action="{{ route('crm.partners.update', $partner->id) }}" method="POST" class="space-y-6">
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

            <!-- Section 1: Partner Details -->
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Partner Details</h3>
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ $partner->is_active ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-800 bg-slate-950 text-amra-primary focus:ring-teal-500/20">
                        <span class="text-xs font-bold text-slate-350">Account Active Status</span>
                    </label>
                </div>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Partner Type -->
                    <div class="space-y-1.5">
                        <label for="type" class="text-xs font-bold text-slate-400">Partner Type <span class="text-rose-500">*</span></label>
                        <select id="type" name="type" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-205">
                            <option value="agent" {{ (old('type') ?? $partner->type) === 'agent' ? 'selected' : '' }}>Agent / Broker</option>
                            <option value="developer" {{ (old('type') ?? $partner->type) === 'developer' ? 'selected' : '' }}>Real Estate Developer</option>
                            <option value="affiliate" {{ (old('type') ?? $partner->type) === 'affiliate' ? 'selected' : '' }}>Affiliate Partner</option>
                        </select>
                    </div>

                    <!-- Company Name -->
                    <div class="space-y-1.5">
                        <label for="company_name" class="text-xs font-bold text-slate-400">Brand / Company Name <span class="text-rose-500">*</span></label>
                        <input id="company_name" name="company_name" type="text" required value="{{ old('company_name') ?? $partner->company_name }}" placeholder="e.g. Acme Developers"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Contact Person -->
                    <div class="space-y-1.5">
                        <label for="contact_person" class="text-xs font-bold text-slate-400">Contact Representative <span class="text-rose-500">*</span></label>
                        <input id="contact_person" name="contact_person" type="text" required value="{{ old('contact_person') ?? $partner->contact_person }}" placeholder="Representative name"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold text-slate-400">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') ?? $partner->email }}" placeholder="office@acme.com"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Contact Phone -->
                    <div class="space-y-1.5">
                        <label for="phone" class="text-xs font-bold text-slate-400">Primary Phone Number <span class="text-rose-500">*</span></label>
                        <input id="phone" name="phone" type="text" required value="{{ old('phone') ?? $partner->phone }}" placeholder="e.g. +91 9999999999"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Operating City -->
                    <div class="space-y-1.5">
                        <label for="city" class="text-xs font-bold text-slate-400">Primary Operating City <span class="text-rose-500">*</span></label>
                        <select id="city" name="city" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-205">
                            <option value="Mumbai" {{ (old('city') ?? $partner->city) === 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                            <option value="Lucknow" {{ (old('city') ?? $partner->city) === 'Lucknow' ? 'selected' : '' }}>Lucknow</option>
                            <option value="Pune" {{ (old('city') ?? $partner->city) === 'Pune' ? 'selected' : '' }}>Pune</option>
                            <option value="Delhi" {{ (old('city') ?? $partner->city) === 'Delhi' ? 'selected' : '' }}>Delhi</option>
                            <option value="Bangalore" {{ (old('city') ?? $partner->city) === 'Bangalore' ? 'selected' : '' }}>Bangalore</option>
                        </select>
                    </div>
                </div>

                <!-- Locality Service Areas comma inputs -->
                <div class="space-y-1.5 mt-4">
                    <label for="service_areas_input" class="text-xs font-bold text-slate-400">Coverage Localities (Comma separated)</label>
                    <input id="service_areas_input" name="service_areas_raw" type="text" value="{{ old('service_areas_raw') ?? (is_array($partner->service_areas) ? implode(', ', $partner->service_areas) : '') }}" placeholder="e.g. Powai, Hiranandani, Hazratganj"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    <!-- Hidden array fields container -->
                    <div id="service-areas-container"></div>
                </div>

                <!-- Office Address -->
                <div class="space-y-1.5 mt-4">
                    <label for="office_address" class="text-xs font-bold text-slate-400">Physical Office Address</label>
                    <textarea id="office_address" name="office_address" rows="3" placeholder="Suite, business plaza, street address..."
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">{{ old('office_address') ?? $partner->office_address }}</textarea>
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
                            <option value="free" {{ (old('package') ?? $partner->package) === 'free' ? 'selected' : '' }}>Free Listing Package</option>
                            <option value="starter" {{ (old('package') ?? $partner->package) === 'starter' ? 'selected' : '' }}>Starter Tier Plan</option>
                            <option value="growth" {{ (old('package') ?? $partner->package) === 'growth' ? 'selected' : '' }}>Growth Tier Plan</option>
                            <option value="premium" {{ (old('package') ?? $partner->package) === 'premium' ? 'selected' : '' }}>Premium Tier Plan</option>
                            <option value="customise" {{ (old('package') ?? $partner->package) === 'customise' ? 'selected' : '' }}>Customise Tier Plan</option>
                        </select>
                    </div>

                    <!-- Package Purchase Date -->
                    <div class="space-y-1.5">
                        <label for="paid_amount" class="text-xs font-bold text-slate-400">Paid Amount (INR)</label>
                        <input id="paid_amount" name="paid_amount" type="number" min="0" step="0.01" value="{{ old('paid_amount') ?? $partner->paid_amount }}"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <!-- Package Purchase Date -->
                    <div class="space-y-1.5">
                        <label for="package_purchase_date" class="text-xs font-bold text-slate-400">Purchase Date</label>
                        <input id="package_purchase_date" name="package_purchase_date" type="date" value="{{ old('package_purchase_date') ?? ($partner->package_purchase_date ? $partner->package_purchase_date->format('Y-m-d') : '') }}"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <!-- Renewal Date -->
                    <div class="space-y-1.5">
                        <label for="renewal_date" class="text-xs font-bold text-slate-400">Renewal Expiry Date</label>
                        <input id="renewal_date" name="renewal_date" type="date" value="{{ old('renewal_date') ?? ($partner->renewal_date ? $partner->renewal_date->format('Y-m-d') : '') }}"
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
                                <option value="{{ $person->id }}" {{ (old('assigned_sales_person_id') ?? $partner->assigned_sales_person_id) == $person->id ? 'selected' : '' }}>{{ $person->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lead Source -->
                    <div class="space-y-1.5">
                        <label for="lead_source" class="text-xs font-bold text-slate-400">Lead Origin / Channel</label>
                        <input id="lead_source" name="lead_source" type="text" value="{{ old('lead_source') ?? $partner->lead_source }}" placeholder="e.g. Broker Reference, Powai Expo"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>
                </div>

                <!-- Remark Notes -->
                <div class="space-y-1.5 mt-4">
                    <label for="remark" class="text-xs font-bold text-slate-400">Partner Remark / Memo Notes</label>
                    <textarea id="remark" name="remark" rows="3" placeholder="Context details, primary listing types..."
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">{{ old('remark') ?? $partner->remark }}</textarea>
                </div>
            </div>

            <!-- Section 4: Portal Access -->
            <div class="space-y-4 pt-4 border-t border-slate-800/40">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Portal Access</h3>
                
                <div class="space-y-4">
                    <!-- Checkbox -->
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="enable_portal_access" name="enable_portal_access" value="1" {{ (old('enable_portal_access') ?? ($partner->user_id ? true : false)) ? 'checked' : '' }}
                            class="w-4 h-4 text-amra-primary bg-slate-950 border-slate-800 rounded focus:ring-amra-primary focus:ring-2 focus:ring-offset-slate-900">
                        <label for="enable_portal_access" class="text-xs font-bold text-slate-400 cursor-pointer">Enable Portal Access</label>
                    </div>

                    <!-- Password and email block (hidden by default unless checkbox is checked) -->
                    <div id="portal_credentials_section" class="grid gap-6 md:grid-cols-2 {{ (old('enable_portal_access') ?? ($partner->user_id ? true : false)) ? '' : 'hidden' }}">
                        <div class="space-y-1.5">
                            <label for="portal_email" class="text-xs font-bold text-slate-400">Portal Login Email</label>
                            <input id="portal_email" name="portal_email" type="email" value="{{ old('portal_email') ?? ($partner->user ? $partner->user->email : '') }}" placeholder="partner-login@amra.com"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                            <p class="text-[10px] text-slate-500">If left blank, the partner's primary email will be used.</p>
                        </div>
                        
                        <div class="space-y-1.5">
                            <label for="password" class="text-xs font-bold text-slate-400">Portal Password {{ $partner->user_id ? '(Leave blank to keep current)' : '' }}</label>
                            <input id="password" name="password" type="password" placeholder="{{ $partner->user_id ? 'Keep existing password' : 'Enter custom password' }}"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                            <p class="text-[10px] text-slate-500">If left blank, it defaults to <code>password</code>.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('crm.partners.show', $partner->id) }}" class="bg-slate-800 hover:bg-slate-755 text-slate-350 font-bold text-xs px-6 py-3.5 rounded-xl transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-amra-primary hover:bg-teal-400 text-slate-955 font-bold text-xs px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-teal-500/10">
                    Save Updates
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

        const portalCheckbox = document.getElementById('enable_portal_access');
        const portalSection = document.getElementById('portal_credentials_section');
        if (portalCheckbox && portalSection) {
            portalCheckbox.addEventListener('change', () => {
                if (portalCheckbox.checked) {
                    portalSection.classList.remove('hidden');
                } else {
                    portalSection.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection
