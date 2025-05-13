<?php

use App\Http\Controllers\dashboard\CategoryController;
use App\Http\Controllers\dashboard\ProductController;
use App\Http\Controllers\dashboard\SlideShowController;
use App\Http\Controllers\dashboard\SubCategoryController;
use App\Http\Controllers\dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});




Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function () {
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');


    Route::resource('users', UserController::class);
    Route::put('users/{user}/coins', [UserController::class, 'updateCoins'])->name('users.updateCoins');

    Route::resource('categories', CategoryController::class);
    Route::resource('sub-categories', SubCategoryController::class);

    Route::resource('slide-shows', SlideShowController::class);
    Route::post('slide-shows/update-order', [SlideShowController::class, 'updateOrder'])->name('slide-shows.update-order');

    Route::resource('products', ProductController::class)->except(['show']);
    Route::delete('products/images/{image}', [ProductController::class, 'destroyImage'])->name('products.destroy-image');
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
});


require __DIR__ . '/auth.php';
