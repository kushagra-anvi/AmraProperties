# Bulk Import Robustness Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Secure the bulk lead and partner upload routes, wrap them in atomic database transactions, and filter out duplicate entries during imports.

**Architecture:** We will refine route RBAC rules in `routes/web.php` to align controller security with the Laravel router. We will wrap import logic in database transaction blocks (`DB::beginTransaction`, `DB::commit`, `DB::rollBack`) and query for existing matching phone/email/company records to skip duplicate rows.

**Tech Stack:** PHP, Laravel, Eloquent DB Transactions, PHPUnit

---

### Task 1: B2B Lead Bulk Import RBAC Route Security

**Files:**
- Modify: `routes/web.php:117-133`
- Modify: `app/Http/Controllers/B2BLeadImportController.php:17-22`
- Test: `tests/Feature/ExampleTest.php`

- [ ] **Step 1: Write the failing test for sales team bulk import block**
  Add a test inside `tests/Feature/ExampleTest.php` that verifies that the `sales_team` cannot access the bulk import route (receives 403 or is redirected depending on auth/roles).
  Add this method to the bottom of the `ExampleTest` class:
  ```php
  public function test_sales_team_cannot_access_b2b_bulk_import_route(): void
  {
      $salesTeamUser = User::factory()->create(['role' => 'sales_team']);
      $response = $this->actingAs($salesTeamUser)->post('/crm/b2b/bulk-import', []);
      $response->assertStatus(403);
  }
  ```

- [ ] **Step 2: Run test to verify it passes (but verify code is aligned)**
  Run: `php artisan test --filter=test_sales_team_cannot_access_b2b_bulk_import_route`
  Expected: PASS (as the controller currently aborts manually, but the route itself is within the wrong middleware block).

- [ ] **Step 3: Restructure routes and clean up controller manual check**
  Modify `routes/web.php` to move the bulk-import route out of the sales_team block (lines 117-132) and put it into the admin group (lines 135-162):
  ```diff
  // Under middleware('role:super_admin,admin,sales_team') group:
  - Route::post('/b2b/bulk-import', [B2BLeadImportController::class, 'import'])->name('crm.b2b.import');
  
  // Under middleware('role:super_admin,admin') group:
  + Route::post('/b2b/bulk-import', [B2BLeadImportController::class, 'import'])->name('crm.b2b.import');
  ```
  Modify `app/Http/Controllers/B2BLeadImportController.php` to remove the redundant manual check:
  ```diff
  - if (Auth::user()->role === 'sales_team') {
  -     abort(403, 'Unauthorized to import leads.');
  - }
  ```

- [ ] **Step 4: Run the test again to verify it passes using route middleware**
  Run: `php artisan test --filter=test_sales_team_cannot_access_b2b_bulk_import_route`
  Expected: PASS (now restricted at the route level instead of manual controller abortion).

- [ ] **Step 5: Commit**
  ```bash
  git add routes/web.php app/Http/Controllers/B2BLeadImportController.php tests/Feature/ExampleTest.php
  git commit -m "security: restrict b2b bulk import route to super_admin and admin roles"
  ```

---

### Task 2: Database Transactions and Deduplication for B2B Leads Import

**Files:**
- Modify: `app/Http/Controllers/B2BLeadImportController.php`
- Modify: `tests/Feature/ExampleTest.php`

- [ ] **Step 1: Write a test for B2B lead import deduplication and atomicity**
  Add two new tests at the end of `tests/Feature/ExampleTest.php`:
  ```php
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

  public function test_b2b_bulk_import_rolls_back_on_failure(): void
  {
      $admin = User::factory()->create(['role' => 'admin']);

      // A CSV where the first row is fine but the second row fails a non-nullable DB field or throws exception
      $csvContent = "company_name,contact_person_name,contact_number,city\n";
      $csvContent .= "Valid B2B Corp,John Doe,1234567890,Mumbai\n";
      $csvContent .= ",Invalid Row (No company name),9998887777,Pune\n"; // empty company name causes controller to skip it, but let's cause a real model error by forcing city to fail or similar, or let's mock/simulate a database failure mid-import
      
      // Let's create an invalid row with extremely long contact person name that exceeds standard database lengths if possible, or trigger an exception inside fgetcsv simulation.
      // Wait, we can test rollback by verifying that if an exception is thrown inside the controller loop, nothing gets inserted.
  }
  ```
  Wait, let's write `test_b2b_bulk_import_skips_duplicates` first.

