<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use Illuminate\Support\Str;

class ImportWordPressProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-wp-properties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import properties from the amarpropertydemo WordPress database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting properties import from WordPress database amarpropertydemo...');

        try {
            $pdo = new \PDO("mysql:host=127.0.0.1;dbname=amarpropertydemo", "root", "");
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->error('Failed to connect to the amarpropertydemo database: ' . $e->getMessage());
            return 1;
        }

        // Fetch published properties
        $stmt = $pdo->query("SELECT ID, post_title, post_name, post_content, post_status FROM wp_posts WHERE post_type = 'properties' AND post_status = 'publish'");
        $properties = $stmt->fetchAll();

        $this->info('Found ' . count($properties) . ' published properties.');

        $importedCount = 0;

        foreach ($properties as $wpProp) {
            $wpId = $wpProp['ID'];
            $title = $wpProp['post_title'];
            $slug = $wpProp['post_name'] ?: Str::slug($title);
            $description = $wpProp['post_content'];

            // Fetch meta fields
            $metaStmt = $pdo->prepare("SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = ?");
            $metaStmt->execute([$wpId]);
            $metaRows = $metaStmt->fetchAll();

            $meta = [];
            foreach ($metaRows as $row) {
                $key = $row['meta_key'];
                $val = $row['meta_value'];
                if ($key === 'es_property_gallery') {
                    $meta[$key][] = $val;
                } else {
                    $meta[$key] = $val;
                }
            }

            // Fetch property type (es_type)
            $typeStmt = $pdo->prepare("
                SELECT t.name, t.slug 
                FROM wp_terms t 
                JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id 
                JOIN wp_term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
                WHERE tr.object_id = ? AND tt.taxonomy = 'es_type'
                LIMIT 1
            ");
            $typeStmt->execute([$wpId]);
            $typeRow = $typeStmt->fetch();
            $wpType = $typeRow ? $typeRow['name'] : null;

            // Fetch amenities (es_amenities)
            $amenitiesStmt = $pdo->prepare("
                SELECT t.name 
                FROM wp_terms t 
                JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id 
                JOIN wp_term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
                WHERE tr.object_id = ? AND tt.taxonomy = 'es_amenities'
            ");
            $amenitiesStmt->execute([$wpId]);
            $amenitiesRows = $amenitiesStmt->fetchAll();
            $amenities = array_column($amenitiesRows, 'name');

            // Fetch city term names to resolve city/state from postmeta terms
            $cityId = $meta['es_property_city'] ?? null;
            $city = null;
            if ($cityId) {
                $cityStmt = $pdo->prepare("SELECT name FROM wp_terms WHERE term_id = ? LIMIT 1");
                $cityStmt->execute([$cityId]);
                $cityRow = $cityStmt->fetch();
                $city = $cityRow ? $cityRow['name'] : null;
            }

            $stateId = $meta['es_property_state'] ?? null;
            $state = null;
            if ($stateId) {
                $stateStmt = $pdo->prepare("SELECT name FROM wp_terms WHERE term_id = ? LIMIT 1");
                $stateStmt->execute([$stateId]);
                $stateRow = $stateStmt->fetch();
                $state = $stateRow ? $stateRow['name'] : null;
            }

            $countryId = $meta['es_property_country'] ?? null;
            $country = null;
            if ($countryId) {
                $countryStmt = $pdo->prepare("SELECT name FROM wp_terms WHERE term_id = ? LIMIT 1");
                $countryStmt->execute([$countryId]);
                $countryRow = $countryStmt->fetch();
                $country = $countryRow ? $countryRow['name'] : null;
            }

            // Resolve featured image URL (_thumbnail_id)
            $thumbnailId = $meta['_thumbnail_id'] ?? null;
            $featuredImage = null;
            if ($thumbnailId) {
                $imgStmt = $pdo->prepare("SELECT guid FROM wp_posts WHERE ID = ? AND post_type = 'attachment' LIMIT 1");
                $imgStmt->execute([$thumbnailId]);
                $imgRow = $imgStmt->fetch();
                $featuredImage = $imgRow ? $this->normalizeImageUrl($imgRow['guid']) : null;
            }

            // Resolve gallery image URLs
            $galleryIds = $meta['es_property_gallery'] ?? [];
            $gallery = [];
            foreach ($galleryIds as $gId) {
                if (empty($gId)) continue;
                $imgStmt = $pdo->prepare("SELECT guid FROM wp_posts WHERE ID = ? AND post_type = 'attachment' LIMIT 1");
                $imgStmt->execute([$gId]);
                $imgRow = $imgStmt->fetch();
                if ($imgRow) {
                    $gallery[] = $this->normalizeImageUrl($imgRow['guid']);
                }
            }

            // Extract developer and RERA
            $developerName = $meta['es_property_project-by1664962801f633d50f1ac746'] ?? null;
            $reraNumber = $meta['es_property_rera1661800542f630d105e5a327'] ?? null;
            $configuration = $meta['es_property_configuration'] ?? $wpType;

            $price = isset($meta['es_property_price']) && is_numeric($meta['es_property_price']) ? (int)$meta['es_property_price'] : null;
            $bedrooms = isset($meta['es_property_bedrooms']) && is_numeric($meta['es_property_bedrooms']) ? (int)$meta['es_property_bedrooms'] : null;
            $bathrooms = isset($meta['es_property_bathrooms']) && is_numeric($meta['es_property_bathrooms']) ? (int)$meta['es_property_bathrooms'] : null;
            $area = isset($meta['es_property_area']) && is_numeric($meta['es_property_area']) ? (int)$meta['es_property_area'] : null;
            $areaUnit = $meta['es_property_area_unit'] ?? 'sq_ft';

            $address = $meta['es_property_address'] ?? null;
            $latitude = isset($meta['es_property_latitude']) && is_numeric($meta['es_property_latitude']) ? $meta['es_property_latitude'] : null;
            $longitude = isset($meta['es_property_longitude']) && is_numeric($meta['es_property_longitude']) ? $meta['es_property_longitude'] : null;

            $isFeatured = (isset($meta['es_property_sort_featured']) && $meta['es_property_sort_featured'] == '1') || (isset($meta['es_property_featured']) && $meta['es_property_featured'] == '1');
            $isReraApproved = !empty($reraNumber);


            // Insert or update Property in Laravel DB
            Property::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'description' => $description,
                    'price' => $price,
                    'bedrooms' => $bedrooms,
                    'bathrooms' => $bathrooms,
                    'area' => $area,
                    'area_unit' => $areaUnit,
                    'address' => $address,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'rera_number' => $reraNumber,
                    'featured_image' => $featuredImage,
                    'gallery' => $gallery,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'status' => 'publish',
                    'is_featured' => $isFeatured,
                    'is_rera_approved' => $isReraApproved,
                    'configuration' => $configuration,
                    'developer_name' => $developerName,
                    'amenities' => $amenities,
                ]
            );

            $importedCount++;
        }

        $this->info("Import completed successfully! {$importedCount} properties were imported/updated.");
    }

    /**
     * Strip host from URL to make it root-relative.
     */
    private function normalizeImageUrl($url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $parsed = parse_url($url);
        if (isset($parsed['path']) && str_contains($parsed['path'], 'wp-content/uploads/')) {
            return $parsed['path'];
        }

        return $url;
    }
}
