@extends('layouts.site')

@section('title', 'Amra Property - Real Estate in Lucknow & Mumbai')
@section('meta_description', 'Buy property for sale in Lucknow & Mumbai with Amra Property. Book Flats, Villas, Row Houses or any type of property at the best price with easy EMI.')

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[80vh] flex items-center pt-24 pb-12 lg:pt-32 lg:pb-16 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/images/clean_hero.png') }}" alt="Hero Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900/50 to-teal-500/20"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 flex flex-col items-center justify-center text-center relative z-10 w-full">
            <div class="animate-[fadeUp_1s_ease_forwards] max-w-3xl flex flex-col items-center">
                <h1 class="text-5xl md:text-6xl font-serif font-bold leading-tight text-white mb-6">
                    Find Your Dream <br><span class="text-teal-300">Property</span> Today
                </h1>
                <p class="text-lg text-white/90 mb-10 max-w-2xl leading-relaxed font-medium">
                    Premium Flats, Villas, and Plots in Lucknow & Mumbai. No fake listings, just genuine homes.
                </p>

                <!-- Search Bar -->
                <div class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col gap-4 mb-10 w-full text-left">
                    <!-- Row 1: Text Search Input -->
                    <div class="w-full relative">
                        <label class="block text-sm text-gray-500 font-semibold mb-2 pl-2">Search Properties</label>
                        <div class="relative">
                            <input type="text" id="home-query" placeholder="Search by property name, builder, or keywords (e.g. Lodha, Villa, Gomti Nagar)..."
                                class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg pl-10 pr-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm h-[48px]">
                            <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        </div>
                    </div>
                    
                    <!-- Row 2: Select Dropdowns & Search Button -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="w-full">
                            <label class="block text-sm text-gray-500 font-semibold mb-2 pl-2">Location</label>
                            <select id="home-location"
                                class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[48px]">
                                <option value="">All Locations</option>
                                <option value="Mumbai">Mumbai</option>
                                <option value="Lucknow">Lucknow</option>
                            </select>
                        </div>
                        <div class="w-full">
                            <label class="block text-sm text-gray-500 font-semibold mb-2 pl-2">Property Type</label>
                            <select id="home-type"
                                class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[48px]">
                                <option value="">All Types</option>
                                <option value="Flat">Flat</option>
                                <option value="Villa">Villa</option>
                                <option value="Plot">Plot</option>
                            </select>
                        </div>
                        <div class="w-full">
                            <label class="block text-sm text-gray-500 font-semibold mb-2 pl-2">Budget</label>
                            <select id="home-budget"
                                class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[48px]">
                                <option value="">All Budgets</option>
                                <option value="₹25 Lac - ₹50 Lac">₹25 Lac - ₹50 Lac</option>
                                <option value="₹50 Lac - ₹1 Cr">₹50 Lac - ₹1 Cr</option>
                                <option value="₹1 Cr+">₹1 Cr+</option>
                            </select>
                        </div>
                        <button id="home-search-btn"
                            class="w-full bg-teal-500 text-white px-8 py-3 rounded-lg font-bold hover:bg-teal-600 transition-all shadow-md hover:-translate-y-0.5 flex items-center justify-center gap-2 h-[48px]">
                            <i data-lucide="search" class="w-4 h-4"></i> Search
                        </button>
                    </div>
                </div>

                <div class="flex justify-center flex-wrap gap-6 text-sm text-white font-semibold">
                    <span
                        class="flex items-center gap-2 bg-slate-900/60 backdrop-blur-md px-5 py-2.5 rounded-full border border-white/20 shadow-lg hover:border-teal-400/50 hover:bg-slate-900/80 transition-all duration-300 cursor-default"><i
                            data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i> New Launch</span>
                    <span
                        class="flex items-center gap-2 bg-slate-900/60 backdrop-blur-md px-5 py-2.5 rounded-full border border-white/20 shadow-lg hover:border-teal-400/50 hover:bg-slate-900/80 transition-all duration-300 cursor-default"><i
                            data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i> Ready to Move</span>
                    <span
                        class="flex items-center gap-2 bg-slate-900/60 backdrop-blur-md px-5 py-2.5 rounded-full border border-white/20 shadow-lg hover:border-teal-400/50 hover:bg-slate-900/80 transition-all duration-300 cursor-default"><i
                            data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i> Resale</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Trusted Developers Section (Infinite Scrolling Marquee) -->
    <section class="py-20 bg-white border-y border-gray-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 mb-12 text-center">
            <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-[10px] mb-3">Verified Partners</p>
            <h2 class="text-3.5xl md:text-4xl font-serif font-bold text-amra-dark">Premium Homes By <span class="text-amra-primary italic">Premium Builders</span></h2>
            <p class="text-gray-400 text-sm mt-3 max-w-xl mx-auto">Verified developers. RERA-approved premium residential and commercial developments from the region's elite companies.</p>
        </div>

        <!-- Marquee Track Wrapper -->
        <div class="relative w-full overflow-hidden py-4">
            <!-- Left & Right Gradient Overlays for Luxury Edge Fade -->
            <div class="absolute left-0 top-0 bottom-0 w-16 sm:w-32 bg-gradient-to-r from-white via-white/80 to-transparent z-10 pointer-events-none"></div>
            <div class="absolute right-0 top-0 bottom-0 w-16 sm:w-32 bg-gradient-to-l from-white via-white/80 to-transparent z-10 pointer-events-none"></div>
            
            <!-- Scrolling Track (Infinite Loop: Original + Duplicate Sets) -->
            <div class="animate-marquee flex gap-6 py-2">
                
                <!-- === ORIGINAL SET OF 7 BUILDERS === -->
                <!-- Builder 1: Shalimar Group -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48">
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/shalimar.png') }}" alt="Shalimar Group" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Shalimar Group</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Lucknow Pioneer</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 2: Eldeco -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48">
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/eldeco.png') }}" alt="Eldeco" class="h-8 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Eldeco</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Lucknow & NCR Elite</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 3: Lodha Group -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48">
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/lodha_new.png') }}" alt="Lodha Group" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Lodha Group</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Mumbai Luxury Leader</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 4: Godrej Properties -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48">
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/gpl_logo.jpg') }}" alt="Godrej Properties" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Godrej Properties</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Mumbai & Lucknow</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 5: Rustomjee -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48">
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/rustom.png') }}" alt="Rustomjee" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Rustomjee</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Mumbai Elite Communities</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 6: Adani Realty -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48">
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/adani.png') }}" alt="Adani Realty" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Adani Realty</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Mumbai & National Elite</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 7: Kalpataru -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48">
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/kalpataru.png') }}" alt="Kalpataru" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Kalpataru</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Mumbai Luxury Skyline</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- === DUPLICATE SET FOR INFINITE LOOPING === -->
                <!-- Builder 1: Shalimar Group -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48" aria-hidden="true" inert>
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/shalimar.png') }}" alt="Shalimar Group" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Shalimar Group</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Lucknow Pioneer</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 2: Eldeco -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48" aria-hidden="true" inert>
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/eldeco.png') }}" alt="Eldeco" class="h-8 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Eldeco</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Lucknow & NCR Elite</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 3: Lodha Group -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48" aria-hidden="true" inert>
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/lodha_new.png') }}" alt="Lodha Group" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Lodha Group</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Mumbai Luxury Leader</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 4: Godrej Properties -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48" aria-hidden="true" inert>
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/gpl_logo.jpg') }}" alt="Godrej Properties" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Godrej Properties</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Mumbai & Lucknow</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 5: Rustomjee -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48" aria-hidden="true" inert>
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/rustom.png') }}" alt="Rustomjee" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Rustomjee</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Mumbai Elite Communities</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 6: Adani Realty -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48" aria-hidden="true" inert>
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/adani.png') }}" alt="Adani Realty" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Adani Realty</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Mumbai & National Elite</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                <!-- Builder 7: Kalpataru -->
                <div class="w-72 flex-shrink-0 group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-amra-primary/20 hover:shadow-md transition-all duration-300 flex flex-col justify-between items-center text-center h-48" aria-hidden="true" inert>
                    <div class="w-full h-14 flex items-center justify-center mb-3">
                        <img src="{{ asset('assets/images/kalpataru.png') }}" alt="Kalpataru" class="h-10 object-contain mix-blend-multiply">
                    </div>
                    <div>
                        <h4 class="text-sm font-serif font-bold text-amra-dark mb-0.5">Kalpataru</h4>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Mumbai Luxury Skyline</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 flex items-center justify-between text-[10px]">
                        <span class="font-medium text-slate-400">RERA Approved</span>
                        <a href="{{ route('site.property') }}" class="font-bold text-amra-primary hover:underline flex items-center gap-0.5">Projects <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <section class="py-24 bg-amra-light">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <p class="text-amra-primary font-bold uppercase tracking-[0.3em] text-[11px] mb-4">Exclusive Selection</p>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-amra-dark leading-tight">Featured <span class="text-amra-primary italic">Properties</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @forelse($featuredProperties as $property)
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between">
                        <div class="relative h-60 overflow-hidden">
                            <img src="{{ $property->featured_image ? asset($property->featured_image) : asset('assets/images/prop1.webp') }}" alt="{{ $property->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($property->is_featured)
                                    <span class="bg-teal-500 text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md backdrop-blur-md">Featured Listing</span>
                                @endif
                                @if($property->is_rera_approved)
                                    <span class="bg-slate-900/80 text-white text-[9px] font-medium px-2.5 py-1 rounded-md backdrop-blur-md flex items-center gap-1 border border-white/10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> RERA Approved: {{ $property->rera_number }}
                                    </span>
                                @endif
                            </div>
                            <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-gray-500 hover:text-rose-500 hover:bg-white flex items-center justify-center transition-all shadow-md active:scale-90">
                                <i data-lucide="heart" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $property->address ?: ($property->city . ($property->state ? ', ' . $property->state : '')) }}
                                </p>
                                <h3 class="text-xl font-serif font-bold text-amra-dark mb-2 group-hover:text-teal-600 transition-colors">{{ $property->title }}</h3>
                                <p class="text-xs text-gray-500 leading-relaxed mb-4">{!! Str::limit(strip_tags($property->description), 140) !!}</p>
                                <div class="flex flex-wrap gap-1.5 mb-5">
                                    @if($property->configuration)
                                        <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">{{ $property->configuration }}</span>
                                    @endif
                                    @if($property->bedrooms)
                                        <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">{{ $property->bedrooms }} Beds</span>
                                    @endif
                                    @if($property->bathrooms)
                                        <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">{{ $property->bathrooms }} Baths</span>
                                    @endif
                                    @if($property->area)
                                        <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">{{ number_format($property->area) }} {{ str_replace('_', ' ', $property->area_unit) }}</span>
                                    @endif
                                    @if($property->developer_name)
                                        <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">By {{ $property->developer_name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <h5 class="text-sm font-bold text-slate-800">{{ $property->formatted_price }}</h5>
                                        <p class="text-[10px] font-medium text-slate-400">Total Price</p>
                                    </div>
                                    <div class="flex gap-1.5">
                                        <a href="https://wa.me/919999999999?text=Hi,%20I%20am%20interested%20in%20{{ rawurlencode($property->title) }}" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg>
                                        </a>
                                        <a href="tel:+919999999999" class="w-8 h-8 rounded-lg bg-teal-500/10 hover:bg-teal-500 text-teal-600 hover:text-white flex items-center justify-center transition-all duration-300">
                                            <i data-lucide="phone" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-white border border-gray-100 rounded-3xl max-w-md mx-auto shadow-sm">
                        <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i data-lucide="search-slash" class="w-6 h-6 text-amra-primary"></i>
                        </div>
                        <h3 class="text-lg font-serif font-bold text-amra-dark mb-1">No Featured Properties</h3>
                        <p class="text-gray-500 text-xs max-w-xs mx-auto">Check back soon for premium curated listings.</p>
                    </div>
                @endforelse

            </div>

            <div class="text-center mt-12">
                <a href="{{ route('site.property') }}"
                    class="inline-block bg-amra-dark text-white px-8 py-4 rounded-full font-bold text-sm hover:bg-black transition-colors shadow-xl">
                    View All Properties
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-amra-dark">
        <div class="max-w-7xl mx-auto px-6">
            <div
                class="bg-gradient-to-br from-teal-500 to-teal-700 rounded-3xl flex flex-col md:flex-row overflow-hidden shadow-2xl">
                <div class="flex-1 p-10 lg:p-16 flex flex-col justify-center items-start text-left">
                    <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">Are You A Property Owner?</h2>
                    <p class="text-teal-100 mb-8 max-w-md text-lg leading-relaxed">
                        List your property for free and reach genuine buyers actively looking for homes like yours.
                    </p>
                    <a href="#"
                        class="inline-block bg-white text-teal-500 px-6 py-3.5 rounded-lg font-bold text-sm hover:bg-slate-50 transition-colors shadow-md flex items-center gap-2">
                        Post Free Property Ad <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="flex-1 min-h-[300px] md:min-h-full relative">
                    <img src="{{ asset('assets/images/cta_key.png') }}" alt="Keys" class="absolute inset-0 w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Homepage Search Form Redirection
        const homeSearchBtn = document.getElementById('home-search-btn');
        const homeQuery = document.getElementById('home-query');
        const homeLocation = document.getElementById('home-location');
        const homeType = document.getElementById('home-type');
        const homeBudget = document.getElementById('home-budget');

        if (homeSearchBtn) {
            homeSearchBtn.addEventListener('click', () => {
                const query = homeQuery ? homeQuery.value.trim() : '';
                const loc = homeLocation ? homeLocation.value : '';
                const type = homeType ? homeType.value : '';
                const budget = homeBudget ? homeBudget.value : '';
                window.location.href = `{{ route('site.property') }}?q=${encodeURIComponent(query)}&location=${encodeURIComponent(loc)}&type=${encodeURIComponent(type)}&budget=${encodeURIComponent(budget)}`;
            });
        }
    </script>
@endsection
