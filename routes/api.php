<?php

use App\Http\Controllers\api\authController;
use App\Http\Controllers\api\mainApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/login', [authController::class, 'login']);
Route::post('/register', [authController::class, 'register']);

Route::post('/update-profile', [authController::class, 'update']);
Route::post('/change-password', [authController::class, 'changePassword']);
Route::get('me', [authController::class, 'user']);
Route::get('user/{user}', [authController::class, 'getUser']);
Route::post('/addExpoPushToken', [authController::class, 'addExpoPushToken']);
Route::post('/delete-account', [authController::class, 'deleteAccount']);


Route::get('/categories', [mainApiController::class, 'Categories']);
Route::get('/sub-categories', [mainApiController::class, 'allSubCategories']);
Route::get('/categories/{category}/sub-categories', [mainApiController::class, 'SubCategories']);
Route::get('/products', [mainApiController::class, 'Products']);
Route::get('/products/{product}', [mainApiController::class, 'Product']);
