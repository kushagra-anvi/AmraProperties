@extends('layouts.site')

@section('title', 'Amra Property - Real Estate Across Top Cities')
@section('meta_description', 'Buy and rent verified properties across Mumbai, Thane, Navi Mumbai, Panvel, Lucknow, Jaipur, Dubai and more with Amra Property.')

@section('seo_schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "Organization",
      "@@id": "https://www.amraproperty.com/#organization",
      "name": "Amra Property",
      "url": "https://www.amraproperty.com/",
      "logo": {
        "@@type": "ImageObject",
        "@@id": "https://www.amraproperty.com/#logo",
        "url": "{{ asset('assets/images/logo.png') }}",
        "caption": "Amra Property"
      },
      "sameAs": [
        "https://www.facebook.com/propertyamra",
        "https://twitter.com/AmraProperty",
        "https://www.instagram.com/amra_property/",
        "https://in.pinterest.com/amraproperty/",
        "https://in.linkedin.com/company/amra-property-india",
        "https://www.youtube.com/@AMRAPROPERTY/"
      ]
    },
    {
      "@@type": "WebSite",
      "@@id": "https://www.amraproperty.com/#website",
      "url": "https://www.amraproperty.com/",
      "name": "Amra Property",
      "publisher": {
        "@@id": "https://www.amraproperty.com/#organization"
      },
      "potentialAction": {
        "@@type": "SearchAction",
        "target": "https://www.amraproperty.com/property?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@@type": "WebPage",
      "@@id": "https://www.amraproperty.com/#webpage",
      "url": "https://www.amraproperty.com/",
      "name": "Amra Property - Real Estate Across Top Cities",
      "about": {
        "@@id": "https://www.amraproperty.com/#organization"
      },
      "isPartOf": {
        "@@id": "https://www.amraproperty.com/#website"
      },
      "description": "Buy and rent verified properties across Mumbai, Thane, Navi Mumbai, Panvel, Lucknow, Jaipur, Dubai and more with Amra Property."
    }
  ]
}
</script>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[80vh] flex items-center pt-28 pb-10 sm:pt-32 sm:pb-16 lg:pt-36 lg:pb-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/images/clean_hero.png') }}" alt="Hero Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-900/65 to-slate-950/50"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 md:px-6 flex flex-col items-center justify-center text-center relative z-10 w-full">
            <div class="animate-[fadeUp_1s_ease_forwards] max-w-3xl flex flex-col items-center">
                <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-serif font-bold leading-tight text-white mb-3 sm:mb-6 drop-shadow-[0_4px_12px_rgba(0,0,0,0.4)]">
                    Find Your Dream <br><span class="text-teal-300">Property</span> Today
                </h1>
                <p class="text-xs sm:text-base md:text-lg text-white mb-5 sm:mb-10 max-w-2xl leading-relaxed font-semibold drop-shadow-[0_2px_6px_rgba(0,0,0,0.5)]">
                    Verified flats, villas, plots, and commercial properties across Mumbai Metropolitan Region, Lucknow, Jaipur, Dubai, and more.
                </p>

                <!-- Rent/Sale Toggle Tabs -->
                <div class="flex gap-1.5 mb-[-1px] z-10 relative self-start">
                    <button type="button" id="toggle-all" class="px-4 py-2 sm:px-5 sm:py-2.5 rounded-t-xl font-extrabold text-xs sm:text-sm transition-all bg-white text-teal-600 border-t border-x border-gray-200 relative z-20" onclick="setListingType('')">Any Purpose</button>
                    <button type="button" id="toggle-sale" class="px-4 py-2 sm:px-5 sm:py-2.5 rounded-t-xl font-extrabold text-xs sm:text-sm transition-all bg-white text-slate-400 hover:text-teal-600 border-t border-x border-b border-gray-200/80 shadow-[inset_0_-2px_3px_rgba(0,0,0,0.03)] relative z-10" onclick="setListingType('sale')">Buy</button>
                    <button type="button" id="toggle-rent" class="px-4 py-2 sm:px-5 sm:py-2.5 rounded-t-xl font-extrabold text-xs sm:text-sm transition-all bg-white text-slate-400 hover:text-teal-600 border-t border-x border-b border-gray-200/80 shadow-[inset_0_-2px_3px_rgba(0,0,0,0.03)] relative z-10" onclick="setListingType('rent')">For Rent</button>
                    <input type="hidden" id="home-listing-type" value="">
                </div>

                <!-- Search Bar -->
                <div class="bg-white p-4 sm:p-6 rounded-2xl rounded-tl-none shadow-2xl flex flex-col gap-3 sm:gap-4 mb-6 sm:mb-10 w-full text-left relative z-0">
                    <!-- Row 1: Text Search Input -->
                    <div class="w-full relative">
                        <label class="block text-xs sm:text-sm text-gray-400 sm:text-gray-500 font-semibold mb-1 sm:mb-2 pl-1 sm:pl-2">Search Properties</label>
                        <div class="relative">
                            <input type="text" id="home-query" placeholder="Search by name, builder, or keywords (e.g. Lodha, Villa)..."
                                class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg pl-10 pr-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm h-[48px]">
                            <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        </div>
                    </div>
                    
                    <!-- Row 2: Select Dropdowns & Search Button -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 md:gap-4 items-end">
                        <div class="w-full">
                            <label class="flex items-center justify-between gap-2 text-xs sm:text-sm text-gray-400 sm:text-gray-500 font-semibold mb-1 sm:mb-2 pl-1 sm:pl-2">
                                <span>Location</span>
                                <span id="home-location-status" class="hidden text-[10px] font-bold text-teal-600"></span>
                            </label>
                            <select id="home-location"
                                class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[48px]">
                                <option value="">All Locations</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc }}">{{ $loc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full">
                            <label class="block text-xs sm:text-sm text-gray-400 sm:text-gray-500 font-semibold mb-1 sm:mb-2 pl-1 sm:pl-2">Property Type</label>
                            <select id="home-type"
                                class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[48px]">
                                <option value="">All Types</option>
                                <option value="flat">Flat / Apartment</option>
                                <option value="villa">Villa / House</option>
                                <option value="plot">Plot / Land</option>
                                <option value="commercial">Commercial / Office</option>
                            </select>
                        </div>
                        <div id="home-budget-range" class="w-full md:col-span-2">
                            <label class="block text-xs sm:text-sm text-gray-400 sm:text-gray-500 font-semibold mb-1 sm:mb-2 pl-1 sm:pl-2 flex justify-between">
                                <span>Budget Range</span>
                                <span id="home-budget-display" class="text-teal-600 font-extrabold text-xs">₹0 - ₹10 Cr+</span>
                            </label>
                            <div class="relative bg-slate-50 border border-gray-200 rounded-lg h-[48px] px-4 flex flex-col justify-center select-none">
                                <div class="relative w-full h-1 bg-gray-200 rounded-lg" style="margin-top: 2px;">
                                    <div id="home-slider-track" class="absolute h-1 bg-teal-500 rounded-lg" style="left: 0%; right: 0%;"></div>
                                    <input type="range" id="home-min-price" min="0" max="100000000" step="500000" value="0" 
                                           class="absolute w-full appearance-none bg-transparent h-1 outline-none left-0 top-0 double-range-input">
                                    <input type="range" id="home-max-price" min="0" max="100000000" step="500000" value="100000000" 
                                           class="absolute w-full appearance-none bg-transparent h-1 outline-none left-0 top-0 double-range-input">
                                </div>
                            </div>
                        </div>
                        <button id="home-search-btn"
                            class="w-full bg-teal-500 text-white px-8 py-3 rounded-lg font-bold hover:bg-teal-600 transition-all shadow-md hover:-translate-y-0.5 flex items-center justify-center gap-2 h-[48px]">
                            <i data-lucide="search" class="w-5 h-5 shrink-0"></i> Search
                        </button>
                    </div>
                </div>

                <div class="flex justify-center flex-wrap gap-3 sm:gap-6 text-xs sm:text-sm text-white font-semibold">
                    <span
                        class="flex items-center gap-1.5 bg-slate-900/60 backdrop-blur-md px-4 py-2 rounded-full border border-white/20 shadow-lg hover:border-teal-400/50 hover:bg-slate-900/80 transition-all duration-300 cursor-default"><i
                            data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-400"></i> New Launch</span>
                    <span
                        class="flex items-center gap-1.5 bg-slate-900/60 backdrop-blur-md px-4 py-2 rounded-full border border-white/20 shadow-lg hover:border-teal-400/50 hover:bg-slate-900/80 transition-all duration-300 cursor-default"><i
                            data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-400"></i> Ready to Move</span>
                    <span
                        class="flex items-center gap-1.5 bg-slate-900/60 backdrop-blur-md px-4 py-2 rounded-full border border-white/20 shadow-lg hover:border-teal-400/50 hover:bg-slate-900/80 transition-all duration-300 cursor-default"><i
                            data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-400"></i> Resale</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Trusted Developers Section (Infinite Scrolling Marquee) -->
    <section class="py-10 sm:py-16 md:py-20 bg-white border-y border-gray-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 md:px-6 mb-8 sm:mb-12 text-center">
            <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-[10px] mb-3">Verified Partners</p>
            <h2 class="text-2xl sm:text-3.5xl md:text-4xl font-serif font-bold text-amra-dark">Premium Homes By <span class="text-amra-primary italic">Premium Builders</span></h2>
            <p class="text-gray-400 text-xs sm:text-sm mt-3 max-w-xl mx-auto">Verified developers. RERA-approved premium residential and commercial developments from the region's elite companies.</p>
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
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Pan-India Portfolio</p>
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
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-wider mb-2">Pan-India Portfolio</p>
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
    <section class="py-12 sm:py-20 md:py-24 bg-amra-light">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="text-center mb-8 sm:mb-16">
                <p class="text-amra-primary font-bold uppercase tracking-[0.3em] text-[11px] mb-3 sm:mb-4">Exclusive Selection</p>
                <h2 class="text-2xl sm:text-3.5xl md:text-5xl font-serif font-bold text-amra-dark leading-tight">Featured <span class="text-amra-primary italic">Properties</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">

                @forelse($featuredProperties as $property)
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(11,193,178,0.12)] hover:-translate-y-2 transition-all duration-500 ease-out border border-gray-100 flex flex-col justify-between">
                        <div class="relative h-60 overflow-hidden">
                            <a href="{{ route('site.property.show', $property->slug) }}" class="block w-full h-full">
                                <img src="{{ $property->featured_image ? asset($property->featured_image) : asset('assets/images/prop1.webp') }}" alt="{{ html_entity_decode($property->title) }}" class="w-full h-full object-cover card-zoom-img">
                            </a>
                            <div class="absolute top-4 left-4 flex flex-col items-start gap-2">
                                @if($property->is_featured)
                                    <span class="bg-teal-500/90 text-white backdrop-blur-md border border-teal-400/20 shadow-sm px-3.5 py-1.5 rounded-lg uppercase tracking-wider text-[8px] font-bold">Featured Listing</span>
                                @endif
                                @if($property->is_rera_approved)
                                    <span class="bg-slate-950/80 text-white text-[8px] font-semibold px-2.5 py-1.5 rounded-lg backdrop-blur-md flex items-center gap-1.5 border border-white/10 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> RERA Approved: {{ $property->rera_number }}
                                    </span>
                                @endif
                            </div>
                            <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/80 hover:bg-white backdrop-blur-sm text-slate-500 hover:text-rose-500 flex items-center justify-center transition-all shadow-md active:scale-90" aria-label="Add to Wishlist">
                                <i data-lucide="heart" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <div class="p-4 sm:p-5 md:p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <p class="text-slate-400 text-[11px] font-medium tracking-wide flex items-center gap-1 mb-2">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-500 shrink-0"></i> 
                                    {{ $property->short_address }}
                                </p>
                                <h3 class="text-base font-serif font-bold text-slate-800 mb-2 group-hover:text-teal-600 transition-colors duration-300 leading-snug line-clamp-2 h-11">
                                    <a href="{{ route('site.property.show', $property->slug) }}">{{ html_entity_decode($property->title) }}</a>
                                </h3>
                                <p class="text-xs text-slate-400 leading-relaxed mb-4 line-clamp-1">{!! strip_tags($property->description) !!}</p>
                                <div class="flex flex-wrap gap-1.5 mb-5 items-center">
                                    @if($property->type_category)
                                        <span class="bg-slate-50 text-slate-600 border border-slate-100/80 rounded-lg py-1 px-2.5 text-[10px] font-semibold flex items-center gap-1 shadow-sm">
                                            <i data-lucide="home" class="w-3.5 h-3.5 text-slate-400"></i>
                                            {{ html_entity_decode(ucfirst($property->type_category)) }}
                                        </span>
                                    @endif
                                    @if($property->configurations->isNotEmpty())
                                        <span class="bg-slate-50 text-slate-600 border border-slate-100/80 rounded-lg py-1 px-2.5 text-[10px] font-semibold flex items-center gap-1 shadow-sm">
                                            <i data-lucide="layout" class="w-3.5 h-3.5 text-slate-400"></i>
                                            {{ $property->configurations->pluck('name')->map(function($val) { return trim(str_ireplace(' BHK', '', $val)); })->implode(', ') }} BHK
                                        </span>
                                    @elseif($property->configuration)
                                        <span class="bg-slate-50 text-slate-600 border border-slate-100/80 rounded-lg py-1 px-2.5 text-[10px] font-semibold flex items-center gap-1 shadow-sm">
                                            <i data-lucide="layout" class="w-3.5 h-3.5 text-slate-400"></i>
                                            {{ $property->configuration }}
                                        </span>
                                    @endif
                                    @if($property->area)
                                        <span class="bg-slate-50 text-slate-600 border border-slate-100/80 rounded-lg py-1 px-2.5 text-[10px] font-semibold flex items-center gap-1 shadow-sm">
                                            <i data-lucide="maximize-2" class="w-3.5 h-3.5 text-slate-400"></i>
                                            {{ number_format($property->area) }} {{ str_replace('_', ' ', $property->area_unit) }}
                                        </span>
                                    @endif
                                    @if($property->developer_name)
                                        <span class="bg-slate-50 text-slate-600 border border-slate-100/80 rounded-lg py-1 px-2.5 text-[10px] font-semibold flex items-center gap-1 shadow-sm">
                                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-400"></i>
                                            By {{ Str::limit(ucwords(strtolower($property->developer_name)), 15) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <h5 class="text-lg font-serif font-extrabold text-teal-600">{{ $property->formatted_price }}</h5>
                                        <p class="text-[9px] font-semibold text-slate-400 tracking-wide uppercase">Total Price</p>
                                    </div>
                                    <div class="flex gap-1.5">
                                        <button type="button"
                                            class="home-property-contact-trigger w-9 h-9 rounded-xl bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm active:scale-90 border border-emerald-100/50"
                                            title="Contact via WhatsApp"
                                            data-action="whatsapp"
                                            data-property-title="{{ e(html_entity_decode($property->title)) }}"
                                            data-endpoint="{{ route('site.property.enquiry', $property) }}">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg>
                                        </button>
                                        <button type="button"
                                            class="home-property-contact-trigger w-9 h-9 rounded-xl bg-teal-50 hover:bg-teal-500 text-teal-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm active:scale-90 border border-teal-100/50"
                                            title="Contact via Phone"
                                            data-action="phone"
                                            data-property-title="{{ e(html_entity_decode($property->title)) }}"
                                            data-endpoint="{{ route('site.property.enquiry', $property) }}">
                                            <i data-lucide="phone" class="w-4 h-4"></i>
                                        </button>
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

    @if(isset($recommendedSellers) && $recommendedSellers->isNotEmpty())
        <section class="py-12 sm:py-16 md:py-20 bg-white border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 md:px-6">
                <div class="mb-8 sm:mb-10">
                    <p class="text-amra-primary font-bold uppercase tracking-[0.3em] text-[11px] mb-2 sm:mb-3">Verified Network</p>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-serif font-bold text-slate-900">Recommended sellers</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-2">Sellers with complete knowledge about locality</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($recommendedSellers as $seller)
                        @php
                            $sellerName = $seller->company_name ?: $seller->contact_person ?: 'Amra Partner';
                            $sellerInitials = Str::of($sellerName)->explode(' ')->filter()->map(fn ($word) => Str::substr($word, 0, 1))->take(2)->implode('');
                            $sellerAreas = array_filter($seller->service_areas ?? [$seller->city]);
                        @endphp
                        <div class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                            <div class="px-4 py-3 bg-slate-100 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-300 text-white flex items-center justify-center font-bold text-xs">
                                    {{ $sellerInitials ?: 'AP' }}
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-slate-800 text-sm truncate">{{ $sellerName }}</h3>
                                    <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wide">{{ ucfirst($seller->type) }}</p>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-slate-600 font-semibold mb-2">
                                    {{ max(1, $seller->created_at?->diffInYears(now()) ?: 1) }} Yrs Experience
                                    <span class="mx-2 text-slate-300">|</span>
                                    {{ $seller->total_listings ?? 0 }} Total listings
                                </p>
                                <div class="flex flex-wrap gap-1.5 mb-4 min-h-7">
                                    @forelse(array_slice($sellerAreas, 0, 3) as $area)
                                        <span class="bg-slate-100 text-slate-500 rounded-lg px-2 py-1 text-[10px] font-semibold">{{ $area }}</span>
                                    @empty
                                        <span class="bg-slate-100 text-slate-500 rounded-lg px-2 py-1 text-[10px] font-semibold">Mumbai</span>
                                    @endforelse
                                </div>
                                <button type="button"
                                    data-seller-name="{{ e($sellerName) }}"
                                    data-endpoint="{{ route('site.recommended-seller.contact', $seller) }}"
                                    class="home-seller-contact-trigger w-full rounded-lg border border-indigo-500 text-indigo-600 hover:bg-indigo-500 hover:text-white px-4 py-2 text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i> Show Contact
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Property Calculators -->
    <section class="py-12 sm:py-16 md:py-20 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="text-center mb-8 sm:mb-12">
                <p class="inline-flex rounded-full border border-teal-200 bg-white px-4 py-2 text-[10px] font-extrabold uppercase tracking-[0.25em] text-amra-primary">Free Tools - Amra Property</p>
                <h2 class="mt-4 text-2xl sm:text-3xl md:text-4xl font-serif font-bold text-slate-950">Property Financial Calculators</h2>
                <p class="mt-2 text-sm text-slate-500">Quick tools to estimate buying costs, EMI, and loan affordability.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">
                <button type="button" data-calculator-open="stamp" class="group text-left rounded-2xl border border-teal-100 bg-white p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-500/10">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                        <i data-lucide="file-text" class="h-5 w-5"></i>
                    </span>
                    <p class="mt-6 text-[10px] font-extrabold uppercase tracking-[0.25em] text-teal-600">Tax & Registration</p>
                    <h3 class="mt-3 text-xl font-serif font-bold text-slate-950">Stamp Duty Calculator</h3>
                    <p class="mt-3 min-h-16 text-sm leading-relaxed text-slate-500">Estimate stamp duty and registration charges for key property markets.</p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">City rates</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">Buyer type</span>
                    </div>
                    <span class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amra-primary px-4 py-3 text-sm font-extrabold text-slate-950 transition-colors group-hover:bg-teal-300">
                        Calculate Now <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </span>
                </button>

                <button type="button" data-calculator-open="emi" class="group text-left rounded-2xl border border-teal-100 bg-white p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-500/10">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                        <i data-lucide="calculator" class="h-5 w-5"></i>
                    </span>
                    <p class="mt-6 text-[10px] font-extrabold uppercase tracking-[0.25em] text-teal-600">Loan Repayment</p>
                    <h3 class="mt-3 text-xl font-serif font-bold text-slate-950">EMI Calculator</h3>
                    <p class="mt-3 min-h-16 text-sm leading-relaxed text-slate-500">Calculate monthly EMI, total interest, and total repayment for your home loan.</p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">Monthly EMI</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">Total interest</span>
                    </div>
                    <span class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amra-primary px-4 py-3 text-sm font-extrabold text-slate-950 transition-colors group-hover:bg-teal-300">
                        Calculate EMI <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </span>
                </button>

                <button type="button" data-calculator-open="eligibility" class="group text-left rounded-2xl border border-teal-100 bg-white p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-500/10">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                        <i data-lucide="landmark" class="h-5 w-5"></i>
                    </span>
                    <p class="mt-6 text-[10px] font-extrabold uppercase tracking-[0.25em] text-teal-600">Loan Eligibility</p>
                    <h3 class="mt-3 text-xl font-serif font-bold text-slate-950">Home Loan Eligibility</h3>
                    <p class="mt-3 min-h-16 text-sm leading-relaxed text-slate-500">Estimate how much loan you may qualify for based on income and existing EMIs.</p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">Max loan</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">Budget range</span>
                    </div>
                    <span class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amra-primary px-4 py-3 text-sm font-extrabold text-slate-950 transition-colors group-hover:bg-teal-300">
                        Check Eligibility <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </span>
                </button>
            </div>
        </div>
    </section>

    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
        <section class="py-12 sm:py-16 md:py-20 bg-white border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 md:px-6">
                <div class="mb-8 sm:mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-amra-primary font-bold uppercase tracking-[0.3em] text-[11px] mb-2 sm:mb-3">Insights & Guides</p>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-serif font-bold text-slate-900">Latest from the blog</h2>
                        <p class="text-slate-500 text-xs sm:text-sm mt-2">Real estate trends, buying guides, and market updates from Amra Property.</p>
                    </div>
                    <a href="{{ route('site.blog') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-5 py-3 text-xs font-extrabold text-slate-700 transition-all hover:border-amra-primary hover:bg-teal-50 hover:text-slate-950">
                        View All Articles <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    @foreach($latestPosts as $post)
                        <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-500/10">
                            <a href="{{ route('site.blog.show', $post->slug) }}" class="block">
                                <div class="relative h-52 overflow-hidden bg-slate-100">
                                    @if($post->featured_image)
                                        <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover card-zoom-img">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-300">
                                            <i data-lucide="newspaper" class="h-10 w-10"></i>
                                        </div>
                                    @endif
                                    <span class="absolute left-4 top-4 rounded-lg bg-slate-950/75 px-3 py-1.5 text-[10px] font-bold text-white backdrop-blur">
                                        {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="p-5 sm:p-6">
                                    <h3 class="line-clamp-2 min-h-14 text-lg font-serif font-extrabold leading-snug text-slate-900 transition-colors group-hover:text-amra-primary">
                                        {{ $post->title }}
                                    </h3>
                                    <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-slate-500">
                                        {{ $post->excerpt(130) }}
                                    </p>
                                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-amra-primary">Read Article</span>
                                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-amra-primary transition-colors group-hover:bg-amra-primary group-hover:text-white">
                                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('site.partials.property-links', [
        'tabs' => $propertyLinkTabs,
        'sectionId' => 'home-property-links',
    ])

    <!-- CTA Section -->
    <section class="bg-white border-b border-slate-100 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="rounded-2xl border border-teal-100 bg-teal-50 px-5 py-4 sm:px-7 sm:py-5 shadow-sm">
                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-slate-950">Are You A Property Owner?</h2>
                        <p class="mt-1 max-w-2xl text-sm text-slate-600 font-medium">
                        List your property for free and reach genuine buyers actively looking for homes like yours.
                        </p>
                    </div>
                    <a href="{{ route('site.sell-property-online') }}"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-amra-primary px-5 py-3 text-sm font-extrabold text-slate-950 shadow-sm transition-colors hover:bg-teal-300">
                        Post Free Property Ad <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
        .calculator-radio {
            display: flex;
            cursor: pointer;
        }

        .calculator-radio input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .calculator-radio span {
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid rgb(226 232 240);
            background: rgb(248 250 252);
            padding: 0.75rem 1rem;
            text-align: center;
            font-size: 0.8125rem;
            font-weight: 800;
            color: rgb(71 85 105);
            transition: all 180ms ease;
        }

        .calculator-radio input:checked + span {
            border-color: rgb(20 184 166);
            background: rgb(204 251 241);
            color: rgb(15 23 42);
            box-shadow: 0 8px 18px rgba(20, 184, 166, 0.16);
        }

        .calculator-submit {
            display: inline-flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: #0bc1b2;
            padding: 0.875rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 900;
            color: rgb(15 23 42);
            transition: background 180ms ease, transform 180ms ease;
        }

        .calculator-submit:hover {
            background: rgb(94 234 212);
            transform: translateY(-1px);
        }

        .calculator-result {
            border-radius: 1rem;
            border: 1px solid rgb(153 246 228);
            background: rgb(240 253 250);
            padding: 1rem;
        }
    </style>

    <div id="calculator-modal" class="fixed inset-0 z-[130] hidden items-center justify-center bg-slate-950/65 px-4 py-6 backdrop-blur-sm">
        <div class="max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white shadow-2xl">
            <div class="sticky top-0 z-10 border-b border-slate-100 bg-white/95 px-5 py-5 backdrop-blur sm:px-7">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p id="calculator-kicker" class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-amra-primary">Free Tool</p>
                        <h3 id="calculator-title" class="mt-2 text-2xl font-serif font-bold text-slate-950">Calculator</h3>
                        <p id="calculator-subtitle" class="mt-1 text-sm text-slate-500"></p>
                    </div>
                    <button type="button" id="calculator-close" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-colors hover:bg-slate-200" aria-label="Close calculator">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>

            <div class="p-5 sm:p-7">
                <form id="stamp-calculator" class="calculator-panel hidden space-y-5">
                    <label class="block">
                        <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">City / Location</span>
                        <select id="stamp-city" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-teal-500 focus:bg-white">
                            <option value="mumbai">Mumbai / MMR</option>
                            <option value="thane">Thane</option>
                            <option value="navi-mumbai">Navi Mumbai</option>
                            <option value="panvel">Panvel</option>
                            <option value="lucknow">Lucknow</option>
                            <option value="jaipur">Jaipur</option>
                            <option value="nashik">Nashik</option>
                        </select>
                    </label>
                    <div>
                        <span class="mb-2 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Buyer Type</span>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="calculator-radio"><input type="radio" name="stamp_buyer" value="male" checked><span>Male</span></label>
                            <label class="calculator-radio"><input type="radio" name="stamp_buyer" value="female"><span>Female</span></label>
                            <label class="calculator-radio"><input type="radio" name="stamp_buyer" value="joint"><span>Joint</span></label>
                        </div>
                    </div>
                    <label class="block">
                        <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Property Value</span>
                        <input id="stamp-value" type="number" min="0" placeholder="e.g. 8500000" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-teal-500 focus:bg-white">
                    </label>
                    <button type="submit" class="calculator-submit">Calculate Stamp Duty</button>
                    <div id="stamp-result" class="calculator-result hidden"></div>
                </form>

                <form id="emi-calculator" class="calculator-panel hidden space-y-5">
                    <label class="block">
                        <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Loan Amount</span>
                        <input id="emi-amount" type="number" min="0" placeholder="e.g. 5000000" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-teal-500 focus:bg-white">
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="block">
                            <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Interest Rate (% p.a.)</span>
                            <input id="emi-rate" type="number" min="0" step="0.01" value="8.5" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-teal-500 focus:bg-white">
                        </label>
                        <label class="block">
                            <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Tenure (Years)</span>
                            <input id="emi-years" type="number" min="1" value="20" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-teal-500 focus:bg-white">
                        </label>
                    </div>
                    <button type="submit" class="calculator-submit">Calculate EMI</button>
                    <div id="emi-result" class="calculator-result hidden"></div>
                </form>

                <form id="eligibility-calculator" class="calculator-panel hidden space-y-5">
                    <div>
                        <span class="mb-2 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Employment Type</span>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="calculator-radio"><input type="radio" name="employment_type" value="salaried" checked><span>Salaried</span></label>
                            <label class="calculator-radio"><input type="radio" name="employment_type" value="self"><span>Self-employed</span></label>
                        </div>
                    </div>
                    <label class="block">
                        <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Net Monthly Income</span>
                        <input id="eligibility-income" type="number" min="0" placeholder="e.g. 80000" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-teal-500 focus:bg-white">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Existing Monthly EMIs</span>
                        <input id="eligibility-existing-emi" type="number" min="0" value="0" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-teal-500 focus:bg-white">
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="block">
                            <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Interest Rate (% p.a.)</span>
                            <input id="eligibility-rate" type="number" min="0" step="0.01" value="8.5" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-teal-500 focus:bg-white">
                        </label>
                        <label class="block">
                            <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Tenure (Years)</span>
                            <input id="eligibility-years" type="number" min="1" value="20" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-teal-500 focus:bg-white">
                        </label>
                    </div>
                    <button type="submit" class="calculator-submit">Check Eligibility</button>
                    <div id="eligibility-result" class="calculator-result hidden"></div>
                </form>
            </div>
        </div>
    </div>

    <div id="home-property-contact-modal" class="fixed inset-0 z-[125] hidden items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-teal-500">Unlock Contact</p>
                    <h3 id="home-property-contact-title" class="mt-2 text-xl font-serif font-extrabold leading-snug text-slate-900">Get property details</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-500">Share your details once. We will save your enquiry and reveal the seller contact.</p>
                </div>
                <button type="button" id="home-property-contact-close" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>

            <form id="home-property-contact-form" class="space-y-3">
                @csrf
                <input type="hidden" id="home-property-contact-action" value="whatsapp">
                <input type="hidden" id="home-property-contact-endpoint" value="">
                <label class="block">
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Name</span>
                    <input id="home-property-contact-name" type="text" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Your name">
                </label>
                <label class="block">
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Phone</span>
                    <input id="home-property-contact-phone" type="tel" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Your phone number">
                </label>
                <label class="block">
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Email</span>
                    <input id="home-property-contact-email" type="email" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Optional">
                </label>
                <button id="home-property-contact-submit" type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-teal-500 px-5 py-3.5 text-sm font-extrabold text-white transition-all hover:bg-teal-600">
                    <i data-lucide="send" class="h-4 w-4"></i>
                    Save Enquiry & Show Contact
                </button>
            </form>

            <div id="home-property-contact-unlocked" class="mt-4 hidden rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                <p class="mb-3 text-xs font-bold text-emerald-800">Contact unlocked</p>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <a id="home-property-contact-phone-link" href="#" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-xs font-bold text-emerald-700">
                        <i data-lucide="phone" class="h-4 w-4"></i><span></span>
                    </a>
                    <a id="home-property-contact-whatsapp-link" href="#" target="_blank" rel="noopener noreferrer" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3 text-xs font-bold text-white">
                        <i data-lucide="send" class="h-4 w-4"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="home-seller-contact-modal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-teal-500">Seller Contact</p>
                    <h3 id="home-seller-modal-title" class="mt-2 text-xl font-serif font-extrabold leading-snug text-slate-900">Show seller contact</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-500">Share your details to unlock this recommended seller's phone number.</p>
                </div>
                <button type="button" id="home-seller-modal-close" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>

            <form id="home-seller-contact-form" class="space-y-3">
                @csrf
                <input type="hidden" id="home-seller-endpoint" value="">
                <label class="block">
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Name</span>
                    <input id="home-seller-name" type="text" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Your name">
                </label>
                <label class="block">
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Phone</span>
                    <input id="home-seller-phone" type="tel" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Your phone number">
                </label>
                <label class="block">
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Email</span>
                    <input id="home-seller-email" type="email" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Optional">
                </label>
                <button id="home-seller-submit" type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-teal-500 px-5 py-3.5 text-sm font-extrabold text-white transition-all hover:bg-teal-600">
                    <i data-lucide="send" class="h-4 w-4"></i>
                    Save Enquiry & Show Contact
                </button>
            </form>

            <div id="home-seller-unlocked" class="mt-4 hidden rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                <p class="mb-3 text-xs font-bold text-emerald-800">Contact unlocked</p>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <a id="home-seller-unlocked-phone" href="#" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-xs font-bold text-emerald-700">
                        <i data-lucide="phone" class="h-4 w-4"></i><span></span>
                    </a>
                    <a id="home-seller-unlocked-whatsapp" href="#" target="_blank" rel="noopener noreferrer" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3 text-xs font-bold text-white">
                        <i data-lucide="send" class="h-4 w-4"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Listing Type selection
        function setListingType(type) {
            document.getElementById('home-listing-type').value = type;
            const btnAll = document.getElementById('toggle-all');
            const btnSale = document.getElementById('toggle-sale');
            const btnRent = document.getElementById('toggle-rent');
            const budgetRange = document.getElementById('home-budget-range');
            
            const inactiveClass = 'px-4 py-2 sm:px-5 sm:py-2.5 rounded-t-xl font-extrabold text-xs sm:text-sm transition-all bg-white text-slate-400 hover:text-teal-600 border-t border-x border-b border-gray-200/80 shadow-[inset_0_-2px_3px_rgba(0,0,0,0.03)] relative z-10';
            const activeClass = 'px-4 py-2 sm:px-5 sm:py-2.5 rounded-t-xl font-extrabold text-xs sm:text-sm transition-all bg-white text-teal-600 border-t border-x border-gray-200 relative z-20';
            
            btnAll.className = (type === '') ? activeClass : inactiveClass;
            btnSale.className = (type === 'sale') ? activeClass : inactiveClass;
            btnRent.className = (type === 'rent') ? activeClass : inactiveClass;

            if (budgetRange) {
                budgetRange.classList.toggle('hidden', type === 'rent');
            }
        }

        // Budget Slider Formatting
        const minPriceInput = document.getElementById('home-min-price');
        const maxPriceInput = document.getElementById('home-max-price');
        const budgetDisplay = document.getElementById('home-budget-display');
        const homeSliderTrack = document.getElementById('home-slider-track');

        function formatPrice(val) {
            val = parseInt(val);
            if (val === 0) return '₹0';
            if (val >= 10000000) {
                return '₹' + (val / 10000000).toFixed(1) + ' Cr';
            }
            if (val >= 100000) {
                return '₹' + (val / 100000).toFixed(0) + ' Lac';
            }
            return '₹' + val.toLocaleString('en-IN');
        }

        function updatePriceDisplay() {
            let minVal = parseInt(minPriceInput.value);
            let maxVal = parseInt(maxPriceInput.value);
            
            // Limit crossover
            if (minVal > maxVal) {
                minVal = maxVal;
            }

            // Fill Slider Track Highlight
            if (homeSliderTrack) {
                const maxRange = parseInt(minPriceInput.max || 100000000);
                const percent1 = (minVal / maxRange) * 100;
                const percent2 = (maxVal / maxRange) * 100;
                homeSliderTrack.style.left = percent1 + "%";
                homeSliderTrack.style.width = (percent2 - percent1) + "%";
            }
            
            if (maxVal === 100000000) {
                budgetDisplay.textContent = `${formatPrice(minVal)} - ₹10 Cr+`;
            } else {
                budgetDisplay.textContent = `${formatPrice(minVal)} - ${formatPrice(maxVal)}`;
            }
        }

        if (minPriceInput && maxPriceInput) {
            minPriceInput.addEventListener('input', () => {
                let minVal = parseInt(minPriceInput.value);
                let maxVal = parseInt(maxPriceInput.value);
                if (minVal > maxVal) {
                    minPriceInput.value = maxVal;
                }
                updatePriceDisplay();
            });
            maxPriceInput.addEventListener('input', () => {
                let minVal = parseInt(minPriceInput.value);
                let maxVal = parseInt(maxPriceInput.value);
                if (maxVal < minVal) {
                    maxPriceInput.value = minVal;
                }
                updatePriceDisplay();
            });
            updatePriceDisplay();
        }

        // Homepage Search Form Redirection
        const homeSearchBtn = document.getElementById('home-search-btn');
        const homeQuery = document.getElementById('home-query');
        const homeLocation = document.getElementById('home-location');
        const homeType = document.getElementById('home-type');
        const homeLocationStatus = document.getElementById('home-location-status');

        const supportedMarkets = [
            { name: 'Mumbai', lat: 19.076, lng: 72.8777, radiusKm: 35 },
            { name: 'Thane', lat: 19.2183, lng: 72.9781, radiusKm: 25 },
            { name: 'Navi Mumbai', lat: 19.033, lng: 73.0297, radiusKm: 28 },
            { name: 'Panvel', lat: 18.9894, lng: 73.1175, radiusKm: 25 },
            { name: 'Dombivli', lat: 19.2184, lng: 73.0867, radiusKm: 18 },
            { name: 'Lucknow', lat: 26.8467, lng: 80.9462, radiusKm: 45 },
            { name: 'Jaipur', lat: 26.9124, lng: 75.7873, radiusKm: 45 },
            { name: 'Nashik', lat: 19.9975, lng: 73.7898, radiusKm: 35 },
            { name: 'Varanasi', lat: 25.3176, lng: 82.9739, radiusKm: 35 },
            { name: 'Dubai', lat: 25.2048, lng: 55.2708, radiusKm: 55 },
        ];

        function distanceKm(lat1, lng1, lat2, lng2) {
            const earthRadiusKm = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2)
                + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
                * Math.sin(dLng / 2) * Math.sin(dLng / 2);
            return earthRadiusKm * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function nearestSupportedMarket(latitude, longitude) {
            return supportedMarkets
                .map((market) => ({
                    ...market,
                    distance: distanceKm(latitude, longitude, market.lat, market.lng),
                }))
                .filter((market) => market.distance <= market.radiusKm)
                .sort((a, b) => a.distance - b.distance)[0] || null;
        }

        function setHomeLocation(locationName, source = 'saved') {
            if (!homeLocation || !locationName) return;

            const matchingOption = [...homeLocation.options].find((option) => (
                option.value.toLowerCase() === locationName.toLowerCase()
            ));

            if (!matchingOption) return;

            homeLocation.value = matchingOption.value;

            if (homeLocationStatus) {
                homeLocationStatus.textContent = source === 'detected' ? `Near ${matchingOption.text}` : `Saved ${matchingOption.text}`;
                homeLocationStatus.classList.remove('hidden');
            }
        }

        // Auto Location Detection on Page Load
        if (navigator.geolocation && !localStorage.getItem('amra_location_detected') && !localStorage.getItem('amra_location_prompted')) {
            navigator.geolocation.getCurrentPosition((position) => {
                const { latitude, longitude } = position.coords;
                const market = nearestSupportedMarket(latitude, longitude);
                localStorage.setItem('amra_location_prompted', '1');
                if (market) {
                    localStorage.setItem('amra_location_detected', market.name);
                    setHomeLocation(market.name, 'detected');
                }
            }, () => {
                localStorage.setItem('amra_location_prompted', '1');
            });
        } else if (localStorage.getItem('amra_location_detected')) {
            const savedLocation = localStorage.getItem('amra_location_detected');
            setHomeLocation(savedLocation, 'saved');
        }

        if (homeSearchBtn) {
            homeSearchBtn.addEventListener('click', () => {
                const query = homeQuery ? homeQuery.value.trim() : '';
                const loc = homeLocation ? homeLocation.value : '';
                const type = homeType ? homeType.value : '';
                const listingType = document.getElementById('home-listing-type').value;
                const params = new URLSearchParams();
                
                if (query) params.set('q', query);
                if (loc) params.set('location', loc);
                if (type) params.set('type', type);
                if (listingType) params.set('listing_type', listingType);

                if (listingType !== 'rent') {
                    let minVal = parseInt(minPriceInput.value);
                    let maxVal = parseInt(maxPriceInput.value);
                    if (minVal > maxVal) {
                        let temp = minVal;
                        minVal = maxVal;
                        maxVal = temp;
                    }
                    params.set('min_price', minVal);
                    params.set('max_price', maxVal);
                }

                const queryString = params.toString();
                window.location.href = `{{ route('site.property') }}${queryString ? `?${queryString}` : ''}`;
            });
        }

        const calculatorModal = document.getElementById('calculator-modal');
        const calculatorClose = document.getElementById('calculator-close');
        const calculatorTitle = document.getElementById('calculator-title');
        const calculatorKicker = document.getElementById('calculator-kicker');
        const calculatorSubtitle = document.getElementById('calculator-subtitle');
        const calculatorPanels = document.querySelectorAll('.calculator-panel');
        const calculatorCopy = {
            stamp: {
                title: 'Stamp Duty Calculator',
                kicker: 'Tax & Registration',
                subtitle: 'Estimate stamp duty and registration charges for common city rates.',
                panel: 'stamp-calculator',
            },
            emi: {
                title: 'EMI Calculator',
                kicker: 'Loan Repayment',
                subtitle: 'Calculate your monthly EMI, total interest, and total payable amount.',
                panel: 'emi-calculator',
            },
            eligibility: {
                title: 'Home Loan Eligibility',
                kicker: 'Loan Eligibility',
                subtitle: 'Estimate the loan amount and property budget your income may support.',
                panel: 'eligibility-calculator',
            },
        };

        function openCalculator(type) {
            const config = calculatorCopy[type];
            if (!calculatorModal || !config) return;

            calculatorTitle.textContent = config.title;
            calculatorKicker.textContent = config.kicker;
            calculatorSubtitle.textContent = config.subtitle;
            calculatorPanels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.id !== config.panel);
            });
            calculatorModal.classList.remove('hidden');
            calculatorModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeCalculator() {
            if (!calculatorModal) return;
            calculatorModal.classList.add('hidden');
            calculatorModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function inr(value) {
            return new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                maximumFractionDigits: 0,
            }).format(Math.max(0, Math.round(value || 0)));
        }

        function loanPrincipalFromEmi(emi, annualRate, years) {
            const months = Math.max(1, years * 12);
            const monthlyRate = annualRate / 12 / 100;
            if (!monthlyRate) return emi * months;
            const compound = Math.pow(1 + monthlyRate, months);
            return emi * ((compound - 1) / (monthlyRate * compound));
        }

        function showCalculatorResult(element, rows) {
            if (!element) return;
            element.innerHTML = rows.map((row) => `
                <div class="flex items-center justify-between gap-4 border-b border-teal-100 py-2 last:border-b-0">
                    <span class="text-xs font-bold text-slate-500">${row.label}</span>
                    <strong class="text-sm sm:text-base font-extrabold text-slate-950">${row.value}</strong>
                </div>
            `).join('');
            element.classList.remove('hidden');
        }

        document.querySelectorAll('[data-calculator-open]').forEach((button) => {
            button.addEventListener('click', () => openCalculator(button.dataset.calculatorOpen));
        });

        if (calculatorClose) calculatorClose.addEventListener('click', closeCalculator);
        if (calculatorModal) {
            calculatorModal.addEventListener('click', (event) => {
                if (event.target === calculatorModal) closeCalculator();
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && calculatorModal && !calculatorModal.classList.contains('hidden')) {
                closeCalculator();
            }
        });

        const stampRates = {
            mumbai: { male: 0.06, female: 0.05, joint: 0.055 },
            thane: { male: 0.06, female: 0.05, joint: 0.055 },
            'navi-mumbai': { male: 0.06, female: 0.05, joint: 0.055 },
            panvel: { male: 0.06, female: 0.05, joint: 0.055 },
            lucknow: { male: 0.07, female: 0.06, joint: 0.065 },
            jaipur: { male: 0.06, female: 0.05, joint: 0.055 },
            nashik: { male: 0.06, female: 0.05, joint: 0.055 },
        };

        const stampForm = document.getElementById('stamp-calculator');
        if (stampForm) {
            stampForm.addEventListener('submit', (event) => {
                event.preventDefault();
                const city = document.getElementById('stamp-city').value;
                const buyer = document.querySelector('input[name="stamp_buyer"]:checked')?.value || 'male';
                const propertyValue = Number(document.getElementById('stamp-value').value);
                if (!propertyValue) return;

                const rate = stampRates[city]?.[buyer] || 0.06;
                const stampDuty = propertyValue * rate;
                const registration = Math.min(propertyValue * 0.01, 30000);
                showCalculatorResult(document.getElementById('stamp-result'), [
                    { label: 'Stamp duty', value: inr(stampDuty) },
                    { label: 'Registration estimate', value: inr(registration) },
                    { label: 'Total government charges', value: inr(stampDuty + registration) },
                ]);
            });
        }

        const emiForm = document.getElementById('emi-calculator');
        if (emiForm) {
            emiForm.addEventListener('submit', (event) => {
                event.preventDefault();
                const amount = Number(document.getElementById('emi-amount').value);
                const rate = Number(document.getElementById('emi-rate').value);
                const years = Number(document.getElementById('emi-years').value);
                if (!amount || !years) return;

                const months = years * 12;
                const monthlyRate = rate / 12 / 100;
                const compound = Math.pow(1 + monthlyRate, months);
                const emi = monthlyRate ? amount * monthlyRate * compound / (compound - 1) : amount / months;
                const totalPayable = emi * months;
                showCalculatorResult(document.getElementById('emi-result'), [
                    { label: 'Monthly EMI', value: inr(emi) },
                    { label: 'Total interest', value: inr(totalPayable - amount) },
                    { label: 'Total payable', value: inr(totalPayable) },
                ]);
            });
        }

        const eligibilityForm = document.getElementById('eligibility-calculator');
        if (eligibilityForm) {
            eligibilityForm.addEventListener('submit', (event) => {
                event.preventDefault();
                const employment = document.querySelector('input[name="employment_type"]:checked')?.value || 'salaried';
                const income = Number(document.getElementById('eligibility-income').value);
                const existingEmi = Number(document.getElementById('eligibility-existing-emi').value);
                const rate = Number(document.getElementById('eligibility-rate').value);
                const years = Number(document.getElementById('eligibility-years').value);
                if (!income || !years) return;

                const foir = employment === 'self' ? 0.5 : 0.55;
                const availableEmi = Math.max(0, income * foir - existingEmi);
                const maxLoan = loanPrincipalFromEmi(availableEmi, rate, years);
                showCalculatorResult(document.getElementById('eligibility-result'), [
                    { label: 'Affordable monthly EMI', value: inr(availableEmi) },
                    { label: 'Estimated max loan', value: inr(maxLoan) },
                    { label: 'Estimated property budget', value: inr(maxLoan / 0.8) },
                ]);
            });
        }

        const homePropertyContactModal = document.getElementById('home-property-contact-modal');
        const homePropertyContactClose = document.getElementById('home-property-contact-close');
        const homePropertyContactForm = document.getElementById('home-property-contact-form');
        const homePropertyContactSubmit = document.getElementById('home-property-contact-submit');
        const homePropertyContactTitle = document.getElementById('home-property-contact-title');
        const homePropertyContactAction = document.getElementById('home-property-contact-action');
        const homePropertyContactEndpoint = document.getElementById('home-property-contact-endpoint');
        const homePropertyContactUnlocked = document.getElementById('home-property-contact-unlocked');
        const homePropertyContactPhoneLink = document.getElementById('home-property-contact-phone-link');
        const homePropertyContactWhatsappLink = document.getElementById('home-property-contact-whatsapp-link');
        let homePropertyContactInFlight = false;

        function setHomePropertyContactButtonsDisabled(disabled) {
            document.querySelectorAll('.home-property-contact-trigger').forEach((button) => {
                button.disabled = disabled;
                button.classList.toggle('pointer-events-none', disabled);
                button.classList.toggle('opacity-60', disabled);
            });
        }

        function closeHomePropertyContactModal() {
            if (!homePropertyContactModal) return;
            homePropertyContactModal.classList.add('hidden');
            homePropertyContactModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedName = localStorage.getItem('amra_enquiry_name');
            const savedPhone = localStorage.getItem('amra_enquiry_phone');
            const savedEmail = localStorage.getItem('amra_enquiry_email');

            if (savedName && savedPhone) {
                if (document.getElementById('home-property-contact-name')) document.getElementById('home-property-contact-name').value = savedName;
                if (document.getElementById('home-property-contact-phone')) document.getElementById('home-property-contact-phone').value = savedPhone;
                if (savedEmail && document.getElementById('home-property-contact-email')) document.getElementById('home-property-contact-email').value = savedEmail;
            }
        });

        document.querySelectorAll('.home-property-contact-trigger').forEach((button) => {
            button.addEventListener('click', () => {
                if (homePropertyContactInFlight) return;

                homePropertyContactAction.value = button.dataset.action || 'whatsapp';
                homePropertyContactEndpoint.value = button.dataset.endpoint;
                homePropertyContactTitle.textContent = button.dataset.propertyTitle || 'Get property details';
                homePropertyContactUnlocked.classList.add('hidden');
                homePropertyContactSubmit.disabled = false;
                homePropertyContactSubmit.innerHTML = '<i data-lucide="send" class="h-4 w-4"></i> Save Enquiry & Show Contact';
                homePropertyContactModal.classList.remove('hidden');
                homePropertyContactModal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
                if (window.lucide) window.lucide.createIcons();

                const savedName = localStorage.getItem('amra_enquiry_name');
                const savedPhone = localStorage.getItem('amra_enquiry_phone');
                if (savedName && savedPhone) {
                    homePropertyContactForm.dispatchEvent(new Event('submit', { cancelable: true }));
                }
            });
        });

        if (homePropertyContactClose) homePropertyContactClose.addEventListener('click', closeHomePropertyContactModal);
        if (homePropertyContactModal) {
            homePropertyContactModal.addEventListener('click', (event) => {
                if (event.target === homePropertyContactModal) closeHomePropertyContactModal();
            });
        }

        if (homePropertyContactForm) {
            homePropertyContactForm.addEventListener('submit', (event) => {
                event.preventDefault();
                if (homePropertyContactInFlight) return;

                homePropertyContactInFlight = true;
                setHomePropertyContactButtonsDisabled(true);
                homePropertyContactSubmit.disabled = true;
                homePropertyContactSubmit.textContent = 'Saving...';

                const action = homePropertyContactAction.value || 'whatsapp';
                const nameVal = document.getElementById('home-property-contact-name').value;
                const phoneVal = document.getElementById('home-property-contact-phone').value;
                const emailVal = document.getElementById('home-property-contact-email').value;

                fetch(homePropertyContactEndpoint.value, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        name: nameVal,
                        phone: phoneVal,
                        email: emailVal,
                        source: `${action}_homepage_featured_card`,
                        intent: action,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (!data.success) throw new Error(data.message || 'Unable to save enquiry.');

                        localStorage.setItem('amra_enquiry_name', nameVal);
                        localStorage.setItem('amra_enquiry_phone', phoneVal);
                        localStorage.setItem('amra_enquiry_email', emailVal);

                        homePropertyContactPhoneLink.href = `tel:${data.phone}`;
                        homePropertyContactPhoneLink.querySelector('span').textContent = data.phone;
                        homePropertyContactWhatsappLink.href = data.whatsapp_url;
                        homePropertyContactUnlocked.classList.remove('hidden');
                        homePropertyContactSubmit.innerHTML = '<i data-lucide="check" class="h-4 w-4"></i> Enquiry Saved';
                        if (window.lucide) window.lucide.createIcons();

                        if (action === 'whatsapp') {
                            window.open(data.whatsapp_url, '_blank', 'noopener');
                        } else {
                            window.location.href = `tel:${data.phone}`;
                        }
                    })
                    .catch((error) => {
                        alert(error.message || 'Unable to save enquiry.');
                        homePropertyContactSubmit.disabled = false;
                        homePropertyContactSubmit.innerHTML = '<i data-lucide="send" class="h-4 w-4"></i> Save Enquiry & Show Contact';
                        if (window.lucide) window.lucide.createIcons();
                    })
                    .finally(() => {
                        homePropertyContactInFlight = false;
                        setHomePropertyContactButtonsDisabled(false);
                    });
            });
        }

        const homeSellerModal = document.getElementById('home-seller-contact-modal');
        const homeSellerModalClose = document.getElementById('home-seller-modal-close');
        const homeSellerForm = document.getElementById('home-seller-contact-form');
        const homeSellerSubmit = document.getElementById('home-seller-submit');
        const homeSellerEndpoint = document.getElementById('home-seller-endpoint');
        const homeSellerModalTitle = document.getElementById('home-seller-modal-title');
        const homeSellerUnlocked = document.getElementById('home-seller-unlocked');
        const homeSellerUnlockedPhone = document.getElementById('home-seller-unlocked-phone');
        const homeSellerUnlockedWhatsapp = document.getElementById('home-seller-unlocked-whatsapp');

        function closeHomeSellerModal() {
            homeSellerModal.classList.add('hidden');
            homeSellerModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedName = localStorage.getItem('amra_enquiry_name');
            const savedPhone = localStorage.getItem('amra_enquiry_phone');
            const savedEmail = localStorage.getItem('amra_enquiry_email');

            if (savedName && savedPhone) {
                if (document.getElementById('home-seller-name')) document.getElementById('home-seller-name').value = savedName;
                if (document.getElementById('home-seller-phone')) document.getElementById('home-seller-phone').value = savedPhone;
                if (savedEmail && document.getElementById('home-seller-email')) document.getElementById('home-seller-email').value = savedEmail;
            }
        });

        document.querySelectorAll('.home-seller-contact-trigger').forEach((button) => {
            button.addEventListener('click', () => {
                homeSellerEndpoint.value = button.dataset.endpoint;
                homeSellerModalTitle.textContent = button.dataset.sellerName || 'Show seller contact';
                homeSellerUnlocked.classList.add('hidden');
                homeSellerSubmit.disabled = false;
                homeSellerSubmit.innerHTML = '<i data-lucide="send" class="h-4 w-4"></i> Save Enquiry & Show Contact';
                homeSellerModal.classList.remove('hidden');
                homeSellerModal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
                if (window.lucide) window.lucide.createIcons();

                // Auto-submit if pre-filled details exist
                const savedName = localStorage.getItem('amra_enquiry_name');
                const savedPhone = localStorage.getItem('amra_enquiry_phone');
                if (savedName && savedPhone) {
                    homeSellerForm.dispatchEvent(new Event('submit', { cancelable: true }));
                }
            });
        });

        if (homeSellerModalClose) homeSellerModalClose.addEventListener('click', closeHomeSellerModal);
        if (homeSellerModal) {
            homeSellerModal.addEventListener('click', (event) => {
                if (event.target === homeSellerModal) closeHomeSellerModal();
            });
        }

        if (homeSellerForm) {
            homeSellerForm.addEventListener('submit', (event) => {
                event.preventDefault();
                homeSellerSubmit.disabled = true;
                homeSellerSubmit.textContent = 'Saving...';

                const nameVal = document.getElementById('home-seller-name').value;
                const phoneVal = document.getElementById('home-seller-phone').value;
                const emailVal = document.getElementById('home-seller-email').value;

                fetch(homeSellerEndpoint.value, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        name: nameVal,
                        phone: phoneVal,
                        email: emailVal,
                        intent: 'seller_contact',
                        message: 'Homepage recommended seller contact request.',
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (!data.success) throw new Error(data.message || 'Unable to save enquiry.');

                        localStorage.setItem('amra_enquiry_name', nameVal);
                        localStorage.setItem('amra_enquiry_phone', phoneVal);
                        localStorage.setItem('amra_enquiry_email', emailVal);

                        homeSellerUnlockedPhone.href = `tel:${data.phone}`;
                        homeSellerUnlockedPhone.querySelector('span').textContent = data.phone;
                        homeSellerUnlockedWhatsapp.href = data.whatsapp_url;
                        homeSellerUnlocked.classList.remove('hidden');
                        homeSellerSubmit.innerHTML = '<i data-lucide="check" class="h-4 w-4"></i> Enquiry Saved';
                        if (window.lucide) window.lucide.createIcons();
                    })
                    .catch((error) => {
                        alert(error.message || 'Unable to save enquiry.');
                        homeSellerSubmit.disabled = false;
                        homeSellerSubmit.innerHTML = '<i data-lucide="send" class="h-4 w-4"></i> Save Enquiry & Show Contact';
                        if (window.lucide) window.lucide.createIcons();
                    });
            });
        }
    </script>
@endsection
