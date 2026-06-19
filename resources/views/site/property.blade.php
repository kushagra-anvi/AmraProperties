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
            <form id="filter-form" method="GET" action="{{ route('site.property') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col gap-4 mb-12 max-w-5xl mx-auto">
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
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Location</label>
                        <select id="filter-location" name="location"
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[46px]">
                            <option value="all" {{ request('location') === 'all' ? 'selected' : '' }}>All Locations</option>
                            <option value="mumbai" {{ request('location') === 'mumbai' ? 'selected' : '' }}>Mumbai</option>
                            <option value="lucknow" {{ request('location') === 'lucknow' ? 'selected' : '' }}>Lucknow</option>
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Property Type</label>
                        <select id="filter-type" name="type"
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[46px]">
                            <option value="all" {{ request('type') === 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="flat" {{ request('type') === 'flat' ? 'selected' : '' }}>Flats / Apartments</option>
                            <option value="villa" {{ request('type') === 'villa' ? 'selected' : '' }}>Villas</option>
                            <option value="plot" {{ request('type') === 'plot' ? 'selected' : '' }}>Plots</option>
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Budget Range</label>
                        <select id="filter-budget" name="budget"
                            class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm h-[46px]">
                            <option value="all" {{ request('budget') === 'all' ? 'selected' : '' }}>All Budgets</option>
                            <option value="25-50" {{ request('budget') === '25-50' ? 'selected' : '' }}>₹25 Lac - ₹50 Lac</option>
                            <option value="50-100" {{ request('budget') === '50-100' ? 'selected' : '' }}>₹50 Lac - ₹1 Cr</option>
                            <option value="100+" {{ request('budget') === '100+' ? 'selected' : '' }}>₹1 Cr+</option>
                        </select>
                    </div>
                    <a href="{{ route('site.property') }}" id="reset-filters"
                        class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2 text-sm h-[46px]">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Property Grid -->
            <div id="property-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @forelse($properties as $property)
                    <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(11,193,178,0.12)] hover:-translate-y-2 transition-all duration-500 ease-out border border-gray-100 flex flex-col justify-between"
                        data-location="{{ $property->location_category }}" 
                        data-type="{{ $property->type_category }}" 
                        data-budget="{{ $property->budget_category }}">
                        <div class="relative h-60 overflow-hidden">
                            <a href="{{ route('site.property.show', $property->slug) }}" class="block w-full h-full">
                                <img src="{{ $property->featured_image ? asset($property->featured_image) : asset('assets/images/prop1.webp') }}" alt="{{ html_entity_decode($property->title) }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                            </a>
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($property->is_featured)
                                    <span class="bg-teal-500/90 text-white backdrop-blur-md border border-teal-400/20 shadow-sm px-3.5 py-1.5 rounded-full uppercase tracking-wider text-[8px] font-bold">Featured Listing</span>
                                @endif
                                @if($property->is_rera_approved)
                                    <span class="bg-slate-950/80 text-white text-[8px] font-semibold px-2.5 py-1.5 rounded-md backdrop-blur-md flex items-center gap-1.5 border border-white/10 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> RERA Approved: {{ $property->rera_number }}
                                    </span>
                                @endif
                            </div>
                            <button class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/80 hover:bg-white backdrop-blur-sm text-slate-500 hover:text-rose-500 flex items-center justify-center transition-all shadow-md active:scale-90" aria-label="Add to Wishlist">
                                <i data-lucide="heart" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <p class="text-slate-400 text-[11px] font-medium tracking-wide flex items-center gap-1 mb-2">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-500 shrink-0"></i> 
                                    {{ Str::limit(ucwords(strtolower($property->address ?: ($property->city . ($property->state ? ', ' . $property->state : '')))), 35) }}
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
                                    @if($property->bedrooms)
                                        <span class="bg-slate-50 text-slate-600 border border-slate-100/80 rounded-lg py-1 px-2.5 text-[10px] font-semibold flex items-center gap-1 shadow-sm">
                                            <i data-lucide="bed" class="w-3.5 h-3.5 text-slate-400"></i>
                                            {{ $property->bedrooms }} Beds
                                        </span>
                                    @endif
                                    @if($property->bathrooms)
                                        <span class="bg-slate-50 text-slate-600 border border-slate-100/80 rounded-lg py-1 px-2.5 text-[10px] font-semibold flex items-center gap-1 shadow-sm">
                                            <i data-lucide="shower-head" class="w-3.5 h-3.5 text-slate-400"></i>
                                            {{ $property->bathrooms }} Baths
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
                                        <a href="https://wa.me/919999999999?text=Hi,%20I%20am%20interested%20in%20{{ rawurlencode(html_entity_decode($property->title)) }}" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm active:scale-90 border border-emerald-100/50" title="Contact via WhatsApp">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg>
                                        </a>
                                        <a href="tel:+919999999999" class="w-9 h-9 rounded-xl bg-teal-50 hover:bg-teal-500 text-teal-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm active:scale-90 border border-teal-100/50" title="Contact via Phone">
                                            <i data-lucide="phone" class="w-4 h-4"></i>
                                        </a>
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

        </div>
</div>
@endsection

@section('scripts')
<script>
    const filterForm = document.getElementById('filter-form');
    const filterQuery = document.getElementById('filter-query');
    const filterLocation = document.getElementById('filter-location');
    const filterType = document.getElementById('filter-type');
    const filterBudget = document.getElementById('filter-budget');

    // Auto-submit form when selects are changed
    if (filterLocation) filterLocation.addEventListener('change', () => filterForm.submit());
    if (filterType) filterType.addEventListener('change', () => filterForm.submit());
    if (filterBudget) filterBudget.addEventListener('change', () => filterForm.submit());

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
</script>
@endsection
