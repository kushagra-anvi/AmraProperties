<?php

namespace Tests\Feature;

use App\Models\B2CLead;
use App\Models\Partner;
use App\Models\SalesPerson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class B2CLeadSharingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_bulk_share_leads_with_sales_team_builders_and_agents(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $salesPerson = SalesPerson::create(['name' => 'Sales One', 'phone' => '9000000001']);
        $builder = Partner::create(['type' => 'developer', 'company_name' => 'Builder One', 'phone' => '9000000002']);
        $agent = Partner::create(['type' => 'agent', 'company_name' => 'Agent One', 'phone' => '9000000003']);
        $leads = collect([
            $this->makeLead('Buyer One'),
            $this->makeLead('Buyer Two'),
        ]);

        $response = $this->actingAs($admin)->post(route('crm.b2c.bulk-share'), [
            'lead_ids' => $leads->pluck('id')->all(),
            'sales_person_ids' => [$salesPerson->id],
            'partner_ids' => [$builder->id, $agent->id],
            'remark' => 'Priority follow-up',
        ]);

        $response->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseCount('b2_c_lead_shares', 6);
        $this->assertDatabaseHas('b2_c_lead_shares', ['recipient_type' => 'sales_team', 'recipient_name' => 'Sales One']);
        $this->assertDatabaseHas('b2_c_lead_shares', ['recipient_type' => 'builder', 'recipient_name' => 'Builder One']);
        $this->assertDatabaseHas('b2_c_lead_shares', ['recipient_type' => 'agent', 'recipient_name' => 'Agent One']);
        $leads->each(fn (B2CLead $lead) => $this->assertSame('shared', $lead->fresh()->status));

        $this->actingAs($admin)->get(route('crm.b2c.index'))
            ->assertOk()
            ->assertSee('Lead Sharing Activity')
            ->assertSee('Builder One');
    }

    public function test_admin_can_update_a_lead_to_a_partner_visible_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $lead = $this->makeLead('Status Buyer');

        $this->actingAs($admin)->post(route('crm.b2c.status', $lead), [
            'status' => 'site_visit_scheduled',
            'notes' => 'Visit confirmed.',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertSame('site_visit_scheduled', $lead->fresh()->status);
        $this->assertDatabaseHas('lead_status_logs', [
            'lead_type' => B2CLead::class,
            'lead_id' => $lead->id,
            'to_status' => 'site_visit_scheduled',
        ]);
    }

    private function makeLead(string $name): B2CLead
    {
        return B2CLead::create([
            'name' => $name,
            'phone' => '9999999999',
            'city' => 'Mumbai',
            'property_type' => 'flat',
            'configuration' => '2BHK',
            'source_platform' => 'manual',
            'status' => 'new',
            'lead_created_at' => now(),
        ]);
    }
}
