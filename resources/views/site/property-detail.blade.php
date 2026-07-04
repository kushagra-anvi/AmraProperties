@extends('layouts.site')

@section('title', html_entity_decode($property->title) . ' - Amra Property')
@section('meta_description', Str::limit(strip_tags($property->description), 150))

@section('seo_schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "Place",
      "@@id": "{{ route('site.property.show', $property->slug) }}#place",
      "name": "{{ addslashes(html_entity_decode($property->title)) }}",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "{{ addslashes($property->address ?: '') }}",
        "addressLocality": "{{ addslashes($property->city) }}",
        "addressRegion": "{{ addslashes($property->state ?: '') }}",
        "addressCountry": "IN"
      }
      <?php if($property->latitude && $property->longitude): ?>
      ,"geo": {
        "@@type": "GeoCoordinates",
        "latitude": "{{ $property->latitude }}",
        "longitude": "{{ $property->longitude }}"
      }
      <?php endif; ?>
    },
    {
      "@@type": "RealEstateListing",
      "@@id": "{{ route('site.property.show', $property->slug) }}#listing",
      "name": "{{ addslashes(html_entity_decode($property->title)) }}",
      "url": "{{ route('site.property.show', $property->slug) }}",
      "datePosted": "{{ $property->created_at ? $property->created_at->toIso8601String() : now()->toIso8601String() }}",
      "description": "{{ addslashes(Str::limit(strip_tags($property->description), 150)) }}",
      "about": {
        "@@id": "{{ route('site.property.show', $property->slug) }}#place"
      },
      "offers": {
        "@@type": "Offer",
        "price": "{{ $property->price }}",
        "priceCurrency": "INR",
        "availability": "https://schema.org/InStock"
      }
    },
    {
      "@@type": "BreadcrumbList",
      "@@id": "{{ route('site.property.show', $property->slug) }}#breadcrumb",
      "itemListElement": [
        {
          "@@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "{{ route('site.home') }}"
        },
        {
          "@@type": "ListItem",
          "position": 2,
          "name": "Properties",
          "item": "{{ route('site.property') }}"
        },
        {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{ addslashes(html_entity_decode($property->title)) }}",
          "item": "{{ route('site.property.show', $property->slug) }}"
        }
      ]
    }
  ]
}
</script>
@endsection

