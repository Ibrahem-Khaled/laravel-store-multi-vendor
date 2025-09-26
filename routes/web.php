<?php

use App\Http\Controllers\dashboard\AdminRoleRequestController;
use App\Http\Controllers\dashboard\BrandController;
use App\Http\Controllers\dashboard\CategoryController;
use App\Http\Controllers\dashboard\CityController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\DriverManagementController;
use App\Http\Controllers\dashboard\FeatureController;
use App\Http\Controllers\dashboard\MerchantAdminController;
use App\Http\Controllers\dashboard\NeighborhoodController;
use App\Http\Controllers\dashboard\NotificationController;
use App\Http\Controllers\dashboard\OrderAdminController;
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
    // اعتماد/إلغاء تفعيل
    Route::post('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

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

    // مسارات إدارة طلبات تغيير الأدوار
    Route::get('role-requests', [AdminRoleRequestController::class, 'index'])->name('role-requests.index');
    Route::put('role-requests/{roleRequest}', [AdminRoleRequestController::class, 'update'])->name('role-requests.update');


    Route::get('/orders',         [OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderAdminController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/bulk',   [OrderAdminController::class, 'bulkAction'])->name('orders.bulk');

    // التجّار
    Route::get('/merchants',           [MerchantAdminController::class, 'index'])->name('merchants.index');
    Route::get('/merchants/{merchant}', [MerchantAdminController::class, 'show'])->name('merchants.show');
    Route::post('/merchants/{merchant}/settlements', [MerchantAdminController::class, 'settle'])->name('merchants.settle');

    // إدارة السواقين
    Route::prefix('driver-management')->name('admin.driver.')->group(function () {
        Route::get('/dashboard', [DriverManagementController::class, 'dashboard'])->name('dashboard');
        Route::get('/drivers', [DriverManagementController::class, 'drivers'])->name('drivers');
        Route::get('/drivers/create', [DriverManagementController::class, 'createDriver'])->name('create');
        Route::post('/drivers', [DriverManagementController::class, 'storeDriver'])->name('store');
        Route::get('/drivers/{id}', [DriverManagementController::class, 'driverDetails'])->name('details');
        Route::get('/drivers/{id}/edit', [DriverManagementController::class, 'editDriver'])->name('edit');
        Route::put('/drivers/{id}', [DriverManagementController::class, 'updateDriver'])->name('update');
        Route::delete('/drivers/{id}', [DriverManagementController::class, 'destroyDriver'])->name('destroy');

        Route::get('/orders', [DriverManagementController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [DriverManagementController::class, 'orderDetails'])->name('order.details');
        Route::post('/orders/assign', [DriverManagementController::class, 'assignOrder'])->name('order.assign');
        Route::post('/orders/{id}/reassign', [DriverManagementController::class, 'reassignOrder'])->name('order.reassign');
        Route::post('/orders/{id}/confirm', [DriverManagementController::class, 'confirmDelivery'])->name('order.confirm');
        Route::post('/orders/{id}/cancel', [DriverManagementController::class, 'cancelOrder'])->name('order.cancel');
    });
});


require __DIR__ . '/auth.php';
