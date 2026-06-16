<?php

namespace Tests\Feature;

use App\Models\B2CLead;
use App\Models\B2CLeadShare;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class B2CLeadAutoDistributionTest extends TestCase
{
    use RefreshDatabase;

    public function test_b2c_lead_is_auto_assigned_to_package_eligible_location_matched_partners(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $starterPartner = Partner::create([
            'type' => 'agent',
            'company_name' => 'Starter Mumbai Realty',
            'contact_person' => 'Starter Owner',
            'phone' => '9000000001',
            'city' => 'Mumbai',
            'package' => 'starter',
            'is_active' => true,
        ]);

        $growthPartner = Partner::create([
            'type' => 'developer',
            'company_name' => 'Growth Powai Developers',
            'contact_person' => 'Growth Owner',
            'phone' => '9000000002',
            'city' => 'Thane',
            'service_areas' => ['Powai', 'Mumbai'],
            'package' => 'growth',
            'is_active' => true,
        ]);

        $freePartner = Partner::create([
            'type' => 'agent',
            'company_name' => 'Free Mumbai Realty',
            'contact_person' => 'Free Owner',
            'phone' => '9000000003',
            'city' => 'Mumbai',
            'package' => 'free',
            'is_active' => true,
        ]);

        $delhiPartner = Partner::create([
            'type' => 'agent',
            'company_name' => 'Starter Delhi Realty',
            'contact_person' => 'Delhi Owner',
            'phone' => '9000000004',
            'city' => 'Delhi',
            'package' => 'starter',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post('/crm/b2c', [
            'name' => 'Auto Buyer',
            'phone' => '9999999999',
            'city' => 'Mumbai',
            'budget_min' => 10000000,
            'budget_max' => 20000000,
            'preferred_locations' => ['Powai'],
            'property_type' => 'flat',
            'configuration' => '3BHK',
            'source_platform' => 'manual',
        ]);

        $response->assertRedirect('/crm/b2c');

        $lead = B2CLead::where('name', 'Auto Buyer')->firstOrFail();

        $this->assertSame('shared', $lead->fresh()->status);
        $this->assertDatabaseHas('b2_c_lead_shares', [
            'b2_c_lead_id' => $lead->id,
            'partner_id' => $starterPartner->id,
        ]);
        $this->assertDatabaseHas('b2_c_lead_shares', [
            'b2_c_lead_id' => $lead->id,
            'partner_id' => $growthPartner->id,
        ]);
        $this->assertDatabaseMissing('b2_c_lead_shares', [
            'b2_c_lead_id' => $lead->id,
            'partner_id' => $freePartner->id,
        ]);
        $this->assertDatabaseMissing('b2_c_lead_shares', [
            'b2_c_lead_id' => $lead->id,
            'partner_id' => $delhiPartner->id,
        ]);
    }

    public function test_manual_partner_sharing_still_works_for_selected_partners(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $manualPartner = Partner::create([
            'type' => 'agent',
            'company_name' => 'Manual Realty',
            'contact_person' => 'Manual Owner',
            'phone' => '9000000005',
            'city' => 'Pune',
            'package' => 'free',
            'is_active' => true,
        ]);

        $lead = B2CLead::create([
            'name' => 'Manual Buyer',
            'phone' => '8888888888',
            'city' => 'Pune',
            'property_type' => 'villa',
            'configuration' => '4BHK',
            'source_platform' => 'manual',
            'status' => 'filtered',
            'lead_created_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post("/crm/b2c/{$lead->id}/share", [
            'partner_ids' => [$manualPartner->id],
            'remark' => 'Manual client-approved distribution.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('b2_c_lead_shares', [
            'b2_c_lead_id' => $lead->id,
            'partner_id' => $manualPartner->id,
            'remark' => 'Manual client-approved distribution.',
        ]);
        $this->assertSame('shared', $lead->fresh()->status);
        $this->assertSame(1, B2CLeadShare::where('b2_c_lead_id', $lead->id)->count());
    }
}
