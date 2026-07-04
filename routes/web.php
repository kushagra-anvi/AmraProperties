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
use App\Http\Controllers\PropertyEnquiryController;
use App\Http\Controllers\PlatformSpendController;
use App\Http\Controllers\SalesTeamController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CRM\PropertyAdminController;
use App\Models\BlogPost;
use App\Models\Partner;
use App\Models\Property;
use App\Support\SeoMeta;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Public Client-Facing Website Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $featuredProperties = \App\Models\Property::with('configurations')->where('status', 'publish')
        ->where('is_featured', true)
        ->latest()
        ->take(6)
        ->get();
    $recommendedSellers = Partner::where('is_active', true)
        ->whereIn('type', ['agent', 'developer'])
        ->withCount(['properties as total_listings'])
        ->orderByDesc('total_listings')
        ->limit(8)
        ->get()
        ->sortByDesc(fn (Partner $partner) => [
            'free' => 0,
            'starter' => 10,
            'growth' => 20,
            'premium' => 30,
            'customise' => 30,
        ][$partner->package] ?? 0)
        ->values();
    $seo = SeoMeta::staticPage('home', route('site.home'));

    $locations = \App\Models\Property::where('status', 'publish')
        ->whereNotNull('city')
        ->where('city', '!=', '')
        ->distinct()
        ->pluck('city')
        ->all();

    return view('site.home', compact('featuredProperties', 'recommendedSellers', 'seo', 'locations'));
})->name('site.home');

Route::get('/about-us', function () {
    return view('site.about', ['seo' => SeoMeta::staticPage('about', route('site.about'))]);
})->name('site.about');

Route::get('/contact', function () {
    return view('site.contact', ['seo' => SeoMeta::staticPage('contact', route('site.contact'))]);
})->name('site.contact');

Route::get('/sell-property-online', function () {
    return view('site.sell-property-online', ['seo' => SeoMeta::staticPage('sell-property-online', route('site.sell-property-online'))]);
})->name('site.sell-property-online');

Route::get('/property', [PropertyController::class, 'index'])->name('site.property');
Route::get('/property/{slug}', [PropertyController::class, 'show'])->name('site.property.show');
Route::get('/compare', [PropertyController::class, 'compare'])->name('site.compare');
Route::post('/property/{property}/enquiry', [PropertyEnquiryController::class, 'store'])->name('site.property.enquiry');
Route::post('/recommended-seller/{partner}/contact', [PropertyEnquiryController::class, 'storeSellerContact'])->name('site.recommended-seller.contact');

Route::get('/blog', [BlogController::class, 'index'])->name('site.blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('site.blog.show');


Route::get('/privacy-policy', function () {
    return view('site.privacy', ['seo' => SeoMeta::staticPage('privacy', route('site.privacy'))]);
})->name('site.privacy');

Route::get('/terms-conditions', function () {
    return view('site.terms', ['seo' => SeoMeta::staticPage('terms', route('site.terms'))]);
})->name('site.terms');

Route::get('/rera-disclaimer', function () {
    return view('site.rera-disclaimer', ['seo' => SeoMeta::staticPage('rera-disclaimer', route('site.rera-disclaimer'))]);
})->name('site.rera-disclaimer');

Route::get('/4521-2', function () {
    return view('site.advertiser-agreement', ['seo' => SeoMeta::staticPage('advertiser-agreement', route('site.advertiser-agreement'))]);
})->name('site.advertiser-agreement');

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\nDisallow: /crm/\nDisallow: /partner/\nDisallow: /login\n\nSitemap: " . url('/sitemap.xml') . "\n";

    return Response::make($content, 200, ['Content-Type' => 'text/plain']);
})->name('site.robots');

Route::get('/sitemap.xml', function () {
    $urls = collect([
        [route('site.home'), now()],
        [route('site.about'), now()],
        [route('site.contact'), now()],
        [route('site.sell-property-online'), now()],
        [route('site.property'), now()],
        [route('site.blog'), now()],
        [route('site.privacy'), now()],
        [route('site.terms'), now()],
        [route('site.rera-disclaimer'), now()],
        [route('site.advertiser-agreement'), now()],
    ]);

    Property::where('status', 'publish')
        ->select(['slug', 'updated_at'])
        ->orderByDesc('updated_at')
        ->chunk(200, function ($properties) use ($urls) {
            foreach ($properties as $property) {
                $urls->push([route('site.property.show', $property->slug), $property->updated_at]);
            }
        });

    BlogPost::where('status', 'publish')
        ->select(['slug', 'updated_at'])
        ->orderByDesc('updated_at')
        ->chunk(200, function ($posts) use ($urls) {
            foreach ($posts as $post) {
                $urls->push([route('site.blog.show', $post->slug), $post->updated_at]);
            }
        });

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($urls as $index => [$loc, $lastmod]) {
        $lastmod = $lastmod ? $lastmod->toAtomString() : now()->toAtomString();
        $priority = $index < 10 ? '0.8' : '0.6';

        $xml .= "    <url>\n";
        $xml .= '        <loc>' . e($loc) . "</loc>\n";
        $xml .= '        <lastmod>' . e($lastmod) . "</lastmod>\n";
        $xml .= "        <changefreq>weekly</changefreq>\n";
        $xml .= '        <priority>' . $priority . "</priority>\n";
        $xml .= "    </url>\n";
    }

    $xml .= '</urlset>';

    return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
})->name('site.sitemap');

