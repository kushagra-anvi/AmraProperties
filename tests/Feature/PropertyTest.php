<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\PropertyTag;
use App\Models\Partner;
use App\Models\User;
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

    public function test_property_search_can_match_and_filter_by_tags(): void
    {
        $nearMetro = PropertyTag::where('slug', 'near-metro')->firstOrFail();
        $premiumBuilder = PropertyTag::where('slug', 'premium-builder')->firstOrFail();

        $taggedProperty = Property::create([
            'title' => 'Central Heights',
            'slug' => 'central-heights',
            'description' => 'A well connected apartment.',
            'price' => 9000000,
            'city' => 'Mumbai',
            'status' => 'publish',
            'configuration' => '2BHK',
        ]);
        $taggedProperty->tags()->attach($nearMetro);

        $otherProperty = Property::create([
            'title' => 'Garden Estate',
            'slug' => 'garden-estate',
            'description' => 'A quiet apartment community.',
            'price' => 8500000,
            'city' => 'Mumbai',
            'status' => 'publish',
            'configuration' => '2BHK',
        ]);
        $otherProperty->tags()->attach($premiumBuilder);

        $this->get('/property?q=near metro')
            ->assertStatus(200)
            ->assertSee('Central Heights')
            ->assertDontSee('Garden Estate');

        $this->get('/property?tag=near-metro')
            ->assertStatus(200)
            ->assertSee('Central Heights')
            ->assertDontSee('Garden Estate');
    }

    public function test_sale_price_filter_handles_reversed_ranges(): void
    {
        Property::create([
            'title' => 'Affordable Sale Home',
            'slug' => 'affordable-sale-home',
            'description' => 'Sale property inside the selected budget.',
            'price' => 5000000,
            'city' => 'Mumbai',
            'status' => 'publish',
            'listing_type' => 'sale',
            'configuration' => '2BHK Flat',
        ]);

        Property::create([
            'title' => 'Premium Sale Home',
            'slug' => 'premium-sale-home',
            'description' => 'Sale property outside the selected budget.',
            'price' => 15000000,
            'city' => 'Mumbai',
            'status' => 'publish',
            'listing_type' => 'sale',
            'configuration' => '3BHK Flat',
        ]);

        $this->get('/property?listing_type=sale&min_price=8000000&max_price=3000000')
            ->assertStatus(200)
            ->assertSee('Affordable Sale Home')
            ->assertDontSee('Premium Sale Home');
    }

    public function test_rent_search_ignores_budget_ranges(): void
    {
        Property::create([
            'title' => 'Low Rent Apartment',
            'slug' => 'low-rent-apartment',
            'description' => 'A rental below the provided sale budget.',
            'price' => 30000,
            'city' => 'Mumbai',
            'status' => 'publish',
            'listing_type' => 'rent',
            'configuration' => '1BHK Flat',
        ]);

        Property::create([
            'title' => 'High Rent Apartment',
            'slug' => 'high-rent-apartment',
            'description' => 'A rental above the provided sale budget.',
            'price' => 250000,
            'city' => 'Mumbai',
            'status' => 'publish',
            'listing_type' => 'rent',
            'configuration' => '3BHK Flat',
        ]);

        $this->get('/property?listing_type=rent&min_price=100000&max_price=150000')
            ->assertStatus(200)
            ->assertSee('Low Rent Apartment')
            ->assertSee('High Rent Apartment');
    }

    public function test_admin_can_create_custom_tags_while_creating_property(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $existingTag = PropertyTag::where('slug', 'luxury')->firstOrFail();

        $response = $this->actingAs($admin)->post(route('crm.properties.store'), [
            'title' => 'Airport View Residence',
            'price' => 9500000,
            'area_unit' => 'sq_ft',
            'configuration' => '2BHK',
            'city' => 'Navi Mumbai',
            'status' => 'publish',
            'listing_type' => 'sale',
            'tag_ids' => [$existingTag->id],
            'custom_tags' => 'Sea View, Near Airport',
        ]);

        $response->assertRedirect(route('crm.properties.index'));

        $property = Property::where('title', 'Airport View Residence')->firstOrFail();

        $this->assertDatabaseHas('property_tags', [
            'name' => 'Sea View',
            'slug' => 'sea-view',
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('property_tags', [
            'name' => 'Near Airport',
            'slug' => 'near-airport',
            'is_active' => true,
        ]);

        $this->assertTrue($property->tags()->where('slug', 'luxury')->exists());
        $this->assertTrue($property->tags()->where('slug', 'sea-view')->exists());
        $this->assertTrue($property->tags()->where('slug', 'near-airport')->exists());
    }

    public function test_recommended_seller_contact_creates_property_enquiry_and_shared_lead(): void
    {
        $partner = Partner::create([
            'type' => 'agent',
            'company_name' => 'Trusted Seller Realty',
            'contact_person' => 'Amit Seller',
            'phone' => '+91 90000 11111',
            'city' => 'Thane',
            'service_areas' => ['Thane', 'Mulund'],
            'package' => 'starter',
            'is_active' => true,
        ]);

        $response = $this->postJson(route('site.recommended-seller.contact', $partner), [
            'name' => 'Repeat Buyer',
            'phone' => '9876543210',
            'email' => 'repeat@example.com',
            'intent' => 'seller_contact',
            'message' => 'Please connect me with this seller.',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'phone' => '+91 90000 11111',
            ])
            ->assertJsonStructure(['enquiry_id', 'lead_id', 'whatsapp_url']);

        $enquiryId = $response->json('enquiry_id');
        $leadId = $response->json('lead_id');

        $this->assertDatabaseHas('property_enquiries', [
            'id' => $enquiryId,
            'property_id' => null,
            'partner_id' => $partner->id,
            'seller_partner_id' => $partner->id,
            'name' => 'Repeat Buyer',
            'phone' => '9876543210',
            'source' => 'recommended_seller_contact',
            'intent' => 'seller_contact',
        ]);

        $this->assertDatabaseHas('b2_c_leads', [
            'id' => $leadId,
            'name' => 'Repeat Buyer',
            'phone' => '9876543210',
            'city' => 'Thane',
            'configuration' => 'seller_contact',
            'status' => 'shared',
        ]);

        $this->assertDatabaseHas('b2_c_lead_shares', [
            'b2_c_lead_id' => $leadId,
            'partner_id' => $partner->id,
        ]);
    }

    public function test_homepage_helpful_links_only_include_searches_with_results(): void
    {
        $luxury = PropertyTag::where('slug', 'luxury')->firstOrFail();

        $property = Property::create([
            'title' => 'Mumbai 2BHK Luxury Flat',
            'slug' => 'mumbai-2bhk-luxury-flat',
            'description' => 'A published property that should back homepage links.',
            'price' => 9000000,
            'city' => 'Mumbai',
            'status' => 'publish',
            'listing_type' => 'sale',
            'configuration' => '2BHK Flat',
        ]);
        $property->tags()->attach($luxury);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Flats to buy in Mumbai')
            ->assertSee('2 BHK flats')
            ->assertSee('Luxury')
            ->assertDontSee('3 BHK flats')
            ->assertDontSee('RERA approved projects');

        $this->get('/property?listing_type=sale&type=flat&location=Mumbai')
            ->assertOk()
            ->assertSee('Mumbai 2BHK Luxury Flat');

        $this->get('/property?q=2BHK')
            ->assertOk()
            ->assertSee('Mumbai 2BHK Luxury Flat');
    }

    /**
     * Test single property details page renders successfully.
     */
    public function test_property_details_page_renders_successfully(): void
    {
        $property = Property::create([
            'title' => 'Superior Palacia',
            'slug' => 'superior-palacia-airoli',
            'description' => 'A luxury apartment in Airoli.',
            'price' => 12000000,
            'city' => 'Airoli',
            'status' => 'publish'
        ]);

        $response = $this->get(route('site.property.show', $property->slug));
        $response->assertStatus(200);
        $response->assertSee('Superior Palacia');
    }

    /**
     * Test property details page returns 404 for draft properties.
     */
    public function test_property_details_page_returns_404_for_draft_properties(): void
    {
        $property = Property::create([
            'title' => 'Draft Palacia',
            'slug' => 'draft-palacia',
            'description' => 'A draft apartment.',
            'price' => 12000000,
            'city' => 'Airoli',
            'status' => 'draft'
        ]);

        $response = $this->get(route('site.property.show', $property->slug));
        $response->assertStatus(404);
    }
}
