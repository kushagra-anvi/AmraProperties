# Sales Performance UI Optimization Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Redesign `/crm/sales/{id}` to use tabbed layouts and full-width containers for lists and tables to resolve cramped UI spacing.

**Architecture:** Create client-side tab switching using Tailwind CSS `hidden` utility class, controlled by vanilla JS. Change columns to stretch to full-width vertically stacked inside each tab.

**Tech Stack:** Laravel, Blade, Tailwind CSS, Vanilla JS.

---

### Task 1: Insert Tab Navigation Header & Create Tab Containers

**Files:**
- Modify: `resources/views/crm/sales/show.blade.php:69-272`

- [ ] **Step 1: Modify show.blade.php to insert tab navigation buttons**
  Under the Date Filters block (line 69), insert a styled navigation container with two buttons:
  - "B2B Pipeline & Leads" (Active default: active styles `text-amra-primary border-b-2 border-amra-primary font-bold`)
  - "Call Performance & Logs" (Inactive default: inactive styles `text-slate-450 hover:text-slate-200 border-b-2 border-transparent`)

  ```html
  <!-- Tab Navigation Bar -->
  <div class="border-b border-slate-800 flex items-center gap-6 text-sm mb-6 mt-2">
      <button id="tab-btn-pipeline" onclick="switchSalesTab('pipeline')" class="pb-3 text-amra-primary border-b-2 border-amra-primary font-bold transition-all px-1">
          B2B Pipeline & Leads
      </button>
      <button id="tab-btn-calls" onclick="switchSalesTab('calls')" class="pb-3 text-slate-400 border-b-2 border-transparent hover:text-slate-200 transition-all px-1">
          Call Performance & Logs
      </button>
  </div>
  ```

- [ ] **Step 2: Wrap the B2B Metrics and Lists in the Pipeline container**
  Wrap the 5-column B2B KPI grid (lines 70-106) and the split block layout (lines 132-203) in a wrapper div:
  `<div id="sales-tab-pipeline" class="space-y-6">`
  Make sure this container is visible by default.

- [ ] **Step 3: Wrap the Call Activity metrics and timelines in the Calls container**
  Wrap the 3-column Follow-up Activity KPI grid (lines 108-130) and the Day-wise Call Performance & Follow-up Timeline section (lines 205-271) in a wrapper div:
  `<div id="sales-tab-calls" class="space-y-6 hidden">`
  Make sure this container is hidden by default.

---

### Task 2: Convert Lists and Tables to Full Width

**Files:**
- Modify: `resources/views/crm/sales/show.blade.php` inside the tab wrappers

- [ ] **Step 1: Convert Converted Partners & Assigned B2B Leads to full-width vertically-stacked layout**
  In the Pipeline tab wrapper, replace the `grid lg:grid-cols-12` wrapper with a standard stacked div `space-y-6`. Change the individual containers to span the full width instead of col-spans.
  - Converted Certified Partners container: Remove `lg:col-span-5` and let it sit on its own line.
  - Assigned B2B Lead List container: Remove `lg:col-span-7` and let it sit on its own line.

  ```html
  <!-- Stacked full-width block layouts -->
  <div class="space-y-6">
      <!-- Converted certified partners list (full-width) -->
      <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
          ...
      </div>

      <!-- Assigned leads list (full-width) -->
      <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md space-y-4">
          ...
      </div>
  </div>
  ```

- [ ] **Step 2: Convert Day-Wise Calls Table & Follow-up Logs Timeline to full-width vertically-stacked layout**
  In the Calls tab wrapper, replace the `grid lg:grid-cols-12` wrapper with a standard stacked div `space-y-6`.
  - Day-wise Call Performance table container: Remove `lg:col-span-4` and let it sit on its own line.
  - Recent Follow-up logs timeline container: Remove `lg:col-span-8` and let it sit on its own line.

---

### Task 3: Implement Vanilla JS Tab Switch Logic

**Files:**
- Modify: `resources/views/crm/sales/show.blade.php:275-286`

- [ ] **Step 1: Add switchSalesTab javascript function**
  Inside the `@section('scripts')` tag, define the tab switching logic:

  ```javascript
  function switchSalesTab(tabName) {
      // Containers
      const pipelineTab = document.getElementById('sales-tab-pipeline');
      const callsTab = document.getElementById('sales-tab-calls');
      
      // Buttons
      const btnPipeline = document.getElementById('tab-btn-pipeline');
      const btnCalls = document.getElementById('tab-btn-calls');
      
      if (tabName === 'pipeline') {
          pipelineTab.classList.remove('hidden');
          callsTab.classList.add('hidden');
          
          btnPipeline.className = "pb-3 text-amra-primary border-b-2 border-amra-primary font-bold transition-all px-1";
          btnCalls.className = "pb-3 text-slate-400 border-b-2 border-transparent hover:text-slate-200 transition-all px-1";
      } else {
          pipelineTab.classList.add('hidden');
          callsTab.classList.remove('hidden');
          
          btnPipeline.className = "pb-3 text-slate-400 border-b-2 border-transparent hover:text-slate-200 transition-all px-1";
          btnCalls.className = "pb-3 text-amra-primary border-b-2 border-amra-primary font-bold transition-all px-1";
      }
  }
  ```

---

### Task 4: Verification & Test Execution

- [ ] **Step 1: Run PHP Artisan Tests**
  Verify that the views compile successfully and the Sales route works.
  Run: `php artisan test`
  Expected: All tests pass.
