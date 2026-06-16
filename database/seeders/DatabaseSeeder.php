<?php

namespace Database\Seeders;

use App\Models\B2BLead;
use App\Models\B2CLead;
use App\Models\B2CLeadShare;
use App\Models\FollowUp;
use App\Models\LeadStatusLog;
use App\Models\Partner;
use App\Models\SalesPerson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Seed default accounts for each role
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@amra.com',
            'password' => $password,
            'role' => 'super_admin',
            'phone' => '+91 99999 11111',
            'location' => 'Mumbai',
        ]);

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@amra.com',
            'password' => $password,
            'role' => 'admin',
            'phone' => '+91 99999 22222',
            'location' => 'Lucknow',
        ]);

        $salesUser = User::create([
            'name' => 'Rahul Sharma',
            'email' => 'sales@amra.com',
            'password' => $password,
            'role' => 'sales_team',
            'phone' => '+91 99999 33333',
            'location' => 'Mumbai',
        ]);

        $analystUser = User::create([
            'name' => 'Analyst User',
            'email' => 'analyst@amra.com',
            'password' => $password,
            'role' => 'analyst',
            'phone' => '+91 99999 44444',
            'location' => 'Pune',
        ]);

        $partnerUser = User::create([
            'name' => 'Amit Shah',
            'email' => 'partner@amra.com',
            'password' => $password,
            'role' => 'partner',
            'phone' => '+91 97777 66666',
            'location' => 'Mumbai',
        ]);

        // 2. Seed Sales People Profiles
        $repRahul = SalesPerson::create([
            'user_id' => $salesUser->id,
            'name' => 'Rahul Sharma',
            'phone' => '+91 99999 33333',
            'location' => 'Mumbai Office',
            'service_areas' => ['Powai', 'Hiranandani', 'Ghatkopar'],
            'is_active' => true,
        ]);

        $repPriya = SalesPerson::create([
            'name' => 'Priya Patel',
            'phone' => '+91 98888 77777',
            'location' => 'Lucknow Office',
            'service_areas' => ['Hazratganj', 'Gomti Nagar', 'Aliganj'],
            'is_active' => true,
        ]);

        // 3. Seed Certified Partners (Agents & Developers)
        $partnerAbc = Partner::create([
            'user_id' => $partnerUser->id,
            'type' => 'agent',
            'company_name' => 'ABC Realty Group',
            'contact_person' => 'Amit Shah',
            'phone' => '+91 97777 66666',
            'email' => 'contact@abcrealty.com',
            'office_address' => '402, Signature Building, Powai, Mumbai',
            'service_areas' => ['Powai', 'Chandivali'],
            'city' => 'Mumbai',
            'package' => 'starter',
            'package_purchase_date' => now()->subMonth(),
            'renewal_date' => now()->addDays(5), // Warning alarm triggers!
            'assigned_sales_person_id' => $repRahul->id,
            'lead_source' => 'Meta Ads',
        ]);

        $partnerXyz = Partner::create([
            'type' => 'developer',
            'company_name' => 'XYZ Developers',
            'contact_person' => 'Rajesh Kumar',
            'phone' => '+91 96666 55555',
            'email' => 'sales@xyzdev.com',
            'office_address' => 'Acme Tech Park, Sector 4, Gomti Nagar, Lucknow',
            'service_areas' => ['Gomti Nagar', 'Hazratganj'],
            'city' => 'Lucknow',
            'package' => 'growth',
            'package_purchase_date' => now()->subMonths(2),
            'renewal_date' => now()->addMonths(4),
            'assigned_sales_person_id' => $repPriya->id,
            'lead_source' => 'Google Ads',
        ]);

        $partnerPowai = Partner::create([
            'type' => 'agent',
            'company_name' => 'Powai Luxury Homes',
            'contact_person' => 'Neha Singh',
            'phone' => '+91 95555 44444',
            'email' => 'neha@powaihomes.com',
            'office_address' => 'Orchard Avenue, Hiranandani Gardens, Powai',
            'service_areas' => ['Powai', 'Hiranandani'],
            'city' => 'Mumbai',
            'package' => 'free',
            'package_purchase_date' => now()->subWeeks(2),
            'renewal_date' => null,
            'assigned_sales_person_id' => $repRahul->id,
            'lead_source' => 'Website Inquiry',
        ]);

        $partnerSkyline = Partner::create([
            'type' => 'developer',
            'company_name' => 'Skyline Builders',
            'contact_person' => 'Vikram Goel',
            'phone' => '+91 94444 33333',
            'email' => 'vikram@skyline.com',
            'office_address' => 'Skyline Corporate Tower, Hazratganj, Lucknow',
            'service_areas' => ['Hazratganj', 'Aliganj'],
            'city' => 'Lucknow',
            'package' => 'growth',
            'package_purchase_date' => now()->subMonth(),
            'renewal_date' => now()->addDays(3), // Warning alarm triggers!
            'assigned_sales_person_id' => $repPriya->id,
            'lead_source' => 'Google Ads',
        ]);

        // 4. Seed B2B Leads
        $b2bLeadsData = [
            [
                'category' => 'agent',
                'company_name' => 'Hiranandani Brokers',
                'contact_person_name' => 'Sanjay Dutt',
                'contact_number' => '+91 93333 22222',
                'city' => 'Mumbai',
                'source_platform' => 'meta',
                'assigned_sales_person_id' => $repRahul->id,
                'status' => 'qualified',
                'remark' => 'Very interested in starter listing package.',
            ],
            [
                'category' => 'developer',
                'company_name' => 'Lucknow Smart Homes',
                'contact_person_name' => 'Anil Verma',
                'contact_number' => '+91 92222 11111',
                'city' => 'Lucknow',
                'source_platform' => 'google',
                'assigned_sales_person_id' => $repPriya->id,
                'status' => 'follow_up',
                'remark' => 'Requested follow-up call on next Monday.',
            ],
            [
                'category' => 'single_owner',
                'company_name' => 'Powai Penthouse Owner',
                'contact_person_name' => 'Karan Johar',
                'contact_number' => '+91 91111 00000',
                'city' => 'Mumbai',
                'source_platform' => 'website',
                'assigned_sales_person_id' => $repRahul->id,
                'status' => 'new',
                'remark' => 'Wants to list a 4BHK luxury penthouse in Powai.',
            ],
            [
                'category' => 'agent',
                'company_name' => 'Gomti Real Estate',
                'contact_person_name' => 'Ramesh Gupta',
                'contact_number' => '+91 90000 99999',
                'city' => 'Lucknow',
                'source_platform' => 'manual',
                'assigned_sales_person_id' => $repPriya->id,
                'status' => 'not_interested',
                'remark' => 'Already has custom portal, not interested.',
            ],
            [
                'category' => 'developer',
                'company_name' => 'Apex Infra Group',
                'contact_person_name' => 'Manish Malhotra',
                'contact_number' => '+91 89999 88888',
                'city' => 'Mumbai',
                'source_platform' => 'google',
                'assigned_sales_person_id' => $repRahul->id,
                'status' => 'paid_listing',
                'remark' => 'Converted to Starter package plan.',
                'converted_partner_id' => $partnerAbc->id,
            ]
        ];

        foreach ($b2bLeadsData as $b2bData) {
            $b2bData['lead_created_at'] = now()->subDays(rand(1, 10));
            $lead = B2BLead::create($b2bData);

            // Audit Logs
            LeadStatusLog::create([
                'lead_type' => B2BLead::class,
                'lead_id' => $lead->id,
                'from_status' => null,
                'to_status' => $lead->status,
                'changed_by_user_id' => $superAdminUser->id,
                'notes' => 'Lead successfully seeded with state ' . $lead->status,
            ]);

            // Seed a default follow-up
            FollowUp::create([
                'followable_type' => B2BLead::class,
                'followable_id' => $lead->id,
                'sales_person_id' => $lead->assigned_sales_person_id,
                'due_at' => now()->addDays(rand(2, 5)),
                'notes' => 'Discussed premium pricing packages.',
            ]);
        }

        // 5. Seed B2C Buyer Leads
        $b2cLeadsData = [
            [
                'name' => 'Amit Bhatia',
                'phone' => '+91 88888 77777',
                'email' => 'amit@bhatia.com',
                'city' => 'Mumbai',
                'pincode' => '400076',
                'budget_min' => 15000000,
                'budget_max' => 25000000,
                'preferred_locations' => ['Powai', 'Hiranandani'],
                'property_type' => 'flat',
                'configuration' => '2BHK',
                'source_platform' => 'website',
                'status' => 'new',
                'remark' => 'Looking for premium 2BHK flat near Powai Lake.',
            ],
            [
                'name' => 'Shweta Tiwari',
                'phone' => '+91 87777 66666',
                'email' => 'shweta@tiwari.com',
                'city' => 'Lucknow',
                'pincode' => '226010',
                'budget_min' => 8000000,
                'budget_max' => 12000000,
                'preferred_locations' => ['Gomti Nagar', 'Hazratganj'],
                'property_type' => 'villa',
                'configuration' => '3BHK',
                'source_platform' => 'meta',
                'status' => 'shared',
                'remark' => 'Interested in independent house or luxury villa.',
            ],
            [
                'name' => 'Raj Malhotra',
                'phone' => '+91 86666 55555',
                'email' => 'raj@malhotra.com',
                'city' => 'Mumbai',
                'pincode' => '400076',
                'budget_min' => 30000000,
                'budget_max' => 50000000,
                'preferred_locations' => ['Hiranandani Gardens'],
                'property_type' => 'flat',
                'configuration' => '3BHK',
                'source_platform' => 'google',
                'status' => 'new',
                'remark' => 'High ticket buyer looking for a luxury flat.',
            ]
        ];

        foreach ($b2cLeadsData as $b2cData) {
            $b2cData['lead_created_at'] = now()->subDays(rand(1, 10));
            $lead = B2CLead::create($b2cData);

            // Audit Logs
            LeadStatusLog::create([
                'lead_type' => B2CLead::class,
                'lead_id' => $lead->id,
                'from_status' => null,
                'to_status' => $lead->status,
                'changed_by_user_id' => $adminUser->id,
                'notes' => 'Seeded B2C buyer lead.',
            ]);

            // Share one of them to a partner to seed distribution logs
            if ($lead->name === 'Shweta Tiwari') {
                B2CLeadShare::create([
                    'b2_c_lead_id' => $lead->id,
                    'partner_id' => $partnerXyz->id,
                    'shared_by_user_id' => $adminUser->id,
                    'shared_at' => now()->subDay(),
                    'remark' => 'High priority Gomti Nagar requirement shared with Developer.',
                ]);
            }

            if ($lead->name === 'Amit Bhatia') {
                B2CLeadShare::create([
                    'b2_c_lead_id' => $lead->id,
                    'partner_id' => $partnerAbc->id,
                    'shared_by_user_id' => $adminUser->id,
                    'shared_at' => now()->subDays(2),
                    'remark' => 'Powai flat buyer shared with ABC Realty Group.',
                ]);
            }
        }
    }
}
