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


Route::middleware('auth.app-token')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
<<<<<<< HEAD
 
=======
//});

Route::middleware('auth.app-token')->group(function () {
Route::get('/list',[TableCategoryController::class,'index']);
Route::post('/store',[TableCategoryController::class,'create']);
Route::put('/update/ {id}',[TableCategoryController::class,'update']);
Route::delete('/destroy/{id}',[TableCategoryController::class,'destroy']);

Route::get('/show',[ArticleController::class,'index']);
Route::get('/detail/{id}',[ArticleController::class,'detail']);
Route::post('/create',[ArticleController::class,'create']);
Route::post('/renew/{id}',[ArticleController::class,'update']);
Route::delete('/delete/{id}',[ArticleController::class,'destroy']);
>>>>>>> ddf1d1f29ff6730551fa1fd8a94168831d45f436
});


Route::post('/add',[CommentController::class,'create']);
Route::post('/post',[CommentController::class,'post']);
Route::get('/display',[CommentController::class,'index']);
Route::get('/specific/{id}',[CommentController::class,'detail']);


Route::post('/generate-app-token', [AuthController::class, 'generateAppToken']);

