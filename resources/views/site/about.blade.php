@extends('layouts.site')

@section('title', 'About Us - Amra Property')
@section('meta_description', 'Learn more about Amra Property, your trusted premium real estate advisor.')

@section('content')
    <div class="pt-24 pb-20 relative">
        <!-- Background Ambient Glow -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-teal-500/5 via-slate-50/0 to-slate-50/0 pointer-events-none"></div>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-6 pt-16 pb-12 text-center relative z-10">
            <span class="inline-flex items-center gap-1.5 bg-teal-500/10 text-teal-700 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider mb-6">
                <i data-lucide="sparkles" class="w-3.5 h-3.5" aria-hidden="true"></i> Our Story
            </span>
            <h1 class="text-4xl md:text-6xl font-serif font-extrabold text-amra-dark mb-6 leading-tight">
                Redefining Luxury <br>Real Estate <span class="text-amra-primary italic font-light">With Integrity</span>
            </h1>
            <p class="text-base md:text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed">
                At Amra Property, we believe luxury is more than an aesthetic—it's an end-to-end experience defined by transparency, trust, and flawless curation.
            </p>
        </section>

        <!-- Stats Grid Block -->
        <section class="max-w-7xl mx-auto px-6 py-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stat 1 -->
                <div class="bg-white/80 backdrop-blur-md p-8 rounded-3xl border border-slate-100 hover:border-teal-500/20 shadow-sm hover:shadow-md transition-all duration-300 text-center group">
                    <p class="text-4xl md:text-5xl font-serif font-extrabold text-teal-600 mb-2 group-hover:scale-105 transition-transform duration-300">1,500+</p>
                    <p class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-1">Happy Families</p>
                    <p class="text-xs text-slate-400">Successfully matched with their dream residences</p>
                </div>
                <!-- Stat 2 -->
                <div class="bg-white/80 backdrop-blur-md p-8 rounded-3xl border border-slate-100 hover:border-teal-500/20 shadow-sm hover:shadow-md transition-all duration-300 text-center group">
                    <p class="text-4xl md:text-5xl font-serif font-extrabold text-amra-dark mb-2 group-hover:scale-105 transition-transform duration-300">₹2,500 Cr+</p>
                    <p class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-1">Portfolio Volume</p>
                    <p class="text-xs text-slate-400">Of ultra-luxury property assets managed</p>
                </div>
                <!-- Stat 3 -->
                <div class="bg-white/80 backdrop-blur-md p-8 rounded-3xl border border-slate-100 hover:border-teal-500/20 shadow-sm hover:shadow-md transition-all duration-300 text-center group">
                    <p class="text-4xl md:text-5xl font-serif font-extrabold text-emerald-500 mb-2 group-hover:scale-105 transition-transform duration-300">100%</p>
                    <p class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-1">Verified Partners</p>
                    <p class="text-xs text-slate-400">Only the most elite developers listed</p>
                </div>
            </div>
        </section>

        <!-- Brand Narration Grid -->
        <section class="max-w-7xl mx-auto px-6 py-12 relative z-10">
            <div class="bg-white border border-slate-100 rounded-3xl p-8 md:p-12 shadow-sm flex flex-col lg:flex-row gap-12 items-center">
                <div class="flex-1 space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-teal-500/10 flex items-center justify-center text-teal-600">
                        <i data-lucide="target" class="w-6 h-6" aria-hidden="true"></i>
                    </div>
                    <h2 class="text-3xl font-serif font-bold text-amra-dark">Our Clear Mission</h2>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Amra Property is established on a single core principle: to dissolve the friction, opacity, and stress of high-value real estate acquisitions. Whether matching prospective owners with elite Powai flats or exquisite villas in Lucknow, we prioritize genuine representation above all else.
                    </p>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        By working exclusively with India's tier-1 accredited builders—such as Lodha, Godrej, and Shalimar—we offer a guaranteed catalog of premium opportunities, vetted and validated for direct-to-owner placement.
                    </p>
                </div>
                <!-- Aesthetic Narrative Image -->
                <div class="flex-1 w-full max-w-md lg:max-w-none aspect-[4/3] rounded-2xl overflow-hidden shadow-xl border border-slate-100 relative group">
                    <img src="{{ asset('assets/images/clean_hero.png') }}" alt="Luxury Real Estate Architectural Facade" width="560" height="420" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent pointer-events-none"></div>
                </div>
            </div>
        </section>

        <!-- Bento Styled 'Why Choose Us' Cards Grid -->
        <section class="max-w-7xl mx-auto px-6 py-12 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-serif font-bold text-amra-dark">Why Choose <span class="text-amra-primary">Amra Property?</span></h2>
                <p class="text-slate-500 text-sm mt-2">Setting the baseline standard for luxury property advising.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Bento Card 1 -->
                <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 hover:border-teal-500/20 transition-all duration-300 flex flex-col justify-between group">
                    <div class="mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                            <i data-lucide="shield-check" class="w-6 h-6" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-amra-dark mb-3">100% Verified Lists</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Every residence, flat, and plot undergoes strict title verification. Zero fake lists, zero pricing markups.
                        </p>
                    </div>
                    <span class="text-xs font-bold text-teal-600 inline-flex items-center gap-1 cursor-default mt-4">
                        Elite Security Standard
                    </span>
                </div>

                <!-- Bento Card 2 -->
                <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 hover:border-teal-500/20 transition-all duration-300 flex flex-col justify-between group">
                    <div class="mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-600 flex items-center justify-center mb-6 group-hover:bg-teal-500 group-hover:text-white transition-all duration-300">
                            <i data-lucide="gem" class="w-6 h-6" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-amra-dark mb-3">Zero Agency Fee</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            We value pure matching over brokerage commissions. Our direct relationship parameters keep our platform free of traditional advisor taxes.
                        </p>
                    </div>
                    <span class="text-xs font-bold text-teal-600 inline-flex items-center gap-1 cursor-default mt-4">
                        Direct Builder Pricing
                    </span>
                </div>

                <!-- Bento Card 3 -->
                <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 hover:border-teal-500/20 transition-all duration-300 flex flex-col justify-between group">
                    <div class="mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                            <i data-lucide="handshake" class="w-6 h-6" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-amra-dark mb-3">Accompanying Support</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            From initial search, architectural viewing, legal verification, up to physical key handover, our expert advisors accompany you throughout.
                        </p>
                    </div>
                    <span class="text-xs font-bold text-teal-600 inline-flex items-center gap-1 cursor-default mt-4">
                        Complete Handholding
                    </span>
                </div>
            </div>
        </section>
    </div>
@endsection
