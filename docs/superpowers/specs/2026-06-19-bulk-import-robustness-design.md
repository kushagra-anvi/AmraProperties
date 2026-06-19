# Design Specification: Bulk Import Robustness & Security Improvements

**Date:** 2026-06-19  
**Status:** Approved  

---

## 1. Goal

To secure, stabilize, and improve the reliability of the bulk CSV upload functionalities in the AmraProperty CRM for **B2B Leads**, **B2C Leads**, and **Partners**. 

---

## 2. Proposed Changes

### 2.1 Route Security (RBAC) Alignments
*   Move B2B bulk import route under `/crm` prefix from the sales team middleware group to the `super_admin,admin` restricted middleware group in `routes/web.php`.
*   Clean up manual role checks inside `B2BLeadImportController` and `B2CLeadImportController` since the middleware handles authorization cleanly.

### 2.2 Database Transactions (Atomicity)
*   Enclose the loop of CSV parsing and database insertions in a try-catch block wrapped by `DB::beginTransaction()`, `DB::commit()`, and `DB::rollBack()`.
*   If an exception occurs during the CSV parsing/database save, ensure the entire batch is rolled back and the user gets a friendly error message detailing the issue.
*   Ensure that the CSV file handle is safely closed (`fclose($handle)`) in all success and error code paths.

### 2.3 Deduplication (Data Integrity)
*   Integrate pre-creation database checks to prevent importing duplicate records:
    *   **B2C Leads:** Unique check on `phone` field.
    *   **Partners:** Unique check on `phone` field (or `email` if phone is blank/null).
    *   **B2B Leads:** Unique check on `contact_number` field (or both `company_name` and `city` if `contact_number` is blank/null).
*   Count skipped duplicates and output the counts in the final success message redirect.

---

## 3. Detailed File Impact

### `routes/web.php`
*   Move `/b2b/bulk-import` route to `role:super_admin,admin` middleware group.

### `app/Http/Controllers/B2BLeadImportController.php`
*   Remove manual check on role `'sales_team'`.
*   Wrap rows in `DB::transaction`.
*   Add deduplication checks: Check for existing `B2BLead` by `contact_number` or (`company_name` AND `city`).
*   Track skipped count.

### `app/Http/Controllers/B2CLeadImportController.php`
*   Remove manual check on role `'analyst'`.
*   Wrap rows in `DB::transaction`.
*   Add deduplication checks: Check for existing `B2CLead` by `phone`.
*   Track skipped count.

### `app/Http/Controllers/PartnerImportController.php`
*   Wrap rows in `DB::transaction`.
*   Add deduplication checks: Check for existing `Partner` by `phone` or `email`.
*   Track skipped count.

---

## 4. Verification & Testing Plan
*   Run existing tests: `php artisan test` to confirm nothing was broken.
*   Write unit/feature tests for:
    *   B2B Lead import route RBAC access limits.
    *   Deduplication behavior (importing identical CSV twice skips duplicates instead of double inserting).
    *   Database transaction rollbacks (forcing a failure in a CSV row rolls back all previous rows inserted in that batch).
