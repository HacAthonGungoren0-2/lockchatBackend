<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MessageController;
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



Route::post('/messagesstore', [MessageController::class, 'store'])->name('store');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/usersearch', [UserController::class, 'searchUsers'])->name('usersearch');
Route::post('/addfriend', [UserController::class, 'addFriend'])->name('addfriend');
Route::get('/friends/{userId}', [UserController::class, 'getFriends'])->name('friends');
Route::get('message/{senderId}/{receiverId}', [MessageController::class, 'getMessagesBetweenUsers']);