@section('content')
<div class="pt-24 pb-28 lg:pb-24 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        
        <!-- Breadcrumbs -->
        <nav class="flex text-slate-400 text-xs font-bold uppercase tracking-wider mb-6 gap-2">
            <a href="{{ route('site.home') }}" class="hover:text-teal-600 transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('site.property') }}" class="hover:text-teal-600 transition-colors">Properties</a>
            <span>/</span>
            <span class="text-slate-600 truncate max-w-[200px]">{{ html_entity_decode($property->title) }}</span>
        </nav>

        <!-- Gallery Slider -->
        <div class="relative bg-black rounded-3xl overflow-hidden shadow-md mb-10 group h-[35vh] sm:h-[50vh] md:h-[65vh] min-h-[250px] sm:min-h-[350px] md:min-h-[500px] max-h-[600px] md:max-h-[750px]">
            @php
                $images = is_array($property->gallery) ? $property->gallery : [];
                if (empty($images) && $property->featured_image) {
                    $images[] = $property->featured_image;
                }
                $videoUrls = is_array($property->video_urls) ? $property->video_urls : [];
                $videoEmbedUrl = function (string $url): string {
                    if (str_contains($url, 'youtube.com/watch')) {
                        parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);
                        return isset($query['v']) ? 'https://www.youtube.com/embed/' . $query['v'] : $url;
                    }
                    if (str_contains($url, 'youtu.be/')) {
                        return 'https://www.youtube.com/embed/' . trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
                    }
                    return $url;
                };
            @endphp

            @if(!empty($images))
                <!-- Slides Container -->
                <div id="slider-container" class="relative w-full h-full flex transition-transform duration-500 ease-in-out" style="width: {{ count($images) * 100 }}%">
                    @foreach($images as $img)
                        <div class="h-full shrink-0 select-none" style="width: {{ 100 / count($images) }}%">
                            <img src="{{ asset($img) }}" alt="{{ html_entity_decode($property->title) }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>

                @if(count($images) > 1)
                    <!-- Slider Actions -->
                    <button id="prev-slide" class="absolute left-6 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/80 hover:bg-white text-slate-800 rounded-full flex items-center justify-center shadow-lg hover:scale-105 active:scale-95 transition-all opacity-0 group-hover:opacity-100 duration-300">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <button id="next-slide" class="absolute right-6 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/80 hover:bg-white text-slate-800 rounded-full flex items-center justify-center shadow-lg hover:scale-105 active:scale-95 transition-all opacity-0 group-hover:opacity-100 duration-300">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                    <!-- Index Overlay -->
                    <div class="absolute bottom-6 left-6 bg-slate-900/80 text-white backdrop-blur-md px-3 py-1.5 rounded-lg text-xs font-bold border border-white/10 tracking-wide select-none">
                        <span id="current-slide-index">1</span> / {{ count($images) }}
                    </div>
                @endif
            @else
                <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                    <i data-lucide="image" class="w-16 h-16"></i>
                </div>
            @endif

            <div class="absolute top-6 left-6 flex flex-col items-start gap-2">
                @if($property->is_featured)
                    <span class="bg-teal-500/90 text-white backdrop-blur-md border border-teal-400/20 shadow-md px-3.5 py-1.5 rounded-lg uppercase tracking-wider text-[8px] font-bold">Featured Listing</span>
                @endif
                @if($property->is_rera_approved)
                    <span class="bg-slate-950/80 text-white text-[8px] font-semibold px-2.5 py-1.5 rounded-lg backdrop-blur-md flex items-center gap-1.5 border border-white/10 shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> RERA Approved: {{ $property->rera_number }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Content Split Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Left Column: Details -->
            <div class="lg:col-span-8 flex flex-col gap-8">
                <!-- Master Detail Container -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden divide-y divide-slate-100 text-left">
                    
                    <!-- Section 1: Overview Specs -->
                    <div class="p-5 sm:p-8">
                        <p class="text-slate-400 text-[10px] sm:text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-teal-500"></i>
                            {{ $property->address ?: ($property->city . ($property->state ? ', ' . $property->state : '')) }}
                        </p>
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-serif font-extrabold text-slate-800 leading-tight mb-4">
                            {{ html_entity_decode($property->title) }}
                        </h1>
                        <div class="h-px bg-slate-100 w-full mb-4"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Asking Price</p>
                                <h2 class="text-2xl sm:text-3xl font-serif font-extrabold text-teal-600">{{ $property->formatted_price }}</h2>
                            </div>
                            @if($property->developer_name)
                                <div class="text-right">
                                    <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Developer</p>
                                    <h4 class="text-base font-bold text-slate-700 flex items-center gap-1.5 justify-end">
                                        <i data-lucide="building-2" class="w-4 h-4 text-slate-400"></i>
                                        {{ html_entity_decode($property->developer_name) }}
                                    </h4>
                                </div>
                            @endif
                        </div>
                        @if($property->formatted_avg_price || $property->formatted_possession)
                            <div class="mt-5 rounded-2xl bg-slate-50 border border-slate-100 px-4 py-3 text-sm font-semibold text-slate-600">
                                @if($property->formatted_avg_price)
                                    Avg. Price: {{ $property->formatted_avg_price }}
                                @endif
                                @if($property->formatted_avg_price && $property->formatted_possession)
                                    <span class="mx-2 text-slate-300">•</span>
                                @endif
                                @if($property->formatted_possession)
                                    Possession: {{ $property->formatted_possession }}
                                @endif
                            </div>
                        @endif

                        <!-- Embedded Highlights Grid -->
                        <div class="h-px bg-slate-100 w-full my-6"></div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @if($property->bedrooms)
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="bed" class="w-4.5 h-4.5"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Bedrooms</p>
                                        <h4 class="text-xs font-extrabold text-slate-700">{{ $property->bedrooms }} Beds</h4>
                                    </div>
                                </div>
                            @endif
                            @if($property->bathrooms)
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="shower-head" class="w-4.5 h-4.5"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Bathrooms</p>
                                        <h4 class="text-xs font-extrabold text-slate-700">{{ $property->bathrooms }} Baths</h4>
                                    </div>
                                </div>
                            @endif
                            @if($property->area)
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="maximize-2" class="w-4.5 h-4.5"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Total Area</p>
                                        <h4 class="text-xs font-extrabold text-slate-700">{{ number_format($property->area) }} {{ str_replace('_', ' ', $property->area_unit) }}</h4>
                                    </div>
                                </div>
                            @endif
                            @if($property->configuration)
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="home" class="w-4.5 h-4.5"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Type</p>
                                        <h4 class="text-xs font-extrabold text-slate-700">{{ html_entity_decode(ucwords(strtolower($property->configuration))) }}</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Section 2: Configurations & Layouts -->
                    @if($property->configurations->isNotEmpty())
                        <div class="p-5 sm:p-8">
                            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <i data-lucide="layout" class="w-4 h-4 text-teal-500"></i> Available Configurations & Pricing
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm divide-y divide-slate-100">
                                    <thead>
                                        <tr class="text-left font-bold text-slate-400 text-xs uppercase tracking-wider">
                                            <th class="py-3 px-4">Layout Name</th>
                                            <th class="py-3 px-4">Bedrooms / Baths</th>
                                            <th class="py-3 px-4">Area</th>
                                            <th class="py-3 px-4 text-right">Pricing</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                                        @foreach($property->configurations as $config)
                                            <tr>
                                                <td class="py-3.5 px-4 text-teal-600 font-bold">{{ $config->name }}</td>
                                                <td class="py-3.5 px-4 text-slate-500">
                                                    {{ $config->bedrooms ? $config->bedrooms . ' BHK' : 'N/A' }} 
                                                    @if($config->bathrooms)
                                                         / {{ $config->bathrooms }} Bath
                                                    @endif
                                                </td>
                                                <td class="py-3.5 px-4 text-slate-500">
                                                    {{ $config->area ? number_format($config->area) . ' ' . str_replace('_', ' ', $config->area_unit) : 'N/A' }}
                                                </td>
                                                <td class="py-3.5 px-4 text-right text-slate-900 font-bold">
                                                    {{ $config->formatted_price }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Section 3: Description -->
                    @if($property->description)
                        <div class="p-5 sm:p-8 text-slate-600 leading-relaxed text-sm">
                            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <i data-lucide="align-left" class="w-4 h-4 text-teal-500"></i> Property Description
                            </h3>
                            <div class="property-description-content text-slate-600 text-sm md:text-base">
                                {!! html_entity_decode($property->description) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Section 4: Amenities -->
                    @php
                        $amenities = is_array($property->amenities) ? $property->amenities : [];
                    @endphp
                    @if(!empty($amenities))
                        <div class="p-5 sm:p-8">
                            <h3 class="text-base font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <i data-lucide="layout-grid" class="w-4 h-4 text-teal-500"></i> Top Amenities
                            </h3>
                            <div id="amenities-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-x-6 gap-y-7">
                                @foreach($amenities as $item)
                                    <div class="amenity-item {{ $loop->index >= 12 ? 'hidden' : '' }} flex flex-col items-center text-center gap-2 text-slate-700 text-xs font-semibold">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                                            <i data-lucide="badge-check" class="w-5 h-5"></i>
                                        </div>
                                        <span>{{ $item }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if(count($amenities) > 12)
                                <button type="button" id="amenities-toggle" class="mt-8 bg-teal-50 hover:bg-teal-100 text-teal-600 px-5 py-3 rounded-xl text-xs font-bold transition-colors">More</button>
                            @endif
                        </div>
                    @endif

                    <!-- Section 5: Videos -->
                    @if(!empty($videoUrls))
                        <div class="p-5 sm:p-8">
                            <h3 class="text-base font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <i data-lucide="video" class="w-4 h-4 text-teal-500"></i> Property Videos
                            </h3>
                            <div class="grid md:grid-cols-2 gap-5">
                                @foreach($videoUrls as $videoUrl)
                                    @php $embedUrl = $videoEmbedUrl($videoUrl); @endphp
                                    <div class="aspect-video rounded-2xl overflow-hidden bg-slate-100 border border-slate-100">
                                        @if(str_ends_with(strtolower(parse_url($embedUrl, PHP_URL_PATH) ?? ''), '.mp4'))
                                            <video controls class="w-full h-full object-cover"><source src="{{ $embedUrl }}" type="video/mp4"></video>
                                        @else
                                            <iframe src="{{ $embedUrl }}" title="{{ html_entity_decode($property->title) }} video" class="w-full h-full" allowfullscreen loading="lazy"></iframe>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Section 6: Map Location -->
                    @if($property->latitude && $property->longitude)
                        <div class="p-5 sm:p-8">
                            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <i data-lucide="map" class="w-4 h-4 text-teal-500"></i> Geographical Location
                            </h3>
                            @if(config('services.google_maps.key'))
                                <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>
                                <div id="property-map" class="h-80 w-full rounded-2xl border border-slate-100 overflow-hidden relative z-10"></div>
                            @else
                                <div class="h-80 w-full rounded-2xl border border-slate-100 overflow-hidden relative z-10">
                                    <iframe
                                        width="100%"
                                        height="100%"
                                        frameborder="0" style="border:0"
                                        src="https://maps.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}&hl=en&z=14&output=embed"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Sidebar Inquiry Form -->
            <div class="lg:col-span-4 lg:sticky lg:top-32 flex flex-col gap-6">
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm text-left">
                    <h3 class="text-lg font-serif font-bold text-slate-800 mb-1">Inquire About Listing</h3>
                    <p class="text-slate-400 text-xs mb-4">Our partners will contact you in under 15 minutes.</p>

                    @if($property->partners->isNotEmpty())
                        <div class="mb-5 p-3.5 bg-slate-50 border border-slate-100 rounded-2xl">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2">Listing Agents</p>
                            <div class="flex flex-col gap-1.5">
                                @foreach($property->partners as $agent)
                                    <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                                        <div class="w-1.5 h-1.5 rounded-full bg-teal-500"></div>
                                        <span>{{ $agent->company_name ?: $agent->contact_person }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div id="form-container">
                        <form id="contact-form" class="flex flex-col gap-4">
                            <div id="saved-user-badge" class="hidden mb-1 p-3 bg-teal-50/50 border border-teal-100 rounded-xl flex items-center justify-between text-xs font-semibold text-slate-700">
                                <div>
                                    <p class="text-[9px] font-bold text-teal-600 uppercase tracking-wider">Enquiring as</p>
                                    <p class="font-extrabold text-slate-800" id="saved-user-display"></p>
                                </div>
                                <button type="button" id="change-details-btn" class="text-teal-600 hover:text-teal-700 text-[10px] font-bold uppercase tracking-wider">Change</button>
                            </div>
                            <div id="input-fields-wrapper" class="flex flex-col gap-4">
                                <div>
                                    <label for="enquiry-name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Name</label>
                                    <input type="text" id="enquiry-name" name="name" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-xs text-slate-800" placeholder="Your name">
                                </div>
                                <div>
                                    <label for="enquiry-phone" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Phone</label>
                                    <input type="tel" id="enquiry-phone" name="phone" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-xs text-slate-800" placeholder="+91">
                                </div>
                                <div>
                                    <label for="email" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Email Address</label>
                                    <input type="email" id="email" name="email"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-xs text-slate-800" placeholder="john@example.com">
                                </div>
                            </div>
                            <div>
                                <label for="message" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Message</label>
                                <textarea id="message" name="message" rows="4" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-xs text-slate-800">Hi, I am interested in "{{ html_entity_decode($property->title) }}" located in {{ $property->city }}. Please share details.</textarea>
                            </div>
                            <button type="submit" id="submit-btn" data-source="property_form" class="w-full bg-amra-primary text-white py-3.5 rounded-xl font-bold text-xs hover:bg-teal-600 transition-all shadow-md flex items-center justify-center gap-1.5 active:scale-95 mt-2">
                                <i data-lucide="send" class="w-3.5 h-3.5"></i> Send Request & Show Contact
                            </button>
                        </form>
                    </div>

                    <div id="contact-reveal" class="hidden mt-5 rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                        <p class="text-xs text-emerald-700 font-bold mb-3">Contact unlocked</p>
                        <div class="flex flex-col gap-2">
                            <a id="revealed-phone" href="#" class="bg-white text-emerald-700 px-4 py-3 rounded-xl text-xs font-bold border border-emerald-100 flex items-center gap-2"><i data-lucide="phone" class="w-4 h-4"></i><span></span></a>
                            <a id="revealed-whatsapp" href="#" target="_blank" rel="noopener noreferrer" class="bg-emerald-500 text-white px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-2"><i data-lucide="send" class="w-4 h-4"></i>Open WhatsApp</a>
                        </div>
                    </div>

                    <div class="h-px bg-slate-100 my-6"></div>

                    <div class="flex flex-col gap-3">
                        <button type="button" data-source="whatsapp_reveal" class="contact-gate-trigger w-full bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white py-3 rounded-xl font-bold text-xs border border-emerald-100 flex items-center justify-center gap-2 transition-all active:scale-[0.98]">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg> WhatsApp Inquiry
                        </button>
                        <button type="button" data-source="phone_reveal" class="contact-gate-trigger w-full bg-teal-50 hover:bg-teal-500 text-teal-600 hover:text-white py-3 rounded-xl font-bold text-xs border border-teal-100 flex items-center justify-center gap-2 transition-all active:scale-[0.98]">
                            <i data-lucide="phone" class="w-4 h-4"></i> Call Concierge Desk
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Sellers -->
        @if(isset($recommendedSellers) && $recommendedSellers->isNotEmpty())
            <div class="h-px bg-slate-200 w-full my-16"></div>
            <div>
                <h3 class="text-2xl font-serif font-bold text-slate-800 mb-2">Recommended sellers</h3>
                <p class="text-slate-500 text-sm mb-8">Sellers with complete knowledge about locality</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recommendedSellers as $seller)
                        <div class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                            <div class="px-4 py-3 bg-slate-100 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-300 text-white flex items-center justify-center font-bold text-xs">{{ Str::of($seller->company_name)->explode(' ')->map(fn($w) => Str::substr($w, 0, 1))->take(2)->implode('') }}</div>
                                <div class="font-bold text-slate-800 text-sm">{{ $seller->company_name }}</div>
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-slate-600 font-semibold mb-2">
                                    {{ max(1, $seller->created_at?->diffInYears(now()) ?: 1) }} Yrs Experience
                                    <span class="mx-2 text-slate-300">|</span>
                                    {{ $seller->total_listings ?? $seller->properties_count ?? 0 }} Total listings
                                </p>
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @foreach(array_slice($seller->service_areas ?? [$seller->city], 0, 3) as $area)
                                        <span class="bg-slate-100 text-slate-500 rounded-lg px-2 py-1 text-[10px] font-semibold">{{ $area }}</span>
                                    @endforeach
                                </div>
                                <button type="button" data-source="seller_reveal" data-partner-id="{{ $seller->id }}" class="contact-gate-trigger w-full rounded-lg border border-indigo-500 text-indigo-600 hover:bg-indigo-500 hover:text-white px-4 py-2 text-xs font-bold flex items-center justify-center gap-1.5">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i> Show Contact
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Compare with similar properties -->
        @if($relatedProperties->isNotEmpty())
            <div class="h-px bg-slate-200 w-full my-16"></div>
            <div class="text-left">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-serif font-bold text-slate-800">Compare with similar properties</h3>
                        <p class="text-slate-500 text-xs mt-1">Core buying signals, pricing, and layout specifications side-by-side.</p>
                    </div>
                </div>

                <!-- Desktop: Sticky Current Property Left / Scrollable Track Right. Mobile: Single Continuous Scroll Track -->
                <div class="flex flex-row overflow-x-auto gap-4 items-stretch pb-4 snap-x snap-mandatory scrollbar-thin lg:overflow-visible lg:pb-0 lg:snap-none lg:scrollbar-none">
                    
                    <!-- Card 1: Current Property (Pinned Left on Desktop, scrolling on mobile) -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm flex flex-col p-4 relative min-w-[310px] w-[310px] shrink-0 snap-start z-10">
                        <!-- Highlight Badge -->
                        <span class="absolute top-4 left-4 bg-teal-600 text-white text-[8px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider z-20 shadow-sm">Current Property</span>
                        
                        <!-- Image Container -->
                        <div class="relative h-32 rounded-2xl overflow-hidden mb-3 shrink-0 bg-slate-100">
                            <img src="{{ $property->featured_image ? asset($property->featured_image) : asset('assets/images/clean_hero.png') }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                        </div>

                        <!-- Content Details -->
                        <div class="flex flex-col flex-grow text-left">
                            <p class="text-[9px] font-bold text-teal-600 uppercase tracking-wider mb-0.5">
                                {{ $property->formatted_possession ?: 'Ready to Move' }}
                            </p>
                            <h3 class="font-serif font-extrabold text-slate-800 text-base mb-0.5 line-clamp-1 leading-snug">
                                <span class="text-slate-800">{{ $property->title }}</span>
                            </h3>
                            <p class="text-xs text-slate-400 font-semibold mb-3 line-clamp-1 flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                {{ $property->city }}
                            </p>

                            <div class="h-px bg-slate-100 w-full mb-3"></div>

                            <!-- BHK Type Display -->
                            <div class="mb-2 text-left">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">BHK Type</p>
                                <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700">
                                    {{ $property->configuration ?: 'Apartment' }}
                                </div>
                            </div>

                            <!-- Carpet Size Display -->
                            <div class="mb-2 text-left">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Carpet Size</p>
                                <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700">
                                    {{ $property->area ? number_format($property->area) . ' ' . str_replace('_', ' ', $property->area_unit) : 'N/A' }}
                                </div>
                            </div>

                            <!-- RERA Status Display -->
                            <div class="mb-2 text-left">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">RERA Status</p>
                                <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold {{ $property->is_rera_approved ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $property->is_rera_approved ? 'Approved' : 'Not shown' }}
                                </div>
                            </div>

                            <!-- Average Price Display (If exists) -->
                            @if($property->formatted_avg_price)
                                <div class="mb-2 text-left">
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Avg. Rate</p>
                                    <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700">
                                        {{ $property->formatted_avg_price }}
                                    </div>
                                </div>
                            @endif

                            <!-- Key Amenities Display -->
                            <div class="mb-3 text-left">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Key Amenities</p>
                                <div class="flex flex-wrap gap-1">
                                    @php $p_amenities = is_array($property->amenities) ? $property->amenities : []; @endphp
                                    @if(!empty($p_amenities))
                                        @foreach(array_slice($p_amenities, 0, 2) as $amenity)
                                            <span class="bg-slate-50 border border-slate-100 text-[10px] font-bold text-slate-600 px-2 py-1 rounded-lg">{{ $amenity }}</span>
                                        @endforeach
                                        @if(count($p_amenities) > 2)
                                            <span class="bg-slate-100 text-[9px] font-extrabold text-slate-500 px-1.5 py-1 rounded-lg">+{{ count($p_amenities) - 2 }}</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400 font-medium pl-1">None specified</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-grow"></div>

                            <!-- Price & Action Trigger -->
                            <div class="mt-2 pt-2 border-t border-slate-100 text-left">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Asking Price</p>
                                <h4 class="text-xl font-serif font-extrabold text-teal-600 mb-2">{{ $property->formatted_price }}</h4>
                                <div class="flex gap-2">
                                    <button type="button" onclick="scrollToEnquiryForm()" class="flex-grow bg-teal-500 hover:bg-teal-600 text-white py-2 rounded-xl font-bold text-xs transition-all active:scale-95 flex items-center justify-center gap-1.5 shadow-md">
                                        <i data-lucide="phone" class="w-3.5 h-3.5"></i> Contact Seller
                                    </button>
                                    <button type="button" data-compare-id="{{ $property->id }}" class="compare-toggle-btn p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-500 hover:text-slate-800 transition-all flex items-center justify-center shrink-0" aria-label="Compare property">
                                        <i data-lucide="columns-3" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scroll track wrapper: contents on mobile, flex scroll on desktop -->
                    <div class="contents lg:flex lg:flex-row lg:gap-4 lg:overflow-x-auto lg:pb-4 lg:snap-x lg:snap-mandatory lg:scrollbar-thin lg:flex-grow">
                        @foreach($relatedProperties as $rel)
                            <!-- Related Property Card -->
                            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm flex flex-col p-4 relative group hover:shadow-md transition-shadow min-w-[310px] w-[310px] snap-start shrink-0">
                                <!-- Image Container -->
                                <div class="relative h-32 rounded-2xl overflow-hidden mb-3 shrink-0 bg-slate-100">
                                    <a href="{{ route('site.property.show', $rel->slug) }}">
                                        <img src="{{ $rel->featured_image ? asset($rel->featured_image) : asset('assets/images/clean_hero.png') }}" alt="{{ $rel->title }}" class="w-full h-full object-cover card-zoom-img group-hover:scale-105 transition-transform duration-500">
                                    </a>
                                </div>

                                <!-- Content Details -->
                                <div class="flex flex-col flex-grow text-left">
                                    <p class="text-[9px] font-bold text-teal-600 uppercase tracking-wider mb-0.5">
                                        {{ $rel->formatted_possession ?: 'Ready to Move' }}
                                    </p>
                                    <h3 class="font-serif font-extrabold text-slate-800 text-base mb-0.5 line-clamp-1 leading-snug">
                                        <a href="{{ route('site.property.show', $rel->slug) }}" class="hover:text-teal-600 transition-colors">{{ $rel->title }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-400 font-semibold mb-3 line-clamp-1 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                        {{ $rel->city }}
                                    </p>

                                    <div class="h-px bg-slate-100 w-full mb-3"></div>

                                    <!-- BHK Type Display -->
                                    <div class="mb-2 text-left">
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">BHK Type</p>
                                        <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700">
                                            {{ $rel->configuration ?: 'Apartment' }}
                                        </div>
                                    </div>

                                    <!-- Carpet Size Display -->
                                    <div class="mb-2 text-left">
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Carpet Size</p>
                                        <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700">
                                            {{ $rel->area ? number_format($rel->area) . ' ' . str_replace('_', ' ', $rel->area_unit) : 'N/A' }}
                                        </div>
                                    </div>

                                    <!-- RERA Status Display -->
                                    <div class="mb-2 text-left">
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">RERA Status</p>
                                        <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold {{ $rel->is_rera_approved ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ $rel->is_rera_approved ? 'Approved' : 'Not shown' }}
                                        </div>
                                    </div>

                                    <!-- Average Price Display (If exists) -->
                                    @if($rel->formatted_avg_price)
                                        <div class="mb-2 text-left">
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Avg. Rate</p>
                                            <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700">
                                                {{ $rel->formatted_avg_price }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Key Amenities Display -->
                                    <div class="mb-3 text-left">
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Key Amenities</p>
                                        <div class="flex flex-wrap gap-1">
                                            @php $rel_amenities = is_array($rel->amenities) ? $rel->amenities : []; @endphp
                                            @if(!empty($rel_amenities))
                                                @foreach(array_slice($rel_amenities, 0, 2) as $amenity)
                                                    <span class="bg-slate-50 border border-slate-100 text-[10px] font-bold text-slate-600 px-2 py-1 rounded-lg">{{ $amenity }}</span>
                                                @endforeach
                                                @if(count($rel_amenities) > 2)
                                                    <span class="bg-slate-100 text-[9px] font-extrabold text-slate-500 px-1.5 py-1 rounded-lg">+{{ count($rel_amenities) - 2 }}</span>
                                                @endif
                                            @else
                                                <span class="text-xs text-slate-400 font-medium pl-1">None specified</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex-grow"></div>

                                    <!-- Price & Action Trigger -->
                                    <div class="mt-2 pt-2 border-t border-slate-100 text-left">
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Asking Price</p>
                                        <h4 class="text-xl font-serif font-extrabold text-teal-600 mb-2">{{ $rel->formatted_price }}</h4>
                                        <div class="flex gap-2">
                                            <a href="{{ route('site.property.show', $rel->slug) }}" class="flex-grow bg-white hover:bg-teal-500 text-teal-600 hover:text-white border border-teal-200 hover:border-teal-500 py-2 rounded-xl font-bold text-xs transition-all active:scale-95 flex items-center justify-center gap-1.5 shadow-sm">
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Details
                                            </a>
                                            <button type="button" data-compare-id="{{ $rel->id }}" class="compare-toggle-btn p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-500 hover:text-slate-800 transition-all flex items-center justify-center shrink-0" aria-label="Compare property">
                                                <i data-lucide="columns-3" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Floating Compare Bar -->
            <div id="compare-bar" class="fixed bottom-24 md:bottom-5 z-[120] hidden rounded-2xl bg-slate-950 text-white shadow-2xl border border-white/10 px-4 py-3 items-center gap-4" style="left: 0; right: 0; margin-left: auto; margin-right: auto; width: fit-content;">
                <span id="compare-count" class="text-xs font-bold">0 selected</span>
                <button type="button" id="compare-open" class="bg-amra-primary text-slate-950 px-4 py-2 rounded-xl text-xs font-extrabold hover:bg-teal-300 transition-colors">Compare</button>
                <button type="button" id="compare-clear" class="text-xs text-slate-300 hover:text-white">Clear</button>
            </div>
        @endif

    </div>
</div>

<!-- Floating Bottom Mobile Bar -->
<div class="fixed bottom-0 left-0 right-0 z-[100] bg-white/95 backdrop-blur-md border-t border-slate-200/80 px-6 py-4 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] lg:hidden flex justify-between items-center animate-[slideInUp_0.4s_ease-out_forwards]">
    <div>
        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Asking Price</p>
        <p class="text-xl font-serif font-extrabold text-teal-600">{{ $property->formatted_price }}</p>
    </div>
    <button type="button" onclick="scrollToEnquiryForm()"
        class="bg-amra-primary hover:bg-teal-600 text-white px-6 py-3 rounded-xl font-bold text-xs shadow-md active:scale-95 transition-all flex items-center gap-1.5">
        <i data-lucide="send" class="w-3.5 h-3.5"></i> Enquire Now
    </button>
</div>
@endsection

@section('scripts')
@if($property->latitude && $property->longitude && config('services.google_maps.key'))
<script>
    // Google Maps JS SDK Initialization
    function initMap() {
        const lat = {{ $property->latitude }};
        const lng = {{ $property->longitude }};
        const myLatLng = { lat: lat, lng: lng };
        
        const map = new google.maps.Map(document.getElementById('property-map'), {
            zoom: 14,
            center: myLatLng,
            scrollwheel: false
        });

        new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: "{{ html_entity_decode($property->title) }}"
        });
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof google !== 'undefined' && google.maps) {
            initMap();
        }
    });
</script>
@endif

@if(!empty($images) && count($images) > 1)
<script>
    // Custom Slider Logic
    document.addEventListener('DOMContentLoaded', () => {
        const prevBtn = document.getElementById('prev-slide');
        const nextBtn = document.getElementById('next-slide');
        const container = document.getElementById('slider-container');
        const indexOverlay = document.getElementById('current-slide-index');
        
        let currentIndex = 0;
        const slidesCount = {{ count($images) }};

        const updateSlider = () => {
            const offset = currentIndex * -100 / slidesCount;
            container.style.transform = `translateX(${offset}%)`;
            if (indexOverlay) indexOverlay.innerText = currentIndex + 1;
        };

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex > 0) ? currentIndex - 1 : slidesCount - 1;
                updateSlider();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex < slidesCount - 1) ? currentIndex + 1 : 0;
                updateSlider();
            });
        }
    });
