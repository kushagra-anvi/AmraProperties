<?php

namespace App\Support;

use App\Models\Property;
use App\Models\PropertyTag;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PropertyLinkTabs
{
    public function build(): array
    {
        $publishedProperties = Property::with('tags')
            ->where('status', 'publish')
            ->select(['id', 'title', 'city', 'configuration', 'listing_type', 'possession_status'])
            ->get();

        $cityLinks = $this->cityLinks($publishedProperties);
        $tagLinks = $this->tagLinks($publishedProperties);
        $projectLinks = $this->projectLinks($tagLinks);
        $popularSearchLinks = $this->popularSearchLinks($publishedProperties, $tagLinks);

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

    private function tagLinks(Collection $publishedProperties): array
    {
        $attachedTagSlugs = $publishedProperties
            ->flatMap(fn (Property $property) => $property->tags->pluck('slug'))
            ->filter()
            ->unique()
            ->values();

        if ($attachedTagSlugs->isEmpty()) {
            return [];
        }

        return PropertyTag::where('is_active', true)
            ->whereIn('slug', $attachedTagSlugs)
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

        return collect($tagLinks)
            ->filter(fn (array $link) => in_array(Str::slug($link['label']), $projectTagSlugs, true))
            ->values()
            ->all();
    }

    private function popularSearchLinks(Collection $publishedProperties, array $tagLinks): array
    {
        $configurationLinks = $publishedProperties
            ->flatMap(function (Property $property) {
                $haystack = strtolower(trim(($property->configuration ?? '') . ' ' . ($property->title ?? '')));

                preg_match_all('/(\d+)\s*bhk/i', $haystack, $matches);

                return collect($matches[1] ?? [])
                    ->unique()
                    ->map(fn (string $bedrooms) => (int) $bedrooms);
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(4)
            ->map(fn (int $bedrooms) => [
                'label' => "{$bedrooms} BHK flats",
                'url' => route('site.property', ['q' => "{$bedrooms}BHK"]),
            ])
            ->all();

        return collect($tagLinks)
            ->take(6)
            ->merge($configurationLinks)
            ->when($publishedProperties->isNotEmpty(), fn (Collection $links) => $links->push(['label' => 'All properties', 'url' => route('site.property')]))
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
