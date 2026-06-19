# Property Cards & Pagination UI Alignment Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Redesign the property listing cards (both on `/property` and the homepage) and pagination UI to match the brand's luxury look, using strict length boundaries to ensure card heights are perfectly uniform.

**Architecture:** Modify the Laravel Blade layout files directly. Enforce `line-clamp` boundaries on text fields, apply standard Title Case formatting on location variables, and replace plain paginator styling with a custom centered glassmorphic nav bar using Lucide icons.

**Tech Stack:** Tailwind CSS, Laravel Blade, Lucide Icons.

---

### Task 1: Redesign Property Cards in property.blade.php

**Files:**
- Modify: `resources/views/site/property.blade.php`

- [ ] **Step 1: Rewrite the property card grid loop**
  Update the `@forelse` loop items in [property.blade.php](file:///Users/kushagra/Desktop/Projects/AmraProperty/resources/views/site/property.blade.php#L70-L137) to enforce Title Case on location strings, strict title `line-clamp-2` tags, `line-clamp-1` description truncation, and add custom Lucide icon badges for property attributes.

  Replace the card wrapper and its contents with the following code block:
  ```html
  <div class="property-card group bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(11,193,178,0.12)] hover:-translate-y-2 transition-all duration-500 ease-out border border-gray-100 flex flex-col justify-between"
      data-location="{{ $property->location_category }}" 
      data-type="{{ $property->type_category }}" 
      data-budget="{{ $property->budget_category }}">
      <div class="relative h-60 overflow-hidden">
          <img src="{{ $property->featured_image ? asset($property->featured_image) : asset('assets/images/prop1.webp') }}" alt="{{ html_entity_decode($property->title) }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
          <div class="absolute top-4 left-4 flex flex-col gap-2">
              @if($property->is_featured)
                  <span class="bg-teal-500/90 text-white backdrop-blur-md border border-teal-400/20 shadow-sm px-3.5 py-1.5 rounded-full uppercase tracking-wider text-[8px] font-bold">Featured Listing</span>
              @endif
              @if($property->is_rera_approved)
                  <span class="bg-slate-955/80 text-white text-[8px] font-semibold px-2.5 py-1.5 rounded-md backdrop-blur-md flex items-center gap-1.5 border border-white/10 shadow-sm">
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
                  {{ html_entity_decode($property->title) }}
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
  ```

- [ ] **Step 2: Save and review changes**
  Save the file and check formatting in `/property`.

---

### Task 2: Redesign Featured Property Cards in home.blade.php

**Files:**
- Modify: `resources/views/site/home.blade.php`

- [ ] **Step 1: Rewrite the featured properties loop**
  Update the `@forelse` loop in [home.blade.php](file:///Users/kushagra/Desktop/Projects/AmraProperty/resources/views/site/home.blade.php#L331-L394) to match the layout modifications made in Task 1.

  Replace the card wrapper and its contents with the same markup structure:
  ```html
  <div class="group bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(11,193,178,0.12)] hover:-translate-y-2 transition-all duration-500 ease-out border border-gray-100 flex flex-col justify-between">
      <div class="relative h-60 overflow-hidden">
          <img src="{{ $property->featured_image ? asset($property->featured_image) : asset('assets/images/prop1.webp') }}" alt="{{ html_entity_decode($property->title) }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
          <div class="absolute top-4 left-4 flex flex-col gap-2">
              @if($property->is_featured)
                  <span class="bg-teal-500/90 text-white backdrop-blur-md border border-teal-400/20 shadow-sm px-3.5 py-1.5 rounded-full uppercase tracking-wider text-[8px] font-bold">Featured Listing</span>
              @endif
              @if($property->is_rera_approved)
                  <span class="bg-slate-955/80 text-white text-[8px] font-semibold px-2.5 py-1.5 rounded-md backdrop-blur-md flex items-center gap-1.5 border border-white/10 shadow-sm">
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
                  {{ html_entity_decode($property->title) }}
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
  ```

- [ ] **Step 2: Save and review changes**
  Save the file and check the homepage `/` layout for correctness.

---

### Task 3: Redesign Pagination Component in property.blade.php

**Files:**
- Modify: `resources/views/site/property.blade.php`

- [ ] **Step 1: Replace pagination HTML block**
  Update the `#property-pagination` container in [property.blade.php](file:///Users/kushagra/Desktop/Projects/AmraProperty/resources/views/site/property.blade.php#L156-L197) to match the centered pill-bar design.

  Replace the pagination nav section with the following code block:
  ```html
  <div id="property-pagination" class="flex justify-center items-center mt-16 select-none animate-fade-in">
      @if ($properties->hasPages())
          <nav role="navigation" aria-label="Pagination Navigation" class="bg-white/80 backdrop-blur-md border border-slate-100/80 px-6 py-3 rounded-2xl shadow-sm inline-flex items-center gap-2 select-none">
              {{-- Previous Page Link --}}
              @if ($properties->onFirstPage())
                  <span class="px-4 py-2 rounded-xl bg-gray-50 border border-gray-100 text-gray-300 cursor-not-allowed flex items-center justify-center h-10 gap-1.5 text-xs font-bold">
                      <i data-lucide="chevron-left" class="w-4 h-4"></i> Prev
                  </span>
              @else
                  <a href="{{ $properties->appends(request()->query())->previousPageUrl() }}" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-teal-600 active:scale-95 transition-all flex items-center justify-center h-10 gap-1.5 text-xs font-bold shadow-sm" aria-label="Previous Page">
                      <i data-lucide="chevron-left" class="w-4 h-4"></i> Prev
                  </a>
              @endif

              {{-- Pagination Elements --}}
              @foreach ($properties->getUrlRange(max(1, $properties->currentPage() - 2), min($properties->lastPage(), $properties->currentPage() + 2)) as $page => $url)
                  @if ($page == $properties->currentPage())
                      <span class="w-10 h-10 rounded-xl bg-teal-500 border border-teal-500 text-white font-bold text-sm shadow-md shadow-teal-500/25 flex items-center justify-center cursor-default">
                          {{ $page }}
                      </span>
                  @else
                      <a href="{{ $properties->appends(request()->query())->url($page) }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-slate-700 font-bold text-sm hover:bg-slate-50 hover:border-slate-300 hover:text-teal-600 active:scale-95 transition-all flex items-center justify-center shadow-sm">
                          {{ $page }}
                      </a>
                  @endif
              @endforeach

              {{-- Next Page Link --}}
              @if ($properties->hasMorePages())
                  <a href="{{ $properties->appends(request()->query())->nextPageUrl() }}" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-teal-600 active:scale-95 transition-all flex items-center justify-center h-10 gap-1.5 text-xs font-bold shadow-sm" aria-label="Next Page">
                      Next <i data-lucide="chevron-right" class="w-4 h-4"></i>
                  </a>
              @else
                  <span class="px-4 py-2 rounded-xl bg-gray-50 border border-gray-100 text-gray-300 cursor-not-allowed flex items-center justify-center h-10 gap-1.5 text-xs font-bold">
                      Next <i data-lucide="chevron-right" class="w-4 h-4"></i>
                  </span>
              @endif
          </nav>
      @endif
  </div>
  ```

---

### Task 4: Verify All Features & Routing Integrity

**Files:**
- Test: `tests/Feature/PropertyTest.php`

- [ ] **Step 1: Execute PHPUnit tests**
  Run the automated test suite to ensure the routes and views render successfully:
  Run: `php artisan test`
  Expected: PASS

- [ ] **Step 2: Commit all modified files**
  ```bash
  git add resources/views/site/property.blade.php resources/views/site/home.blade.php
  git commit -m "style: align property cards with strict text constraints and custom centered pagination"
  ```
