<?php

use App\Http\Controllers\api\authController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\ChatController;
use App\Http\Controllers\api\CreateProductController;
use App\Http\Controllers\api\DriverController;
use App\Http\Controllers\api\DriverSupervisorController;
use App\Http\Controllers\api\FollowController;
use App\Http\Controllers\api\LoyaltyController;
use App\Http\Controllers\api\mainApiController;
use App\Http\Controllers\api\MerchantController;
use App\Http\Controllers\api\productController;
use App\Http\Controllers\api\ReviewController;
use App\Http\Controllers\api\RoleChangeRequestController;
use App\Http\Controllers\api\SettingController;
use App\Http\Controllers\api\TicketController;
use App\Http\Controllers\api\UserAddressController;
use App\Http\Controllers\api\ShippingProofController;
use App\Http\Controllers\api\CurrencyController;
use App\Http\Controllers\api\ReturnController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Multi-Vendor Store Application
|--------------------------------------------------------------------------
|
| This file contains all API routes for the multi-vendor store application.
| Routes are organized by functionality and grouped logically for better
| maintainability and code readability.
|
| Version: 1.0
| Last Updated: 2024
|
*/

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (No Authentication Required)
|--------------------------------------------------------------------------
| These routes are accessible without user authentication
| Used for general data access and user registration/login
*/

// ========================================
// AUTHENTICATION ROUTES (Public)
// ========================================
Route::prefix('auth')->group(function () {
    Route::post('/login', [authController::class, 'login']);
    Route::post('/register', [authController::class, 'register']);
});

// ========================================
// GENERAL DATA ROUTES (Public)
// ========================================
Route::prefix('data')->group(function () {
    // Geographic data
    Route::get('/cities', [mainApiController::class, 'cities']);

    // Merchants
    Route::get('/merchants', [mainApiController::class, 'getMerchants']);
    Route::get('/merchants/{id}', [mainApiController::class, 'getMerchant']);

    // Search functionality
    Route::get('/search', [mainApiController::class, 'searchProducts']); // Legacy: البحث عن المنتجات فقط
    Route::get('/search/advanced', [mainApiController::class, 'search']); // البحث الشامل (منتجات + تجار)
    Route::get('/search/merchants', [mainApiController::class, 'searchMerchants']); // البحث عن التجار فقط
    Route::get('/search/popular', [mainApiController::class, 'popularSearches']); // عمليات البحث الأكثر شيوعاً

    // Content management
    Route::get('/slider', [mainApiController::class, 'getSlider']);

    // Category system
    Route::get('/categories', [mainApiController::class, 'Categories']);
    Route::get('/categories/{category}/sub-categories', [mainApiController::class, 'SubCategoriesV2']);
    Route::get('/sub-categories/{subCategory}/products', [mainApiController::class, 'getSubCategoryProducts']);

    // Product features
    Route::get('/features', [mainApiController::class, 'getFeatures']);

    // Home page
    Route::get('/home', [mainApiController::class, 'homePage']);
    Route::get('/show-more', [mainApiController::class, 'showMore']);
});

// ========================================
// PRODUCT ROUTES (Public)
// ========================================
Route::prefix('products')->group(function () {
    // Product listing and details
    Route::get('/', [productController::class, 'Products']);

    // Brand management - يجب أن يكون قبل {product} route
    Route::get('/brands', [productController::class, 'brands'])->middleware('api.auth.active');

    Route::get('/{product}', [productController::class, 'Product']);
    Route::get('/featured/list', [productController::class, 'featuredProducts']);
    Route::get('/{product}/similar', [ProductController::class, 'similarsProducts']);

    // Product reviews (Public - get reviews)
    Route::get('/{productId}/reviews', [ReviewController::class, 'getProductReviews']);
});

// ========================================
// USER PROFILE ROUTES (Public)
// ========================================
Route::get('/users/{user}', [authController::class, 'getUser']);

