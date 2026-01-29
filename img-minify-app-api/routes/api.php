<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\ImageController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/posts', [ImageController::class, 'OptimizeImage']);
Route::post('/posts', [ImageController::class, 'GetOptimizedImages']);