</script>
@endif

	<script>
	    const form = document.getElementById('contact-form');
	    const formContainer = document.getElementById('form-container');
	    const submitBtn = document.getElementById('submit-btn');
	    const contactReveal = document.getElementById('contact-reveal');
	    const revealedPhone = document.getElementById('revealed-phone');
	    const revealedWhatsapp = document.getElementById('revealed-whatsapp');

	    const savedBadge = document.getElementById('saved-user-badge');
	    const savedDisplay = document.getElementById('saved-user-display');
	    const fieldsWrapper = document.getElementById('input-fields-wrapper');
	    const changeDetailsBtn = document.getElementById('change-details-btn');

	    function initSavedDetails() {
	        const name = localStorage.getItem('amra_enquiry_name');
	        const phone = localStorage.getItem('amra_enquiry_phone');
	        const email = localStorage.getItem('amra_enquiry_email');

	        if (name && phone) {
	            document.getElementById('enquiry-name').value = name;
	            document.getElementById('enquiry-phone').value = phone;
	            if (email) document.getElementById('email').value = email;

	            document.getElementById('enquiry-name').removeAttribute('required');
	            document.getElementById('enquiry-phone').removeAttribute('required');

	            if (savedDisplay) savedDisplay.textContent = `${name} (${phone})`;
	            if (savedBadge) {
	                savedBadge.classList.remove('hidden');
	                savedBadge.classList.add('flex');
	            }
	            if (fieldsWrapper) fieldsWrapper.classList.add('hidden');
	        }
	    }

	    if (changeDetailsBtn) {
	        changeDetailsBtn.addEventListener('click', () => {
	            document.getElementById('enquiry-name').setAttribute('required', 'required');
	            document.getElementById('enquiry-phone').setAttribute('required', 'required');
	            if (savedBadge) {
	                savedBadge.classList.add('hidden');
	                savedBadge.classList.remove('flex');
	            }
	            if (fieldsWrapper) fieldsWrapper.classList.remove('hidden');
	        });
	    }

	    document.addEventListener('DOMContentLoaded', initSavedDetails);

	    function collectEnquiryPayload(source = 'property_form', partnerId = null) {
	        return {
	            name: document.getElementById('enquiry-name').value,
	            phone: document.getElementById('enquiry-phone').value,
	            email: document.getElementById('email').value,
	            message: document.getElementById('message').value,
	            source,
	            intent: source.includes('phone') ? 'phone' : (source.includes('whatsapp') ? 'whatsapp' : 'details'),
	            partner_id: partnerId
	        };
	    }

	    function revealContact(data) {
	        if (!contactReveal || !revealedPhone || !revealedWhatsapp) return;
	        revealedPhone.href = `tel:${data.phone}`;
	        revealedPhone.querySelector('span').textContent = data.phone;
	        revealedWhatsapp.href = data.whatsapp_url;
	        contactReveal.classList.remove('hidden');
	        contactReveal.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
	        if (window.lucide) window.lucide.createIcons();
	    }

	    function submitPropertyEnquiry(source = 'property_form', partnerId = null) {
	        const payload = collectEnquiryPayload(source, partnerId);
	        if (!payload.name || !payload.phone) {
	            alert('Please enter your name and phone number first.');
	            formContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
	            return Promise.reject(new Error('missing required contact fields'));
	        }

	        return fetch('{{ route('site.property.enquiry', $property) }}', {
	            method: 'POST',
	            headers: {
	                'Content-Type': 'application/json',
	                'X-CSRF-TOKEN': '{{ csrf_token() }}',
	                'Accept': 'application/json',
	            },
	            body: JSON.stringify(payload)
	        })
	        .then(response => response.json())
	        .then(data => {
	            if (!data.success) {
	                throw new Error(data.message || 'Unable to save enquiry.');
	            }
	            
	            localStorage.setItem('amra_enquiry_name', payload.name);
	            localStorage.setItem('amra_enquiry_phone', payload.phone);
	            localStorage.setItem('amra_enquiry_email', payload.email);

	            revealContact(data);
	            return data;
	        });
	    }

	    if (form) {
	        form.addEventListener('submit', (e) => {
	            e.preventDefault();
	            submitBtn.disabled = true;
	            submitBtn.innerHTML = 'Sending...';

	            submitPropertyEnquiry(submitBtn.dataset.source || 'property_form')
	                .then(() => {
	                    formContainer.classList.add('transition-all', 'duration-500', 'opacity-0');
	                    setTimeout(() => {
	                        formContainer.innerHTML = `
	                            <div class="text-center py-8 flex flex-col items-center justify-center">
	                                <div class="w-14 h-14 bg-emerald-500/10 rounded-full flex items-center justify-center mb-4 shadow-inner">
	                                    <i data-lucide="check" class="w-7 h-7 text-emerald-500"></i>
	                                </div>
	                                <h4 class="text-lg font-serif font-extrabold text-slate-800 mb-2">Request Sent!</h4>
	                                <p class="text-slate-400 text-xs leading-relaxed mb-4">Your enquiry has been saved. Contact details are unlocked below.</p>
	                            </div>
	                        `;
	                        if (window.lucide) window.lucide.createIcons();
	                        formContainer.classList.remove('opacity-0');
	                        formContainer.classList.add('opacity-100');
	                    }, 300);
	                })
	                .catch((err) => {
	                    if (err.message !== 'missing required contact fields') {
	                        alert(err.message || 'Unable to save enquiry.');
	                    }
	                    submitBtn.disabled = false;
	                    submitBtn.innerHTML = '<i data-lucide="send" class="w-3.5 h-3.5"></i> Send Request & Show Contact';
	                    if (window.lucide) window.lucide.createIcons();
	                });
	        });
	    }

	    document.querySelectorAll('.contact-gate-trigger').forEach((button) => {
	        button.addEventListener('click', () => {
	            submitPropertyEnquiry(button.dataset.source || 'contact_reveal', button.dataset.partnerId || null)
	                .then((data) => {
	                    if ((button.dataset.source || '').includes('whatsapp')) {
	                        window.open(data.whatsapp_url, '_blank', 'noopener');
	                    }
	                })
	                .catch((err) => {
	                    if (err.message !== 'missing required contact fields') {
	                        alert(err.message || 'Unable to save enquiry.');
	                    }
	                });
	        });
	    });

	    const amenitiesToggle = document.getElementById('amenities-toggle');
	    if (amenitiesToggle) {
	        amenitiesToggle.addEventListener('click', () => {
	            const currentlyCollapsed = document.querySelectorAll('.amenity-item.hidden').length > 0;
	            document.querySelectorAll('.amenity-item').forEach((item, index) => {
	                if (index >= 12) item.classList.toggle('hidden', !currentlyCollapsed);
	            });
	            amenitiesToggle.textContent = currentlyCollapsed ? 'Less' : 'More';
	        });
	    }

        function scrollToEnquiryForm() {
            const formContainer = document.getElementById('form-container');
            if (formContainer) {
                formContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                const card = formContainer.closest('.bg-white');
                if (card) {
                    card.classList.add('ring-2', 'ring-teal-500', 'ring-offset-2');
                    setTimeout(() => {
                        card.classList.remove('ring-2', 'ring-teal-500', 'ring-offset-2');
                    }, 2000);
                }
                const nameInput = document.getElementById('enquiry-name');
                if (nameInput) {
                    setTimeout(() => nameInput.focus(), 500);
                }
            }
        }

        // Compare Properties Logic
        const compareBar = document.getElementById('compare-bar');
        const compareCount = document.getElementById('compare-count');
        const compareOpen = document.getElementById('compare-open');
        const compareClear = document.getElementById('compare-clear');
        const selectedCompare = new Set(JSON.parse(localStorage.getItem('amra_compare_properties') || '[]'));

        function renderCompareState() {
            if (!compareBar) return;
            document.querySelectorAll('.compare-toggle-btn').forEach((button) => {
                const id = button.dataset.compareId;
                const isSelected = selectedCompare.has(String(id)) || selectedCompare.has(Number(id));
                
                button.classList.toggle('bg-slate-900', isSelected);
                button.classList.toggle('text-white', isSelected);
                button.classList.toggle('border-slate-900', isSelected);
                button.classList.toggle('bg-white', !isSelected);
                button.classList.toggle('text-slate-500', !isSelected);
                button.classList.toggle('border-slate-200', !isSelected);
            });

            if (selectedCompare.size > 0) {
                compareBar.classList.remove('hidden');
                compareBar.classList.add('flex');
            } else {
                compareBar.classList.add('hidden');
                compareBar.classList.remove('flex');
            }
            if (compareCount) compareCount.textContent = `${selectedCompare.size} selected`;
            localStorage.setItem('amra_compare_properties', JSON.stringify([...selectedCompare]));
        }

        document.querySelectorAll('.compare-toggle-btn').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                const id = String(button.dataset.compareId);
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
	</script>

