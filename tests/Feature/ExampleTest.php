<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public homepage accessibility.
     */
    public function test_home_returns_the_public_website(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_public_site_blade_pages_render_successfully(): void
    {
        foreach (['/', '/about', '/contact', '/property', '/privacy-policy', '/terms-conditions', '/rera-disclaimer', '/advertiser-agreement'] as $path) {
            $this->get($path)->assertStatus(200);
        }
    }

    public function test_legacy_html_site_urls_redirect_to_blade_routes(): void
    {
        $this->get('/pages/about.html')->assertRedirect('/about');
        $this->get('/pages/contact.html')->assertRedirect('/contact');
        $this->get('/pages/property.html')->assertRedirect('/property');
        $this->get('/pages/privacy.html')->assertRedirect('/privacy-policy');
        $this->get('/pages/terms.html')->assertRedirect('/terms-conditions');
    }

    public function test_legacy_privacy_terms_urls_redirect_to_new_paths(): void
    {
        $this->get('/privacy')->assertRedirect('/privacy-policy');
        $this->get('/terms')->assertRedirect('/terms-conditions');
        $this->get('/4521-2')->assertRedirect('/advertiser-agreement');
    }

    /**
     * Test unauthenticated redirection.
     */
    public function test_crm_redirects_unauthenticated_to_login(): void
    {
        $response = $this->get('/crm');
        $response->assertRedirect('/login');

        $response = $this->get('/crm/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test dashboard accessibility for super_admin.
     */
    public function test_crm_dashboard_returns_success_for_authenticated_users(): void
    {
        $user = User::factory()->create([
            'role' => 'super_admin'
        ]);

        $response = $this->actingAs($user)->get('/crm/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Test RBAC for B2C Buyer Leads (/crm/b2c).
     * Allowed: super_admin, admin. Blocked: analyst, sales_team.
     */
    public function test_b2c_leads_rbac_gates(): void
    {
        // 1. Super Admin (Allowed)
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($superAdmin)->get('/crm/b2c')->assertStatus(200);

        // 2. Admin (Allowed)
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/crm/b2c')->assertStatus(200);

        // 3. Analyst (Blocked)
        $analyst = User::factory()->create(['role' => 'analyst']);
        $this->actingAs($analyst)->get('/crm/b2c')->assertStatus(403);

        // 4. Sales Team (Blocked)
        $sales = User::factory()->create(['role' => 'sales_team']);
        $this->actingAs($sales)->get('/crm/b2c')->assertStatus(403);
    }

    /**
     * Test RBAC for Partners Directory (/crm/partners).
     * Allowed: super_admin, admin. Blocked: analyst, sales_team.
     */
    public function test_partners_rbac_gates(): void
    {
        // 1. Super Admin (Allowed)
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($superAdmin)->get('/crm/partners')->assertStatus(200);

        // 2. Admin (Allowed)
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/crm/partners')->assertStatus(200);

        // 3. Analyst (Blocked)
        $analyst = User::factory()->create(['role' => 'analyst']);
        $this->actingAs($analyst)->get('/crm/partners')->assertStatus(403);

        // 4. Sales Team (Blocked)
        $sales = User::factory()->create(['role' => 'sales_team']);
        $this->actingAs($sales)->get('/crm/partners')->assertStatus(403);
    }

    /**
     * Test RBAC for Sales Metrics Directory (/crm/sales).
     * Allowed: super_admin. Blocked: admin, analyst, sales_team.
     */
    public function test_sales_rbac_gates(): void
    {
        // 1. Super Admin (Allowed)
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($superAdmin)->get('/crm/sales')->assertStatus(200);

        // 2. Admin (Blocked)
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/crm/sales')->assertStatus(403);

        // 3. Analyst (Blocked)
        $analyst = User::factory()->create(['role' => 'analyst']);
        $this->actingAs($analyst)->get('/crm/sales')->assertStatus(403);

        // 4. Sales Team (Blocked)
        $sales = User::factory()->create(['role' => 'sales_team']);
        $this->actingAs($sales)->get('/crm/sales')->assertStatus(403);
    }

    /**
     * Test sidebar only exposes links allowed for the logged-in role.
     */
    public function test_sidebar_hides_unauthorized_tabs_for_sales_team(): void
    {
        $sales = User::factory()->create(['role' => 'sales_team']);

        $response = $this->actingAs($sales)->get('/crm/b2b');

        $response->assertStatus(200);
        $response->assertDontSee('href="http://localhost/crm/dashboard"', false);
        $response->assertSee('B2B Leads');
        $response->assertDontSee('B2C Buyer Leads');
        $response->assertDontSee('href="http://localhost/crm/b2c"', false);
        $response->assertDontSee('href="http://localhost/crm/partners"', false);
        $response->assertDontSee('href="http://localhost/crm/sales"', false);
    }

    /**
     * Test analysts only see reports/dashboard access.
     */
    public function test_sidebar_only_shows_reports_for_analysts(): void
    {
        $analyst = User::factory()->create(['role' => 'analyst']);

        $response = $this->actingAs($analyst)->get('/crm/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertDontSee('href="http://localhost/crm/b2b"', false);
        $response->assertDontSee('B2C Buyer Leads');
        $response->assertDontSee('href="http://localhost/crm/partners"', false);
        $response->assertDontSee('href="http://localhost/crm/sales"', false);
    }

    /**
     * Test B2C bulk CSV import.
     */
    public function test_b2c_bulk_csv_import(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create a temporary CSV with headers and data
        $csvContent = "name,phone,city,property_type,configuration,email,budget_min,budget_max,preferred_locations,source_platform,remark\n";
        $csvContent .= "Test Buyer,9876543210,Mumbai,flat,3BHK,test@buyer.com,6000000,12000000,\"Powai, Bandra\",meta,Interested in Powai flats\n";
        
        $tempFile = tempnam(sys_get_temp_dir(), 'test_b2c_') . '.csv';
        file_put_contents($tempFile, $csvContent);

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempFile,
            'b2c_leads.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->actingAs($admin)->post('/crm/b2c/bulk-import', [
            'csv_file' => $uploadedFile,
        ]);

        $response->assertStatus(302); // Redirect back
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('b2_c_leads', [
            'name' => 'Test Buyer',
            'phone' => '9876543210',
            'city' => 'Mumbai',
            'property_type' => 'flat',
            'configuration' => '3BHK',
            'email' => 'test@buyer.com',
            'budget_min' => 6000000,
            'budget_max' => 12000000,
            'source_platform' => 'meta',
            'remark' => 'Interested in Powai flats',
        ]);

        unlink($tempFile);
    }

    /**
     * Test B2C Lead Assignment.
     */
    public function test_b2c_lead_assignment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $lead = \App\Models\B2CLead::create([
            'name' => 'John Buyer',
            'phone' => '1234567890',
            'city' => 'Pune',
            'property_type' => 'villa',
            'configuration' => '4BHK',
            'status' => 'new',
            'lead_created_at' => now(),
        ]);
        $salesPerson = \App\Models\SalesPerson::create([
            'name' => 'Jane Caller',
            'phone' => '5551234567',
            'email' => 'jane@amra.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post("/crm/b2c/{$lead->id}/assign", [
            'assigned_sales_person_id' => $salesPerson->id,
            'notes' => 'Assigning Jane to look into this buyer requirement.',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $lead->refresh();
        $this->assertEquals($salesPerson->id, $lead->assigned_sales_person_id);

        $this->assertDatabaseHas('lead_status_logs', [
            'lead_type' => \App\Models\B2CLead::class,
            'lead_id' => $lead->id,
            'notes' => 'Reassigned B2C Lead from Unassigned to Jane Caller. Note: Assigning Jane to look into this buyer requirement.',
        ]);
    }

    /**
     * Test creating a partner with affiliate type.
     */
    public function test_partner_creation_with_affiliate_type(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/crm/partners', [
            'type' => 'affiliate',
            'company_name' => 'Affiliate Company Ltd',
            'contact_person' => 'Jane Affiliate',
            'phone' => '1234509876',
            'email' => 'jane@affiliate.com',
            'city' => 'Pune',
            'package' => 'starter',
            'paid_amount' => 5000,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('partners', [
            'type' => 'affiliate',
            'company_name' => 'Affiliate Company Ltd',
            'contact_person' => 'Jane Affiliate',
            'phone' => '1234509876',
            'email' => 'jane@affiliate.com',
            'city' => 'Pune',
            'package' => 'starter',
            'paid_amount' => 5000,
        ]);
    }

    /**
     * Test Partner Bulk CSV Import.
     */
    public function test_partner_bulk_csv_import(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create temporary CSV with headers and data
        $csvContent = "company_name,contact_person,phone,city,type,email,office_address,service_areas,package,paid_amount,package_purchase_date,renewal_date,lead_source,remark\n";
        $csvContent .= "Imported Agent Corp,Agent Contact,7777777777,Mumbai,agent,agent@corp.com,Office 101,\"Powai, Chembur\",growth,15000,2026-06-01,2027-06-01,Direct,Great profile\n";
        $csvContent .= "Imported Affiliate Inc,Affiliate Person,8888888888,Lucknow,affiliate,aff@inc.com,Suite 202,Hazratganj,premium,25000,2026-06-02,2027-06-02,Reference,Affiliate partner\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'test_partner_') . '.csv';
        file_put_contents($tempFile, $csvContent);

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempFile,
            'partners.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->actingAs($admin)->post('/crm/partners/bulk-import', [
            'csv_file' => $uploadedFile,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('partners', [
            'company_name' => 'Imported Agent Corp',
            'contact_person' => 'Agent Contact',
            'phone' => '7777777777',
            'city' => 'Mumbai',
            'type' => 'agent',
            'email' => 'agent@corp.com',
            'package' => 'growth',
            'paid_amount' => 15000.00,
        ]);

        $this->assertDatabaseHas('partners', [
            'company_name' => 'Imported Affiliate Inc',
            'contact_person' => 'Affiliate Person',
            'phone' => '8888888888',
            'city' => 'Lucknow',
            'type' => 'affiliate',
            'email' => 'aff@inc.com',
            'package' => 'premium',
            'paid_amount' => 25000.00,
        ]);

        unlink($tempFile);
    }

    /**
     * Test sales person detailed profile view shows call stats and logs.
     */
    public function test_sales_person_show_profile_shows_call_analytics(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $salesPerson = \App\Models\SalesPerson::create([
            'name' => 'John Rep',
            'phone' => '1234567890',
            'email' => 'john@amra.com',
            'is_active' => true,
        ]);

        // Create a lead assigned to him
        $lead = \App\Models\B2BLead::create([
            'company_name' => 'B2B Client Corp',
            'contact_person_name' => 'Alice Client',
            'contact_number' => '1112223333',
            'city' => 'Pune',
            'status' => 'new',
            'assigned_sales_person_id' => $salesPerson->id,
            'lead_created_at' => now(),
        ]);

        // Create completed and pending followups
        $completedFollowUp = \App\Models\FollowUp::create([
            'followable_type' => \App\Models\B2BLead::class,
            'followable_id' => $lead->id,
            'sales_person_id' => $salesPerson->id,
            'completed_at' => now(),
            'notes' => 'Completed introduction call with Alice.',
        ]);

        $pendingFollowUp = \App\Models\FollowUp::create([
            'followable_type' => \App\Models\B2BLead::class,
            'followable_id' => $lead->id,
            'sales_person_id' => $salesPerson->id,
            'due_at' => now()->addDays(2),
            'notes' => 'Schedule price negotiations.',
        ]);

        $response = $this->actingAs($superAdmin)->get("/crm/sales/{$salesPerson->id}");

        $response->assertStatus(200);
        $response->assertViewHas('dayWiseCalls');
        $response->assertViewHas('recentFollowUps');
        $response->assertSee('Day-Wise Calls Completed');
        $response->assertSee('Completed introduction call with Alice.');
        $response->assertSee('Schedule price negotiations.');
    }

    public function test_sales_team_cannot_access_b2b_bulk_import_route(): void
    {
        $salesTeamUser = User::factory()->create(['role' => 'sales_team']);
        $response = $this->actingAs($salesTeamUser)->post('/crm/b2b/bulk-import', []);
        $response->assertStatus(403);
    }

    public function test_b2b_bulk_import_skips_duplicates(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Seed an existing lead with contact number
        \App\Models\B2BLead::create([
            'company_name' => 'Existing B2B Corp',
            'contact_person_name' => 'John Doe',
            'contact_number' => '9999999999',
            'city' => 'Mumbai',
            'status' => 'new',
        ]);

        // Seed another existing lead by name + city
        \App\Models\B2BLead::create([
            'company_name' => 'Unique Name Corp',
            'contact_person_name' => 'Jane Smith',
            'city' => 'Pune',
            'status' => 'new',
        ]);

        $csvContent = "company_name,contact_person_name,contact_number,city\n";
        $csvContent .= "Existing B2B Corp,John Doe,9999999999,Mumbai\n"; // Duplicate by contact number
        $csvContent .= "Unique Name Corp,Jane Smith,,Pune\n"; // Duplicate by company_name + city (blank contact_number)
        $csvContent .= "New Unique B2B,Bob Builder,8888888888,Mumbai\n"; // New unique lead
        
        $tempFile = tempnam(sys_get_temp_dir(), 'test_b2b_') . '.csv';
        file_put_contents($tempFile, $csvContent);

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempFile,
            'b2b_leads.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->actingAs($admin)->post('/crm/b2b/bulk-import', [
            'csv_file' => $uploadedFile,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', function($msg) {
            return str_contains($msg, 'Successfully imported 1') && str_contains($msg, 'Skipped 2 duplicate(s)');
        });

        unlink($tempFile);
    }

    public function test_b2c_bulk_import_skips_duplicates(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Seed an existing buyer lead with same phone
        \App\Models\B2CLead::create([
            'name' => 'Existing Buyer',
            'phone' => '9876543210',
            'city' => 'Mumbai',
            'property_type' => 'flat',
            'configuration' => '3BHK',
            'status' => 'new',
            'lead_created_at' => now(),
        ]);

        $csvContent = "name,phone,city,property_type,configuration,email,budget_min,budget_max,preferred_locations,source_platform,remark\n";
        $csvContent .= "Existing Buyer,9876543210,Mumbai,flat,3BHK,test@buyer.com,6000000,12000000,\"Powai, Bandra\",meta,Interested in Powai flats\n"; // Duplicate
        $csvContent .= "Unique Buyer,9991112223,Pune,villa,4BHK,unique@buyer.com,8000000,15000000,Baner,manual,Looking for villa\n"; // New unique lead
        
        $tempFile = tempnam(sys_get_temp_dir(), 'test_b2c_') . '.csv';
        file_put_contents($tempFile, $csvContent);

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempFile,
            'b2c_leads.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->actingAs($admin)->post('/crm/b2c/bulk-import', [
            'csv_file' => $uploadedFile,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', function($msg) {
            return str_contains($msg, 'Successfully imported 1') && str_contains($msg, 'Skipped 1 duplicate(s)');
        });

        unlink($tempFile);
    }
}
