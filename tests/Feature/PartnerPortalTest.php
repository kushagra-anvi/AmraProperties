<?php

namespace Tests\Feature;

use App\Models\B2CLead;
use App\Models\B2CLeadShare;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerPortalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that guest/unauthenticated users are redirected to login.
     */
    public function test_guest_is_redirected_from_partner_portal(): void
    {
        $response = $this->get('/partner/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test that non-partner roles (like sales_team, analyst, admin) are forbidden from partner routes.
     */
    public function test_non_partner_roles_are_forbidden(): void
    {
        $roles = ['sales_team', 'analyst', 'admin'];

        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role]);

            $response = $this->actingAs($user)->get('/partner/dashboard');
            $response->assertStatus(403);
        }
    }

    /**
     * Test that authenticated partners can view dashboard and leads shared with them.
     */
    public function test_partner_can_view_own_dashboard_and_leads(): void
    {
        // Create partner user
        $user = User::factory()->create([
            'role' => 'partner',
            'name' => 'Partner Amit',
            'email' => 'partner@amra.com',
        ]);

        // Create partner profile linked to user
        $partner = Partner::create([
            'user_id' => $user->id,
            'type' => 'agent',
            'company_name' => 'ABC Realty Group',
            'contact_person' => 'Partner Amit',
            'phone' => '+91 97777 66666',
            'city' => 'Mumbai',
            'package' => 'starter',
            'is_active' => true,
        ]);

        // Create another partner profile
        $otherUser = User::factory()->create(['role' => 'partner']);
        $otherPartner = Partner::create([
            'user_id' => $otherUser->id,
            'type' => 'agent',
            'company_name' => 'Other Realty Group',
            'contact_person' => 'Other Amit',
            'phone' => '+91 97777 55555',
            'city' => 'Mumbai',
            'package' => 'starter',
            'is_active' => true,
        ]);

        // Create B2C leads
        $leadSharedWithMe = B2CLead::create([
            'name' => 'Shared Buyer',
            'phone' => '1234567890',
            'city' => 'Mumbai',
            'property_type' => 'flat',
            'configuration' => '2BHK',
            'status' => 'new',
            'lead_created_at' => now(),
        ]);

        $leadSharedWithOther = B2CLead::create([
            'name' => 'Other Buyer',
            'phone' => '0987654321',
            'city' => 'Mumbai',
            'property_type' => 'flat',
            'configuration' => '3BHK',
            'status' => 'new',
            'lead_created_at' => now(),
        ]);

        // Share lead with me
        B2CLeadShare::create([
            'b2_c_lead_id' => $leadSharedWithMe->id,
            'partner_id' => $partner->id,
            'shared_by_user_id' => $user->id,
            'shared_at' => now(),
        ]);

        // Share lead with other partner
        B2CLeadShare::create([
            'b2_c_lead_id' => $leadSharedWithOther->id,
            'partner_id' => $otherPartner->id,
            'shared_by_user_id' => $otherUser->id,
            'shared_at' => now(),
        ]);

        // Access dashboard
        $response = $this->actingAs($user)->get('/partner/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Shared Buyer');
        $response->assertDontSee('Other Buyer');

        // Access own lead details page
        $response = $this->actingAs($user)->get("/partner/leads/{$leadSharedWithMe->id}");
        $response->assertStatus(200);
        $response->assertSee('Shared Buyer');

        // Access other partner's lead details page (should be forbidden)
        $response = $this->actingAs($user)->get("/partner/leads/{$leadSharedWithOther->id}");
        $response->assertStatus(403);
    }
}
