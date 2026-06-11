@extends('layouts.crm')

@section('title', 'Add Buyer Lead')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Link -->
    <a href="{{ route('crm.b2c.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Buyer Pipeline
    </a>

    <!-- Header Block -->
    <div>
        <h1 class="text-3xl font-serif font-extrabold text-white">Create B2C Buyer Lead</h1>
        <p class="text-sm text-slate-400 mt-1">Register a new home-buyer or commercial investor requirement profile</p>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md">
        <form action="{{ route('crm.b2c.store') }}" method="POST" class="space-y-6">
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

            <!-- Section 1: Personal Details -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Personal Details</h3>
                
                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Name -->
                    <div class="md:col-span-1 space-y-1.5">
                        <label for="name" class="text-xs font-bold text-slate-400">Buyer Full Name <span class="text-rose-500">*</span></label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="John Doe"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Contact Phone -->
                    <div class="md:col-span-1 space-y-1.5">
                        <label for="phone" class="text-xs font-bold text-slate-400">Phone Number <span class="text-rose-500">*</span></label>
                        <input id="phone" name="phone" type="text" required value="{{ old('phone') }}" placeholder="e.g. +91 9999988888"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-1 space-y-1.5">
                        <label for="email" class="text-xs font-bold text-slate-400">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="buyer@example.com"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>
                </div>
            </div>

            <!-- Section 2: Property Preferences -->
            <div class="space-y-4 pt-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Property Preferences</h3>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Property Type -->
                    <div class="space-y-1.5">
                        <label for="property_type" class="text-xs font-bold text-slate-400">Property Category <span class="text-rose-500">*</span></label>
                        <select id="property_type" name="property_type" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-200">
                            <option value="flat" {{ old('property_type') === 'flat' ? 'selected' : '' }}>Flat / Apartment</option>
                            <option value="plot" {{ old('property_type') === 'plot' ? 'selected' : '' }}>Plot / Land</option>
                            <option value="villa" {{ old('property_type') === 'villa' ? 'selected' : '' }}>Villa / Independent House</option>
                            <option value="commercial" {{ old('property_type') === 'commercial' ? 'selected' : '' }}>Commercial Office</option>
                        </select>
                    </div>

                    <!-- Configuration -->
                    <div class="space-y-1.5">
                        <label for="configuration" class="text-xs font-bold text-slate-400">Configuration <span class="text-rose-500">*</span></label>
                        <select id="configuration" name="configuration" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-200">
                            <option value="1BHK" {{ old('configuration') === '1BHK' ? 'selected' : '' }}>1 BHK</option>
                            <option value="2BHK" {{ old('configuration') === '2BHK' ? 'selected' : '' }}>2 BHK</option>
                            <option value="3BHK" {{ old('configuration') === '3BHK' ? 'selected' : '' }}>3 BHK</option>
                            <option value="4BHK" {{ old('configuration') === '4BHK' ? 'selected' : '' }}>4 BHK</option>
                            <option value="Plot" {{ old('configuration') === 'Plot' ? 'selected' : '' }}>Plot</option>
                            <option value="Studio" {{ old('configuration') === 'Studio' ? 'selected' : '' }}>Studio</option>
                        </select>
                    </div>

                    <!-- Budget Min -->
                    <div class="space-y-1.5">
                        <label for="budget_min" class="text-xs font-bold text-slate-400">Min Budget Budget (INR)</label>
                        <input id="budget_min" name="budget_min" type="number" min="0" value="{{ old('budget_min') }}" placeholder="e.g. 5000000"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Budget Max -->
                    <div class="space-y-1.5">
                        <label for="budget_max" class="text-xs font-bold text-slate-400">Max Budget Budget (INR)</label>
                        <input id="budget_max" name="budget_max" type="number" min="0" value="{{ old('budget_max') }}" placeholder="e.g. 20000000"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>
                </div>
            </div>

            <!-- Section 3: Locations & Source -->
            <div class="space-y-4 pt-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Target Locations & Channels</h3>
                
                <div class="grid gap-6 md:grid-cols-4">
                    <!-- City Dropdown -->
                    <div class="space-y-1.5">
                        <label for="city" class="text-xs font-bold text-slate-400">Target City <span class="text-rose-500">*</span></label>
                        <select id="city" name="city" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-200">
                            <option value="Mumbai" {{ old('city') === 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                            <option value="Lucknow" {{ old('city') === 'Lucknow' ? 'selected' : '' }}>Lucknow</option>
                            <option value="Pune" {{ old('city') === 'Pune' ? 'selected' : '' }}>Pune</option>
                            <option value="Delhi" {{ old('city') === 'Delhi' ? 'selected' : '' }}>Delhi</option>
                            <option value="Bangalore" {{ old('city') === 'Bangalore' ? 'selected' : '' }}>Bangalore</option>
                        </select>
                    </div>

                    <!-- Pincode -->
                    <div class="space-y-1.5">
                        <label for="pincode" class="text-xs font-bold text-slate-400">Target Area Pincode</label>
                        <input id="pincode" name="pincode" type="text" value="{{ old('pincode') }}" placeholder="e.g. 400076"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Source Platform -->
                    <div class="space-y-1.5">
                        <label for="source_platform" class="text-xs font-bold text-slate-400">Source Platform <span class="text-rose-500">*</span></label>
                        <select id="source_platform" name="source_platform" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-200">
                            <option value="manual" {{ old('source_platform') === 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="website" {{ old('source_platform') === 'website' ? 'selected' : '' }}>Website</option>
                            <option value="meta" {{ old('source_platform') === 'meta' ? 'selected' : '' }}>Meta Ads</option>
                            <option value="google" {{ old('source_platform') === 'google' ? 'selected' : '' }}>Google Ads</option>
                        </select>
                    </div>

                    <!-- Assigned Sales Person -->
                    <div class="space-y-1.5">
                        <label for="assigned_sales_person_id" class="text-xs font-bold text-slate-400">Assigned Representative</label>
                        <select id="assigned_sales_person_id" name="assigned_sales_person_id" 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-200">
                            <option value="">Leave Unassigned</option>
                            @foreach ($salesPeople as $person)
                                <option value="{{ $person->id }}" {{ old('assigned_sales_person_id') == $person->id ? 'selected' : '' }}>
                                    {{ $person->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Preferred Localities (comma separated) -->
                <div class="space-y-1.5 mt-4">
                    <label for="preferred_locations_input" class="text-xs font-bold text-slate-400">Preferred Localities / Landmarks (Comma separated)</label>
                    <input id="preferred_locations_input" name="preferred_locations_raw" type="text" placeholder="e.g. Powai Lake, Hiranandani Gardens, Hazratganj Metro"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    <p class="text-[10px] text-slate-500">Provide comma-separated localities to filter potential property matchings.</p>
                    <!-- Hidden array fields container -->
                    <div id="preferred-locations-container"></div>
                </div>

                <!-- Remark Notes -->
                <div class="space-y-1.5 mt-4">
                    <label for="remark" class="text-xs font-bold text-slate-400">Buyer Remarks / Notes</label>
                    <textarea id="remark" name="remark" rows="3" placeholder="Context details, primary listing types..."
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">{{ old('remark') }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('crm.b2c.index') }}" class="bg-slate-800 hover:bg-slate-755 text-slate-350 font-bold text-xs px-6 py-3.5 rounded-xl transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-emerald-500/10">
                    Add Buyer Requirement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rawInput = document.getElementById('preferred_locations_input');
        const container = document.getElementById('preferred-locations-container');
        
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
                    input.name = `preferred_locations[${index}]`;
                    input.value = value;
                    container.appendChild(input);
                });
            });
        }
    });
</script>
@endsection
