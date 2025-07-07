<?php

use App\Http\Controllers\dashboard\BrandController;
use App\Http\Controllers\dashboard\CategoryController;
use App\Http\Controllers\dashboard\CityController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\FeatureController;
use App\Http\Controllers\dashboard\NeighborhoodController;
use App\Http\Controllers\dashboard\NotificationController;
use App\Http\Controllers\dashboard\ProductController;
use App\Http\Controllers\dashboard\ReviewController;
use App\Http\Controllers\dashboard\SlideShowController;
use App\Http\Controllers\dashboard\SubCategoryController;
use App\Http\Controllers\dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    Route::resource('users', UserController::class);
    Route::put('users/{user}/coins', [UserController::class, 'updateCoins'])->name('users.updateCoins');

    Route::resource('categories', CategoryController::class);
    Route::resource('sub-categories', SubCategoryController::class);

    Route::resource('slide-shows', SlideShowController::class);
    Route::post('slide-shows/update-order', [SlideShowController::class, 'updateOrder'])->name('slide-shows.update-order');

    Route::resource('products', ProductController::class)->except(['show']);
    Route::delete('products/images/{image}', [ProductController::class, 'destroyImage'])->name('products.destroy-image');
    Route::get('products/{product}/features', [ProductController::class, 'showFeatures'])
        ->name('products.features.show');

    Route::resource('brands', BrandController::class)->except(['show']);
    Route::post('brands/update-order', [BrandController::class, 'updateOrder'])->name('brands.update-order');

    Route::resource('cities', CityController::class)->except(['show']);
    Route::resource('neighborhoods', NeighborhoodController::class);

    Route::resource('features', FeatureController::class);
    Route::get('features/statistics', 'FeatureController@statistics')->name('features.statistics');
    Route::post('features/add-to-product', [FeatureController::class, 'addFeatureToProduct'])
        ->name('features.add-to-product');
    Route::post('features/remove-from-product', [FeatureController::class, 'removeFeatureFromProduct'])
        ->name('features.remove-from-product');

    Route::resource('reviews', ReviewController::class);
    Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/disapprove', [ReviewController::class, 'disapprove'])->name('reviews.disapprove');

    Route::resource('notifications', NotificationController::class);
    Route::post('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('notifications/{notification}/mark-as-unread', [NotificationController::class, 'markAsUnread'])->name('notifications.mark-as-unread');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});


require __DIR__ . '/auth.php';
