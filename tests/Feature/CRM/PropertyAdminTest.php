<?php

namespace Tests\Feature\CRM;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_property_admin(): void
    {
        $this->get(route('crm.properties.index'))->assertRedirect(route('login'));
    }

    public function test_admin_and_super_admin_can_access_property_admin_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        Property::create([
            'title' => 'Test Property 1',
            'slug' => 'test-property-1',
            'price' => 10000000,
            'configuration' => '3BHK',
            'city' => 'Mumbai',
            'status' => 'publish',
        ]);

        $this->actingAs($admin)->get(route('crm.properties.index'))
            ->assertOk()
            ->assertSee('Test Property 1');

        $this->actingAs($superAdmin)->get(route('crm.properties.index'))
            ->assertOk()
            ->assertSee('Test Property 1');
    }

    public function test_unauthorized_roles_cannot_access_property_admin_index(): void
    {
        $analyst = User::factory()->create(['role' => 'analyst']);
        $partner = User::factory()->create(['role' => 'partner']);
        $sales = User::factory()->create(['role' => 'sales_team']);

        $this->actingAs($analyst)->get(route('crm.properties.index'))->assertForbidden();
        $this->actingAs($partner)->get(route('crm.properties.index'))->assertForbidden();
        $this->actingAs($sales)->get(route('crm.properties.index'))->assertForbidden();
    }

    public function test_admin_can_store_property(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('crm.properties.store'), [
            'title' => 'Brand New Villa',
            'description' => 'A gorgeous premium villa in Gomti Nagar.',
            'price' => 25000000,
            'bedrooms' => 4,
            'bathrooms' => 4,
            'area' => 3200,
            'area_unit' => 'sq_ft',
            'address' => 'Gomti Nagar Extension, Lucknow',
            'city' => 'Lucknow',
            'state' => 'Uttar Pradesh',
            'country' => 'India',
            'rera_number' => 'UPRERAPRJ12345',
            'configuration' => 'Villa',
            'developer_name' => 'Apex Infra Group',
            'status' => 'publish',
            'listing_type' => 'sale',
            'is_featured' => '1',
            'is_rera_approved' => '1',
            'amenities' => ['Swimming Pool', 'Gymnasium'],
        ]);

        $response->assertRedirect(route('crm.properties.index'))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('properties', [
            'title' => 'Brand New Villa',
            'price' => 25000000,
            'city' => 'Lucknow',
            'is_featured' => true,
            'is_rera_approved' => true,
        ]);
    }

    public function test_admin_can_update_property(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $property = Property::create([
            'title' => 'Old Property Name',
            'slug' => 'old-property-name',
            'price' => 8000000,
            'configuration' => '2BHK',
            'city' => 'Mumbai',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)->put(route('crm.properties.update', $property->id), [
            'title' => 'Updated Property Name',
            'price' => 9000000,
            'bedrooms' => 2,
            'bathrooms' => 2,
            'area' => 1100,
            'area_unit' => 'sq_ft',
            'city' => 'Mumbai',
            'configuration' => '2BHK',
            'status' => 'publish',
            'listing_type' => 'sale',
        ]);

        $response->assertRedirect(route('crm.properties.index'))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'title' => 'Updated Property Name',
            'price' => 9000000,
            'status' => 'publish',
        ]);
    }

    public function test_admin_can_delete_property(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $property = Property::create([
            'title' => 'Property to Delete',
            'slug' => 'property-to-delete',
            'price' => 6000000,
            'configuration' => '1BHK',
            'city' => 'Mumbai',
            'status' => 'publish',
        ]);

        $response = $this->actingAs($admin)->delete(route('crm.properties.destroy', $property->id));
        $response->assertRedirect(route('crm.properties.index'))->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('properties', ['id' => $property->id]);
    }
}
