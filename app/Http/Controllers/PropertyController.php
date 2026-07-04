<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Partner;
use App\Support\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     */
    public function index(Request $request): View
    {
        $query = Property::with('configurations')->where('status', 'publish');

        // Search query filtering
        if ($q = $request->input('q')) {
            $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%')
                    ->orWhere('developer_name', 'like', '%' . $q . '%')
                    ->orWhere('address', 'like', '%' . $q . '%')
                    ->orWhere('city', 'like', '%' . $q . '%')
                    ->orWhere('configuration', 'like', '%' . $q . '%');

                // Extract BHK query numbers (e.g. 2 BHK)
                if (preg_match_all('/(\d+)\s*bhk/i', $q, $matches)) {
                    foreach ($matches[1] as $bhkNum) {
                        $sub->orWhere('bedrooms', $bhkNum)
                            ->orWhere('configuration', 'like', '%' . $bhkNum . '%bhk%');
                    }
                }
            });
        }

        // Location filtering (dynamic city check)
        if ($location = strtolower($request->input('location') ?? '')) {
            if ($location === 'mumbai') {
                $query->where(function($sub) {
                    $mumbaiCities = ['mumbai', 'thane', 'navi mumbai', 'panvel', 'dombivli', 'chembur', 'prabhadevi', 'versova', 'airoli', 'kharghar', 'kolshet', 'kapurbawdi'];
                    foreach ($mumbaiCities as $mc) {
                        $sub->orWhere('city', 'like', '%' . $mc . '%');
                    }
                });
            } elseif ($location === 'lucknow') {
                $query->where(function($sub) {
                    $lucknowCities = ['lucknow', 'hazratganj', 'gomti nagar', 'aliganj'];
                    foreach ($lucknowCities as $lc) {
                        $sub->orWhere('city', 'like', '%' . $lc . '%');
                    }
                });
            } else {
                $query->where('city', 'like', '%' . $location . '%');
            }
        }

        // Rent / Sale filtering
        if ($listingType = $request->input('listing_type')) {
            if (in_array($listingType, ['sale', 'rent'])) {
                $query->where('listing_type', $listingType);
            }
        }

        // Property type filtering
        if ($type = $request->input('type')) {
            if ($type === 'flat') {
                $query->where(function($sub) {
                    $sub->where('configuration', 'like', '%bhk%')
                        ->orWhere('configuration', 'like', '%flat%')
                        ->orWhere('configuration', 'like', '%apartment%')
                        ->orWhere('title', 'like', '%flat%')
                        ->orWhere('title', 'like', '%apartment%')
                        ->orWhere('title', 'like', '%bhk%')
                        ->orWhere(function($sub2) {
                            $sub2->where('configuration', 'not like', '%villa%')
                                 ->where('configuration', 'not like', '%house%')
                                 ->where('title', 'not like', '%villa%')
                                 ->where('title', 'not like', '%house%')
                                 ->where('configuration', 'not like', '%plot%')
                                 ->where('configuration', 'not like', '%land%')
                                 ->where('title', 'not like', '%plot%')
                                 ->where('title', 'not like', '%land%')
                                 ->where('configuration', 'not like', '%commercial%')
                                 ->where('configuration', 'not like', '%office%')
                                 ->where('configuration', 'not like', '%shop%')
                                 ->where('title', 'not like', '%commercial%')
                                 ->where('title', 'not like', '%office%')
                                 ->where('title', 'not like', '%shop%');
                        });
                });
            } elseif ($type === 'villa') {
                $query->where(function($sub) {
                    $sub->where('configuration', 'like', '%villa%')
                        ->orWhere('configuration', 'like', '%house%')
                        ->orWhere('title', 'like', '%villa%')
                        ->orWhere('title', 'like', '%house%');
                });
            } elseif ($type === 'plot') {
                $query->where(function($sub) {
                    $sub->where('configuration', 'like', '%plot%')
                        ->orWhere('configuration', 'like', '%land%')
                        ->orWhere('title', 'like', '%plot%')
                        ->orWhere('title', 'like', '%land%');
                });
            } elseif ($type === 'commercial' || $type === 'office') {
                $query->where(function($sub) {
                    $sub->where('configuration', 'like', '%commercial%')
                        ->orWhere('configuration', 'like', '%office%')
                        ->orWhere('configuration', 'like', '%shop%')
                        ->orWhere('title', 'like', '%commercial%')
                        ->orWhere('title', 'like', '%office%')
                        ->orWhere('title', 'like', '%shop%');
                });
            }
        }

        // Slider-based Price range filtering
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = (int) $request->input('min_price', 0);
            $maxPrice = (int) $request->input('max_price', 9999999999);
            $query->where(function ($sub) use ($minPrice, $maxPrice) {
                $sub->whereBetween('price', [$minPrice, $maxPrice])
                    ->orWhereHas('configurations', function ($subConfig) use ($minPrice, $maxPrice) {
                        $subConfig->whereBetween('price', [$minPrice, $maxPrice]);
                    });
            });
        } elseif ($request->filled('budget')) {
            // Backward-compatible fallback for legacy budget queries
            $budget = $request->input('budget');
            $minPrice = 0;
            $maxPrice = 9999999999;
            if ($budget === '25-50') {
                $minPrice = 2500000;
                $maxPrice = 5000000;
            } elseif ($budget === '50-100') {
                $minPrice = 5000000;
                $maxPrice = 10000000;
            } elseif ($budget === '100+') {
                $minPrice = 10000000;
            }
            $query->where(function ($sub) use ($minPrice, $maxPrice) {
                $sub->whereBetween('price', [$minPrice, $maxPrice])
                    ->orWhereHas('configurations', function ($subConfig) use ($minPrice, $maxPrice) {
                        $subConfig->whereBetween('price', [$minPrice, $maxPrice]);
                    });
            });
        }

        $properties = $query->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->paginate(9);

        // Fetch dynamic locations
        $locations = Property::where('status', 'publish')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city')
            ->all();

        $seo = SeoMeta::propertyIndex($request, route('site.property'));

        return view('site.property', compact('properties', 'seo', 'locations'));
    }

    /**
     * Display the specified property.
     */
    public function show(string $slug): View
    {
        $property = Property::with(['partner', 'configurations'])
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                      ->orWhere('slug', urlencode($slug))
                      ->orWhere('slug', rawurlencode($slug))
                      ->orWhere('slug', urldecode($slug));
            })
            ->where('status', 'publish')
            ->firstOrFail();

        // Retrieve up to 3 related properties in the same city, excluding the current one
        $relatedProperties = Property::with('configurations')->where('status', 'publish')
            ->where('id', '!=', $property->id)
            ->where(function($query) use ($property) {
                $query->where('city', $property->city)
                      ->orWhere('configuration', 'like', '%' . $property->bedrooms . '%bhk%');
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $recommendedSellers = Partner::query()
            ->where('is_active', true)
            ->whereIn('type', ['agent', 'developer'])
            ->where(function ($query) use ($property) {
                $query->where('city', $property->city);

                if ($property->city) {
                    $query->orWhereJsonContains('service_areas', $property->city);
                }
            })
            ->withCount(['properties as total_listings'])
            ->orderByDesc('total_listings')
            ->limit(8)
            ->get()
            ->sortByDesc(fn (Partner $partner) => [
                'free' => 0,
                'starter' => 10,
                'growth' => 20,
                'premium' => 30,
                'customise' => 30,
            ][$partner->package] ?? 0)
            ->values();

        if ($recommendedSellers->isEmpty()) {
            $recommendedSellers = Partner::query()
                ->where('is_active', true)
                ->whereIn('type', ['agent', 'developer'])
                ->withCount(['properties as total_listings'])
                ->orderByDesc('total_listings')
                ->limit(8)
                ->get()
                ->sortByDesc(fn (Partner $partner) => [
                    'free' => 0,
                    'starter' => 10,
                    'growth' => 20,
                    'premium' => 30,
                    'customise' => 30,
                ][$partner->package] ?? 0)
                ->values();
        }

        if ($property->partner && !$recommendedSellers->contains('id', $property->partner_id)) {
            $recommendedSellers->prepend($property->partner);
        }

        $seo = SeoMeta::property($property, route('site.property.show', $property->slug));

        return view('site.property-detail', compact('property', 'relatedProperties', 'recommendedSellers', 'seo'));
    }

    public function compare(Request $request): View
    {
        $ids = collect($request->input('properties', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->take(20)
            ->values();

        $properties = Property::with('configurations')->where('status', 'publish')
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn (Property $property) => $ids->search($property->id))
            ->values();

        $selectableProperties = Property::where('status', 'publish')
            ->whereNotIn('id', $ids)
            ->orderBy('title')
            ->get(['id', 'title', 'city', 'configuration']);

        $seo = SeoMeta::compare(route('site.compare'));

        return view('site.compare', compact('properties', 'selectableProperties', 'seo'));
    }
}
