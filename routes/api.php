<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;

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

Route::post('/register', [MemberController::class, 'register'])->name('api.register');
Route::post('/login', [MemberController::class, 'login'])->name('api.login');
Route::get('/members', [MemberController::class, 'index'])->name('api.members.index');
Route::get('/members/{id}', [MemberController::class, 'show'])->name('api.members.show');
Route::put('/members/{id}', [MemberController::class, 'update'])->name('api.members.update');
Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('api.members.destroy');