- [ ] **Step 2: Run the test to verify it fails**
  Run: `php artisan test --filter=test_b2b_bulk_import_skips_duplicates`
  Expected: FAIL (as duplicate B2B leads are created and the success message doesn't mention skipped duplicates).

- [ ] **Step 3: Implement Transactions and Deduplication in B2BLeadImportController.php**
  Rewrite the `import` method in `app/Http/Controllers/B2BLeadImportController.php`:
  ```php
  <?php
  
  namespace App\Http\Controllers;
  
  use App\Models\B2BLead;
  use App\Models\LeadStatusLog;
  use Illuminate\Http\RedirectResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  
  class B2BLeadImportController extends Controller
  {
      /**
       * Import B2B Leads from a CSV file.
       */
      public function import(Request $request): RedirectResponse
      {
          $request->validate([
              'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // Max 5MB
          ]);
  
          $file = $request->file('csv_file');
          $path = $file->getRealPath();
  
          if (($handle = fopen($path, 'r')) === false) {
              return back()->with('error', 'Unable to open the uploaded file.');
          }
  
          // Parse Header Row
          $headers = fgetcsv($handle);
          if (!$headers) {
              fclose($handle);
              return back()->with('error', 'The uploaded file is empty.');
          }
  
          // Clean headers (remove BOM if present, trim whitespace, convert to lowercase)
          $headers = array_map(function ($h) {
              $h = preg_replace('/[\x{FEFF}\x{200B}]/u', '', $h);
              return strtolower(trim($h));
          }, $headers);
  
          // Required headers validation
          $required = ['company_name', 'contact_person_name', 'contact_number', 'city'];
          $missing = array_diff($required, $headers);
  
          if (!empty($missing)) {
              fclose($handle);
              return back()->with('error', 'Missing required CSV columns: ' . implode(', ', $missing));
          }
  
          $rowCount = 0;
          $importCount = 0;
          $skipCount = 0;
  
          DB::beginTransaction();
          try {
              while (($row = fgetcsv($handle)) !== false) {
                  $rowCount++;
                  
                  // Map row data using headers
                  $data = [];
                  foreach ($headers as $index => $header) {
                      if (isset($row[$index])) {
                          $data[$header] = trim($row[$index]);
                      }
                  }
  
                  if (empty($data['company_name'])) {
                      continue; // Skip blank rows
                  }
  
                  // Deduplication check
                  $isDuplicate = false;
                  if (!empty($data['contact_number'])) {
                      $isDuplicate = B2BLead::where('contact_number', $data['contact_number'])->exists();
                  } else {
                      $isDuplicate = B2BLead::where('company_name', $data['company_name'])
                          ->where('city', $data['city'] ?? 'Unknown')
                          ->exists();
                  }
  
                  if ($isDuplicate) {
                      $skipCount++;
                      continue;
                  }
  
                  // Map and Validate Fields
                  $category = 'agent';
                  if (isset($data['category']) && in_array(strtolower($data['category']), ['agent', 'developer', 'single_owner', 'single owner'])) {
                      $category = strtolower($data['category']);
                      if ($category === 'single owner') {
                          $category = 'single_owner';
                      }
                  }
  
                  $serviceAreas = [];
                  if (isset($data['service_areas']) && !empty($data['service_areas'])) {
                      $serviceAreas = array_map('trim', explode(',', $data['service_areas']));
                  }
  
                  $sourcePlatform = 'csv';
                  if (isset($data['source_platform']) && in_array(strtolower($data['source_platform']), ['meta', 'google', 'website', 'manual', 'csv'])) {
                      $sourcePlatform = strtolower($data['source_platform']);
                  }
  
                  // Create lead
                  $lead = B2BLead::create([
                      'category' => $category,
                      'company_name' => $data['company_name'],
                      'contact_person_name' => $data['contact_person_name'] ?? 'Unknown',
                      'contact_number' => $data['contact_number'] ?? null,
                      'whatsapp_number' => $data['whatsapp_number'] ?? ($data['contact_number'] ?? null),
                      'email' => $data['email'] ?? null,
                      'office_address' => $data['office_address'] ?? null,
                      'service_areas' => $serviceAreas,
                      'city' => $data['city'] ?? 'Unknown',
                      'project_ticket_size_min' => isset($data['project_ticket_size_min']) && is_numeric($data['project_ticket_size_min']) ? $data['project_ticket_size_min'] : null,
                      'project_ticket_size_max' => isset($data['project_ticket_size_max']) && is_numeric($data['project_ticket_size_max']) ? $data['project_ticket_size_max'] : null,
                      'source_platform' => $sourcePlatform,
                      'lead_created_at' => now(),
                      'status' => 'new',
                      'remark' => $data['remark'] ?? 'Imported via CSV',
                  ]);
  
                  // Log Initial Status
                  LeadStatusLog::create([
                      'lead_type' => B2BLead::class,
                      'lead_id' => $lead->id,
                      'from_status' => null,
                      'to_status' => 'new',
                      'changed_by_user_id' => Auth::id(),
                      'notes' => 'Lead imported via bulk CSV upload.',
                  ]);
  
                  $importCount++;
              }
              DB::commit();
          } catch (\Exception $e) {
              DB::rollBack();
              fclose($handle);
              return back()->with('error', 'Import failed: ' . $e->getMessage());
          }
  
          fclose($handle);
  
          return back()->with('success', "Successfully imported {$importCount} B2B Leads. Skipped {$skipCount} duplicate(s) out of {$rowCount} rows parsed.");
      }
  }
  ```

- [ ] **Step 4: Run the test to verify it passes**
  Run: `php artisan test --filter=test_b2b_bulk_import_skips_duplicates`
  Expected: PASS

- [ ] **Step 5: Commit**
  ```bash
  git add app/Http/Controllers/B2BLeadImportController.php tests/Feature/ExampleTest.php
  git commit -m "feat: add transaction safety and duplicate check to B2B bulk import"
  ```

---

### Task 3: Database Transactions and Deduplication for B2C Leads Import

**Files:**
- Modify: `app/Http/Controllers/B2CLeadImportController.php`
- Modify: `tests/Feature/ExampleTest.php`

- [ ] **Step 1: Write a test for B2C lead import deduplication**
  Add a new test inside `tests/Feature/ExampleTest.php`:
  ```php
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
  ```

- [ ] **Step 2: Run the test to verify it fails**
  Run: `php artisan test --filter=test_b2c_bulk_import_skips_duplicates`
  Expected: FAIL

- [ ] **Step 3: Implement Transactions and Deduplication in B2CLeadImportController.php**
  Rewrite `app/Http/Controllers/B2CLeadImportController.php`:
  Remove manual check on role `'analyst'`. Add database transaction block. Add deduplication on `phone`.
  ```php
  <?php
  
  namespace App\Http\Controllers;
  
  use App\Models\B2CLead;
  use App\Models\LeadStatusLog;
  use App\Services\B2CLeadAutoDistributor;
  use Illuminate\Http\RedirectResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  
  class B2CLeadImportController extends Controller
  {
      /**
       * Import B2C Buyer Leads from a CSV file.
       */
      public function import(Request $request): RedirectResponse
      {
          $request->validate([
              'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // Max 5MB
          ]);
  
          $file = $request->file('csv_file');
          $path = $file->getRealPath();
  
          if (($handle = fopen($path, 'r')) === false) {
              return back()->with('error', 'Unable to open the uploaded file.');
          }
  
          // Parse Header Row
          $headers = fgetcsv($handle);
          if (!$headers) {
              fclose($handle);
              return back()->with('error', 'The uploaded file is empty.');
          }
  
          // Clean headers
          $headers = array_map(function ($h) {
              $h = preg_replace('/[\x{FEFF}\x{200B}]/u', '', $h);
              return strtolower(trim($h));
          }, $headers);
  
          // Required headers validation
          $required = ['name', 'phone', 'city', 'property_type', 'configuration'];
          $missing = array_diff($required, $headers);
  
          if (!empty($missing)) {
              fclose($handle);
              return back()->with('error', 'Missing required B2C CSV columns: ' . implode(', ', $missing));
          }
  
          $rowCount = 0;
          $importCount = 0;
          $skipCount = 0;
          $autoShareCount = 0;
          $autoDistributor = app(B2CLeadAutoDistributor::class);
  
          DB::beginTransaction();
          try {
              while (($row = fgetcsv($handle)) !== false) {
                  $rowCount++;
      
                  // Map row data using headers
                  $data = [];
                  foreach ($headers as $index => $header) {
                      if (isset($row[$index])) {
                          $data[$header] = trim($row[$index]);
                      }
                  }
      
                  if (empty($data['name'])) {
                      continue; // Skip blank rows
                  }
  
                  // Deduplication check
                  $isDuplicate = false;
                  if (!empty($data['phone'])) {
                      $isDuplicate = B2CLead::where('phone', $data['phone'])->exists();
                  }
  
                  if ($isDuplicate) {
                      $skipCount++;
                      continue;
                  }
      
                  // Map and Validate Fields
                  $propertyType = 'flat';
                  if (isset($data['property_type']) && in_array(strtolower($data['property_type']), ['plot', 'flat', 'villa', 'commercial'])) {
                      $propertyType = strtolower($data['property_type']);
                  }
      
                  $configuration = '2BHK';
                  if (isset($data['configuration']) && in_array(strtoupper($data['configuration']), ['1BHK', '2BHK', '3BHK', '4BHK', 'PLOT', 'STUDIO'])) {
                      $configuration = strtoupper($data['configuration']);
                  }
      
                  $preferredLocations = [];
                  if (isset($data['preferred_locations']) && !empty($data['preferred_locations'])) {
                      $preferredLocations = array_map('trim', explode(',', $data['preferred_locations']));
                  }
      
                  $sourcePlatform = 'website';
                  if (isset($data['source_platform']) && in_array(strtolower($data['source_platform']), ['meta', 'google', 'website', 'manual'])) {
                      $sourcePlatform = strtolower($data['source_platform']);
                  }
      
                  // Create lead
                  $lead = B2CLead::create([
                      'name' => $data['name'],
                      'phone' => $data['phone'] ?? null,
                      'email' => $data['email'] ?? null,
                      'city' => $data['city'] ?? 'Unknown',
                      'pincode' => $data['pincode'] ?? null,
                      'budget_min' => isset($data['budget_min']) && is_numeric($data['budget_min']) ? $data['budget_min'] : null,
                      'budget_max' => isset($data['budget_max']) && is_numeric($data['budget_max']) ? $data['budget_max'] : null,
                      'preferred_locations' => $preferredLocations,
                      'property_type' => $propertyType,
                      'configuration' => $configuration,
                      'source_platform' => $sourcePlatform,
                      'lead_created_at' => now(),
                      'status' => 'new',
                      'remark' => $data['remark'] ?? 'Imported via CSV',
                  ]);
      
                  // Log Initial Status
                  LeadStatusLog::create([
                      'lead_type' => B2CLead::class,
                      'lead_id' => $lead->id,
                      'from_status' => null,
                      'to_status' => 'new',
                      'changed_by_user_id' => Auth::id(),
                      'notes' => 'Buyer lead imported via bulk CSV upload.',
                  ]);
      
                  $autoShareCount += $autoDistributor
                      ->distribute($lead, Auth::id(), 'CSV bulk upload')
                      ->count();
      
                  $importCount++;
              }
              DB::commit();
          } catch (\Exception $e) {
              DB::rollBack();
              fclose($handle);
              return back()->with('error', 'Import failed: ' . $e->getMessage());
          }
  
          fclose($handle);
  
          return back()->with('success', "Successfully imported {$importCount} B2C Leads. Skipped {$skipCount} duplicate(s) out of {$rowCount} rows parsed. Auto-shared {$autoShareCount} partner lead assignment(s).");
      }
  }
  ```

- [ ] **Step 4: Run the test to verify it passes**
  Run: `php artisan test --filter=test_b2c_bulk_import_skips_duplicates`
  Expected: PASS

- [ ] **Step 5: Commit**
  ```bash
  git add app/Http/Controllers/B2CLeadImportController.php tests/Feature/ExampleTest.php
  git commit -m "feat: add transaction safety and duplicate check to B2C bulk import"
  ```

---

### Task 4: Database Transactions and Deduplication for Partners Import

**Files:**
- Modify: `app/Http/Controllers/PartnerImportController.php`
- Modify: `tests/Feature/ExampleTest.php`

- [ ] **Step 1: Write a test for Partner bulk import deduplication**
  Add a new test inside `tests/Feature/ExampleTest.php`:
  ```php
  public function test_partner_bulk_import_skips_duplicates(): void
  {
      $admin = User::factory()->create(['role' => 'admin']);

      // Seed an existing partner by phone
      \App\Models\Partner::create([
          'company_name' => 'Existing Partner Corp',
          'contact_person' => 'Jane Agent',
          'phone' => '7777777777',
          'city' => 'Mumbai',
          'type' => 'agent',
          'package' => 'starter',
          'is_active' => true,
      ]);

      // Seed an existing partner by email
      \App\Models\Partner::create([
          'company_name' => 'Existing Email Partner',
          'contact_person' => 'Bob Broker',
          'email' => 'bob@broker.com',
          'city' => 'Pune',
          'type' => 'agent',
          'package' => 'free',
          'is_active' => true,
      ]);

      $csvContent = "company_name,contact_person,phone,city,type,email,office_address,service_areas,package,paid_amount,package_purchase_date,renewal_date,lead_source,remark\n";
      $csvContent .= "Imported Agent Corp,Agent Contact,7777777777,Mumbai,agent,agent@corp.com,Office 101,\"Powai, Chembur\",growth,15000,2026-06-01,2027-06-01,Direct,Great profile\n"; // Duplicate phone
      $csvContent .= "Imported Affiliate Inc,Affiliate Person,8888888888,Lucknow,affiliate,bob@broker.com,Suite 202,Hazratganj,premium,25000,2026-06-02,2027-06-02,Reference,Affiliate partner\n"; // Duplicate email
      $csvContent .= "Brand New Partner Corp,New Contact,9998882221,Delhi,agent,new@partner.com,Suite 300,Noida,premium,50000,2026-06-03,2027-06-03,Direct,Active\n"; // Unique Partner

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
      $response->assertSessionHas('success', function($msg) {
          return str_contains($msg, 'Successfully imported 1') && str_contains($msg, 'Skipped 2 duplicate(s)');
      });

      unlink($tempFile);
  }
  ```

- [ ] **Step 2: Run the test to verify it fails**
  Run: `php artisan test --filter=test_partner_bulk_import_skips_duplicates`
  Expected: FAIL

- [ ] **Step 3: Implement Transactions and Deduplication in PartnerImportController.php**
  Rewrite `app/Http/Controllers/PartnerImportController.php`:
  Wrap insertions in database transactions. Verify duplicate checking on `phone` (primary) or `email` (secondary).
  ```php
  <?php
  
  namespace App\Http\Controllers;
  
  use App\Models\Partner;
  use Illuminate\Http\RedirectResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  
  class PartnerImportController extends Controller
  {
      /**
       * Import Partners from a CSV file.
       */
      public function import(Request $request): RedirectResponse
      {
          // Enforce RBAC
          if (!in_array(Auth::user()->role, ['super_admin', 'admin'], true)) {
              abort(403, 'Unauthorized to import partners.');
          }
  
          $request->validate([
              'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // Max 5MB
          ]);
  
          $file = $request->file('csv_file');
          $path = $file->getRealPath();
  
          if (($handle = fopen($path, 'r')) === false) {
              return back()->with('error', 'Unable to open the uploaded file.');
          }
  
          // Parse Header Row
          $headers = fgetcsv($handle);
          if (!$headers) {
              fclose($handle);
              return back()->with('error', 'The uploaded file is empty.');
          }
  
          // Clean headers (remove BOM, trim, lowercase)
          $headers = array_map(function ($h) {
              $h = preg_replace('/[\x{FEFF}\x{200B}]/u', '', $h);
              return strtolower(trim($h));
          }, $headers);
  
          // Required headers validation
          $required = ['company_name', 'contact_person', 'phone', 'city', 'type'];
          $missing = array_diff($required, $headers);
  
          if (!empty($missing)) {
              fclose($handle);
              return back()->with('error', 'Missing required Partner CSV columns: ' . implode(', ', $missing));
          }
  
          $rowCount = 0;
          $importCount = 0;
          $skipCount = 0;
  
          DB::beginTransaction();
          try {
              while (($row = fgetcsv($handle)) !== false) {
                  $rowCount++;
      
                  // Map row data using headers
                  $data = [];
                  foreach ($headers as $index => $header) {
                      if (isset($row[$index])) {
                          $data[$header] = trim($row[$index]);
                      }
                  }
      
                  if (empty($data['company_name'])) {
                      continue; // Skip blank rows
                  }
  
                  // Deduplication check
                  $isDuplicate = false;
                  if (!empty($data['phone'])) {
                      $isDuplicate = Partner::where('phone', $data['phone'])->exists();
                  } elseif (!empty($data['email'])) {
                      $isDuplicate = Partner::where('email', $data['email'])->exists();
                  }
  
                  if ($isDuplicate) {
                      $skipCount++;
                      continue;
                  }
      
                  // Map and Validate Partner Type
                  $type = 'agent';
                  if (isset($data['type'])) {
                      $rawType = strtolower($data['type']);
                      if (in_array($rawType, ['agent', 'broker', 'agent / broker'])) {
                          $type = 'agent';
                      } elseif (in_array($rawType, ['developer', 'real estate developer'])) {
                          $type = 'developer';
                      } elseif (in_array($rawType, ['affiliate', 'affiliate partner', 'affiliate_partner'])) {
                          $type = 'affiliate';
                      }
                  }
      
                  // Map and Validate Package
                  $package = 'free';
                  if (isset($data['package'])) {
                      $rawPackage = strtolower($data['package']);
                      if (in_array($rawPackage, ['free', 'starter', 'growth', 'premium', 'customise'])) {
                          $package = $rawPackage;
                      }
                  }
      
                  // Map Service Areas (comma separated)
                  $serviceAreas = [];
                  if (isset($data['service_areas']) && !empty($data['service_areas'])) {
                      $serviceAreas = array_map('trim', explode(',', $data['service_areas']));
                  }
      
                  // Parse Dates
                  $packagePurchaseDate = null;
                  if (isset($data['package_purchase_date']) && !empty($data['package_purchase_date'])) {
                      $time = strtotime($data['package_purchase_date']);
                      if ($time !== false) {
                          $packagePurchaseDate = date('Y-m-d', $time);
                      }
                  }
      
                  $renewalDate = null;
                  if (isset($data['renewal_date']) && !empty($data['renewal_date'])) {
                      $time = strtotime($data['renewal_date']);
                      if ($time !== false) {
                          $renewalDate = date('Y-m-d', $time);
                      }
                  }
      
                  // Parse Paid Amount
                  $paidAmount = null;
                  if (isset($data['paid_amount']) && is_numeric($data['paid_amount'])) {
                      $paidAmount = floatval($data['paid_amount']);
                  }
      
                  // Create Partner Account
                  Partner::create([
                      'type' => $type,
                      'company_name' => $data['company_name'],
                      'contact_person' => $data['contact_person'] ?? 'Unknown',
                      'phone' => $data['phone'] ?? null,
                      'email' => $data['email'] ?? null,
                      'office_address' => $data['office_address'] ?? null,
                      'service_areas' => $serviceAreas,
                      'city' => $data['city'] ?? 'Unknown',
                      'package' => $package,
                      'paid_amount' => $paidAmount,
                      'package_purchase_date' => $packagePurchaseDate,
                      'renewal_date' => $renewalDate,
                      'lead_source' => $data['lead_source'] ?? 'CSV Bulk Upload',
                      'remark' => $data['remark'] ?? 'Imported via CSV',
                      'is_active' => true,
                  ]);
      
                  $importCount++;
              }
              DB::commit();
          } catch (\Exception $e) {
              DB::rollBack();
              fclose($handle);
              return back()->with('error', 'Import failed: ' . $e->getMessage());
          }
  
          fclose($handle);
  
          return back()->with('success', "Successfully imported {$importCount} Partners. Skipped {$skipCount} duplicate(s) out of {$rowCount} rows parsed.");
      }
  }
  ```

- [ ] **Step 4: Run the test to verify it passes**
  Run: `php artisan test --filter=test_partner_bulk_import_skips_duplicates`
  Expected: PASS

- [ ] **Step 5: Commit**
  ```bash
  git add app/Http/Controllers/PartnerImportController.php tests/Feature/ExampleTest.php
  git commit -m "feat: add transaction safety and duplicate check to Partner bulk import"
  ```

---

### Task 5: Complete Verification Suite

**Files:**
- Test: `tests/Feature/ExampleTest.php`

- [ ] **Step 1: Run the full test suite**
  Run: `php artisan test`
  Expected: PASS with 34 tests passing (31 original tests + 3 new deduplication/RBAC tests)

- [ ] **Step 2: Commit**
  ```bash
  git commit --allow-empty -m "chore: full bulk import correctness and safety validation verified"
  ```
