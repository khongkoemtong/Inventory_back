<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuppyerController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\Group;


Route::middleware(['auth:sanctum','checkRole:admin'])->group(function (){
    

Route::prefix('role')->group(function(){
    Route::post('/create',[RoleController::class ,'create']);
    Route::get('/read',[RoleController::class,'read']);
    Route::get('/read/{id}',[RoleController::class,'fetchone']);
    Route::delete('/delete/{id}',[RoleController::class,'delete']);
    Route::post('/update/{id}',[RoleController::class,'update']);

});

Route::prefix('categories')->group(function(){
    Route::post('/create',[RoleController::class ,'create']);
    Route::get('/read',[RoleController::class,'read']);
    Route::get('/read/{id}',[RoleController::class,'fetchone']);
    Route::delete('/delete/{id}',[RoleController::class,'delete']);
    Route::post('/update/{id}',[RoleController::class,'update']);

});
Route::prefix('suppliers')->group(function(){
    Route::post('/create',[SuppyerController::class ,'create']);
    Route::get('/read',[SuppyerController::class,'read']);
    Route::get('/read/{id}',[SuppyerController::class,'fetchone']);
    Route::delete('/delete/{id}',[SuppyerController::class,'delete']);
    Route::post('/update/{id}',[SuppyerController::class,'update']);

});
Route::prefix('product')->group(function(){
    Route::post('/create',[ProductController::class ,'create']);
    Route::get('/read',[ProductController::class,'read']);
    Route::get('/read/{id}',[ProductController::class,'fetchone']);
    Route::delete('/delete/{id}',[ProductController::class,'delete']);
    Route::post('/update/{id}',[ProductController::class,'update']);

});
Route::prefix('user')->group(function(){
    Route::post('/create',[UserController::class ,'create']);
    Route::get('/read',[UserController::class,'read']);
    Route::get('/read/{id}',[UserController::class,'readone']);
    Route::delete('/delete/{id}',[UserController::class,'delete']);
    Route::post('/update/{id}',[UserController::class,'update']);
  

});




}); 

  Route::post('login',[UserController::class,'login']);