// ========================================
// SETTINGS ROUTES (Public)
// ========================================
Route::prefix('settings')->group(function () {
    // Routes المحددة يجب أن تكون قبل {key} route
    Route::get('/site/info', [SettingController::class, 'siteInfo']);
    Route::get('/privacy-policy', [SettingController::class, 'privacyPolicy']);
    Route::get('/terms-of-service', [SettingController::class, 'termsOfService']);
    Route::get('/about-us', [SettingController::class, 'aboutUs']);
    Route::get('/group/{group}', [SettingController::class, 'getGroup']);

    // Routes العامة
    Route::get('/', [SettingController::class, 'index']);
    Route::get('/{key}', [SettingController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Authentication & Active User Required)
|--------------------------------------------------------------------------
| All routes below require user authentication and active user status
| via 'api.auth.active' middleware which checks:
| - User is authenticated
| - User account is active (not deactivated)
| - User account is not banned
| - User account is not deleted
*/

Route::middleware('api.auth.active')->group(function () {

    // ========================================
    // USER AUTHENTICATION & PROFILE MANAGEMENT
    // ========================================
    Route::prefix('user')->group(function () {
        // Profile management
        Route::get('/profile', [authController::class, 'user']);
        Route::post('/profile/update', [authController::class, 'update']);
        Route::post('/password/change', [authController::class, 'changePassword']);
        Route::post('/account/delete', [authController::class, 'deleteAccount']);

        // Push notifications
        Route::post('/expo-token', [authController::class, 'addExpoPushToken']);
    });

    // ========================================
    // NOTIFICATION SYSTEM
    // ========================================
    Route::prefix('notifications')->group(function () {
        Route::get('/{type?}', [mainApiController::class, 'Notifications']);
        Route::get('/unread/count', [mainApiController::class, 'unreadCountNotifications']);
        Route::post('/{id}/mark-read', [mainApiController::class, 'markNotificationAsRead']);
        Route::delete('/{id}', [mainApiController::class, 'deleteNotification']);
    });

    // ========================================
    // PRODUCT INTERACTIONS & MANAGEMENT
    // ========================================
    Route::prefix('products')->group(function () {
        // Product creation and management
        Route::post('/create', [CreateProductController::class, 'store']);
        Route::delete('/{product}', [CreateProductController::class, 'destroy']);

        // Product reviews - Managed by ReviewController
        Route::post('/reviews', [ReviewController::class, 'store']);
        Route::put('/reviews/{reviewId}', [ReviewController::class, 'update']);
        Route::delete('/reviews/{reviewId}', [ReviewController::class, 'destroy']);
        Route::get('/reviews/my-reviews', [ReviewController::class, 'getUserReviews']);

        // User favorites
        Route::get('/favorites/user', [ProductController::class, 'userFavorites']);
        Route::post('/favorites/{product}', [ProductController::class, 'addToFavorites']);
        Route::delete('/favorites/{product}', [ProductController::class, 'removeFromFavorites']);
    });

    // ========================================
    // SOCIAL FEATURES & MESSAGING
    // ========================================
    Route::prefix('social')->group(function () {
        // Chat and messaging system
        Route::prefix('conversations')->group(function () {
            Route::post('/', [ChatController::class, 'startConversation']);
            Route::get('/', [ChatController::class, 'getConversations']);
            Route::get('/{conversation}/messages', [ChatController::class, 'getMessages']);
            Route::get('/{conversation}/messages/new', [ChatController::class, 'getNewMessages']);
            Route::post('/{conversation}/messages', [ChatController::class, 'sendMessage']);
        });

        // Follow system
        Route::prefix('follow')->group(function () {
            Route::post('/{user}', [FollowController::class, 'follow']);
            Route::delete('/{user}', [FollowController::class, 'unfollow']);
            Route::get('/{user}/following', [FollowController::class, 'following']);
            Route::get('/{user}/followers', [FollowController::class, 'followers']);
        });
    });

    // ========================================
    // HELP CENTER & TICKETS
    // ========================================
    Route::prefix('help-center')->group(function () {
        // Get ticket categories (public)
        Route::get('/categories', [TicketController::class, 'categories']);

        // Ticket management (requires auth)
        Route::prefix('tickets')->group(function () {
            Route::get('/', [TicketController::class, 'index']); // Get user tickets
            Route::post('/', [TicketController::class, 'store']); // Create ticket
            Route::get('/{id}', [TicketController::class, 'show']); // Get ticket details
            Route::post('/{id}/rate', [TicketController::class, 'rate']); // Rate ticket
        });
    });

    // ========================================
    // USER ADDRESS MANAGEMENT
    // ========================================
    Route::prefix('addresses')->group(function () {
        Route::get('/all', [UserAddressController::class, 'index']);
        Route::get('/{id}', [UserAddressController::class, 'show']);
        Route::post('/', [UserAddressController::class, 'store']);
        Route::put('/{id}', [UserAddressController::class, 'update']);
        Route::delete('/{id}', [UserAddressController::class, 'destroy']);
    });

    // ========================================
    // SHIPPING PROOF & COINS MANAGEMENT
    // ========================================
    Route::prefix('shipping-proofs')->group(function () {
        Route::get('/', [ShippingProofController::class, 'index']); // Get user's shipping proofs
        Route::post('/', [ShippingProofController::class, 'store']); // Create new shipping proof
        Route::get('/{id}', [ShippingProofController::class, 'show']); // Get specific shipping proof
        
        // Admin routes
        Route::prefix('admin')->group(function () {
            Route::get('/all', [ShippingProofController::class, 'adminIndex']); // Get all proofs (admin)
            Route::post('/{id}/approve', [ShippingProofController::class, 'approve']); // Approve proof
            Route::post('/{id}/reject', [ShippingProofController::class, 'reject']); // Reject proof
        });
    });

    // ========================================
    // SHOPPING CART & CHECKOUT
    // ========================================
    Route::prefix('cart')->group(function () {
        // Cart management
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);

        // Item management
        Route::delete('/items/{product_id}', [CartController::class, 'destroyItem']);
        Route::delete('/', [CartController::class, 'destroyCart']);

        // Checkout process
        Route::post('/checkout', [CartController::class, 'checkOut']);
    });

    // ========================================
    // ORDER TRACKING & RETURNS
    // ========================================
    Route::prefix('orders')->group(function () {
        // Get user orders
        Route::get('/', [\App\Http\Controllers\api\OrderController::class, 'index']);
        
        // Get order details
        Route::get('/{id}', [\App\Http\Controllers\api\OrderController::class, 'show']);
        
        // Track order
        Route::get('/{id}/track', [\App\Http\Controllers\api\OrderController::class, 'track']);
        
        // Confirm order receipt
        Route::post('/{id}/confirm-receipt', [\App\Http\Controllers\api\OrderController::class, 'confirmReceipt']);
    });

    // ========================================
    // RETURNS & REFUNDS
    // ========================================
    Route::prefix('returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::get('/{id}', [ReturnController::class, 'show']);
        Route::post('/', [ReturnController::class, 'store']);
        Route::post('/{id}/cancel', [ReturnController::class, 'cancel']);
    });

    // ========================================
    // ROLE MANAGEMENT & REQUESTS
    // ========================================
    Route::prefix('role')->group(function () {
        Route::get('/change-request', [RoleChangeRequestController::class, 'show']);
        Route::post('/change-request', [RoleChangeRequestController::class, 'store']);
    });

    // ========================================
    // MERCHANT DASHBOARD & MANAGEMENT
    // ========================================
    Route::prefix('merchant')->group(function () {
        // Dashboard & Statistics
        Route::get('/dashboard', [MerchantController::class, 'dashboard']);
        Route::get('/monthly-stats', [MerchantController::class, 'monthlyStats']);

        // Orders Management
        Route::get('/orders/pending', [MerchantController::class, 'pendingOrders']);
        Route::get('/orders/history', [MerchantController::class, 'orderHistory']);

        // Earnings & Payments
        Route::get('/earnings', [MerchantController::class, 'earnings']);
        Route::get('/withdrawals', [MerchantController::class, 'withdrawals']);
        Route::post('/withdrawals/request', [MerchantController::class, 'requestWithdrawal']);

        // Profile Management
        Route::get('/profile', [MerchantController::class, 'profile']);
        Route::put('/profile', [MerchantController::class, 'updateProfile']);
    });

    // ========================================
    // DRIVER MANAGEMENT & OPERATIONS
    // ========================================
    Route::prefix('driver')->group(function () {
        // Dashboard & Profile
        Route::get('/dashboard', [DriverController::class, 'dashboard']);
        Route::get('/profile', [DriverController::class, 'profile']);
        Route::put('/profile', [DriverController::class, 'updateProfile']);

        // Orders Management
        Route::get('/orders/current', [DriverController::class, 'currentOrders']);
        Route::get('/orders/history', [DriverController::class, 'orderHistory']);

        // Order Actions
        Route::post('/orders/{orderId}/accept', [DriverController::class, 'acceptOrder']);
        Route::post('/orders/{orderId}/pickup', [DriverController::class, 'markAsPickedUp']);
        Route::post('/orders/{orderId}/deliver', [DriverController::class, 'markAsDelivered']);
        Route::post('/orders/{orderId}/cancel', [DriverController::class, 'cancelOrder']);

        // Status & Location Updates
        Route::post('/availability', [DriverController::class, 'updateAvailability']);
        Route::post('/location', [DriverController::class, 'updateLocation']);
    });

    // ========================================
    // DRIVER SUPERVISOR MANAGEMENT
    // ========================================
    Route::prefix('supervisor')->group(function () {
        // Dashboard & Overview
        Route::get('/dashboard', [DriverSupervisorController::class, 'dashboard']);

        // Drivers Management
        Route::get('/drivers', [DriverSupervisorController::class, 'getDrivers']);
        Route::get('/drivers/{driverId}', [DriverSupervisorController::class, 'getDriver']);
        Route::get('/drivers/available', [DriverSupervisorController::class, 'getAvailableDrivers']);
        Route::put('/drivers/{driverId}/status', [DriverSupervisorController::class, 'updateDriverStatus']);

        // Orders Management
        Route::get('/orders', [DriverSupervisorController::class, 'getOrders']);
        Route::post('/orders/assign', [DriverSupervisorController::class, 'assignOrder']);
        Route::post('/orders/{driverOrderId}/reassign', [DriverSupervisorController::class, 'reassignOrder']);
        Route::post('/orders/{driverOrderId}/confirm', [DriverSupervisorController::class, 'confirmDelivery']);
        Route::post('/orders/{driverOrderId}/cancel', [DriverSupervisorController::class, 'cancelOrder']);
    });

    // ========================================
    // LOYALTY POINTS SYSTEM
    // ========================================
    Route::prefix('loyalty')->group(function () {
        // User loyalty points management
        Route::get('/points', [LoyaltyController::class, 'getLoyaltyPoints']);
        Route::get('/transactions', [LoyaltyController::class, 'getLoyaltyTransactions']);
        Route::post('/use', [LoyaltyController::class, 'useLoyaltyPoints']);

        // Admin loyalty points management
        Route::post('/add', [LoyaltyController::class, 'addLoyaltyPoints']);
    });

    // ========================================
    // CURRENCY MANAGEMENT (Admin Only)
    // ========================================
    Route::prefix('admin/currencies')->group(function () {
        Route::get('/', [CurrencyController::class, 'index']);
        Route::post('/', [CurrencyController::class, 'store']);
        Route::get('/{id}', [CurrencyController::class, 'show']);
        Route::put('/{id}', [CurrencyController::class, 'update']);
        Route::delete('/{id}', [CurrencyController::class, 'destroy']);
        Route::patch('/{id}/exchange-rate', [CurrencyController::class, 'updateExchangeRate']);
        Route::patch('/{id}/toggle-status', [CurrencyController::class, 'toggleStatus']);
        Route::get('/{id}/exchange-rate-history', [CurrencyController::class, 'exchangeRateHistory']);
        Route::post('/bulk-update-rates', [CurrencyController::class, 'bulkUpdateRates']);
    });

});

// ========================================
// PUBLIC CURRENCY ROUTES (No Auth Required)
// ========================================
Route::prefix('currencies')->group(function () {
    Route::get('/', [CurrencyController::class, 'getCurrencies']); // Get all active currencies
    Route::get('/{code}', [CurrencyController::class, 'getCurrencyByCode']); // Get currency by code
    Route::post('/convert', [CurrencyController::class, 'convertCurrency']); // Convert currency
});

Route::prefix('settings')->group(function () {
    Route::get('/exchange-rates', [CurrencyController::class, 'getExchangeRates']); // Get exchange rates
});
