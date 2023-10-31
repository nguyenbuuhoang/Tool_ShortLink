<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ShortURLController;
use App\Http\Controllers\Admin\MakeRoleController;
use App\Http\Controllers\Admin\UserListController;
use App\Http\Controllers\Admin\PermissionController;
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
    //logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    //short URL
    Route::put('/short-urls/{id}', [ShortURLController::class, 'updateShortCode']);
    Route::delete('/short-urls/{id}', [ShortURLController::class, 'deleteShortURL']);
});

//Admin ListUser
Route::get('/users-list', [UserListController::class, 'getListUser']);
Route::put('/users/{id}', [UserListController::class, 'updateUser']);
Route::delete('/users/{id}', [UserListController::class, 'deleteUser']);
Route::delete('delete-selected-users', [UserListController::class, 'deleteSelectedUsers']); //select delete
Route::get('/totals', [ShortLinksController::class, 'getTotal']);
//Admin ShortURL
Route::get('/shortURL', [ShortLinksController::class, 'getShortURL']);
Route::get('/shortURL/qrcode/{id}', [ShortLinksController::class, 'getQRCode']);
Route::put('/shortURL/{id}', [ShortLinksController::class, 'updateShortURL']);
Route::delete('/shortURL/{id}', [ShortLinksController::class, 'deleteShortURL']);
//Admin Permission
Route::get('/roles', [PermissionController::class, 'getRoles']);
Route::get('/permissions', [PermissionController::class, 'getPermissions']);
Route::post('/assign_permission', [PermissionController::class, 'assignPermission']);
Route::post('/revoke_permission', [PermissionController::class, 'revokePermission']);

//create role and permission
Route::get('addrole', [MakeRoleController::class, 'addRole']);
Route::get('addpermission/user', [MakeRoleController::class, 'addPermissionUser']);
Route::get('addpermission/link', [MakeRoleController::class, 'addPermissionLink']);
Route::get('role/permission/user', [MakeRoleController::class, 'roleAsPermissionUser']);
Route::get('role/permission/link', [MakeRoleController::class, 'roleAsPermissionLink']);
