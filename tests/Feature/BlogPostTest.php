<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the blog listing page (/blog) displays published posts.
     */
    public function test_blog_listing_page_renders_published_posts(): void
    {
        // 1. Create a published blog post
        $post = BlogPost::create([
            'title' => 'Exploring Real Estate in Mumbai',
            'slug' => 'exploring-real-estate-mumbai',
            'content' => '<p>Mumbai real estate is growing rapidly.</p>',
            'status' => 'publish',
            'published_at' => now(),
        ]);

        // 2. Create a draft blog post
        $draft = BlogPost::create([
            'title' => 'Draft Post Secrets',
            'slug' => 'draft-post-secrets',
            'content' => '<p>Secret draft content.</p>',
            'status' => 'draft',
            'published_at' => now(),
        ]);

        // 3. Make GET request to /blog
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('Exploring Real Estate in Mumbai');
        $response->assertDontSee('Draft Post Secrets');
    }

    /**
     * Test single blog post detail page displays post content.
     */
    public function test_single_blog_detail_page_renders_content(): void
    {
        $post = BlogPost::create([
            'title' => 'Lucknow Growth Corridors',
            'slug' => 'lucknow-growth-corridors',
            'content' => '<p>Detailed guide to Lucknow growth areas.</p>',
            'status' => 'publish',
            'published_at' => now(),
        ]);

        $response = $this->get('/blog/lucknow-growth-corridors');

        $response->assertStatus(200);
        $response->assertSee('Lucknow Growth Corridors');
        $response->assertSee('Detailed guide to Lucknow growth areas.', false);
    }

    /**
     * Test single blog post detail returns 404 for drafts or non-existent slugs.
     */
    public function test_single_blog_detail_returns_404_for_invalid_slugs(): void
    {
        $draft = BlogPost::create([
            'title' => 'Draft Secret Guide',
            'slug' => 'draft-secret-guide',
            'content' => '<p>Secret guide details.</p>',
            'status' => 'draft',
            'published_at' => now(),
        ]);

        // Check draft returns 404
        $this->get('/blog/draft-secret-guide')->assertStatus(404);

        // Check non-existent returns 404
        $this->get('/blog/does-not-exist')->assertStatus(404);
    }

    /**
     * Test homepage displays featured properties dynamically.
     */
    public function test_homepage_displays_featured_properties(): void
    {
        // 1. Create a featured property
        Property::create([
            'title' => 'Mumbai Gated Penthouse',
            'slug' => 'mumbai-gated-penthouse',
            'status' => 'publish',
            'is_featured' => true,
            'price' => 25000000,
        ]);

        // 2. Create a standard (non-featured) property
        Property::create([
            'title' => 'Lucknow Standard Row House',
            'slug' => 'lucknow-standard-row-house',
            'status' => 'publish',
            'is_featured' => false,
            'price' => 6000000,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        // Featured should be visible
        $response->assertSee('Mumbai Gated Penthouse');

        // Standard properties are not loaded in featured homepage list (max 6 featured are shown)
        $response->assertDontSee('Lucknow Standard Row House');
    }
}
