@extends('layouts.crm')

@section('title', 'Add Sales Representative')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Link -->
    <a href="{{ route('crm.sales.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Sales Directory
    </a>

    <!-- Header Block -->
    <div>
        <h1 class="text-3xl font-serif font-extrabold text-white">Add Sales Representative</h1>
        <p class="text-sm text-slate-400 mt-1">Register a new salesperson / tele-caller account and assign office locations and service areas.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md">
        <form action="{{ route('crm.sales.store') }}" method="POST" class="space-y-6">
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

            <!-- Section: Profile Details -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Representative Details</h3>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Representative Name -->
                    <div class="space-y-1.5">
                        <label for="name" class="text-xs font-bold text-slate-400">Full Name <span class="text-rose-500">*</span></label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="e.g. Rahul Sharma"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold text-slate-400">Login Email Address <span class="text-rose-500">*</span></label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="rep@amra.com"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label for="password" class="text-xs font-bold text-slate-400">Login Password <span class="text-rose-500">*</span></label>
                        <input id="password" name="password" type="password" required placeholder="Enter login password"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Contact Phone -->
                    <div class="space-y-1.5">
                        <label for="phone" class="text-xs font-bold text-slate-400">Primary Phone Number <span class="text-rose-500">*</span></label>
                        <input id="phone" name="phone" type="text" required value="{{ old('phone') }}" placeholder="e.g. +91 99999 11111"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Location / Office -->
                    <div class="space-y-1.5">
                        <label for="location" class="text-xs font-bold text-slate-400">Office Location <span class="text-rose-500">*</span></label>
                        <input id="location" name="location" type="text" required value="{{ old('location') }}" placeholder="e.g. Mumbai Office"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>
                </div>

                <!-- Locality Coverage comma inputs -->
                <div class="space-y-1.5 mt-4">
                    <label for="service_areas_input" class="text-xs font-bold text-slate-400">Coverage Localities (Comma separated)</label>
                    <input id="service_areas_input" name="service_areas_raw" type="text" placeholder="e.g. Powai, Hiranandani, Hazratganj"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    <!-- Hidden array fields container -->
                    <div id="service-areas-container"></div>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('crm.sales.index') }}" class="bg-slate-800 hover:bg-slate-755 text-slate-350 font-bold text-xs px-6 py-3.5 rounded-xl transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-amra-primary hover:bg-teal-400 text-slate-955 font-bold text-xs px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-teal-500/10">
                    Save Representative
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
