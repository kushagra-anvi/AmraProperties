# Property Cards & Pagination UI Alignment Design

Design specification for upgrading the property cards and custom pagination interface on the Amra Property directory pages (`/property` and homepage). This design fixes layout inconsistencies and establishes a premium, luxury aesthetic.

## 1. Objectives & Guidelines

1. **Height Consistency & Layout Alignment**: Solve layout shifts and unequal card heights by enforcing strict string length and line limits on text layers (location, title, description).
2. **Premium Visual Aesthetics**: Transition cards from basic grey-bordered boxes to luxury directory components with micro-interactions, soft shadows, and clean metadata indicators.
3. **Website UI Synchronization**: Use the primary brand color (#0bc1b2 - `amra-primary`), Outfit serif headers, and Inter body fonts.
4. **Enhanced Navigation**: Design a custom, centered pagination pill navigation bar with elegant transition animations.

---

## 2. Components Design

### A. Property Cards Component

The cards grid will be optimized for consistency across all display widths.

#### 1. Image Wrapper Layout (`relative h-60 rounded-t-3xl overflow-hidden`)
* **Hover Zoom**: The listing image will scale from `scale-100` to `scale-105` on card hover.
* **Glassmorphic Badges**:
  * **Featured Listing**: `bg-teal-500/90 text-white backdrop-blur-md border border-teal-400/20 shadow-sm px-3.5 py-1.5 rounded-full uppercase tracking-wider text-[8px] font-bold`
  * **RERA Approved**: `bg-slate-950/80 text-white backdrop-blur-md border border-white/10 text-[8px] px-2.5 py-1 rounded-md font-medium tracking-wide flex items-center gap-1.5` with a green pulsating dot.
  * **Wishlist Button**: A floating circle button in the top right using `bg-white/80 hover:bg-white text-slate-500 hover:text-rose-500 shadow-md transition-all active:scale-90`.

#### 2. Typography Constraints (No Layout Shifts)
* **Location Line**:
  * Style: `text-slate-400 text-[11px] font-medium tracking-wide flex items-center gap-1 mb-2`
  * Content: Formatted in standard Title Case using `Str::limit(ucwords(strtolower($property->address ?: ...)), 35)` to prevent overflow and wrapping.
* **Title Header**:
  * Style: `text-base font-serif font-bold text-slate-800 leading-snug group-hover:text-teal-600 transition-colors duration-300 mb-2`
  * Content: Wrapped in `line-clamp-2` to strictly cap title display height to 2 lines regardless of title length.
* **Description Sub-text**:
  * Style: `text-xs text-slate-400 leading-relaxed mb-4`
  * Content: Wrapped in `line-clamp-1` to limit description height to exactly 1 line.

#### 3. Specification Capsules Row
* Style: `flex flex-wrap gap-2 mb-4 items-center`
* Badges: Small light-grey pills with a subtle border and colored **Lucide Icons**:
  * **Property Type**: [Home Icon] `Flat` / `Villa` / `Plot` (`home` icon)
  * **Bedrooms**: [Bed Double Icon] `3 Beds` (`bed` icon)
  * **Bathrooms**: [Bath Icon] `2 Baths` (`bath` icon)
  * **Area**: [Maximize Icon] `1,181 sq ft` (`maximize-2` icon)
  * **Developer**: [Building Icon] `By DLH` (`building-2` icon)

#### 4. Price & Action Footer
* Border: `pt-4 border-t border-slate-100 flex items-center justify-between`
* Price: Large premium bold font: `text-lg font-serif font-extrabold text-teal-600`
* Contact Actions:
  * **WhatsApp Button**: Green brand accent box: `w-9 h-9 rounded-xl bg-emerald-50 hover:bg-emerald-500 border border-emerald-100/50 text-emerald-600 hover:text-white flex items-center justify-center transition-all shadow-sm active:scale-90`
  * **Call Button**: Teal brand accent box: `w-9 h-9 rounded-xl bg-teal-50 hover:bg-teal-500 border border-teal-100/50 text-teal-600 hover:text-white flex items-center justify-center transition-all shadow-sm active:scale-90`

---

### B. Custom Pagination Component

Replace default Laravel link templates with a custom centered glassmorphic component:

* **Container**: `bg-white/80 backdrop-blur-md border border-slate-100/80 px-6 py-3 rounded-2xl shadow-sm inline-flex items-center gap-2 select-none`
* **Active Page**: Circular teal glowing box: `bg-teal-500 text-white shadow-md shadow-teal-500/20 w-10 h-10 flex items-center justify-center rounded-xl font-bold text-sm border border-teal-500`
* **Inactive Pages**: Clear page link button: `bg-white border border-slate-200 text-slate-700 w-10 h-10 flex items-center justify-center rounded-xl font-bold text-sm hover:bg-slate-50 hover:border-slate-300 hover:text-teal-600 active:scale-95 transition-all shadow-sm`
* **Previous / Next Buttons**: Standardize on elegant text labels rather than plain arrows:
  * **Previous**: `px-4 py-2 border border-slate-200 rounded-xl bg-white text-slate-600 text-xs font-bold hover:bg-slate-50 hover:text-teal-600 active:scale-95 transition-all flex items-center gap-1.5 shadow-sm` displaying `← Prev`.
  * **Next**: `px-4 py-2 border border-slate-200 rounded-xl bg-white text-slate-600 text-xs font-bold hover:bg-slate-50 hover:text-teal-600 active:scale-95 transition-all flex items-center gap-1.5 shadow-sm` displaying `Next →`.

---

## 3. Testing and Verification Plan

1. **Visual Testing**: Inspect the layout of both the homepage `/` and `/property` directories across responsive widths (mobile, tablet, desktop).
2. **Pagination Test**: Click through pagination numbers and verify that state queries are preserved and active/inactive button styles render correctly.
3. **Automated Verification**: Ensure that the controller test suite runs successfully.
