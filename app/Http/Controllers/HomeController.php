<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Partner;
use App\Models\Property;
use App\Models\PropertyTag;
use App\Support\SeoMeta;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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

        $propertyLinkTabs = $this->propertyLinkTabs();

        return view('site.home', compact('featuredProperties', 'recommendedSellers', 'seo', 'locations', 'latestPosts', 'propertyLinkTabs'));
    }

    private function propertyLinkTabs(): array
    {
        $publishedProperties = Property::with('tags')
            ->where('status', 'publish')
            ->select(['id', 'title', 'city', 'configuration', 'listing_type', 'possession_status'])
            ->get();

        $cityLinks = $this->cityLinks($publishedProperties);
        $tagLinks = $this->tagLinks();
        $projectLinks = $this->projectLinks($tagLinks);
        $popularSearchLinks = $this->popularSearchLinks($tagLinks);

        return collect([
            'buyers' => [
                'label' => 'For Buyers',
                'heading' => 'Popular searches for buyers',
                'links' => array_merge(
                    $this->cityTypeLinks($publishedProperties, 'sale', 8),
                    count($cityLinks) ? [array_merge($cityLinks[0], ['label' => 'All ' . strtolower($cityLinks[0]['label'])])] : []
                ),
            ],
            'tenants' => [
                'label' => 'For Tenants',
                'heading' => 'Popular searches for tenants',
                'links' => $this->cityTypeLinks($publishedProperties, 'rent', 9),
            ],
            'projects' => [
                'label' => 'Projects',
                'heading' => 'Explore property projects',
                'links' => array_slice($projectLinks, 0, 8),
            ],
            'cities' => [
                'label' => 'Popular Cities',
                'heading' => 'Browse by city and property type',
                'links' => $cityLinks,
            ],
            'popular' => [
                'label' => 'Popular Searches',
                'heading' => 'Helpful property related searches',
                'links' => $popularSearchLinks,
            ],
        ])
            ->map(fn (array $tab) => array_merge($tab, ['links' => array_slice($tab['links'], 0, 12)]))
            ->filter(fn (array $tab) => count($tab['links']) > 0)
            ->all();
    }

    private function cityLinks(Collection $publishedProperties): array
    {
        return $publishedProperties
            ->pluck('city')
            ->filter()
            ->map(fn ($city) => Str::of($city)->trim()->title()->toString())
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(8)
            ->map(fn (string $city) => [
                'label' => "Properties in {$city}",
                'url' => route('site.property', ['location' => $city]),
            ])
            ->all();
    }

    private function cityTypeLinks(Collection $publishedProperties, string $listingType, int $limit): array
    {
        return $publishedProperties
            ->filter(fn (Property $property) => ($property->listing_type ?: 'sale') === $listingType && filled($property->city))
            ->groupBy(fn (Property $property) => Str::of($property->city)->trim()->title()->toString() . '|' . $this->propertyTypeSlug($property))
            ->map(function ($properties, string $key) use ($listingType) {
                [$city, $type] = explode('|', $key);
                $intent = $listingType === 'rent' ? 'for rent' : 'to buy';

                return [
                    'count' => $properties->count(),
                    'label' => $this->propertyTypeLabel($type) . " {$intent} in {$city}",
                    'url' => route('site.property', [
                        'listing_type' => $listingType,
                        'type' => $type,
                        'location' => $city,
                    ]),
                ];
            })
            ->sortByDesc('count')
            ->take($limit)
            ->map(fn (array $link) => ['label' => $link['label'], 'url' => $link['url']])
            ->values()
            ->all();
    }

    private function tagLinks(): array
    {
        return PropertyTag::where('is_active', true)
            ->whereHas('properties', fn ($query) => $query->where('status', 'publish'))
            ->withCount(['properties as published_properties_count' => fn ($query) => $query->where('status', 'publish')])
            ->orderByDesc('published_properties_count')
            ->orderBy('name')
            ->limit(12)
            ->get()
            ->map(fn (PropertyTag $tag) => [
                'label' => Str::of($tag->name)->title()->toString(),
                'url' => route('site.property', ['tag' => $tag->slug]),
            ])
            ->all();
    }

    private function projectLinks(array $tagLinks): array
    {
        $projectTagSlugs = ['new-launch', 'rera-approved', 'premium-builder', 'under-construction', 'possession-soon', 'luxury', 'affordable', 'investment'];
        $projectLinks = collect($tagLinks)
            ->filter(fn (array $link) => in_array(Str::slug($link['label']), $projectTagSlugs, true))
            ->values()
            ->all();

        if (count($projectLinks) >= 4) {
            return $projectLinks;
        }

        return array_merge($projectLinks, collect([
            ['label' => 'RERA approved projects', 'url' => route('site.property', ['tag' => 'rera-approved'])],
            ['label' => 'New launch projects', 'url' => route('site.property', ['tag' => 'new-launch'])],
            ['label' => 'Under construction projects', 'url' => route('site.property', ['tag' => 'under-construction'])],
            ['label' => 'Ready to move projects', 'url' => route('site.property', ['tag' => 'ready-to-move'])],
        ])->reject(fn (array $fallback) => collect($projectLinks)->contains('url', $fallback['url']))->all());
    }

    private function popularSearchLinks(array $tagLinks): array
    {
        return collect($tagLinks)
            ->take(6)
            ->merge([
                ['label' => '1 BHK flats', 'url' => route('site.property', ['q' => '1BHK'])],
                ['label' => '2 BHK flats', 'url' => route('site.property', ['q' => '2BHK'])],
                ['label' => '3 BHK flats', 'url' => route('site.property', ['q' => '3BHK'])],
                ['label' => 'All properties', 'url' => route('site.property')],
            ])
            ->unique('url')
            ->take(10)
            ->values()
            ->all();
    }

    private function propertyTypeLabel(string $type): string
    {
        return match ($type) {
            'flat' => 'Flats',
            'villa' => 'Villas',
            'plot' => 'Plots',
            'commercial' => 'Commercial properties',
            default => 'Properties',
        };
    }

    private function propertyTypeSlug(Property $property): string
    {
        return $property->type_category ?: 'flat';
    }
}
