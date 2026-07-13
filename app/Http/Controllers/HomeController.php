<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Partner;
use App\Models\Property;
use App\Support\PropertyLinkTabs;
use App\Support\SeoMeta;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProperties = Property::with('configurations')
            ->where('status', 'publish')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $recommendedSellers = Partner::where('is_active', true)
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

        $seo = SeoMeta::staticPage('home', route('site.home'));

        $locations = Property::where('status', 'publish')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city')
            ->all();

        $latestPosts = BlogPost::where('status', 'publish')
            ->latest('published_at')
            ->latest()
            ->take(3)
            ->get();

        $propertyLinkTabs = app(PropertyLinkTabs::class)->build();

        return view('site.home', compact('featuredProperties', 'recommendedSellers', 'seo', 'locations', 'latestPosts', 'propertyLinkTabs'));
    }
}
