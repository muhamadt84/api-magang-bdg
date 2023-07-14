<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\TableCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::middleware('auth:app-token')->group(function () {
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
//});

//Route::middleware('auth:app-token')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
//});

Route::middleware('auth.app-token')->group(function () {
Route::get('/list',[TableCategoryController::class,'index']);
Route::post('/store',[TableCategoryController::class,'create']);
Route::put('/update/{id}',[TableCategoryController::class,'update']);
Route::delete('/destroy/{id}',[TableCategoryController::class,'destroy']);
Route::get('/show',[ArticleController::class,'index']);
Route::get('/detail/{id}',[ArticleController::class,'detail']);
Route::post('/create',[ArticleController::class,'create']);
Route::post('/update/{id}',[ArticleController::class,'update']);
Route::delete('/destroy/{id}',[ArticleController::class,'destroy']);
});


Route::post('/generate-app-token', [AuthController::class, 'generateAppToken']);