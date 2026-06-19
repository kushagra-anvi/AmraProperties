# Property Detail Page Design Specification

Design specification for implementing single property detail pages (`/property/{slug}`) on the Amra Property website.

## 1. Routes & Controller Setup

### A. Routing
Add the detail route in `routes/web.php` immediately below the `/property` listing route:
```php
Route::get('/property/{slug}', [PropertyController::class, 'show'])->name('site.property.show');
```

### B. Controller Method (`PropertyController@show`)
* Fetch property: Query `Property` by `slug` where `status = 'publish'`. If not found, trigger `abort(404)`.
* Fetch related listings: Retrieve 3 published properties in the same city (or type) as the current property, excluding the current property ID, ordered by `is_featured` DESC, then `created_at` DESC.
* Return view: Pass `$property` and `$relatedProperties` to the `site.property-detail` Blade view.

---

## 2. View Template (`property-detail.blade.php`)

### A. Hero Gallery Carousel
* Display a custom image slider showcasing the property's `gallery` images (parsed from JSON array). If the gallery is empty, fall back to the `featured_image` as a single banner.
* Include previous/next arrow overlays and a pagination indicator dots row.

### B. Layout Layout Split Grid
Use a responsive split layout: `grid grid-cols-1 lg:grid-cols-12 gap-10 items-start`.

#### 1. Left Content Column (`lg:col-span-8`)
* **Highlights Grid**: A modern 3-column metadata card containing Bed, Bath, Area, Configuration, Developer name, and RERA registration info with clear Lucide icons.
* **Description Section**: Render the full HTML description block safely.
* **Amenities Grid**: Iterate over the `amenities` array, rendering each item as a capsule badge with a checkmark icon.
* **Location Map Embed**: Check if coordinates exist (`latitude` and `longitude`). If present, render a clean Leaflet.js interactive map centered on the coordinates with a customized map marker.

#### 2. Sticky Right Sidebar Column (`lg:col-span-4`)
* **Sticky Container**: Wrap in Tailwind's `sticky top-32` class to float during scroll.
* **Direct Inquiry Form**:
  * Fields: First Name, Last Name, Email Address, and Message (auto-populated with interest text like *"Hi, I am interested in [Property Title]"*).
  * Submission: Sent via AJAX POST request to route `contact.submit` (`/contact`), reusing our lead capture pipeline to insert B2C leads organically.
* **Contact Action Shortcuts**:
  * Direct Call button.
  * WhatsApp chat button pre-seeded with custom message content.

### C. Related Properties Section
* Render a `Related Properties` row at the bottom displaying up to 3 similar cards, styled matching our aligned catalog layout.

---

## 3. Cards Linking Upgrades
* Update the listing cards in [property.blade.php](file:///Users/kushagra/Desktop/Projects/AmraProperty/resources/views/site/property.blade.php) and [home.blade.php](file:///Users/kushagra/Desktop/Projects/AmraProperty/resources/views/site/home.blade.php):
  * Wrap the listing images in `<a>` tags pointing to the show route: `{{ route('site.property.show', $property->slug) }}`.
  * Wrap the title text headers in matching `<a>` tags pointing to the show route.