<style>
.property-description-content h1, .property-description-content h2, .property-description-content h3, .property-description-content h4, .property-description-content h5 {
    font-family: 'Outfit', sans-serif;
    color: #0F172A;
    font-weight: 700;
    margin-top: 1.75rem;
    margin-bottom: 0.75rem;
    line-height: 1.3;
}
.property-description-content h1 { font-size: 1.5rem; }
.property-description-content h2 { font-size: 1.35rem; }
.property-description-content h3 { font-size: 1.2rem; }
.property-description-content h4 { font-size: 1.1rem; }
.property-description-content h5 { font-size: 1rem; }

.property-description-content p {
    margin-bottom: 1.25rem;
    line-height: 1.75;
}
.property-description-content ul {
    list-style-type: disc;
    padding-left: 1.5rem;
    margin-bottom: 1.25rem;
}
.property-description-content ol {
    list-style-type: decimal;
    padding-left: 1.5rem;
    margin-bottom: 1.25rem;
}
.property-description-content li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}
.property-description-content strong {
    color: #0F172A;
    font-weight: 700;
}
.property-description-content a {
    color: #0bc1b2;
    text-decoration: underline;
    font-weight: 600;
}
.property-description-content a:hover {
    color: #09a296;
}
.property-description-content blockquote {
    border-left: 4px solid #0bc1b2;
    padding-left: 1rem;
    font-style: italic;
    color: #475569;
    margin: 1.5rem 0;
}
</style>
@endsection
