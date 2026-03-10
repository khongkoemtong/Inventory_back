<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuppyerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes (អ្នកណាក៏ចូលបាន)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (ទាល់តែ Login ទើបចូលបាន)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/read/{id}', [UserController::class, 'readone']);
   

    // Logout
    Route::post('logout', [UserController::class, 'logout']);
    

    // ==========================================
    // ១. សម្រាប់តែ SuperAdmin ប៉ុណ្ណោះ (ID 4)
    // ==========================================
    Route::middleware(['checkRole:SuperAdmin'])->group(function () {
        
        // Role Management
        Route::prefix('role')->group(function () {
            Route::post('/create', [RoleController::class, 'create']);
            Route::get('/read', [RoleController::class, 'read']);
            Route::get('/read/{id}', [RoleController::class, 'fetchone']);
            Route::post('/update/{id}', [RoleController::class, 'update']);
            Route::delete('/delete/{id}', [RoleController::class, 'delete']);
        });

        // SuperAdmin បង្កើត Admin ថ្មី
        Route::post('/user/create-admin', [UserController::class, 'create']); 
    });

    // ==========================================
    // ២. សម្រាប់ Admin និង SuperAdmin (ID 1 និង 4)
    // ==========================================
    Route::middleware(['checkRole:Admin,SuperAdmin'])->group(function () {
        
        // User/Customer Management
        Route::prefix('user')->group(function () {
            Route::get('/read', [UserController::class, 'read']);
            
            Route::post('/update/{id}', [UserController::class, 'update']);
            Route::delete('/delete/{id}', [UserController::class, 'delete']);
        });

        // Product Management
        Route::prefix('product')->group(function () {
            Route::get('/read', [ProductController::class, 'read']);
            Route::get('/read/{id}', [ProductController::class, 'readOne']);
            Route::post('/create', [ProductController::class, 'create']);
            Route::post('/update/{id}', [ProductController::class, 'update']);
            Route::delete('/delete/{id}', [ProductController::class, 'delete']);
        });

        // Supplier Management
        Route::prefix('suppliers')->group(function () {
            Route::get('/read', [SuppyerController::class, 'read']);
            Route::post('/create', [SuppyerController::class, 'create']);
            Route::post('/update/{id}', [SuppyerController::class, 'update']);
            Route::delete('/delete/{id}', [SuppyerController::class, 'delete']);
        });

        // Categories Management
        Route::prefix('categories')->group(function () {
            Route::get('/read', [CategoriesController::class, 'read']);
            Route::post('/create', [CategoriesController::class, 'create']);
            Route::post('/update/{id}', [CategoriesController::class, 'update']);
            Route::delete('/delete/{id}', [CategoriesController::class, 'delete']);
        });

        // Analytics / Report (Admin ក៏មើលបាន)
        Route::get('/analytics/summary', [AnalyticsController::class, 'getSummary']);
        
        // Admin មើល Order ទាំងអស់ក្នុង System
        Route::get('/orders/read-all', [OrderController::class, 'readall']);
    });

    // ==========================================
    // ៣. សម្រាប់ User ធម្មតា និងគ្រប់ Role ទាំងអស់
    // ==========================================
    Route::prefix('orders')->group(function () {
        Route::post('/create', [OrderController::class, 'create']); 
        Route::get('/my-orders', [OrderController::class, 'readMyOrders']); 
    });

});