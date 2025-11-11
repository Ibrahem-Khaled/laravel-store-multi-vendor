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
use App\Http\Controllers\dashboard\AuditLogController;
use App\Http\Controllers\dashboard\BackupController;
use App\Http\Controllers\dashboard\RoleController;
use App\Http\Controllers\dashboard\HelpCenterController;
use App\Http\Controllers\dashboard\TicketController;
use App\Http\Controllers\dashboard\ShippingProofController;
use App\Http\Controllers\Admin\LoyaltyManagementController;
use Illuminate\Support\Facades\Route;

// الصفحات العامة
Route::get('/', [\App\Http\Controllers\PageController::class, 'home'])->name('home');
Route::get('/privacy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\PageController::class, 'terms'])->name('terms');
Route::get('/support', [\App\Http\Controllers\PageController::class, 'support'])->name('support');
Route::post('/support/contact', [\App\Http\Controllers\PageController::class, 'contact'])->name('support.contact');

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    Route::resource('users', UserController::class);
    // اعتماد/إلغاء تفعيل
    Route::post('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/toggle-verification', [UserController::class, 'toggleVerification'])->name('users.toggle-verification');

    Route::resource('categories', CategoryController::class);
    Route::resource('sub-categories', SubCategoryController::class);

    Route::resource('slide-shows', SlideShowController::class);
    Route::post('slide-shows/update-order', [SlideShowController::class, 'updateOrder'])->name('slide-shows.update-order');

    Route::resource('products', ProductController::class)->except(['show']);
    Route::delete('products/images/{image}', [ProductController::class, 'destroyImage'])->name('products.destroy-image');
    Route::post('products/{product}/toggle-approval', [ProductController::class, 'toggleApproval'])->name('products.toggle-approval');
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

    // إدارة طلبات الشحن والعملات
    Route::prefix('shipping-proofs')->name('shipping-proofs.')->group(function () {
        Route::get('/', [ShippingProofController::class, 'index'])->name('index');
        Route::get('/{id}', [ShippingProofController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [ShippingProofController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [ShippingProofController::class, 'reject'])->name('reject');
    });


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

    // إدارة نقاط الولاء
    Route::prefix('loyalty-management')->name('loyalty-management.')->group(function () {
        Route::get('/dashboard', [LoyaltyManagementController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [LoyaltyManagementController::class, 'users'])->name('users');
        Route::get('/users/{userId}', [LoyaltyManagementController::class, 'userDetails'])->name('user-details');
        Route::get('/transactions', [LoyaltyManagementController::class, 'transactions'])->name('transactions');
        Route::post('/add-points', [LoyaltyManagementController::class, 'addPoints'])->name('add-points');
        Route::delete('/transactions/{transactionId}', [LoyaltyManagementController::class, 'deleteTransaction'])->name('delete-transaction');
        Route::get('/export', [LoyaltyManagementController::class, 'exportReport'])->name('export');
    });

    // سجل التدقيق
    Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
        Route::get('/{auditLog}', [AuditLogController::class, 'show'])->name('show');
    });

    // النسخ الاحتياطية
    Route::middleware('permission:manage-backups')->prefix('backups')->name('backups.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/', [BackupController::class, 'create'])->name('create');
        Route::get('/{backup}/download', [BackupController::class, 'download'])->name('download');
        Route::post('/{backup}/restore', [BackupController::class, 'restore'])->name('restore');
        Route::delete('/{backup}', [BackupController::class, 'destroy'])->name('destroy');
    });

    // إدارة الأدوار والصلاحيات
    Route::middleware('permission:manage-roles')->prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // إعدادات الموقع
    Route::middleware('permission:manage-settings')->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\dashboard\SettingController::class, 'index'])->name('index');
        Route::put('/', [\App\Http\Controllers\dashboard\SettingController::class, 'update'])->name('update');
        Route::post('/reset', [\App\Http\Controllers\dashboard\SettingController::class, 'reset'])->name('reset');
        Route::get('/export', [\App\Http\Controllers\dashboard\SettingController::class, 'export'])->name('export');
        Route::post('/import', [\App\Http\Controllers\dashboard\SettingController::class, 'import'])->name('import');
    });

    // مركز المساعدة والدعم الفني
    Route::middleware('permission:manage-tickets')->prefix('help-center')->name('help-center.')->group(function () {
        Route::get('/', [HelpCenterController::class, 'index'])->name('index');
    });

    Route::middleware('permission:manage-tickets')->prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/respond', [TicketController::class, 'respond'])->name('respond');
        Route::put('/{ticket}/status', [TicketController::class, 'updateStatus'])->name('update-status');
        Route::put('/{ticket}/priority', [TicketController::class, 'updatePriority'])->name('update-priority');
        Route::get('/{ticket}/download', [TicketController::class, 'downloadAttachment'])->name('download');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('destroy');
    });
});


require __DIR__ . '/auth.php';
