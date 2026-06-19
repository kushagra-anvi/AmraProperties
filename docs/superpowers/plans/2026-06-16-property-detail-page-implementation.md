# Property Detail Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Create individual property detail pages (`/property/{slug}`) displaying gallery carousels, highlights grids, dynamic Leaflet maps, amenities lists, and sticky lead capture inquiry forms.

**Architecture:** Add a new GET route, implement a controller query to fetch property details and related properties, create a single Blade view template (`property-detail.blade.php`), and wire catalog links.

**Tech Stack:** Laravel Eloquent, Blade, Leaflet.js (maps), Tailwind CSS, Lucide Icons.

---

### Task 1: Routing and Controller Logic

**Files:**
- Modify: `routes/web.php`
- Modify: `app/Http/Controllers/PropertyController.php`

- [ ] **Step 1: Add property details route in web.php**
  Open [routes/web.php](file:///Users/kushagra/Desktop/Projects/AmraProperty/routes/web.php) and insert the details route on line 43:
  ```php
  Route::get('/property/{slug}', [PropertyController::class, 'show'])->name('site.property.show');
  ```

- [ ] **Step 2: Add show method in PropertyController.php**
  Open [PropertyController.php](file:///Users/kushagra/Desktop/Projects/AmraProperty/app/Http/Controllers/PropertyController.php) and add the `show` method at the end of the class before the closing bracket:
  ```php
  public function show(string $slug): \Illuminate\View\View
  {
      $property = Property::where('slug', $slug)
          ->where('status', 'publish')
          ->firstOrFail();

      // Retrieve up to 3 related properties in the same city, excluding the current one
      $relatedProperties = Property::where('status', 'publish')
          ->where('id', '!=', $property->id)
          ->where(function($query) use ($property) {
              $query->where('city', $property->city)
                    ->orWhere('configuration', 'like', '%' . $property->bedrooms . '%bhk%');
          })
          ->orderByDesc('is_featured')
          ->orderByDesc('created_at')
          ->take(3)
          ->get();

      return view('site.property-detail', compact('property', 'relatedProperties'));
  }
  ```

---

### Task 2: Create Details Blade View

**Files:**
- Create: `resources/views/site/property-detail.blade.php`

- [ ] **Step 1: Create the details view template**
  Write a new file [property-detail.blade.php](file:///Users/kushagra/Desktop/Projects/AmraProperty/resources/views/site/property-detail.blade.php) containing the hero gallery layout, split content panel, Leaflet map initializers, and sidebar inquiry script.

  ```html
  @extends('layouts.site')

  @section('title', html_entity_decode($property->title) . ' - Amra Property')
  @section('meta_description', Str::limit(strip_tags($property->description), 150))

  @section('content')
  <div class="pt-32 pb-24 bg-slate-50 min-h-screen">
      <div class="max-w-7xl mx-auto px-6">
          
          <!-- Breadcrumbs -->
          <nav class="flex text-slate-400 text-xs font-bold uppercase tracking-wider mb-6 gap-2">
              <a href="{{ route('site.home') }}" class="hover:text-teal-600 transition-colors">Home</a>
              <span>/</span>
              <a href="{{ route('site.property') }}" class="hover:text-teal-600 transition-colors">Properties</a>
              <span>/</span>
              <span class="text-slate-600 truncate max-w-[200px]">{{ html_entity_decode($property->title) }}</span>
          </nav>

          <!-- Gallery Slider -->
          <div class="relative bg-black rounded-3xl overflow-hidden shadow-md mb-10 group h-[50vh] min-h-[350px] max-h-[600px]">
              @php
                  $images = is_array($property->gallery) ? $property->gallery : [];
                  if (empty($images) && $property->featured_image) {
                      $images[] = $property->featured_image;
                  }
              @endphp

              @if(!empty($images))
                  <!-- Slides Container -->
                  <div id="slider-container" class="relative w-full h-full flex transition-transform duration-500 ease-in-out" style="width: {{ count($images) * 100 }}%">
                      @foreach($images as $img)
                          <div class="w-full h-full shrink-0 select-none">
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

              <div class="absolute top-6 left-6 flex flex-col gap-2">
                  @if($property->is_featured)
                      <span class="bg-teal-500/90 text-white backdrop-blur-md border border-teal-400/20 shadow-md px-3.5 py-1.5 rounded-full uppercase tracking-wider text-[8px] font-bold">Featured Listing</span>
                  @endif
                  @if($property->is_rera_approved)
                      <span class="bg-slate-950/80 text-white text-[8px] font-semibold px-2.5 py-1.5 rounded-md backdrop-blur-md flex items-center gap-1.5 border border-white/10 shadow-sm">
                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> RERA Approved: {{ $property->rera_number }}
                      </span>
                  @endif
              </div>
          </div>

          <!-- Content Split Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
              
              <!-- Left Column: Details -->
              <div class="lg:col-span-8 flex flex-col gap-8">
                  <!-- Title Block -->
                  <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                      <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-1.5">
                          <i data-lucide="map-pin" class="w-4 h-4 text-teal-500"></i>
                          {{ $property->address ?: ($property->city . ($property->state ? ', ' . $property->state : '')) }}
                      </p>
                      <h1 class="text-3xl font-serif font-extrabold text-slate-800 leading-tight mb-4">
                          {{ html_entity_decode($property->title) }}
                      </h1>
                      <div class="h-px bg-slate-100 w-full mb-4"></div>
                      <div class="flex items-center justify-between">
                          <div>
                              <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Asking Price</p>
                              <h2 class="text-3xl font-serif font-extrabold text-teal-600">{{ $property->formatted_price }}</h2>
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
                  </div>

                  <!-- Highlights Grid -->
                  <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                      @if($property->bedrooms)
                          <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                              <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                  <i data-lucide="bed" class="w-5 h-5"></i>
                              </div>
                              <div>
                                  <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Bedrooms</p>
                                  <h4 class="text-sm font-extrabold text-slate-700">{{ $property->bedrooms }} Beds</h4>
                              </div>
                          </div>
                      @endif
                      @if($property->bathrooms)
                          <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                              <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                  <i data-lucide="shower-head" class="w-5 h-5"></i>
                              </div>
                              <div>
                                  <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Bathrooms</p>
                                  <h4 class="text-sm font-extrabold text-slate-700">{{ $property->bathrooms }} Baths</h4>
                              </div>
                          </div>
                      @endif
                      @if($property->area)
                          <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                              <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                  <i data-lucide="maximize-2" class="w-5 h-5"></i>
                              </div>
                              <div>
                                  <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Total Area</p>
                                  <h4 class="text-sm font-extrabold text-slate-700">{{ number_format($property->area) }} {{ str_replace('_', ' ', $property->area_unit) }}</h4>
                              </div>
                          </div>
                      @endif
                  </div>

                  <!-- Description -->
                  @if($property->description)
                      <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm text-slate-600 leading-relaxed text-sm">
                          <h3 class="text-lg font-serif font-bold text-slate-800 mb-4 flex items-center gap-2">
                              <i data-lucide="align-left" class="w-4 h-4 text-teal-500"></i> Property Description
                          </h3>
                          <div class="prose prose-sm prose-teal max-w-none text-slate-600">
                              {!! html_entity_decode($property->description) !!}
                          </div>
                      </div>
                  @endif

                  <!-- Amenities -->
                  @php
                      $amenities = is_array($property->amenities) ? $property->amenities : [];
                  @endphp
                  @if(!empty($amenities))
                      <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                          <h3 class="text-lg font-serif font-bold text-slate-800 mb-6 flex items-center gap-2">
                              <i data-lucide="layout-grid" class="w-4 h-4 text-teal-500"></i> Premium Amenities
                          </h3>
                          <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                              @foreach($amenities as $item)
                                  <div class="flex items-center gap-2 text-slate-600 text-xs font-semibold">
                                      <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                          <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                      </div>
                                      <span>{{ $item }}</span>
                                  </div>
                              @endforeach
                          </div>
                      </div>
                  @endif

                  <!-- Map Location -->
                  @if($property->latitude && $property->longitude)
                      <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                          <h3 class="text-lg font-serif font-bold text-slate-800 mb-4 flex items-center gap-2">
                              <i data-lucide="map" class="w-4 h-4 text-teal-500"></i> Geographical Location
                          </h3>
                          <!-- Leaflet Map Styles -->
                          <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
                          <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
                          
                          <div id="property-map" class="h-80 w-full rounded-2xl border border-slate-100 overflow-hidden relative z-10"></div>
                      </div>
                  @endif
              </div>

              <!-- Right Column: Sidebar Inquiry Form -->
              <div class="lg:col-span-4 lg:sticky lg:top-32 flex flex-col gap-6">
                  <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm text-left">
                      <h3 class="text-lg font-serif font-bold text-slate-800 mb-1">Inquire About Listing</h3>
                      <p class="text-slate-400 text-xs mb-6">Our partners will contact you in under 15 minutes.</p>

                      <div id="form-container">
                          <form id="contact-form" class="flex flex-col gap-4">
                              <div>
                                  <label for="first-name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">First Name</label>
                                  <input type="text" id="first-name" name="first_name" required
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-amra-primary focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-xs text-slate-800" placeholder="John">
                              </div>
                              <div>
                                  <label for="last-name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Last Name</label>
                                  <input type="text" id="last-name" name="last_name" required
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-amra-primary focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-xs text-slate-800" placeholder="Doe">
                              </div>
                              <div>
                                  <label for="email" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Email Address</label>
                                  <input type="email" id="email" name="email" required
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-amra-primary focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-xs text-slate-800" placeholder="john@example.com">
                              </div>
                              <div>
                                  <label for="message" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Message</label>
                                  <textarea id="message" name="message" rows="4" required
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-amra-primary focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-xs text-slate-800">Hi, I am interested in "{{ html_entity_decode($property->title) }}" located in {{ $property->city }}. Please share details.</textarea>
                              </div>
                              <button type="submit" id="submit-btn" class="w-full bg-amra-primary text-white py-3.5 rounded-xl font-bold text-xs hover:bg-teal-600 transition-all shadow-md flex items-center justify-center gap-1.5 active:scale-95 mt-2">
                                  <i data-lucide="send" class="w-3.5 h-3.5"></i> Send Request
                              </button>
                          </form>
                      </div>

                      <div class="h-px bg-slate-100 my-6"></div>

                      <div class="flex flex-col gap-3">
                          <a href="https://wa.me/919999999999?text=Hi,%20I%20am%20interested%20in%20{{ rawurlencode(html_entity_decode($property->title)) }}" target="_blank" class="w-full bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white py-3 rounded-xl font-bold text-xs border border-emerald-100 flex items-center justify-center gap-2 transition-all active:scale-[0.98]">
                              <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg> WhatsApp Inquiry
                          </a>
                          <a href="tel:+919999999999" class="w-full bg-teal-50 hover:bg-teal-500 text-teal-600 hover:text-white py-3 rounded-xl font-bold text-xs border border-teal-100 flex items-center justify-center gap-2 transition-all active:scale-[0.98]">
                              <i data-lucide="phone" class="w-4 h-4"></i> Call Concierge Desk
                          </a>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Related Properties -->
          @if($relatedProperties->isNotEmpty())
              <div class="h-px bg-slate-200 w-full my-16"></div>
              <div>
                  <h3 class="text-2xl font-serif font-bold text-slate-800 mb-8">Related Properties</h3>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                      @foreach($relatedProperties as $rel)
                          <div class="group bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(11,193,178,0.12)] hover:-translate-y-2 transition-all duration-500 ease-out border border-gray-100 flex flex-col justify-between">
                              <div class="relative h-48 overflow-hidden">
                                  <a href="{{ route('site.property.show', $rel->slug) }}">
                                      <img src="{{ $rel->featured_image ? asset($rel->featured_image) : asset('assets/images/prop1.webp') }}" alt="{{ html_entity_decode($rel->title) }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                                  </a>
                                  <div class="absolute top-4 left-4 flex flex-col gap-2">
                                      @if($rel->is_featured)
                                          <span class="bg-teal-500/90 text-white backdrop-blur-md border border-teal-400/20 shadow-sm px-2.5 py-1.5 rounded-full uppercase tracking-wider text-[8px] font-bold">Featured Listing</span>
                                      @endif
                                  </div>
                              </div>
                              <div class="p-5 flex-grow flex flex-col justify-between">
                                  <div>
                                      <p class="text-slate-400 text-[10px] font-medium tracking-wide flex items-center gap-1 mb-1.5">
                                          <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-500 shrink-0"></i> 
                                          {{ Str::limit(ucwords(strtolower($rel->address ?: ($rel->city . ($rel->state ? ', ' . $rel->state : '')))), 35) }}
                                      </p>
                                      <h3 class="text-sm font-serif font-bold text-slate-800 mb-1.5 group-hover:text-teal-600 transition-colors duration-300 leading-snug line-clamp-2 h-10">
                                          <a href="{{ route('site.property.show', $rel->slug) }}">{{ html_entity_decode($rel->title) }}</a>
                                      </h3>
                                      <div class="flex flex-wrap gap-1 mb-4 items-center">
                                          @if($rel->bedrooms)
                                              <span class="bg-slate-50 text-slate-600 border border-slate-100/80 rounded-md py-0.5 px-2 text-[9px] font-semibold flex items-center gap-0.5 shadow-sm">
                                                  <i data-lucide="bed" class="w-3 h-3 text-slate-400"></i>
                                                  {{ $rel->bedrooms }} Beds
                                              </span>
                                          @endif
                                          @if($rel->area)
                                              <span class="bg-slate-50 text-slate-600 border border-slate-100/80 rounded-md py-0.5 px-2 text-[9px] font-semibold flex items-center gap-0.5 shadow-sm">
                                                  <i data-lucide="maximize-2" class="w-3 h-3 text-slate-400"></i>
                                                  {{ number_format($rel->area) }} {{ str_replace('_', ' ', $rel->area_unit) }}
                                              </span>
                                          @endif
                                      </div>
                                  </div>
                                  <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                      <h5 class="text-base font-serif font-extrabold text-teal-600">{{ $rel->formatted_price }}</h5>
                                      <a href="{{ route('site.property.show', $rel->slug) }}" class="text-[10px] font-bold text-teal-600 hover:text-teal-700 flex items-center gap-0.5 uppercase tracking-wide">
                                          Details <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                      </a>
                                  </div>
                              </div>
                          </div>
                      @endforeach
                  </div>
              </div>
          @endif

      </div>
  </div>
  @endsection

  @section('scripts')
  @if($property->latitude && $property->longitude)
  <script>
      // Leaflet Map Initialization
      document.addEventListener('DOMContentLoaded', () => {
          const lat = {{ $property->latitude }};
          const lng = {{ $property->longitude }};
          const map = L.map('property-map', {
              center: [lat, lng],
              zoom: 14,
              scrollWheelZoom: false
          });

          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(map);

          L.marker([lat, lng]).addTo(map)
              .bindPopup('<b>{{ html_entity_decode($property->title) }}</b><br>{{ $property->city }}')
              .openPopup();
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
      // Lead form submission via AJAX
      const form = document.getElementById('contact-form');
      const formContainer = document.getElementById('form-container');
      const submitBtn = document.getElementById('submit-btn');

      if (form) {
          form.addEventListener('submit', (e) => {
              e.preventDefault();

              const firstName = document.getElementById('first-name').value;
              const lastName = document.getElementById('last-name').value;
              const email = document.getElementById('email').value;
              const message = document.getElementById('message').value;

              submitBtn.disabled = true;
              submitBtn.innerHTML = `
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Sending…
              `;

              // Send inquiry lead organically to B2C contacts pipeline
              fetch('{{ route('contact.submit') }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}',
                      'Accept': 'application/json',
                  },
                  body: JSON.stringify({
                      first_name: firstName,
                      last_name: lastName,
                      email: email,
                      message: message,
                      phone: '' // website leads can pass blank or admin overrides
                  })
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      formContainer.classList.add('transition-all', 'duration-500', 'opacity-0');
                      
                      setTimeout(() => {
                          formContainer.innerHTML = `
                              <div class="text-center py-8 flex flex-col items-center justify-center">
                                  <div class="w-14 h-14 bg-emerald-500/10 text-emerald-505 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                      <i data-lucide="check" class="w-7 h-7 text-emerald-500"></i>
                                  </div>
                                  <h4 class="text-lg font-serif font-extrabold text-slate-800 mb-2">Request Sent!</h4>
                                  <p class="text-slate-400 text-xs leading-relaxed mb-4">
                                      Your inquiry for this property has been received. Our certified partners will get in touch shortly.
                                  </p>
                              </div>
                          `;
                          if (window.lucide) {
                              window.lucide.createIcons();
                          }
                          formContainer.classList.remove('opacity-0');
                          formContainer.classList.add('opacity-100');
                      }, 300);
                  } else {
                      alert(data.message || 'An error occurred. Please try again.');
                      submitBtn.disabled = false;
                      submitBtn.innerHTML = '<i data-lucide="send" class="w-3.5 h-3.5"></i> Send Request';
                      if (window.lucide) window.lucide.createIcons();
                  }
              })
              .catch(err => {
                  console.error(err);
                  alert('Unable to connect to the server. Please try again.');
                  submitBtn.disabled = false;
                  submitBtn.innerHTML = '<i data-lucide="send" class="w-3.5 h-3.5"></i> Send Request';
                  if (window.lucide) window.lucide.createIcons();
              });
          });
      }
  </script>
  @endsection
  ```

---

### Task 3: Wire Directory & Home Cards to Details

**Files:**
- Modify: `resources/views/site/property.blade.php`
- Modify: `resources/views/site/home.blade.php`

- [ ] **Step 1: Update card links in property.blade.php**
  Wrap card image tag and card title text with links to the property details view:
  ```html
  <!-- Wrap image -->
  <a href="{{ route('site.property.show', $property->slug) }}">
      <img src="{{ $property->featured_image ? asset($property->featured_image) : asset('assets/images/prop1.webp') }}" ...>
  </a>

  <!-- Wrap title -->
  <h3 class="text-base font-serif font-bold text-slate-800 mb-2 group-hover:text-teal-600 transition-colors duration-300 leading-snug line-clamp-2 h-11">
      <a href="{{ route('site.property.show', $property->slug) }}">{{ html_entity_decode($property->title) }}</a>
  </h3>
  ```

- [ ] **Step 2: Update card links in home.blade.php**
  Apply the exact same link wraps in homepage featured loop.

---

### Task 4: Add Verification Test and Run Suite

**Files:**
- Modify: `tests/Feature/PropertyTest.php`

- [ ] **Step 1: Write property details test case**
  Add a test to verify details page fetching, related properties, and 404 behavior:
  ```php
  public function test_property_details_page_renders_successfully()
  {
      $property = Property::factory()->create([
          'title' => 'Superior Palacia',
          'slug' => 'superior-palacia-airoli',
          'status' => 'publish',
          'city' => 'Mumbai'
      ]);

      $response = $this->get(route('site.property.show', $property->slug));
      $response->assertStatus(200);
      $response->assertSee('Superior Palacia');
  }

  public function test_property_details_page_returns_404_for_draft_properties()
  {
      $property = Property::factory()->create([
          'slug' => 'draft-palacia',
          'status' => 'draft'
      ]);

      $response = $this->get(route('site.property.show', $property->slug));
      $response->assertStatus(404);
  }
  ```

- [ ] **Step 2: Run artisan test**
  Run: `php artisan test`
  Expected: PASS
