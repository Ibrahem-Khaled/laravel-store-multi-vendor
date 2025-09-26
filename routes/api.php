<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Multi-Vendor Store Application
|--------------------------------------------------------------------------
|
| This file manages API versioning for the multi-vendor store application.
| Each version is maintained separately to ensure backward compatibility.
|
| Version Management:
| - v1: Legacy routes (maintained for existing apps)
| - v2: New organized routes (recommended for new integrations)
|
| Last Updated: 2024
|
*/

/*
|--------------------------------------------------------------------------
| API VERSIONING SYSTEM
|--------------------------------------------------------------------------
|
| The API supports multiple versions to ensure backward compatibility
| while allowing for future improvements and better organization.
|
*/

// ========================================
// API VERSION 1 (Legacy - Backward Compatibility)
// ========================================
Route::prefix('v1')->group(function () {
    require __DIR__ . '/api/v1.php';
});

// ========================================
// API VERSION 2 (Current - Recommended)
// ========================================
Route::prefix('v2')->group(function () {
    require __DIR__ . '/api/v2.php';
});

// ========================================
// DEFAULT ROUTES (v1 for backward compatibility)
// ========================================
// These routes maintain the original API structure without version prefix
// This ensures existing applications continue to work without changes
require __DIR__ . '/api/v1.php';
