# Property Text Search Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement text-based property name/builder/keyword search on the homepage and connect it to the properties catalog page.

**Architecture:** Create a text search input on both `index.html` and `pages/property.html`. Redirect from the homepage with a `q` parameter, parse it on the catalog page, and perform client-side text filtering across card text content.

**Tech Stack:** HTML5, CSS (Tailwind), Vanilla JS.

---

### Task 1: Update Homepage Search UI & Redirection Script

**Files:**
- Modify: `index.html:62-95` and `index.html:674-694`

- [ ] **Step 1: Replace single-row search bar with two-row search bar**
  Update the search bar block (lines 63-95) to have a stacked layout. The first row contains the search text input. The second row contains the Location, Type, and Budget dropdown selects, and the Search button.
  
  ```html
  <!-- Search Bar -->
  <div class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col gap-4 mb-10 w-full text-left">
      <!-- Row 1: Text Search Input -->
      <div class="w-full relative">
          <label class="block text-sm text-gray-500 font-semibold mb-2 pl-2">Search Properties</label>
          <div class="relative">
              <input type="text" id="home-query" placeholder="Search by property name, builder, or keywords (e.g. Lodha, Villa, Gomti Nagar)..."
                  class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg pl-10 pr-4 py-3.5 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
              <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4.5 h-4.5"></i>
          </div>
      </div>
      
      <!-- Row 2: Select Dropdowns & Search Button -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
          <div class="w-full">
              <label class="block text-sm text-gray-500 font-semibold mb-2 pl-2">Location</label>
              <select id="home-location"
                  class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg px-4 py-3.5 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm">
                  <option value="">All Locations</option>
                  <option value="Mumbai">Mumbai</option>
                  <option value="Lucknow">Lucknow</option>
              </select>
          </div>
          <div class="w-full">
              <label class="block text-sm text-gray-500 font-semibold mb-2 pl-2">Property Type</label>
              <select id="home-type"
                  class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg px-4 py-3.5 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm">
                  <option value="">All Types</option>
                  <option value="Flat">Flat</option>
                  <option value="Villa">Villa</option>
                  <option value="Plot">Plot</option>
              </select>
          </div>
          <div class="w-full">
              <label class="block text-sm text-gray-500 font-semibold mb-2 pl-2">Budget</label>
              <select id="home-budget"
                  class="w-full bg-slate-50 border border-gray-200 text-gray-800 font-medium rounded-lg px-4 py-3.5 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm">
                  <option value="">All Budgets</option>
                  <option value="₹25 Lac - ₹50 Lac">₹25 Lac - ₹50 Lac</option>
                  <option value="₹50 Lac - ₹1 Cr">₹50 Lac - ₹1 Cr</option>
                  <option value="₹1 Cr+">₹1 Cr+</option>
              </select>
          </div>
          <button id="home-search-btn"
              class="w-full bg-teal-500 text-white px-8 py-3.5 rounded-lg font-bold hover:bg-teal-600 transition-all shadow-md hover:-translate-y-0.5 flex items-center justify-center gap-2 h-[52px]">
              <i data-lucide="search" class="w-4 h-4"></i> Search
          </button>
      </div>
  </div>
  ```

- [ ] **Step 2: Update redirection script logic**
  Modify the script section in `index.html` (lines 674-694) to extract `#home-query` and append `q` to the query params on window redirection.
  
  ```javascript
      <script>
          // Initialize Lucide Icons
          lucide.createIcons();

          // Homepage Search Form Redirection
          document.addEventListener('DOMContentLoaded', () => {
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
                      window.location.href = `pages/property.html?q=${encodeURIComponent(query)}&location=${encodeURIComponent(loc)}&type=${encodeURIComponent(type)}&budget=${encodeURIComponent(budget)}`;
                  });
              }
          });
      </script>
  ```

---

### Task 2: Update Property Catalog Filter UI & JavaScript Filtering Logic

**Files:**
- Modify: `pages/property.html:52-87` and `pages/property.html:545-630`

- [ ] **Step 1: Replace catalog single-row filter bar with two-row filters**
  Modify `pages/property.html` (lines 52-87) to implement the two-row filters layout. Row 1 contains the query search text input `#filter-query`. Row 2 contains Location, Type, Budget dropdowns, and the Reset button.
  
  ```html
  <!-- Interactive Search & Filter Control Panel -->
  <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col gap-4 mb-12 max-w-5xl mx-auto">
      <!-- Row 1: Search query input -->
      <div class="w-full relative">
          <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Search Properties</label>
          <div class="relative">
              <input type="text" id="filter-query" placeholder="Search by name, builder, keywords..."
                  class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl pl-10 pr-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all text-sm">
              <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
          </div>
      </div>
      
      <!-- Row 2: Select Filters & Reset -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
          <div class="w-full">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Location</label>
              <select id="filter-location"
                  class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm">
                  <option value="all">All Locations</option>
                  <option value="mumbai">Mumbai</option>
                  <option value="lucknow">Lucknow</option>
              </select>
          </div>
          <div class="w-full">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Property Type</label>
              <select id="filter-type"
                  class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm">
                  <option value="all">All Types</option>
                  <option value="flat">Flats / Apartments</option>
                  <option value="villa">Villas</option>
                  <option value="plot">Plots</option>
              </select>
          </div>
          <div class="w-full">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-2">Budget Range</label>
              <select id="filter-budget"
                  class="w-full bg-slate-50 border border-slate-100 text-slate-800 font-semibold rounded-xl px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all cursor-pointer text-sm">
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
  ```

- [ ] **Step 2: Update the catalog javascript filtering script**
  Modify the script section of `pages/property.html` (lines 545-630) to include `#filter-query` logic.
  - Parse `q` parameter from URL and set it as `#filter-query` value.
  - In `applyFilters()`, retrieve input search text `q`, match it against card text content (`card.textContent.toLowerCase().includes(q)`).
  - Add input event listener on `#filter-query` to trigger filtering instantly as the user types.
  - Reset `#filter-query` to empty string in `resetFilters()`.
  
  ```javascript
      <!-- Interactive Filtering Javascript -->
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

              function applyFilters() {
                  const q = filterQuery ? filterQuery.value.trim().toLowerCase() : '';
                  const loc = filterLocation.value;
                  const type = filterType.value;
                  const budget = filterBudget.value;
                  let visibleCount = 0;

                  propertyCards.forEach(card => {
                      const cardLoc = card.getAttribute('data-location');
                      const cardType = card.getAttribute('data-type');
                      const cardBudget = card.getAttribute('data-budget');

                      const cardText = card.textContent.toLowerCase();
                      const textMatch = !q || cardText.includes(q);

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
  ```

---

### Task 3: Verification & Interactive Testing

- [ ] **Step 1: Test Homepage Query Redirection**
  - Search for "Lodha" on the homepage and click Search.
  - Verify redirection to `/pages/property.html?q=Lodha&...`.
  - Confirm the search text box in the catalog is auto-populated with "Lodha" and only "Lodha" cards are visible.
