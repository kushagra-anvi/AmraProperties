@extends('layouts.site')

@section('title', 'Properties - Amra Property')
@section('meta_description', 'View all available properties by Amra Property.')

@section('content')
<div class="pt-32 pb-24">
<div class="max-w-7xl mx-auto px-6">
            
            <!-- Page Header -->
            <div class="text-center mb-10">
                <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-xs mb-3">Premium Directory</p>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-amra-dark">All <span class="text-amra-primary italic">Properties</span></h1>
                <p class="text-gray-500 text-sm mt-3">RERA-Approved, verified listings of Amra Property.</p>
            </div>

            <!-- Interactive Search & Filter Control Panel -->
            <!-- Interactive Search & Filter Control Panel -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col gap-4 mb-12 max-w-5xl mx-auto">
                <!-- Row 1: Search query input -->
                <div class="w-full relative">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Search Properties</label>
                    <div class="relative">
                        <input type="text" id="filter-query" placeholder="Search by name, builder, keywords..."
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl pl-10 pr-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm h-[46px]">
                        <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                    </div>
                </div>
                
                <!-- Row 2: Select Filters & Reset -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Location</label>
                        <select id="filter-location"
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[46px]">
                            <option value="all">All Locations</option>
                            <option value="mumbai">Mumbai</option>
                            <option value="lucknow">Lucknow</option>
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Property Type</label>
                        <select id="filter-type"
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[46px]">
                            <option value="all">All Types</option>
                            <option value="flat">Flats / Apartments</option>
                            <option value="villa">Villas</option>
                            <option value="plot">Plots</option>
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Budget Range</label>
                        <select id="filter-budget"
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[46px]">
                            <option value="all">All Budgets</option>
                            <option value="25-50">₹25 Lac - ₹50 Lac</option>
                            <option value="50-100">₹50 Lac - ₹1 Cr</option>
                            <option value="100+">₹1 Cr+</option>
                        </select>
                    </div>
                    <button id="reset-filters"
                        class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2 text-sm h-[46px]">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Reset
                    </button>
                </div>
            </div>

            <!-- Property Grid -->
            <div id="property-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Property Card 1 -->
                <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between"
                    data-location="mumbai" data-type="flat" data-budget="100+">
                    <div class="relative h-60 overflow-hidden">
                        <img src="{{ asset('assets/images/prop1.webp') }}" alt="Lodha Stella" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-teal-500 text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md backdrop-blur-md">Featured Listing</span>
                            <span class="bg-slate-900/80 text-white text-[9px] font-medium px-2.5 py-1 rounded-md backdrop-blur-md flex items-center gap-1 border border-white/10">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> RERA Approved
                            </span>
                        </div>
                        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-gray-500 hover:text-rose-500 hover:bg-white flex items-center justify-center transition-all shadow-md active:scale-90">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Kapurbawdi, Thane West
                            </p>
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2 group-hover:text-teal-600 transition-colors">Lodha Stella</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">Premium luxury flats starting from 4 BHK. Located just 5 mins from Viviana Mall & Eastern Express Highway.</p>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Infinity Pool</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Vastu Compliant</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">4 BHK Flats</span>
                            </div>
                        </div>
                        <div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white font-bold text-xs shadow-inner">AS</div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Amit Sharma</h5>
                                        <p class="text-[10px] font-medium text-slate-400">Amra Partner • 5★</p>
                                    </div>
                                </div>
                                <div class="flex gap-1.5">
                                    <a href="https://wa.me/919999999999" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300">
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

                <!-- Property Card 2 -->
                <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between"
                    data-location="mumbai" data-type="flat" data-budget="100+">
                    <div class="relative h-60 overflow-hidden">
                        <img src="{{ asset('assets/images/prop2.jpeg') }}" alt="Sheth Avalon" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-slate-900/80 text-white text-[9px] font-medium px-2.5 py-1 rounded-md backdrop-blur-md flex items-center gap-1 border border-white/10">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> RERA Approved
                            </span>
                        </div>
                        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-gray-500 hover:text-rose-500 hover:bg-white flex items-center justify-center transition-all shadow-md active:scale-90">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Thane Platinum Belt, Mumbai
                            </p>
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2 group-hover:text-teal-600 transition-colors">Sheth Avalon</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">Sleek ultra-modern design. Extremely high appreciation potential with sprawling green views.</p>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Sky Lounge</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Tennis Court</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">3, 4, 5 BHK</span>
                            </div>
                        </div>
                        <div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white font-bold text-xs shadow-inner">VM</div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Vikram Malhotra</h5>
                                        <p class="text-[10px] font-medium text-slate-400">Amra Partner • 4.9★</p>
                                    </div>
                                </div>
                                <div class="flex gap-1.5">
                                    <a href="https://wa.me/919999999999" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300">
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

                <!-- Property Card 3 -->
                <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between"
                    data-location="mumbai" data-type="flat" data-budget="50-100">
                    <div class="relative h-60 overflow-hidden">
                        <img src="{{ asset('assets/images/prop3.png') }}" alt="Rainbow Life" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-amra-secondary text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md backdrop-blur-md">New Launch</span>
                        </div>
                        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-gray-500 hover:text-rose-500 hover:bg-white flex items-center justify-center transition-all shadow-md active:scale-90">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Kharghar, Mumbai
                            </p>
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2 group-hover:text-teal-600 transition-colors">Rainbow Life</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">Vibrant gated community offering comfortable, high-quality, and modern lifestyle apartments.</p>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">0% Brokerage</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Grand Clubhouse</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">1, 2, 3 BHK</span>
                            </div>
                        </div>
                        <div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white font-bold text-xs shadow-inner">PN</div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Priya Nair</h5>
                                        <p class="text-[10px] font-medium text-slate-400">Amra Partner • 5★</p>
                                    </div>
                                </div>
                                <div class="flex gap-1.5">
                                    <a href="https://wa.me/919999999999" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300">
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

                <!-- Property Card 4 -->
                <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between"
                    data-location="mumbai" data-type="flat" data-budget="100+">
                    <div class="relative h-60 overflow-hidden">
                        <img src="{{ asset('assets/images/clean_hero.png') }}" alt="Godrej Ascend" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-teal-500 text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md backdrop-blur-md">Featured Listing</span>
                        </div>
                        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-gray-500 hover:text-rose-500 hover:bg-white flex items-center justify-center transition-all shadow-md active:scale-90">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Kolshet Road, Thane
                            </p>
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2 group-hover:text-teal-600 transition-colors">Godrej Ascend</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">State-of-the-art township offering unmatched luxury with 40+ dynamic lifestyle amenities.</p>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Swimming Pool</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Smart Homes</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">2, 3 BHK</span>
                            </div>
                        </div>
                        <div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white font-bold text-xs shadow-inner">RG</div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Rahul Gupta</h5>
                                        <p class="text-[10px] font-medium text-slate-400">Amra Partner • 4.8★</p>
                                    </div>
                                </div>
                                <div class="flex gap-1.5">
                                    <a href="https://wa.me/919999999999" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300">
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

                <!-- Property Card 5 -->
                <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between"
                    data-location="mumbai" data-type="flat" data-budget="100+">
                    <div class="relative h-60 overflow-hidden">
                        <img src="{{ asset('assets/images/prop1.webp') }}" alt="Rustomjee Crown" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-yellow-500 text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md backdrop-blur-md">Featured Listing</span>
                        </div>
                        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-gray-500 hover:text-rose-500 hover:bg-white flex items-center justify-center transition-all shadow-md active:scale-90">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Prabhadevi, Mumbai
                            </p>
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2 group-hover:text-teal-600 transition-colors">Rustomjee Crown</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">Ultra-luxury residential skyscrapers offering high-end gated living in South Mumbai.</p>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Sea View</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Private Elevator</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">3, 4, 5 BHK</span>
                            </div>
                        </div>
                        <div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white font-bold text-xs shadow-inner">SR</div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Sneha Rao</h5>
                                        <p class="text-[10px] font-medium text-slate-400">Amra Partner • 5★</p>
                                    </div>
                                </div>
                                <div class="flex gap-1.5">
                                    <a href="https://wa.me/919999999999" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300">
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

                <!-- Property Card 6 -->
                <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between"
                    data-location="mumbai" data-type="flat" data-budget="100+">
                    <div class="relative h-60 overflow-hidden">
                        <img src="{{ asset('assets/images/prop3.png') }}" alt="Lodha Bellagio" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-amra-secondary text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md backdrop-blur-md">New Launch</span>
                        </div>
                        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-gray-500 hover:text-rose-500 hover:bg-white flex items-center justify-center transition-all shadow-md active:scale-90">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Powai, Mumbai
                            </p>
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2 group-hover:text-teal-600 transition-colors">Lodha Bellagio</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">Inspired by European architecture. Nestled comfortably in Powai's prime luxury residential zone.</p>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Powai Lake View</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Luxury Lobby</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">2, 3 BHK</span>
                            </div>
                        </div>
                        <div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white font-bold text-xs shadow-inner">KM</div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Kabir Mehra</h5>
                                        <p class="text-[10px] font-medium text-slate-400">Amra Partner • 4.9★</p>
                                    </div>
                                </div>
                                <div class="flex gap-1.5">
                                    <a href="https://wa.me/919999999999" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300">
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

                <!-- Property Card 7 (Lucknow Flat) -->
                <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between"
                    data-location="lucknow" data-type="flat" data-budget="50-100">
                    <div class="relative h-60 overflow-hidden">
                        <img src="{{ asset('assets/images/banner.jpeg') }}" alt="Shalimar One World" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-teal-500 text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md backdrop-blur-md">Featured Listing</span>
                            <span class="bg-slate-900/80 text-white text-[9px] font-medium px-2.5 py-1 rounded-md backdrop-blur-md flex items-center gap-1 border border-white/10">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> RERA Approved
                            </span>
                        </div>
                        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-gray-500 hover:text-rose-500 hover:bg-white flex items-center justify-center transition-all shadow-md active:scale-90">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Gomti Nagar Ext, Lucknow
                            </p>
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2 group-hover:text-teal-600 transition-colors">Shalimar One World</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">Luxurious waterfront township apartments offering exceptional landscaping, sports facilities, and premium clubhouse.</p>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">River Views</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Jogging Track</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">2 & 3 BHK</span>
                            </div>
                        </div>
                        <div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white font-bold text-xs shadow-inner">RT</div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Ravi Tiwari</h5>
                                        <p class="text-[10px] font-medium text-slate-400">Amra Partner • 4.9★</p>
                                    </div>
                                </div>
                                <div class="flex gap-1.5">
                                    <a href="https://wa.me/919999999999" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300">
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

                <!-- Property Card 8 (Lucknow Villa) -->
                <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between"
                    data-location="lucknow" data-type="villa" data-budget="100+">
                    <div class="relative h-60 overflow-hidden">
                        <img src="{{ asset('assets/images/clean_hero.png') }}" alt="Eldeco Twin Towers" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-teal-500 text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md backdrop-blur-md">Featured Listing</span>
                            <span class="bg-slate-900/80 text-white text-[9px] font-medium px-2.5 py-1 rounded-md backdrop-blur-md flex items-center gap-1 border border-white/10">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> RERA Approved
                            </span>
                        </div>
                        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-gray-500 hover:text-rose-500 hover:bg-white flex items-center justify-center transition-all shadow-md active:scale-90">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Amar Shaheed Path, Lucknow
                            </p>
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2 group-hover:text-teal-600 transition-colors">Eldeco Twin Towers & Villas</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">Elite spacious residential villas and sky-bungalows offering unmatched security, lush green gardens, and individual private terraces.</p>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Private Yard</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">24/7 Security</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">4 BHK Villas</span>
                            </div>
                        </div>
                        <div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white font-bold text-xs shadow-inner">KP</div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Kamlesh Prasad</h5>
                                        <p class="text-[10px] font-medium text-slate-400">Amra Partner • 5★</p>
                                    </div>
                                </div>
                                <div class="flex gap-1.5">
                                    <a href="https://wa.me/919999999999" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300">
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

                <!-- Property Card 9 (Lucknow Plot) -->
                <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between"
                    data-location="lucknow" data-type="plot" data-budget="25-50">
                    <div class="relative h-60 overflow-hidden">
                        <img src="{{ asset('assets/images/prop2.jpeg') }}" alt="Amra Elite Plots" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-emerald-500 text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md backdrop-blur-md">Hot Deal</span>
                        </div>
                        <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-gray-500 hover:text-rose-500 hover:bg-white flex items-center justify-center transition-all shadow-md active:scale-90">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Sultanpur Road, Lucknow
                            </p>
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2 group-hover:text-teal-600 transition-colors">Amra Elite Plots</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">Gated plot community inside high-growth boundary lines. Construct your custom dream villa with immediate registries.</p>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Gated Boundary</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">RERA Approved</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">Immediate Registry</span>
                            </div>
                        </div>
                        <div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white font-bold text-xs shadow-inner">KM</div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Kabir Mehra</h5>
                                        <p class="text-[10px] font-medium text-slate-400">Amra Partner • 4.9★</p>
                                    </div>
                                </div>
                                <div class="flex gap-1.5">
                                    <a href="https://wa.me/919999999999" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300">
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

            </div>

            <!-- No Results State -->
            <div id="no-results-state" class="hidden flex-col items-center justify-center text-center py-20 bg-white border border-gray-100 rounded-3xl max-w-xl mx-auto shadow-sm mt-12">
                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-4 mx-auto">
                    <i data-lucide="search-slash" class="w-8 h-8 text-amra-primary"></i>
                </div>
                <h3 class="text-xl font-serif font-bold text-amra-dark mb-2">No Matching Homes Found</h3>
                <p class="text-gray-500 text-sm max-w-sm mb-6 mx-auto">Try adjusting your budget, location, or property type filters to find available premium listings.</p>
                <button id="no-results-reset" class="bg-teal-500 hover:bg-teal-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md active:scale-95">
                    Clear All Filters
                </button>
            </div>

        </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
            const filterQuery = document.getElementById('filter-query');
            const filterLocation = document.getElementById('filter-location');
            const filterType = document.getElementById('filter-type');
            const filterBudget = document.getElementById('filter-budget');
            const resetFiltersBtn = document.getElementById('reset-filters');
            const noResultsState = document.getElementById('no-results-state');
            const noResultsResetBtn = document.getElementById('no-results-reset');
            const propertyGrid = document.getElementById('property-grid');
            const propertyCards = document.querySelectorAll('.property-card');

            function normalizeText(value) {
                return (value || '')
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, ' ')
                    .trim();
            }

            function extractBhkTokens(value) {
                const tokens = new Set();
                const text = value || '';
                const groupedMatches = text.matchAll(/((?:\d+\s*(?:,|&|and)?\s*)+)\s*bhk/gi);

                for (const match of groupedMatches) {
                    const numbers = match[1].match(/\d+/g) || [];
                    numbers.forEach((number) => tokens.add(`${number}bhk`));
                }

                const directMatches = text.matchAll(/\b(\d+)\s*bhk\b/gi);
                for (const match of directMatches) {
                    tokens.add(`${match[1]}bhk`);
                }

                return Array.from(tokens);
            }

            function applyFilters() {
                const q = filterQuery ? filterQuery.value.trim() : '';
                const normalizedQuery = normalizeText(q);
                const bhkQueryTokens = extractBhkTokens(q);
                const queryWithoutBhk = q.replace(/((?:\d+\s*(?:,|&|and)?\s*)+)\s*bhk/gi, '').trim();
                const normalizedRemainingQuery = normalizeText(queryWithoutBhk);
                const loc = filterLocation.value;
                const type = filterType.value;
                const budget = filterBudget.value;
                let visibleCount = 0;

                propertyCards.forEach(card => {
                    const cardLoc = card.getAttribute('data-location');
                    const cardType = card.getAttribute('data-type');
                    const cardBudget = card.getAttribute('data-budget');

                    const cardText = card.textContent;
                    const normalizedCardText = normalizeText(cardText);
                    const cardBhkTokens = extractBhkTokens(cardText);
                    const bhkMatch = bhkQueryTokens.length > 0
                        && bhkQueryTokens.some((token) => cardBhkTokens.includes(token))
                        && (!normalizedRemainingQuery || normalizedCardText.includes(normalizedRemainingQuery));

                    const textMatch = !normalizedQuery || normalizedCardText.includes(normalizedQuery) || bhkMatch;

                    const locMatch = (loc === 'all' || cardLoc === loc);
                    const typeMatch = (type === 'all' || cardType === type);
                    const budgetMatch = (budget === 'all' || cardBudget === budget);

                    if (textMatch && locMatch && typeMatch && budgetMatch) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                if (visibleCount === 0) {
                    propertyGrid.classList.add('hidden');
                    noResultsState.classList.remove('hidden');
                    noResultsState.classList.add('flex');
                } else {
                    propertyGrid.classList.remove('hidden');
                    noResultsState.classList.add('hidden');
                    noResultsState.classList.remove('flex');
                }
            }

            function resetFilters() {
                if (filterQuery) filterQuery.value = '';
                filterLocation.value = 'all';
                filterType.value = 'all';
                filterBudget.value = 'all';
                applyFilters();
            }

            if (filterQuery) filterQuery.addEventListener('input', applyFilters);
            if (filterLocation) filterLocation.addEventListener('change', applyFilters);
            if (filterType) filterType.addEventListener('change', applyFilters);
            if (filterBudget) filterBudget.addEventListener('change', applyFilters);
            if (resetFiltersBtn) resetFiltersBtn.addEventListener('click', resetFilters);
            if (noResultsResetBtn) noResultsResetBtn.addEventListener('click', resetFilters);

            // URL Query Parameter Parsing (Bridges Search on Homepage to Properties page)
            const params = new URLSearchParams(window.location.search);
            const queryQ = params.get('q');
            const queryLocation = params.get('location');
            const queryType = params.get('type');
            const queryBudget = params.get('budget');

            if (queryQ && filterQuery) {
                filterQuery.value = queryQ;
            }
            if (queryLocation) {
                const locVal = queryLocation.toLowerCase();
                if (locVal.includes('mumbai')) filterLocation.value = 'mumbai';
                else if (locVal.includes('lucknow')) filterLocation.value = 'lucknow';
            }
            if (queryType) {
                const typeVal = queryType.toLowerCase();
                if (typeVal.includes('flat')) filterType.value = 'flat';
                else if (typeVal.includes('villa')) filterType.value = 'villa';
                else if (typeVal.includes('plot')) filterType.value = 'plot';
            }
            if (queryBudget) {
                const bVal = queryBudget.toLowerCase();
                if (bVal.includes('25') || bVal.includes('50 lac')) filterBudget.value = '25-50';
                else if (bVal.includes('50') || bVal.includes('1 cr')) filterBudget.value = '50-100';
                else if (bVal.includes('1 cr') || bVal.includes('1cr')) filterBudget.value = '100+';
            }

            // Perform initial filtering based on URL query parameters
            applyFilters();
        });
</script>
@endsection
