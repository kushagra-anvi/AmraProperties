<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoMeta
{
    public static function staticPage(string $key, string $url): array
    {
        $page = config("seo.static.{$key}", []);
        $title = $page['title'] ?? config('seo.site_name');
        $description = $page['description'] ?? 'Verified flats, villas, plots and commercial properties across top cities.';
        $image = $page['image'] ?? config('seo.default_image');

        return self::make([
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'type' => $page['type'] ?? 'website',
            'image' => $image,
            'published_at' => $page['published_at'] ?? null,
            'modified_at' => $page['modified_at'] ?? null,
            'twitter_label1' => $page['twitter_label1'] ?? null,
            'twitter_data1' => $page['twitter_data1'] ?? null,
            'schema' => self::webPageGraph($title, $description, $url, $image, [
                'breadcrumb_label' => $page['breadcrumb_label'] ?? $title,
                'published_at' => $page['published_at'] ?? null,
                'modified_at' => $page['modified_at'] ?? null,
            ]),
        ]);
    }

    public static function propertyIndex(Request $request, string $url): array
    {
        $location = (string) Str::of((string) $request->query('location'))->lower()->ucfirst();
        $type = (string) Str::of((string) $request->query('type'))->lower()->ucfirst();

        $parts = array_filter([$type ?: 'Properties', $location ? 'in ' . $location : null]);
        $title = implode(' ', $parts) . ' - Amra Property';
        $description = $location
            ? "Explore verified properties in {$location} with Amra Property. Compare flats, villas, plots, prices, amenities and possession details."
            : 'View verified properties by Amra Property. Compare flats, villas, plots, prices, amenities and possession details across top cities.';

        return self::make([
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'type' => 'website',
            'schema' => self::webPageGraph($title, $description, $url, config('seo.default_image'), [
                'breadcrumb_label' => 'Properties',
            ]),
        ]);
    }

    public static function property(Property $property, string $url): array
    {
        $title = html_entity_decode($property->title) . ' - Amra Property';
        $description = Str::limit(trim(strip_tags((string) $property->description)), 155);
        if ($description === '') {
            $description = trim(implode(' ', array_filter([
                html_entity_decode($property->title),
                $property->city ? 'in ' . $property->city : null,
                $property->formatted_price,
                $property->formatted_possession ? 'Possession: ' . $property->formatted_possession : null,
            ])));
        }

        $image = self::assetUrl($property->featured_image ?: (is_array($property->gallery) ? ($property->gallery[0] ?? null) : null));

        return self::make([
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'type' => 'article',
            'image' => $image,
            'published_at' => optional($property->created_at)->toIso8601String(),
            'modified_at' => optional($property->updated_at)->toIso8601String(),
            'schema' => self::propertyGraph($property, $title, $description, $url, $image),
        ]);
    }

    public static function compare(string $url): array
    {
        $title = 'Compare Properties - Amra Property';
        $description = 'Compare Amra Property listings by price, average price per square foot, possession, size, RERA status, amenities and location.';

        return self::make([
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'schema' => self::webPageGraph($title, $description, $url, config('seo.default_image'), [
                'breadcrumb_label' => 'Compare Properties',
            ]),
        ]);
    }

    public static function blogIndex(string $url): array
    {
        $title = 'Blog & News - Amra Property';
        $description = 'Read the latest updates, real estate trends, investment guides, and news from Amra Property.';

        return self::make([
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'schema' => self::webPageGraph($title, $description, $url, config('seo.default_image'), [
                'breadcrumb_label' => 'Blog',
            ]),
        ]);
    }

    public static function blog(BlogPost $post, string $url): array
    {
        $title = html_entity_decode($post->title) . ' - Amra Property';
        $description = $post->excerpt(155);
        $image = self::assetUrl($post->featured_image);

        return self::make([
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'type' => 'article',
            'image' => $image,
            'published_at' => optional($post->published_at ?: $post->created_at)->toIso8601String(),
            'modified_at' => optional($post->updated_at)->toIso8601String(),
            'schema' => self::blogGraph($post, $title, $description, $url, $image),
        ]);
    }

    public static function make(array $meta): array
    {
        return array_merge([
            'title' => config('seo.site_name'),
            'description' => 'Verified flats, villas, plots and commercial properties across top cities.',
            'canonical' => url()->current(),
            'robots' => config('seo.robots'),
            'locale' => config('seo.locale'),
            'type' => 'website',
            'image' => config('seo.default_image'),
            'twitter_card' => 'summary_large_image',
            'twitter_site' => config('seo.twitter_site'),
            'publisher' => config('seo.publisher'),
            'published_at' => null,
            'modified_at' => null,
            'twitter_label1' => null,
            'twitter_data1' => null,
            'schema' => [],
        ], $meta);
    }

    private static function webPageGraph(string $title, string $description, string $url, ?string $image, array $options = []): array
    {
        $image = $image ?: config('seo.default_image');
        $graph = [
            self::organization(),
            self::website(),
            [
                '@type' => 'WebPage',
                '@id' => $url . '#webpage',
                'url' => $url,
                'name' => $title,
                'isPartOf' => ['@id' => self::siteUrl('/#website')],
                'primaryImageOfPage' => ['@id' => $url . '#primaryimage'],
                'image' => ['@id' => $url . '#primaryimage'],
                'thumbnailUrl' => $image,
                'description' => $description,
                'breadcrumb' => ['@id' => $url . '#breadcrumb'],
                'inLanguage' => config('seo.locale'),
                'potentialAction' => [[
                    '@type' => 'ReadAction',
                    'target' => [$url],
                ]],
            ],
            [
                '@type' => 'ImageObject',
                'inLanguage' => config('seo.locale'),
                '@id' => $url . '#primaryimage',
                'url' => $image,
                'contentUrl' => $image,
                'caption' => $title,
            ],
            self::breadcrumb($url, $options['breadcrumb_label'] ?? $title),
        ];

        if (!empty($options['published_at'])) {
            $graph[2]['datePublished'] = $options['published_at'];
        }

        if (!empty($options['modified_at'])) {
            $graph[2]['dateModified'] = $options['modified_at'];
        }

        return $graph;
    }

    private static function propertyGraph(Property $property, string $title, string $description, string $url, ?string $image): array
    {
        $place = [
            '@type' => 'Place',
            '@id' => $url . '#place',
            'name' => html_entity_decode($property->title),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $property->address ?: '',
                'addressLocality' => $property->city ?: '',
                'addressRegion' => $property->state ?: '',
                'addressCountry' => 'IN',
            ],
        ];

        if ($property->latitude && $property->longitude) {
            $place['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => (string) $property->latitude,
                'longitude' => (string) $property->longitude,
            ];
        }

        return array_merge(
            self::webPageGraph($title, $description, $url, $image, [
                'breadcrumb_label' => html_entity_decode($property->title),
                'published_at' => optional($property->created_at)->toIso8601String(),
                'modified_at' => optional($property->updated_at)->toIso8601String(),
            ]),
            [
                $place,
                [
                    '@type' => 'RealEstateListing',
                    '@id' => $url . '#listing',
                    'name' => html_entity_decode($property->title),
                    'url' => $url,
                    'datePosted' => optional($property->created_at)->toIso8601String(),
                    'description' => $description,
                    'image' => array_values(array_filter([$image])),
                    'about' => ['@id' => $url . '#place'],
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => $property->price ?: null,
                        'priceCurrency' => 'INR',
                        'availability' => 'https://schema.org/InStock',
                    ],
                ],
            ]
        );
    }

    private static function blogGraph(BlogPost $post, string $title, string $description, string $url, ?string $image): array
    {
        return array_merge(
            self::webPageGraph($title, $description, $url, $image, [
                'breadcrumb_label' => html_entity_decode($post->title),
                'published_at' => optional($post->published_at ?: $post->created_at)->toIso8601String(),
                'modified_at' => optional($post->updated_at)->toIso8601String(),
            ]),
            [[
                '@type' => 'BlogPosting',
                '@id' => $url . '#article',
                'headline' => html_entity_decode($post->title),
                'description' => $description,
                'image' => array_values(array_filter([$image])),
                'datePublished' => optional($post->published_at ?: $post->created_at)->toIso8601String(),
                'dateModified' => optional($post->updated_at)->toIso8601String(),
                'author' => ['@type' => 'Organization', 'name' => config('seo.site_name')],
                'publisher' => ['@id' => self::siteUrl('/#organization')],
                'mainEntityOfPage' => ['@id' => $url . '#webpage'],
            ]]
        );
    }

    private static function organization(): array
    {
        return [
            '@type' => 'Organization',
            '@id' => self::siteUrl('/#organization'),
            'name' => config('seo.site_name'),
            'url' => self::siteUrl('/'),
            'logo' => [
                '@type' => 'ImageObject',
                '@id' => self::siteUrl('/#logo'),
                'url' => asset('assets/images/logo.png'),
                'caption' => config('seo.site_name'),
            ],
            'sameAs' => config('seo.same_as'),
        ];
    }

    private static function website(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => self::siteUrl('/#website'),
            'url' => self::siteUrl('/'),
            'name' => config('seo.site_name'),
            'description' => 'Buy and rent verified properties across top cities',
            'publisher' => ['@id' => self::siteUrl('/#organization')],
            'potentialAction' => [[
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('site.property') . '?q={search_term_string}',
                ],
                'query-input' => [
                    '@type' => 'PropertyValueSpecification',
                    'valueRequired' => true,
                    'valueName' => 'search_term_string',
                ],
            ]],
            'inLanguage' => config('seo.locale'),
        ];
    }

    private static function breadcrumb(string $url, string $label): array
    {
        return [
            '@type' => 'BreadcrumbList',
            '@id' => $url . '#breadcrumb',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => route('site.home'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $label,
                ],
            ],
        ];
    }

    private static function assetUrl(?string $path): ?string
    {
        if (!$path) {
            return config('seo.default_image');
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset($path);
    }

    private static function siteUrl(string $path = '/'): string
    {
        return rtrim(config('seo.domain'), '/') . '/' . ltrim($path, '/');
    }
}
