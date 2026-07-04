<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PropertyAdminController extends Controller
{
    /**
     * Display a listing of the properties in CRM.
     */
    public function index(Request $request): View
    {
        $query = Property::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('developer_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('configuration', 'like', "%{$search}%");
            });
        }

        $properties = $query->orderByDesc('created_at')->paginate(15);
        return view('crm.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create(): View
    {
        $partners = Partner::where('is_active', true)->orderBy('company_name')->get();
        return view('crm.properties.create', compact('partners'));
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|integer|min:0',
            'avg_price_per_sqft' => 'nullable|numeric|min:0',
            'possession_date' => 'nullable|date',
            'possession_status' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|integer|min:0',
            'area_unit' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'rera_number' => 'nullable|string|max:255',
            'configuration' => 'nullable|string|max:255',
            'developer_name' => 'nullable|string|max:255',
            'partner_id' => 'nullable|exists:partners,id',
            'contact_phone' => 'nullable|string|max:20',
            'contact_whatsapp' => 'nullable|string|max:20',
            'video_urls' => 'nullable|string',
            'status' => 'required|in:publish,draft',
            'listing_type' => 'required|in:sale,rent',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'featured_image' => 'nullable|image|max:5120',
            'featured_image_url' => 'nullable|string|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:5120',
            'gallery_urls' => 'nullable|string',
            'amenities' => 'nullable|array',
            'partners' => 'nullable|array',
            'partners.*' => 'exists:partners,id',
            'configurations' => 'nullable|array',
            'configurations.*.name' => 'required|string|max:255',
            'configurations.*.price' => 'nullable|integer|min:0',
            'configurations.*.area' => 'nullable|integer|min:0',
            'configurations.*.area_unit' => 'required|string',
            'configurations.*.bedrooms' => 'nullable|integer|min:0',
            'configurations.*.bathrooms' => 'nullable|integer|min:0',
            'configurations.*.status' => 'required|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(1000, 9999);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_rera_approved'] = $request->has('is_rera_approved');
        
        // Handle Featured Image Upload or URL
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('properties', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        } elseif ($request->filled('featured_image_url')) {
            $validated['featured_image'] = $request->input('featured_image_url');
        }

        // Handle Gallery Uploads and URLs combined
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('properties/gallery', 'public');
                $galleryPaths[] = '/storage/' . $path;
            }
        }
        if ($request->filled('gallery_urls')) {
            $urlLines = collect(preg_split('/\r\n|\r|\n/', $request->input('gallery_urls')))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values()
                ->all();
            $galleryPaths = array_merge($galleryPaths, $urlLines);
        }
        $validated['gallery'] = $galleryPaths;

        $validated['video_urls'] = $this->parseLines($validated['video_urls'] ?? null);

        $property = Property::create($validated);

        if ($request->has('partners')) {
            $property->partners()->sync($request->input('partners'));
        }

        if ($request->has('configurations')) {
            foreach ($request->input('configurations') as $config) {
                if (!empty($config['name'])) {
                    $property->configurations()->create($config);
                }
            }
        }

        return redirect()->route('crm.properties.index')->with('success', 'Property created successfully.');
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property): View
    {
        $partners = Partner::where('is_active', true)->orderBy('company_name')->get();
        return view('crm.properties.edit', compact('property', 'partners'));
    }

    /**
     * Update the specified property in storage.
     */
    public function update(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|integer|min:0',
            'avg_price_per_sqft' => 'nullable|numeric|min:0',
            'possession_date' => 'nullable|date',
            'possession_status' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|integer|min:0',
            'area_unit' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'rera_number' => 'nullable|string|max:255',
            'configuration' => 'nullable|string|max:255',
            'developer_name' => 'nullable|string|max:255',
            'partner_id' => 'nullable|exists:partners,id',
            'contact_phone' => 'nullable|string|max:20',
            'contact_whatsapp' => 'nullable|string|max:20',
            'video_urls' => 'nullable|string',
            'status' => 'required|in:publish,draft',
            'listing_type' => 'required|in:sale,rent',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'featured_image' => 'nullable|image|max:5120',
            'featured_image_url' => 'nullable|string|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:5120',
            'gallery_urls' => 'nullable|string',
            'amenities' => 'nullable|array',
            'partners' => 'nullable|array',
            'partners.*' => 'exists:partners,id',
            'configurations' => 'nullable|array',
            'configurations.*.name' => 'required|string|max:255',
            'configurations.*.price' => 'nullable|integer|min:0',
            'configurations.*.area' => 'nullable|integer|min:0',
            'configurations.*.area_unit' => 'required|string',
            'configurations.*.bedrooms' => 'nullable|integer|min:0',
            'configurations.*.bathrooms' => 'nullable|integer|min:0',
            'configurations.*.status' => 'required|string',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_rera_approved'] = $request->has('is_rera_approved');
        
        // Handle Featured Image Upload or URL
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('properties', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        } elseif ($request->filled('featured_image_url')) {
            $validated['featured_image'] = $request->input('featured_image_url');
        }

        // Handle Gallery Uploads and URLs combined
        $newGalleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('properties/gallery', 'public');
                $newGalleryPaths[] = '/storage/' . $path;
            }
        }
        if ($request->filled('gallery_urls')) {
            $urlLines = collect(preg_split('/\r\n|\r|\n/', $request->input('gallery_urls')))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values()
                ->all();
            $newGalleryPaths = array_merge($newGalleryPaths, $urlLines);
        }
        if ($request->hasFile('gallery') || $request->filled('gallery_urls')) {
            $validated['gallery'] = $newGalleryPaths;
        }

        $validated['video_urls'] = $this->parseLines($validated['video_urls'] ?? null);

        $property->update($validated);

        $property->partners()->sync($request->input('partners', []));

        $property->configurations()->delete();
        if ($request->has('configurations')) {
            foreach ($request->input('configurations') as $config) {
                if (!empty($config['name'])) {
                    $property->configurations()->create($config);
                }
            }
        }

        return redirect()->route('crm.properties.index')->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(Property $property): RedirectResponse
    {
        $property->delete();
        return redirect()->route('crm.properties.index')->with('success', 'Property deleted successfully.');
    }

    private function parseLines(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value ?? ''))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
