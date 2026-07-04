@extends('layouts.site')

@section('title', 'Amra Property - Real Estate in Lucknow & Mumbai')
@section('meta_description', 'Buy property for sale in Lucknow & Mumbai with Amra Property. Book Flats, Villas, Row Houses or any type of property at the best price with easy EMI.')

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
      "name": "Amra Property - Real Estate in Lucknow & Mumbai",
      "about": {
        "@@id": "https://www.amraproperty.com/#organization"
      },
      "isPartOf": {
        "@@id": "https://www.amraproperty.com/#website"
      },
      "description": "Buy property for sale in Lucknow & Mumbai with Amra Property. Book Flats, Villas, Row Houses or any type of property at the best price with easy EMI."
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
                    Premium Flats, Villas, and Plots in Lucknow & Mumbai. No fake listings, just genuine homes.
                </p>

                <!-- Rent/Sale Toggle Tabs -->
                <div class="flex gap-1.5 mb-[-1px] z-10 relative self-start">
                    <button type="button" id="toggle-all" class="px-4 py-2 sm:px-5 sm:py-2.5 rounded-t-xl font-extrabold text-xs sm:text-sm transition-all bg-white text-teal-600 border-t border-x border-gray-200 relative z-20" onclick="setListingType('')">Any Purpose</button>
                    <button type="button" id="toggle-sale" class="px-4 py-2 sm:px-5 sm:py-2.5 rounded-t-xl font-extrabold text-xs sm:text-sm transition-all bg-white text-slate-400 hover:text-teal-600 border-t border-x border-b border-gray-200/80 shadow-[inset_0_-2px_3px_rgba(0,0,0,0.03)] relative z-10" onclick="setListingType('sale')">For Sale</button>
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
                            <label class="block text-xs sm:text-sm text-gray-400 sm:text-gray-500 font-semibold mb-1 sm:mb-2 pl-1 sm:pl-2">Location</label>
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
                        <div class="w-full md:col-span-2">
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
                            <i data-lucide="search" class="w-4 h-4"></i> Search
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
                                        <a href="https://wa.me/919559992958?text=Hi,%20I%20am%20interested%20in%20{{ rawurlencode(html_entity_decode($property->title)) }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm active:scale-90 border border-emerald-100/50" title="Contact via WhatsApp">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg>
                                        </a>
                                        <a href="tel:+919559992958" class="w-9 h-9 rounded-xl bg-teal-50 hover:bg-teal-500 text-teal-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm active:scale-90 border border-teal-100/50" title="Contact via Phone">
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

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-amra-dark">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div
                class="bg-gradient-to-br from-[#0bc1b2] to-[#079186] rounded-3xl flex flex-col md:flex-row overflow-hidden shadow-2xl">
                <div class="flex-1 p-6 sm:p-10 lg:p-16 flex flex-col justify-center items-start text-left">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-serif font-bold text-white mb-4">Are You A Property Owner?</h2>
                    <p class="text-teal-50/95 mb-6 sm:mb-8 max-w-md text-base sm:text-lg leading-relaxed font-medium">
                        List your property for free and reach genuine buyers actively looking for homes like yours.
                    </p>
                    <a href="{{ route('site.sell-property-online') }}"
                        class="inline-block bg-white text-amra-primary px-6 py-3.5 rounded-lg font-bold text-sm hover:bg-slate-50 transition-colors shadow-md flex items-center gap-2">
                        Post Free Property Ad <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="flex-1 p-6 pt-0 md:p-0 min-h-[300px] md:min-h-full relative">
                    <img src="{{ asset('assets/images/cta_key.png') }}" alt="Keys" class="w-full h-[250px] md:h-full object-cover rounded-2xl md:rounded-none md:absolute md:inset-0 shadow-inner">
                </div>
            </div>
        </div>
    </section>

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
            
            const inactiveClass = 'px-4 py-2 sm:px-5 sm:py-2.5 rounded-t-xl font-extrabold text-xs sm:text-sm transition-all bg-white text-slate-400 hover:text-teal-600 border-t border-x border-b border-gray-200/80 shadow-[inset_0_-2px_3px_rgba(0,0,0,0.03)] relative z-10';
            const activeClass = 'px-4 py-2 sm:px-5 sm:py-2.5 rounded-t-xl font-extrabold text-xs sm:text-sm transition-all bg-white text-teal-600 border-t border-x border-gray-200 relative z-20';
            
            btnAll.className = (type === '') ? activeClass : inactiveClass;
            btnSale.className = (type === 'sale') ? activeClass : inactiveClass;
            btnRent.className = (type === 'rent') ? activeClass : inactiveClass;
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

        // Auto Location Detection on Page Load
        if (navigator.geolocation && !localStorage.getItem('amra_location_detected') && !localStorage.getItem('amra_location_prompted')) {
            navigator.geolocation.getCurrentPosition((position) => {
                const { latitude, longitude } = position.coords;
                const location = latitude > 18 && latitude < 20 && longitude > 72 && longitude < 74 ? 'mumbai'
                    : latitude > 25 && latitude < 28 && longitude > 79 && longitude < 82 ? 'lucknow'
                    : '';
                if (location) {
                    localStorage.setItem('amra_location_detected', location);
                    localStorage.setItem('amra_location_prompted', '1');
                    if (homeLocation) {
                        homeLocation.value = location.charAt(0).toUpperCase() + location.slice(1);
                    }
                }
            }, () => {
                localStorage.setItem('amra_location_prompted', '1');
            });
        } else if (localStorage.getItem('amra_location_detected')) {
            const savedLocation = localStorage.getItem('amra_location_detected');
            if (homeLocation) {
                homeLocation.value = savedLocation;
            }
        }

        if (homeSearchBtn) {
            homeSearchBtn.addEventListener('click', () => {
                const query = homeQuery ? homeQuery.value.trim() : '';
                const loc = homeLocation ? homeLocation.value : '';
                const type = homeType ? homeType.value : '';
                const listingType = document.getElementById('home-listing-type').value;
                
                let minVal = parseInt(minPriceInput.value);
                let maxVal = parseInt(maxPriceInput.value);
                if (minVal > maxVal) {
                    let temp = minVal;
                    minVal = maxVal;
                    maxVal = temp;
                }

                window.location.href = `{{ route('site.property') }}?q=${encodeURIComponent(query)}&location=${encodeURIComponent(loc)}&type=${encodeURIComponent(type)}&listing_type=${encodeURIComponent(listingType)}&min_price=${minVal}&max_price=${maxVal}`;
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
