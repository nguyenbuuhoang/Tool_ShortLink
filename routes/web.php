<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ShortURLController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->get('/', function () {
    return view('user.home');
})->name('home');
//auth
Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/verify', 'auth.verify')->name('verify');
});

Route::middleware(['auth'])->get('/links', function () {
    return view('user.links');
})->name('links');

Route::get('/{shortCode}', [ShortURLController::class, 'redirectToURL'])->name('shortcode');

//admin
Route::group(['middleware' => ['role:admin|editor'], 'prefix' => 'admin'], function () {
    Route::get('/index', function () {
        return view('admin.index');
    })->name('admin.index');
    Route::get('/user-list', function () {
        return view('admin.user-list');
    })->name('admin.user-list');
});
