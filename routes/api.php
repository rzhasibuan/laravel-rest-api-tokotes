<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::get('your-firstname', fn () => response()->json(['name' => 'reza']));
//Route::get('your-lastname', fn () => response()->json(['name' => 'hasibuan']));

Route::apiResource('products', \App\Http\Controllers\ProductController::class);
Route::apiResource('category', \App\Http\Controllers\CategoryController::class);
Route::apiResource('news-category', \App\Models\CategoryNews::class);

