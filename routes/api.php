<?php

use App\Http\Controllers\api\authController;
use App\Http\Controllers\api\mainApiController;
use App\Http\Controllers\api\productController;
use App\Http\Controllers\api\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/login', [authController::class, 'login']);
Route::post('/register', [authController::class, 'register']);

Route::group([], function () {

    Route::post('/update-profile', [authController::class, 'update']);
    Route::post('/change-password', [authController::class, 'changePassword']);
    Route::get('me', [authController::class, 'user']);
    Route::get('user/{user}', [authController::class, 'getUser']);
    Route::post('/addExpoPushToken', [authController::class, 'addExpoPushToken']);
    Route::post('/delete-account', [authController::class, 'deleteAccount']);


    Route::get('/cities', [mainApiController::class, 'cities']);
    Route::get('/categories', [mainApiController::class, 'Categories']);
    Route::get('/sub-categories', [mainApiController::class, 'allSubCategories']);
    Route::get('/categories/{category}/sub-categories', [mainApiController::class, 'SubCategories']);

    Route::get('/notifications/{type?}', [mainApiController::class, 'Notifications']);
    Route::get('/notifications/unread-count', [mainApiController::class, 'unreadCountNotifications']);
    Route::post('/notifications/{id}/mark-as-read', [mainApiController::class, 'markNotificationAsRead']);
    Route::delete('/notifications/{id}', [mainApiController::class, 'deleteNotification']);

    Route::get('/products', [productController::class, 'Products']);
    Route::get('/products/{product}', [productController::class, 'Product']);
    Route::get('/featured/products', [productController::class, 'featuredProducts']);
    Route::get('/products/{product}/similars', [ProductController::class, 'similarsProducts']);
    Route::post('/products/{product}/reviews', [ProductController::class, 'addReview']);
    Route::delete('/products/{product}/reviews', [ProductController::class, 'deleteReview']);
    Route::get('/user/favorites/products', [ProductController::class, 'userFavorites']);
    Route::post('/favorites/products/{product}', [ProductController::class, 'addToFavorites']);
    Route::delete('/favorites/products/{product}', [ProductController::class, 'removeFromFavorites']);


    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
});
