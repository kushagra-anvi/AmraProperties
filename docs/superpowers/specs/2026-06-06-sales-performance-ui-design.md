# Design Spec: Sales Performance Profile UI Layout Optimization

## Problem Statement
The Sales Performance page (`/crm/sales/{id}`) displays B2B metrics, call KPIs, converted partners, assigned B2B leads, day-wise call counts, and follow-up timeline activity logs. Rendering all of these widgets sequentially and side-by-side (using 5/12 and 7/12 grid columns) makes the UI feel cramped and hard to read, especially for data-heavy tables and timeline activity logs.

## Selected Solution
We will implement **Approach A: Tabbed Navigation with Full-Width Components**.
This groups the dashboard widgets into two functional views:
1. **Pipeline & Leads**: B2B metrics cards, Converted Partners list (full width), and Assigned B2B Lead list (full width).
2. **Call Performance & Logs**: Call metrics cards, Day-wise calls completed table (full width), and Follow-up activity timeline (full width).

We will add a tab switching navigation row under the date filters. The switching will be performed client-side using Tailwind's `hidden` utility class via vanilla JavaScript.

## UI/UX Design Hierarchy
* **Date Filters Container**: (Unchanged)
* **Tab Nav Bar**: Two horizontal tab buttons with active states matching the theme (#0bc1b2 / border-amra-primary).
* **Tab 1 Panel (`#pipeline-tab`)**:
  - B2B metrics cards (5 cards in grid).
  - Converted Partners card (Full width `w-full`).
  - Assigned B2B Leads card (Full width `w-full`).
* **Tab 2 Panel (`#calls-tab` - hidden by default)**:
  - Call metrics cards (3 cards in grid).
  - Day-wise completed calls card (Full width `w-full`).
  - Follow-up timeline logs card (Full width `w-full`).

## Proposed Changes

### `resources/views/crm/sales/show.blade.php`
- Add Tab Switch HTML component with navigation buttons.
- Wrap Pipeline KPI cards and split grids in a `<div id="pipeline-tab" class="space-y-6">` container.
- Wrap Follow-up KPI cards and split grids in a `<div id="calls-tab" class="space-y-6 hidden">` container.
- Update nested layout grids to be full width (`w-full`) instead of column spans (e.g., `lg:col-span-5` and `lg:col-span-7` -> single stacked vertical structure, or single column layouts with clean spacing).
- Add JavaScript functions to toggle the active tab visibility and update button active classes.

## Verification Plan
- **Manual Verification**: Run/verify page loading at `http://127.0.0.1:8181/crm/sales/1`. Check responsiveness on mobile.
- **Automated Tests**: Run `php artisan test` to ensure no Blade compile or route resolution errors were introduced.
