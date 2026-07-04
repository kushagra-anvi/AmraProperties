@extends('layouts.site')

@section('title', 'Properties - Amra Property')
@section('meta_description', 'View all available properties by Amra Property.')

@section('content')
<div class="pt-24 pb-12 sm:pt-32 sm:pb-24">
<div class="max-w-7xl mx-auto px-4 md:px-6">
            
            <!-- Page Header -->
            <div class="text-center mb-8 sm:mb-10">
                <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-[10px] sm:text-xs mb-2 sm:mb-3">Premium Directory</p>
                <h1 class="text-2xl sm:text-3.5xl md:text-5xl font-serif font-bold text-amra-dark">All <span class="text-amra-primary italic">Properties</span></h1>
                <p class="text-gray-500 text-xs sm:text-sm mt-2 sm:mt-3">RERA-Approved, verified listings of Amra Property.</p>
            </div>

            <!-- Interactive Search & Filter Control Panel -->
            <form id="filter-form" method="GET" action="{{ route('site.property') }}" class="bg-white p-4 sm:p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col gap-4 mb-8 sm:mb-12 max-w-5xl mx-auto">

                <!-- Row 1: Search query input -->
                <div class="w-full relative">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Search Properties</label>
                    <div class="relative">
                        <input type="text" id="filter-query" name="q" value="{{ request('q') }}" placeholder="Search by name, builder, keywords..."
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl pl-10 pr-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm h-[46px]">
                        <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                    </div>
                </div>
                
                <!-- Row 2: Select Filters & Reset -->
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Location</label>
                        <select id="filter-location" name="location"
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[46px]">
                            <option value="" {{ !request('location') ? 'selected' : '' }}>All Locations</option>
                            @foreach($locations as $loc)
                                <option value="{{ strtolower($loc) }}" {{ strtolower(request('location')) === strtolower($loc) ? 'selected' : '' }}>{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Listing Type</label>
                        <select id="filter-listing-type" name="listing_type"
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[46px]">
                            <option value="" {{ !request('listing_type') ? 'selected' : '' }}>Any Purpose</option>
                            <option value="sale" {{ request('listing_type') === 'sale' ? 'selected' : '' }}>For Sale</option>
                            <option value="rent" {{ request('listing_type') === 'rent' ? 'selected' : '' }}>For Rent</option>
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Property Type</label>
                        <select id="filter-type" name="type"
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[46px]">
                            <option value="" {{ !request('type') ? 'selected' : '' }}>All Types</option>
                            <option value="flat" {{ request('type') === 'flat' ? 'selected' : '' }}>Flats / Apartments</option>
                            <option value="villa" {{ request('type') === 'villa' ? 'selected' : '' }}>Villas / Houses</option>
                            <option value="plot" {{ request('type') === 'plot' ? 'selected' : '' }}>Plots / Land</option>
                            <option value="commercial" {{ request('type') === 'commercial' ? 'selected' : '' }}>Commercial / Office</option>
                        </select>
                    </div>
                    <div class="w-full md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2 flex justify-between">
                            <span>Budget Range</span>
                            <span id="slider-budget-display" class="text-teal-600 font-bold">₹0 - ₹10 Cr+</span>
                        </label>
                        <div class="relative bg-slate-50 border border-slate-100 rounded-xl h-[46px] px-4 flex flex-col justify-center select-none">
                            <div class="relative w-full h-1 bg-gray-200 rounded-lg" style="margin-top: 2px;">
                                <div id="filter-slider-track" class="absolute h-1 bg-teal-500 rounded-lg" style="left: 0%; right: 0%;"></div>
                                <input type="range" id="filter-min-price" name="min_price" min="0" max="100000000" step="500000" value="{{ request('min_price', 0) }}" 
                                       class="absolute w-full appearance-none bg-transparent h-1 outline-none left-0 top-0 double-range-input">
                                <input type="range" id="filter-max-price" name="max_price" min="0" max="100000000" step="500000" value="{{ request('max_price', 100000000) }}" 
                                       class="absolute w-full appearance-none bg-transparent h-1 outline-none left-0 top-0 double-range-input">
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('site.property') }}" id="reset-filters"
                        class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2 text-sm h-[46px]">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Property Grid -->
            <div id="property-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">

                @forelse($properties as $property)
                    <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(11,193,178,0.12)] hover:-translate-y-2 transition-all duration-500 ease-out border border-gray-100 flex flex-col justify-between"
                        data-location="{{ $property->location_category }}" 
                        data-type="{{ $property->type_category }}" 
                        data-budget="{{ $property->budget_category }}">
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
                            <button type="button" data-compare-id="{{ $property->id }}" class="compare-toggle absolute bottom-4 right-4 h-9 rounded-xl bg-white/90 hover:bg-slate-900 text-slate-700 hover:text-white backdrop-blur-sm flex items-center justify-center gap-1.5 px-3 transition-all shadow-md active:scale-90 text-[10px] font-bold" aria-label="Compare property">
                                <i data-lucide="columns-3" class="w-3.5 h-3.5"></i> Compare
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
                                            {{ $property->configurations->pluck('name')->implode(', ') }}
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
                                @if($property->formatted_avg_price || $property->formatted_possession)
                                    <p class="text-[11px] text-slate-500 font-semibold mb-4">
                                        @if($property->formatted_avg_price)
                                            Avg. Price: {{ $property->formatted_avg_price }}
                                        @endif
                                        @if($property->formatted_avg_price && $property->formatted_possession)
                                            <span class="mx-1 text-slate-300">•</span>
                                        @endif
                                        @if($property->formatted_possession)
                                            Possession: {{ $property->formatted_possession }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <div>
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <h5 class="text-lg font-serif font-extrabold text-teal-600">{{ $property->formatted_price }}</h5>
                                        <p class="text-[9px] font-semibold text-slate-400 tracking-wide uppercase">Total Price</p>
                                    </div>
                                    <div class="flex gap-1.5">
                                        <button type="button"
                                            class="property-contact-trigger w-9 h-9 rounded-xl bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm active:scale-90 border border-emerald-100/50"
                                            title="Contact via WhatsApp"
                                            data-action="whatsapp"
                                            data-property-id="{{ $property->id }}"
                                            data-property-title="{{ e(html_entity_decode($property->title)) }}"
                                            data-endpoint="{{ route('site.property.enquiry', $property) }}">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg>
                                        </button>
                                        <button type="button"
                                            class="property-contact-trigger w-9 h-9 rounded-xl bg-teal-50 hover:bg-teal-500 text-teal-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm active:scale-90 border border-teal-100/50"
                                            title="Contact via Phone"
                                            data-action="phone"
                                            data-property-id="{{ $property->id }}"
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
                    <div class="col-span-full py-20 text-center bg-white border border-gray-100 rounded-3xl max-w-xl mx-auto shadow-sm">
                        <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i data-lucide="search-slash" class="w-8 h-8 text-amra-primary"></i>
                        </div>
                        @if(request('q') || request('location') || request('type') || request('budget'))
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2">No Matching Homes Found</h3>
                            <p class="text-gray-500 text-sm max-w-sm mb-6 mx-auto">Try adjusting your budget, location, or property type filters to find available premium listings.</p>
                            <a href="{{ route('site.property') }}" class="inline-block bg-teal-500 hover:bg-teal-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md active:scale-95">
                                Clear All Filters
                            </a>
                        @else
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-2">No Properties Available</h3>
                            <p class="text-gray-500 text-sm max-w-sm mx-auto">There are currently no active property listings in the database.</p>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination Controls -->
            {{ $properties->links('components.pagination') }}

            <div id="compare-bar" class="fixed bottom-5 z-50 hidden rounded-2xl bg-slate-950 text-white shadow-2xl border border-white/10 px-4 py-3 items-center gap-4" style="left: 0; right: 0; margin-left: auto; margin-right: auto; width: fit-content;">
                <span id="compare-count" class="text-xs font-bold">0 selected</span>
                <button type="button" id="compare-open" class="bg-amra-primary text-slate-950 px-4 py-2 rounded-xl text-xs font-extrabold">Compare</button>
                <button type="button" id="compare-clear" class="text-xs text-slate-300 hover:text-white">Clear</button>
            </div>

            <div id="property-contact-modal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
                <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
                    <div class="mb-5 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-teal-500">Unlock Contact</p>
                            <h3 id="contact-modal-title" class="mt-2 text-xl font-serif font-extrabold leading-snug text-slate-900">Get property details</h3>
                            <p class="mt-2 text-xs leading-relaxed text-slate-500">Share your details once. We will save your enquiry and reveal the seller contact.</p>
                        </div>
                        <button type="button" id="contact-modal-close" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>
                    </div>

                    <form id="property-contact-form" class="space-y-3">
                        @csrf
                        <input type="hidden" id="contact-action" value="whatsapp">
                        <input type="hidden" id="contact-endpoint" value="">
                        <label class="block">
                            <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Name</span>
                            <input id="contact-name" type="text" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Your name">
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Phone</span>
                            <input id="contact-phone" type="tel" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Your phone number">
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Email</span>
                            <input id="contact-email" type="email" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Optional">
                        </label>
                        <button id="contact-submit" type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-teal-500 px-5 py-3.5 text-sm font-extrabold text-white transition-all hover:bg-teal-600">
                            <i data-lucide="send" class="h-4 w-4"></i>
                            Save Enquiry & Show Contact
                        </button>
                    </form>

                    <div id="contact-unlocked" class="mt-4 hidden rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                        <p class="mb-3 text-xs font-bold text-emerald-800">Contact unlocked</p>
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <a id="contact-unlocked-phone" href="#" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-xs font-bold text-emerald-700">
                                <i data-lucide="phone" class="h-4 w-4"></i><span></span>
                            </a>
                            <a id="contact-unlocked-whatsapp" href="#" target="_blank" rel="noopener noreferrer" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3 text-xs font-bold text-white">
                                <i data-lucide="send" class="h-4 w-4"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</div>
@endsection

@section('scripts')
<script>
    const filterForm = document.getElementById('filter-form');
    const filterQuery = document.getElementById('filter-query');
    const filterLocation = document.getElementById('filter-location');
    const filterListingType = document.getElementById('filter-listing-type');
    const filterType = document.getElementById('filter-type');
    const minPriceInput = document.getElementById('filter-min-price');
    const maxPriceInput = document.getElementById('filter-max-price');
    const sliderBudgetDisplay = document.getElementById('slider-budget-display');
    const filterSliderTrack = document.getElementById('filter-slider-track');

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

        // Fill track
        if (filterSliderTrack) {
            const maxRange = parseInt(minPriceInput.max || 100000000);
            const percent1 = (minVal / maxRange) * 100;
            const percent2 = (maxVal / maxRange) * 100;
            filterSliderTrack.style.left = percent1 + "%";
            filterSliderTrack.style.width = (percent2 - percent1) + "%";
        }
        
        if (maxVal === 100000000) {
            sliderBudgetDisplay.textContent = `${formatPrice(minVal)} - ₹10 Cr+`;
        } else {
            sliderBudgetDisplay.textContent = `${formatPrice(minVal)} - ${formatPrice(maxVal)}`;
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
        
        minPriceInput.addEventListener('change', () => filterForm.submit());
        maxPriceInput.addEventListener('change', () => filterForm.submit());
    }

    if (filterLocation) filterLocation.addEventListener('change', () => filterForm.submit());
    if (filterListingType) filterListingType.addEventListener('change', () => filterForm.submit());
    if (filterType) filterType.addEventListener('change', () => filterForm.submit());

    const hasLocationFilter = new URLSearchParams(window.location.search).has('location');
    if (navigator.geolocation && !hasLocationFilter) {
        const savedLocation = localStorage.getItem('amra_location_detected');
        if (savedLocation) {
            filterLocation.value = savedLocation;
            filterForm.submit();
        } else if (!localStorage.getItem('amra_location_prompted')) {
            localStorage.setItem('amra_location_prompted', '1');
            navigator.geolocation.getCurrentPosition((position) => {
                const { latitude, longitude } = position.coords;
                const location = latitude > 18 && latitude < 20 && longitude > 72 && longitude < 74 ? 'mumbai'
                    : latitude > 25 && latitude < 28 && longitude > 79 && longitude < 82 ? 'lucknow'
                    : '';
                if (location) {
                    localStorage.setItem('amra_location_detected', location);
                    filterLocation.value = location;
                    filterForm.submit();
                }
            }, () => {
                // Silently handle location block
            });
        }
    }

    const compareBar = document.getElementById('compare-bar');
    const compareCount = document.getElementById('compare-count');
    const compareOpen = document.getElementById('compare-open');
    const compareClear = document.getElementById('compare-clear');
    const selectedCompare = new Set(JSON.parse(localStorage.getItem('amra_compare_properties') || '[]'));

    function renderCompareState() {
        document.querySelectorAll('.compare-toggle').forEach((button) => {
            const id = button.dataset.compareId;
            button.classList.toggle('bg-slate-900', selectedCompare.has(id));
            button.classList.toggle('text-white', selectedCompare.has(id));
            button.classList.toggle('bg-white/90', !selectedCompare.has(id));
            button.classList.toggle('text-slate-700', !selectedCompare.has(id));
        });
        if (selectedCompare.size > 0) {
            compareBar.classList.remove('hidden');
            compareBar.classList.add('flex');
        } else {
            compareBar.classList.add('hidden');
            compareBar.classList.remove('flex');
        }
        compareCount.textContent = `${selectedCompare.size} selected`;
        localStorage.setItem('amra_compare_properties', JSON.stringify([...selectedCompare]));
    }

    document.querySelectorAll('.compare-toggle').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.compareId;
            if (selectedCompare.has(id)) {
                selectedCompare.delete(id);
            } else if (selectedCompare.size < 4) {
                selectedCompare.add(id);
            } else {
                alert('You can compare up to 4 properties at a time.');
            }
            renderCompareState();
        });
    });
    if (compareOpen) {
        compareOpen.addEventListener('click', () => {
            const query = [...selectedCompare].map((id) => `properties[]=${encodeURIComponent(id)}`).join('&');
            window.location.href = `{{ route('site.compare') }}?${query}`;
        });
    }
    if (compareClear) {
        compareClear.addEventListener('click', () => {
            selectedCompare.clear();
            renderCompareState();
        });
    }
    renderCompareState();

    // Debounce search query input to auto-submit form after 500ms of inactivity
    if (filterQuery) {
        let debounceTimer;
        filterQuery.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
        
        // Also support submitting immediately if user presses Enter
        filterQuery.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(debounceTimer);
                filterForm.submit();
            }
        });

        // Focus search query input and place cursor at the end for interactive flow
        const valLen = filterQuery.value.length;
        if (valLen > 0) {
            filterQuery.focus();
            filterQuery.setSelectionRange(valLen, valLen);
        }
    }

    const contactModal = document.getElementById('property-contact-modal');
    const contactModalClose = document.getElementById('contact-modal-close');
    const contactForm = document.getElementById('property-contact-form');
    const contactSubmit = document.getElementById('contact-submit');
    const contactTitle = document.getElementById('contact-modal-title');
    const contactAction = document.getElementById('contact-action');
    const contactEndpoint = document.getElementById('contact-endpoint');
    const contactUnlocked = document.getElementById('contact-unlocked');
    const contactUnlockedPhone = document.getElementById('contact-unlocked-phone');
    const contactUnlockedWhatsapp = document.getElementById('contact-unlocked-whatsapp');

    function closeContactModal() {
        contactModal.classList.add('hidden');
        contactModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedName = localStorage.getItem('amra_enquiry_name');
        const savedPhone = localStorage.getItem('amra_enquiry_phone');
        const savedEmail = localStorage.getItem('amra_enquiry_email');

        if (savedName && savedPhone) {
            if (document.getElementById('contact-name')) document.getElementById('contact-name').value = savedName;
            if (document.getElementById('contact-phone')) document.getElementById('contact-phone').value = savedPhone;
            if (savedEmail && document.getElementById('contact-email')) document.getElementById('contact-email').value = savedEmail;
        }
    });

    document.querySelectorAll('.property-contact-trigger').forEach((button) => {
        button.addEventListener('click', () => {
            contactAction.value = button.dataset.action || 'whatsapp';
            contactEndpoint.value = button.dataset.endpoint;
            contactTitle.textContent = button.dataset.propertyTitle || 'Get property details';
            contactUnlocked.classList.add('hidden');
            contactSubmit.disabled = false;
            contactSubmit.innerHTML = '<i data-lucide="send" class="h-4 w-4"></i> Save Enquiry & Show Contact';
            contactModal.classList.remove('hidden');
            contactModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            if (window.lucide) window.lucide.createIcons();

            // Auto-submit if pre-filled details exist
            const savedName = localStorage.getItem('amra_enquiry_name');
            const savedPhone = localStorage.getItem('amra_enquiry_phone');
            if (savedName && savedPhone) {
                contactForm.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });
    });

    if (contactModalClose) contactModalClose.addEventListener('click', closeContactModal);
    if (contactModal) {
        contactModal.addEventListener('click', (event) => {
            if (event.target === contactModal) closeContactModal();
        });
    }

    if (contactForm) {
        contactForm.addEventListener('submit', (event) => {
            event.preventDefault();

            contactSubmit.disabled = true;
            contactSubmit.textContent = 'Saving...';

            const action = contactAction.value || 'whatsapp';
            const nameVal = document.getElementById('contact-name').value;
            const phoneVal = document.getElementById('contact-phone').value;
            const emailVal = document.getElementById('contact-email').value;

            fetch(contactEndpoint.value, {
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
                    source: `${action}_listing_card`,
                    intent: action,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.success) throw new Error(data.message || 'Unable to save enquiry.');

                    localStorage.setItem('amra_enquiry_name', nameVal);
                    localStorage.setItem('amra_enquiry_phone', phoneVal);
                    localStorage.setItem('amra_enquiry_email', emailVal);

                    contactUnlockedPhone.href = `tel:${data.phone}`;
                    contactUnlockedPhone.querySelector('span').textContent = data.phone;
                    contactUnlockedWhatsapp.href = data.whatsapp_url;
                    contactUnlocked.classList.remove('hidden');
                    contactSubmit.innerHTML = '<i data-lucide="check" class="h-4 w-4"></i> Enquiry Saved';
                    if (window.lucide) window.lucide.createIcons();

                    if (action === 'whatsapp') {
                        window.open(data.whatsapp_url, '_blank', 'noopener');
                    } else {
                        window.location.href = `tel:${data.phone}`;
                    }
                })
                .catch((error) => {
                    alert(error.message || 'Unable to save enquiry.');
                    contactSubmit.disabled = false;
                    contactSubmit.innerHTML = '<i data-lucide="send" class="h-4 w-4"></i> Save Enquiry & Show Contact';
                    if (window.lucide) window.lucide.createIcons();
                });
        });
    }
</script>
@endsection
