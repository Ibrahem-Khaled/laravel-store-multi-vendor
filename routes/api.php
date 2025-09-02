<?php

use App\Http\Controllers\api\authController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\ChatController;
use App\Http\Controllers\api\CreateProductController;
use App\Http\Controllers\api\FollowController;
use App\Http\Controllers\api\mainApiController;
use App\Http\Controllers\api\productController;
use App\Http\Controllers\api\RoleChangeRequestController;
use App\Http\Controllers\api\UserAddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (لا تحتاج إلى تسجيل دخول)
|--------------------------------------------------------------------------
*/
// --- Auth ---
Route::post('/login', [authController::class, 'login']);
Route::post('/register', [authController::class, 'register']);

// --- Main Data ---
Route::get('/cities', [mainApiController::class, 'cities']);
Route::get('/search', [mainApiController::class, 'searchProducts']);
Route::get('/slider', [mainApiController::class, 'getSlider']);
Route::get('/categories', [mainApiController::class, 'Categories']);
Route::get('/categories/{category}/sub-categories', [mainApiController::class, 'SubCategories']);
Route::get('/features', [mainApiController::class, 'getFeatures']);

// --- Products ---
Route::get('/products', [productController::class, 'Products']);
Route::get('/brands', [productController::class, 'brands'])->middleware('api.auth');
Route::get('/products/{product}', [productController::class, 'Product']);
Route::get('/featured/products', [productController::class, 'featuredProducts']);
Route::get('/products/{product}/similars', [ProductController::class, 'similarsProducts']);
Route::get('user/{user}', [authController::class, 'getUser']);


/*
|--------------------------------------------------------------------------
| Authenticated Routes (تحتاج إلى تسجيل دخول ومصادقة)
|--------------------------------------------------------------------------
|
| كل المسارات هنا محمية بواسطة الميدل وير 'api.auth'
|
*/
Route::middleware('api.auth')->group(function () {

    // --- User Profile & Account ---
    Route::post('/update-profile', [authController::class, 'update']);
    Route::post('/change-password', [authController::class, 'changePassword']);
    Route::get('me', [authController::class, 'user']);
    Route::post('/addExpoPushToken', [authController::class, 'addExpoPushToken']);
    Route::post('/delete-account', [authController::class, 'deleteAccount']);

    // --- Notifications ---
    Route::get('/notifications/{type?}', [mainApiController::class, 'Notifications']);
    Route::get('/notifications/unread-count', [mainApiController::class, 'unreadCountNotifications']);
    Route::post('/notifications/{id}/mark-as-read', [mainApiController::class, 'markNotificationAsRead']);
    Route::delete('/notifications/{id}', [mainApiController::class, 'deleteNotification']);

    // --- Product Interactions (Reviews, Favorites) ---
    Route::post('/products/{product}/reviews', [ProductController::class, 'addReview']);
    Route::delete('/products/{product}/reviews', [ProductController::class, 'deleteReview']);
    Route::get('/user/favorites/products', [ProductController::class, 'userFavorites']);
    Route::post('/favorites/products/{product}', [ProductController::class, 'addToFavorites']);
    Route::delete('/favorites/products/{product}', [ProductController::class, 'removeFromFavorites']);


    Route::post('/conversations', [ChatController::class, 'startConversation']);
    Route::get('/conversations', [ChatController::class, 'getConversations']);
    Route::get('/conversations/{conversation}/messages', [ChatController::class, 'getMessages']);
    Route::get('/conversations/{conversation}/new-messages', [ChatController::class, 'getNewMessages']);
    Route::post('/conversations/{conversation}/messages', [ChatController::class, 'sendMessage']);


    // POST /api/users/{user}/follow
    Route::post('/follow/{user}', [FollowController::class, 'follow']);
    Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow']);
    Route::get('users/{user}/following', [FollowController::class, 'following']);
    Route::get('users/{user}/followers', [FollowController::class, 'followers']);


    Route::post('/create/products', [CreateProductController::class, 'store']);
    Route::delete('products/{product}', [CreateProductController::class, 'destroy']);


    //this api handles user addresses
    Route::get('user/addresses', [UserAddressController::class, 'index']);
    Route::apiResource('user/addresses', UserAddressController::class)->only(['store', 'destroy']);

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    // مسار حذف منتج واحد من السلة
    Route::delete('/cart/items/{product_id}', [CartController::class, 'destroyItem']);
    // مسار حذف السلة بأكملها
    Route::delete('/cart', [CartController::class, 'destroyCart']);


    Route::get('role-change-request', [RoleChangeRequestController::class, 'show']);
    Route::post('role-change-request', [RoleChangeRequestController::class, 'store']);
});
