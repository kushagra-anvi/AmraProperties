# Design Spec: Convert Static Website HTML to Laravel Blade Templates

## Problem Statement
The current public site serves static `.html` files (`index.html` and `pages/*.html`) directly from the filesystem. This is non-standard for Laravel, leads to duplicate assets/pages, and uses client-side JavaScript injection (`components.js`) to render the shared Header and Footer layout. Serving static pages prevents the use of dynamic server-side features and makes template editing tedious.

## Selected Solution
We will convert the public website to standard Laravel Blade templates:
1. **Create `resources/views/layouts/site.blade.php`**: A shared master layout for the public pages containing the shared HTML `<head>`, Lucide initialization scripts, and the premium Header and Footer (removing the need for `components.js` injecting layout client-side).
2. **Migrate Page Content to Blades**:
   - `index.html` -> `resources/views/site/home.blade.php`
   - `pages/about.html` -> `resources/views/site/about.blade.php`
   - `pages/property.html` -> `resources/views/site/property.blade.php`
   - `pages/contact.html` -> `resources/views/site/contact.blade.php`
   - `pages/privacy.html` -> `resources/views/site/privacy.blade.php`
   - `pages/terms.html` -> `resources/views/site/terms.blade.php`
3. **Asset Link Adjustments**: All relative paths to images, CSS, and JS (e.g. `../assets/` or `assets/`) will be updated to point to `/assets/` or wrap with `{{ asset('assets/...') }}`.
4. **Link Routing Update**: Replace hardcoded HTML navigation links (`pages/about.html` or `../index.html`) with blade routes (e.g., `{{ route('site.about') }}`).
5. **Update Web Routes (`routes/web.php`)**: Update the routes to serve the Blade views instead of reading filesystem paths.
6. **Cleanup**: Remove `index.html`, `pages/` directory, and `public/pages/` directory.

## UI/UX Design Hierarchy
The shared public layout will extend the premium look-and-feel:
- **Shared Header**: Fully responsive dropdown menu matching the existing `components.js` layout.
- **Shared Footer**: Sleek dark slate layout matching the current layout.
- **Dynamic Active Tracking**: Blade routes will determine the active state indicator color (`text-amra-primary font-bold`) on navbar links dynamically on the server-side.

## Proposed Changes

### `routes/web.php`
- Change file responses to view responses:
  ```php
  Route::get('/', function () { return view('site.home'); })->name('site.home');
  Route::get('/about', function () { return view('site.about'); })->name('site.about');
  Route::get('/property', function () { return view('site.property'); })->name('site.property');
  Route::get('/contact', function () { return view('site.contact'); })->name('site.contact');
  Route::get('/privacy', function () { return view('site.privacy'); })->name('site.privacy');
  Route::get('/terms', function () { return view('site.terms'); })->name('site.terms');
  ```
- Remove dynamic `.html` fallback route since all links now resolve to Laravel view routes.

### `resources/views/layouts/site.blade.php` [NEW]
- Define the shared HTML structural skeleton, importing Tailwind, Outfit/Inter google fonts, and Lucide icons.
- Implement the HTML navigation bar and mobile drawer menu using blade helper directives.
- Implement the shared footer template.
- Add `@yield('content')` and `@yield('scripts')` entry points.

### `resources/views/site/*.blade.php` [NEW]
- Populate each view page extending `layouts.site`, wrapping content under `@section('content')` and custom JS under `@section('scripts')`.

## Verification Plan
- **Manual Verification**: Run/verify page navigation at `http://127.0.0.1:8181/` and check tabs (Home, Property, About, Contact). Verify that property search and live filter controls function correctly on Blade templates.
- **Automated Tests**: Execute `php artisan test` to verify route routing and controller status codes.
