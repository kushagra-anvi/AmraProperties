<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test properties render dynamically when present in the database.
     */
    public function test_properties_render_dynamically_on_property_page(): void
    {
        // 1. Create sample properties
        $propertyFeatured = Property::create([
            'title' => 'Premium 3BHK Apartment',
            'slug' => 'premium-3bhk-apartment',
            'description' => 'A luxury flat in the heart of Mumbai.',
            'price' => 12500000, // 1.25 Cr
            'bedrooms' => 3,
            'bathrooms' => 3,
            'area' => 1800,
            'area_unit' => 'sq_ft',
            'address' => 'Andheri East',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'status' => 'publish',
            'is_featured' => true,
            'is_rera_approved' => true,
            'rera_number' => 'P51800012345',
            'configuration' => '3BHK',
            'developer_name' => 'Amra Builders',
        ]);

        $propertyStandard = Property::create([
            'title' => 'Cosy 1BHK Flat',
            'slug' => 'cosy-1bhk-flat',
            'description' => 'Affordable and cosy housing in Lucknow.',
            'price' => 3500000, // 35 Lac
            'bedrooms' => 1,
            'bathrooms' => 1,
            'area' => 650,
            'area_unit' => 'sq_ft',
            'address' => 'Hazratganj',
            'city' => 'Lucknow',
            'state' => 'Uttar Pradesh',
            'status' => 'publish',
            'is_featured' => false,
            'is_rera_approved' => false,
            'configuration' => '1BHK Flat',
        ]);

        // 2. Fetch the /property page
        $response = $this->get('/property');

        $response->assertStatus(200);

        // 3. Assert properties appear
        $response->assertSee('Premium 3BHK Apartment');
        $response->assertSee('Cosy 1BHK Flat');

        // 4. Check prices are formatted correctly (using getFormattedPriceAttribute)
        $response->assertSee('₹1.25 Cr');
        $response->assertSee('₹35.00 Lac');

        // 5. Check filter attributes (data-location, data-type, data-budget)
        $response->assertSee('data-location="mumbai"', false);
        $response->assertSee('data-location="lucknow"', false);
        $response->assertSee('data-budget="100+"', false); // 1.25 Cr is 100+
        $response->assertSee('data-budget="25-50"', false); // 35 Lac is 25-50

        // 6. Check custom badges
        $response->assertSee('Featured Listing');
        $response->assertSee('RERA Approved: P51800012345');
    }

    /**
     * Test draft or non-published properties are excluded from the page.
     */
    public function test_draft_properties_are_not_rendered(): void
    {
        Property::create([
            'title' => 'Draft Apartment',
            'slug' => 'draft-apartment',
            'status' => 'draft',
            'price' => 5000000,
        ]);

        $response = $this->get('/property');
        $response->assertStatus(200);
        $response->assertDontSee('Draft Apartment');
    }

    /**
     * Test that server-side filters return only matching properties.
     */
    public function test_server_side_filtering_returns_correct_properties(): void
    {
        // 1. Create Mumbai flat in 100+ range
        $mumbaiFlat = Property::create([
            'title' => 'Mumbai Luxury Flat',
            'slug' => 'mumbai-luxury-flat',
            'description' => 'A luxury flat in Mumbai.',
            'price' => 12000000,
            'city' => 'Mumbai',
            'status' => 'publish',
            'configuration' => '3BHK Flat',
        ]);

        // 2. Create Lucknow plot in 25-50 range
        $lucknowPlot = Property::create([
            'title' => 'Lucknow Premium Plot',
            'slug' => 'lucknow-premium-plot',
            'description' => 'A plot in Lucknow.',
            'price' => 3000000,
            'city' => 'Lucknow',
            'status' => 'publish',
            'configuration' => 'Plots',
        ]);

        // Assert query filter 'q'
        $response = $this->get('/property?q=Plot');
        $response->assertStatus(200);
        $response->assertSee('Lucknow Premium Plot');
        $response->assertDontSee('Mumbai Luxury Flat');

        // Assert location filter 'location=mumbai'
        $response = $this->get('/property?location=mumbai');
        $response->assertStatus(200);
        $response->assertSee('Mumbai Luxury Flat');
        $response->assertDontSee('Lucknow Premium Plot');

        // Assert location filter 'location=lucknow'
        $response = $this->get('/property?location=lucknow');
        $response->assertStatus(200);
        $response->assertSee('Lucknow Premium Plot');
        $response->assertDontSee('Mumbai Luxury Flat');

        // Assert type filter 'type=flat'
        $response = $this->get('/property?type=flat');
        $response->assertStatus(200);
        $response->assertSee('Mumbai Luxury Flat');
        $response->assertDontSee('Lucknow Premium Plot');

        // Assert type filter 'type=plot'
        $response = $this->get('/property?type=plot');
        $response->assertStatus(200);
        $response->assertSee('Lucknow Premium Plot');
        $response->assertDontSee('Mumbai Luxury Flat');

        // Assert budget filter 'budget=25-50'
        $response = $this->get('/property?budget=25-50');
        $response->assertStatus(200);
        $response->assertSee('Lucknow Premium Plot');
        $response->assertDontSee('Mumbai Luxury Flat');

        // Assert budget filter 'budget=100+'
        $response = $this->get('/property?budget=100%2B');
        $response->assertStatus(200);
        $response->assertSee('Mumbai Luxury Flat');
        $response->assertDontSee('Lucknow Premium Plot');
    }
}
