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

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('user.home');
    })->name('home');

    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/verify', 'auth.verify')->name('verify');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/links', function () {
        return view('user.links');
    })->name('links');
});

Route::get('/{shortCode}', [ShortURLController::class, 'redirectToURL'])->name('shortcode');

Route::middleware(['role:admin|editor'])->prefix('admin')->group(function () {
    Route::get('/index', function () {
        return view('admin.index');
    })->name('admin.index');

    Route::get('/user-list', function () {
        return view('admin.user-list');
    })->name('admin.user-list');
    Route::get('/permission', function () {
        return view('admin.permission');
    })->name('admin.permission');
});
