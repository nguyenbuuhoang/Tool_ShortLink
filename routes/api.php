<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ShortURLController;
use App\Http\Controllers\Admin\MakeRoleController;
use App\Http\Controllers\Admin\UserListController;
use App\Http\Controllers\Admin\ShortLinksController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
//Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//create link short
Route::post('/create-short-url', [ShortURLController::class, 'createShortURL']);
//verify link
Route::post('/verify/{id}', [AuthController::class, 'verify']);
Route::post('/resend-verification/{id}', [AuthController::class, 'resendVerificationEmail']);
//get user
Route::get('/user-short-urls', [ShortURLController::class, 'getUserShortUrls']);
Route::get('/short-urls/{user_id}', [ShortURLController::class, 'getShortURLsByUserId']);
Route::get('short-urls/{user_id}/totals', [ShortURLController::class, 'getTotalsByUserId']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/short-urls/{id}', [ShortURLController::class, 'updateShortCode'])->name('short-urls.update');
    Route::delete('/short-urls/{id}', [ShortURLController::class, 'deleteShortURL']);
});

//Admin ListUser
Route::get('/users-list', [UserListController::class, 'getListUser']);
Route::put('/users/{id}', [UserListController::class, 'updateUser']);
Route::delete('/users/{id}', [UserListController::class, 'deleteUser']);
Route::delete('delete-selected-users', [UserListController::class, 'deleteSelectedUsers']); //select delete
Route::get('/totals', [ShortLinksController::class, 'getTotal']);
//Admin ShortURL
Route::get('/user-list/shortURL', [ShortLinksController::class, 'getShortURL']);

Route::get('create', [MakeRoleController::class, 'create']);