// Legacy redirects
Route::get('/about', fn () => redirect()->route('site.about', [], 301));
Route::get('/privacy', fn () => redirect()->route('site.privacy', [], 301));
Route::get('/terms', fn () => redirect()->route('site.terms', [], 301));
Route::get('/advertiser-agreement', fn () => redirect()->route('site.advertiser-agreement', [], 301));

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
        Route::post('/b2c/bulk-share', [B2CLeadController::class, 'bulkShare'])->name('crm.b2c.bulk-share');
        Route::post('/b2c/{lead}/status', [B2CLeadController::class, 'updateStatus'])->name('crm.b2c.status');
        Route::post('/b2c/{lead}/auto-distribute', [B2CLeadController::class, 'autoDistribute'])->name('crm.b2c.auto-distribute');
        
        // B2C Tele-caller assignment and CSV bulk import
        Route::post('/b2c/{lead}/assign', [B2CLeadController::class, 'assign'])->name('crm.b2c.assign');
        Route::post('/b2c/bulk-assign', [B2CLeadController::class, 'bulkAssign'])->name('crm.b2c.bulk-assign');
        Route::post('/b2c/bulk-import', [B2CLeadImportController::class, 'import'])->name('crm.b2c.import');
        Route::post('/b2c/{lead}/followup', [B2CLeadController::class, 'logFollowUp'])->name('crm.b2c.followup');
        Route::post('/b2c/followup/{followUp}/complete', [B2CLeadController::class, 'completeFollowUp'])->name('crm.b2c.followup.complete');

        // Partners Directory (Agents & Developers)
        Route::get('/partners', [PartnerController::class, 'index'])->name('crm.partners.index');
        Route::get('/partners/create', [PartnerController::class, 'create'])->name('crm.partners.create');
        Route::post('/partners', [PartnerController::class, 'store'])->name('crm.partners.store');
        Route::post('/partners/bulk-import', [PartnerImportController::class, 'import'])->name('crm.partners.import');
        Route::post('/b2b/bulk-import', [B2BLeadImportController::class, 'import'])->name('crm.b2b.import');
        Route::get('/partners/{partner}', [PartnerController::class, 'show'])->name('crm.partners.show');
        Route::get('/partners/{partner}/edit', [PartnerController::class, 'edit'])->name('crm.partners.edit');
        Route::put('/partners/{partner}', [PartnerController::class, 'update'])->name('crm.partners.update');

        Route::get('/property-enquiries', [PropertyEnquiryController::class, 'index'])->name('crm.property-enquiries.index');

        // Properties CRUD
        Route::resource('properties', PropertyAdminController::class)->names([
            'index' => 'crm.properties.index',
            'create' => 'crm.properties.create',
            'store' => 'crm.properties.store',
            'edit' => 'crm.properties.edit',
            'update' => 'crm.properties.update',
            'destroy' => 'crm.properties.destroy',
        ]);
    });

    // Sales Performance Directory
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/sales', [SalesTeamController::class, 'index'])->name('crm.sales.index');
        Route::get('/sales/create', [SalesTeamController::class, 'create'])->name('crm.sales.create');
        Route::post('/sales', [SalesTeamController::class, 'store'])->name('crm.sales.store');
        Route::get('/sales/{salesPerson}', [SalesTeamController::class, 'show'])->name('crm.sales.show');
        Route::get('/sales/{salesPerson}/edit', [SalesTeamController::class, 'edit'])->name('crm.sales.edit');
        Route::put('/sales/{salesPerson}', [SalesTeamController::class, 'update'])->name('crm.sales.update');
    });
});

/*
|--------------------------------------------------------------------------
| Partner Portal Routes (Protected by Auth and Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:partner'])->prefix('partner')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\PartnerPortalController::class, 'dashboard'])->name('crm.partner.dashboard');
    Route::get('/leads/create', [App\Http\Controllers\PartnerPortalController::class, 'createLead'])->name('crm.partner.leads.create');
    Route::post('/leads', [App\Http\Controllers\PartnerPortalController::class, 'storeLead'])->name('crm.partner.leads.store');
    Route::get('/leads/{lead}', [App\Http\Controllers\PartnerPortalController::class, 'showLead'])->name('crm.partner.leads.show');
    Route::post('/leads/{lead}/remark', [App\Http\Controllers\PartnerPortalController::class, 'updateLeadRemark'])->name('crm.partner.leads.remark');
    Route::post('/leads/{lead}/followup', [App\Http\Controllers\PartnerPortalController::class, 'scheduleFollowup'])->name('crm.partner.leads.followup');
    Route::post('/leads/followup/{followUp}/complete', [App\Http\Controllers\PartnerPortalController::class, 'completeFollowup'])->name('crm.partner.leads.followup.complete');
    Route::get('/properties', [App\Http\Controllers\PartnerPortalController::class, 'properties'])->name('crm.partner.properties.index');
    Route::get('/properties/create', [App\Http\Controllers\PartnerPortalController::class, 'createProperty'])->name('crm.partner.properties.create');
    Route::post('/properties', [App\Http\Controllers\PartnerPortalController::class, 'storeProperty'])->name('crm.partner.properties.store');
});
