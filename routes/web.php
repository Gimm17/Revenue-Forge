<?php

use App\Http\Controllers\App\AffiliateController;
use App\Http\Controllers\App\AffiliateLinkStoreController;
use App\Http\Controllers\App\AffiliateShowController;
use App\Http\Controllers\App\AnalyticsController;
use App\Http\Controllers\App\BillingController;
use App\Http\Controllers\App\CustomerIndexController;
use App\Http\Controllers\App\CustomerShowController;
use App\Http\Controllers\App\SendCustomerPortalLinkController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\GenerateOfferCopyController;
use App\Http\Controllers\App\OfferCreateController;
use App\Http\Controllers\App\OfferEditController;
use App\Http\Controllers\App\OfferIndexController;
use App\Http\Controllers\App\OfferPublishController;
use App\Http\Controllers\App\OfferStoreController;
use App\Http\Controllers\App\OfferUpdateController;
use App\Http\Controllers\App\OfferDestroyController;
use App\Http\Controllers\App\LandingPageBuilderController;
use App\Http\Controllers\App\WorkspaceSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\CheckoutResultController;
use App\Http\Controllers\Public\OfferCheckoutController;
use App\Http\Controllers\Public\PublicLandingController;
use App\Http\Controllers\Public\PublicOfferController;
use App\Http\Controllers\Public\DemoCheckoutController;
use App\Http\Controllers\Public\PricingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', PublicLandingController::class)->name('landing');
Route::get('/pricing', PricingController::class)->name('pricing');

// Public Offer & Checkout
Route::get('/o/{slug}', PublicOfferController::class)->name('offer.show');
Route::post('/o/{slug}/checkout', OfferCheckoutController::class)->middleware('throttle:checkout')->name('offer.checkout');
Route::get('/checkout/success', [CheckoutResultController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutResultController::class, 'cancel'])->name('checkout.cancel');

// Local Sandbox / Demo Checkout
Route::get('/checkout/demo/{order}', [DemoCheckoutController::class, 'show'])->name('checkout.demo.show');
Route::post('/checkout/demo/{order}/pay', [DemoCheckoutController::class, 'process'])->name('checkout.demo.process');
Route::get('/portal/mock', function (\Illuminate\Http\Request $request) {
    return inertia('Public/DemoPortal', ['email' => $request->query('email')]);
})->name('portal.demo.show');

/*
|--------------------------------------------------------------------------
| App Routes (Authenticated + Workspace Resolved)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'resolve.workspace'])
    ->prefix('app')
    ->name('app.')
    ->group(function () {

        // Dashboard
        Route::get('/', DashboardController::class)->name('dashboard');

        // Offers
        Route::get('/offers', OfferIndexController::class)->name('offers.index');
        Route::get('/offers/create', OfferCreateController::class)->name('offers.create');
        Route::post('/offers', OfferStoreController::class)->name('offers.store');
        Route::get('/offers/{offer}/edit', OfferEditController::class)->name('offers.edit');
        Route::put('/offers/{offer}', OfferUpdateController::class)->name('offers.update');
        Route::delete('/offers/{offer}', OfferDestroyController::class)->name('offers.destroy');
        Route::post('/offers/{offer}/publish', OfferPublishController::class)->name('offers.publish');
        Route::post('/offers/generate', GenerateOfferCopyController::class)->middleware('throttle:ai-generate')->name('offers.generate');

        // Landing Page Builder
        Route::get('/offers/{offer}/landing-builder', [LandingPageBuilderController::class, 'show'])->name('offers.landing-builder');
        Route::put('/offers/{offer}/landing-builder', [LandingPageBuilderController::class, 'update'])->name('offers.landing-builder.update');

        // Customers
        Route::get('/customers', CustomerIndexController::class)->name('customers.index');
        Route::get('/customers/{customer}', CustomerShowController::class)->name('customers.show');
        Route::post('/customers/{customer}/portal-link', SendCustomerPortalLinkController::class)->name('customers.portal-link');

        // Analytics
        Route::get('/analytics', AnalyticsController::class)->name('analytics');

        // Affiliates
        Route::get('/affiliates', [AffiliateController::class, 'index'])->name('affiliates.index');
        Route::post('/affiliates', [AffiliateController::class, 'store'])->name('affiliates.store');
        Route::get('/affiliates/{program}', AffiliateShowController::class)->name('affiliates.show');
        Route::post('/affiliates/{program}/links', AffiliateLinkStoreController::class)->name('affiliates.links.store');

        // Billing / Orders
        Route::get('/billing', BillingController::class)->name('billing');

        // Settings
        Route::get('/settings', [WorkspaceSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [WorkspaceSettingsController::class, 'update'])->name('settings.update');
    });

/*
|--------------------------------------------------------------------------
| Profile Routes (Breeze default)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
