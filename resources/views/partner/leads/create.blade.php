@extends('layouts.crm')

@section('title', 'Manually Add Lead')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('crm.partner.dashboard') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700 flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Dashboard
        </a>
    </div>

    <div class="text-left">
        <h1 class="text-3xl font-serif font-extrabold text-amra-dark">Add Lead Manually</h1>
        <p class="text-sm text-slate-400">Register a new buyer match directly to your partner database.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-md text-left">
        <form action="{{ route('crm.partner.leads.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Client Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Phone Number <span class="text-rose-500">*</span></label>
                    <input type="text" id="phone" name="phone" required value="{{ old('phone') }}"
                        class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
                </div>

                <div>
                    <label for="city" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">City <span class="text-rose-500">*</span></label>
                    <input type="text" id="city" name="city" required value="{{ old('city', $partner->city) }}"
                        class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
                </div>

                <div>
                    <label for="pincode" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Pincode</label>
                    <input type="text" id="pincode" name="pincode" value="{{ old('pincode') }}"
                        class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
                </div>

                <div>
                    <label for="property_type" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Property Type Category <span class="text-rose-500">*</span></label>
                    <select id="property_type" name="property_type" required
                        class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
                        <option value="flat" {{ old('property_type') === 'flat' ? 'selected' : '' }}>Flats / Apartments</option>
                        <option value="villa" {{ old('property_type') === 'villa' ? 'selected' : '' }}>Villas / Houses</option>
                        <option value="plot" {{ old('property_type') === 'plot' ? 'selected' : '' }}>Plots / Land</option>
                        <option value="commercial" {{ old('property_type') === 'commercial' ? 'selected' : '' }}>Commercial / Office</option>
                    </select>
                </div>

                <div>
                    <label for="budget_min" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Min Budget (₹)</label>
                    <input type="number" id="budget_min" name="budget_min" value="{{ old('budget_min') }}" placeholder="e.g. 5000000"
                        class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
                </div>

                <div>
                    <label for="budget_max" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Max Budget (₹)</label>
                    <input type="number" id="budget_max" name="budget_max" value="{{ old('budget_max') }}" placeholder="e.g. 10000000"
                        class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
                </div>
            </div>

            <div>
                <label for="configuration" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Desired Configuration (BHK)</label>
                <input type="text" id="configuration" name="configuration" value="{{ old('configuration') }}" placeholder="e.g. 2 BHK, 3 BHK"
                    class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
            </div>

            <div>
                <label for="remark" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Initial Remarks</label>
                <textarea id="remark" name="remark" rows="4" placeholder="Describe the buyer requirements or initial notes..."
                    class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">{{ old('remark') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('crm.partner.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3 rounded-xl font-bold transition-all text-xs">Cancel</a>
                <button type="submit" class="bg-amra-primary text-slate-950 hover:bg-teal-300 px-6 py-3 rounded-xl font-bold transition-all text-xs shadow-md active:scale-95">Save Lead</button>
            </div>
        </form>
    </div>
</div>
@endsection
