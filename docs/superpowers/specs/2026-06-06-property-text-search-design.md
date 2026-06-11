# Design Spec: Property Text Search Feature

## Problem Statement
The current website homepage search widget only allows dropdown-based filtering (Location, Type, Budget). Users cannot search for specific property names (like "Lodha Stella" or "Shalimar One World"), builders, or custom keywords directly.

## Selected Solution
We will implement **Approach A: Two-Row Layout + Integrated search input on Catalog**.
This consists of:
1. Redesigning the search bar on the homepage (`index.html`) to have two rows: a text input row and a filters + search button row.
2. Extending the properties catalog page (`pages/property.html`) filter bar to also include a text search input.
3. Propagating the text query `q` via URL parameters and performing fast client-side keyword matching across card text content.

## UI/UX Design

### Homepage (`index.html`) Search Bar
- Row 1: Full-width text search input with a magnifying glass search icon.
- Row 2: Location select, Property Type select, Budget select, Search button in a grid layout.

### Catalog Page (`pages/property.html`) Filters
- Row 1: Full-width text search input with a magnifying glass search icon.
- Row 2: Location select, Property Type select, Budget select, Reset filters button.

## Proposed Changes

### `index.html`
- Update the layout structure of the Search Bar to the two-row layout.
- Bind the text input `home-query` value into the query parameters during redirection.

### `pages/property.html`
- Update the filters panel to match the two-row layout, adding `#filter-query`.
- Update the JavaScript parsing logic to populate `#filter-query` from the `q` URL parameter.
- Update `applyFilters()` to verify card text content matches the query.
- Update `resetFilters()` to clear the query text.

## Verification Plan
- **Manual Verification**: Run/verify homepage search for "Lodha" and confirm redirection to `pages/property.html?q=Lodha` with correct card filtering.
- **Responsiveness**: Verify that the two-row search box adapts correctly on mobile viewports.
