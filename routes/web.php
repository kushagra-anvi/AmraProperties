<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\B2BLeadController;
use App\Http\Controllers\B2BLeadImportController;
use App\Http\Controllers\B2CLeadController;
use App\Http\Controllers\B2CLeadImportController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PartnerImportController;
use App\Http\Controllers\PlatformSpendController;
use App\Http\Controllers\SalesTeamController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BlogController;

/*
|--------------------------------------------------------------------------
| Public Client-Facing Website Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $featuredProperties = \App\Models\Property::where('status', 'publish')
        ->where('is_featured', true)
        ->latest()
        ->take(6)
        ->get();
    return view('site.home', compact('featuredProperties'));
})->name('site.home');

Route::get('/about', function () {
    return view('site.about');
})->name('site.about');

Route::get('/contact', function () {
    return view('site.contact');
})->name('site.contact');

Route::get('/property', [PropertyController::class, 'index'])->name('site.property');
Route::get('/property/{slug}', [PropertyController::class, 'show'])->name('site.property.show');

Route::get('/blog', [BlogController::class, 'index'])->name('site.blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('site.blog.show');


Route::get('/privacy-policy', function () {
    return view('site.privacy');
})->name('site.privacy');

Route::get('/terms-conditions', function () {
    return view('site.terms');
})->name('site.terms');

Route::get('/rera-disclaimer', function () {
    return view('site.rera-disclaimer');
})->name('site.rera-disclaimer');

Route::get('/advertiser-agreement', function () {
    return view('site.advertiser-agreement');
})->name('site.advertiser-agreement');

// Legacy redirects
Route::get('/privacy', fn () => redirect()->route('site.privacy', [], 301));
Route::get('/terms', fn () => redirect()->route('site.terms', [], 301));
Route::get('/4521-2', fn () => redirect()->route('site.advertiser-agreement', [], 301));

Route::get('/pages/{page}.html', function (string $page) {
    abort_unless(in_array($page, ['about', 'contact', 'privacy', 'property', 'terms'], true), 404);

    return redirect()->route("site.{$page}", [], 301);
});

// Organic Contact Form submission
Route::post('/contact', [ContactFormController::class, 'submit'])->name('contact.submit');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Admin CRM Portal Routes (Protected by Auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('crm')->group(function () {
    
    // Redirect each role to the first module they are allowed to use.
    Route::get('/', function () {
        if (auth()->user()->role === 'sales_team') {
            return redirect()->route('crm.b2b.index');
        }

        return redirect()->route('crm.dashboard');
    });

    // Executive Dashboard
    Route::get('/dashboard', DashboardController::class)
        ->middleware('role:super_admin,admin,analyst')
        ->name('crm.dashboard');
    Route::post('/dashboard/platform-spend', [PlatformSpendController::class, 'store'])
        ->middleware('role:super_admin,admin')
        ->name('crm.dashboard.platform-spend');

    // B2B Leads pipeline management
    Route::middleware('role:super_admin,admin,sales_team')->group(function () {
        Route::get('/b2b', [B2BLeadController::class, 'index'])->name('crm.b2b.index');
        Route::get('/b2b/create', [B2BLeadController::class, 'create'])->name('crm.b2b.create');
        Route::post('/b2b', [B2BLeadController::class, 'store'])->name('crm.b2b.store');
        Route::get('/b2b/{lead}', [B2BLeadController::class, 'show'])->name('crm.b2b.show');
        Route::get('/b2b/{lead}/edit', [B2BLeadController::class, 'edit'])->name('crm.b2b.edit');
        Route::put('/b2b/{lead}', [B2BLeadController::class, 'update'])->name('crm.b2b.update');

        // B2B Lead Workflows
        Route::post('/b2b/{lead}/status', [B2BLeadController::class, 'updateStatus'])->name('crm.b2b.status');
        Route::post('/b2b/{lead}/assign', [B2BLeadController::class, 'assign'])->name('crm.b2b.assign');
        Route::post('/b2b/{lead}/followup', [B2BLeadController::class, 'logFollowUp'])->name('crm.b2b.followup');
    });

    // B2C distribution and partner management
    Route::middleware('role:super_admin,admin')->group(function () {
        // B2C Buyer Leads management
        Route::get('/b2c', [B2CLeadController::class, 'index'])->name('crm.b2c.index');
        Route::get('/b2c/create', [B2CLeadController::class, 'create'])->name('crm.b2c.create');
        Route::post('/b2c', [B2CLeadController::class, 'store'])->name('crm.b2c.store');
        Route::get('/b2c/{lead}', [B2CLeadController::class, 'show'])->name('crm.b2c.show');
        Route::get('/b2c/{lead}/edit', [B2CLeadController::class, 'edit'])->name('crm.b2c.edit');
        Route::put('/b2c/{lead}', [B2CLeadController::class, 'update'])->name('crm.b2c.update');
        Route::post('/b2c/{lead}/filter', [B2CLeadController::class, 'markFiltered'])->name('crm.b2c.filter');
        
        // B2C Share to Agent/Developer workflow
        Route::post('/b2c/{lead}/share', [B2CLeadController::class, 'share'])->name('crm.b2c.share');
        Route::post('/b2c/{lead}/auto-distribute', [B2CLeadController::class, 'autoDistribute'])->name('crm.b2c.auto-distribute');
        
        // B2C Tele-caller assignment and CSV bulk import
        Route::post('/b2c/{lead}/assign', [B2CLeadController::class, 'assign'])->name('crm.b2c.assign');
        Route::post('/b2c/bulk-assign', [B2CLeadController::class, 'bulkAssign'])->name('crm.b2c.bulk-assign');
        Route::post('/b2c/bulk-import', [B2CLeadImportController::class, 'import'])->name('crm.b2c.import');

        // Partners Directory (Agents & Developers)
        Route::get('/partners', [PartnerController::class, 'index'])->name('crm.partners.index');
        Route::get('/partners/create', [PartnerController::class, 'create'])->name('crm.partners.create');
        Route::post('/partners', [PartnerController::class, 'store'])->name('crm.partners.store');
        Route::post('/partners/bulk-import', [PartnerImportController::class, 'import'])->name('crm.partners.import');
        Route::post('/b2b/bulk-import', [B2BLeadImportController::class, 'import'])->name('crm.b2b.import');
        Route::get('/partners/{partner}', [PartnerController::class, 'show'])->name('crm.partners.show');
        Route::get('/partners/{partner}/edit', [PartnerController::class, 'edit'])->name('crm.partners.edit');
        Route::put('/partners/{partner}', [PartnerController::class, 'update'])->name('crm.partners.update');
    });

    // Sales Performance Directory
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/sales', [SalesTeamController::class, 'index'])->name('crm.sales.index');
        Route::get('/sales/{salesPerson}', [SalesTeamController::class, 'show'])->name('crm.sales.show');
    });
});

/*
|--------------------------------------------------------------------------
| Partner Portal Routes (Protected by Auth and Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:partner'])->prefix('partner')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\PartnerPortalController::class, 'dashboard'])->name('crm.partner.dashboard');
    Route::get('/leads/{lead}', [App\Http\Controllers\PartnerPortalController::class, 'showLead'])->name('crm.partner.leads.show');
});
