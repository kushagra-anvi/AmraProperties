<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlogPost;
use Illuminate\Support\Str;

class ImportWordPressBlogPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-wp-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import blog posts from the amarpropertydemo WordPress database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting blog posts import from WordPress database...');

        try {
            $pdo = new \PDO("mysql:host=127.0.0.1;dbname=amarpropertydemo", "root", "");
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->error('Failed to connect to the WordPress database: ' . $e->getMessage());
            return 1;
        }

        // Fetch published posts
        $stmt = $pdo->query("SELECT ID, post_title, post_name, post_content, post_date, post_status FROM wp_posts WHERE post_type = 'post' AND post_status = 'publish'");
        $posts = $stmt->fetchAll();

        $this->info('Found ' . count($posts) . ' published blog posts.');

        $importedCount = 0;

        foreach ($posts as $wpPost) {
            $wpId = $wpPost['ID'];
            $title = $this->sanitizeUtf8($wpPost['post_title']);
            $slug = $wpPost['post_name'] ?: Str::slug($title);
            $slug = $this->sanitizeUtf8($slug);
            $content = $this->sanitizeUtf8($wpPost['post_content']);
            $publishedAt = $wpPost['post_date'];

            // Fetch meta field for thumbnail/featured image
            $metaStmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_thumbnail_id' LIMIT 1");
            $metaStmt->execute([$wpId]);
            $thumbnailId = $metaStmt->fetchColumn();

            $featuredImage = null;
            if ($thumbnailId) {
                $imgStmt = $pdo->prepare("SELECT guid FROM wp_posts WHERE ID = ? AND post_type = 'attachment' LIMIT 1");
                $imgStmt->execute([$thumbnailId]);
                $guid = $imgStmt->fetchColumn();
                if ($guid) {
                    $featuredImage = $this->normalizeImageUrl($guid);
                    $featuredImage = $this->sanitizeUtf8($featuredImage);
                }
            }

            // Create or update the BlogPost record in Laravel
            BlogPost::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'content' => $content,
                    'featured_image' => $featuredImage,
                    'status' => 'publish',
                    'published_at' => $publishedAt,
                ]
            );

            $importedCount++;
        }

        $this->info("Import completed successfully! {$importedCount} blog posts were imported/updated.");
    }

    /**
     * Sanitize string to remove any invalid UTF-8 bytes.
     */
    private function sanitizeUtf8(string $string): string
    {
        // Strip invalid UTF-8 bytes using iconv
        $sanitized = @iconv('UTF-8', 'UTF-8//IGNORE', $string);
        if ($sanitized === false) {
            // Fallback: replace non-ascii/corrupted bytes using regex
            return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\x9F]/u', '', $string);
        }
        return $sanitized;
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
