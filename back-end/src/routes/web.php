<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return (new \App\Http\Controllers\BaseController)->sendResponse('access resources from /api', []);
});

/*
    Route::redirect('', 'login');

    Route::get('login', [App\Http\Controllers\Authentication::class, 'login_form'])->name('login.form');
    Route::post('login', [App\Http\Controllers\Authentication::class, 'login'])->name('login');

    Route::post('logout', [App\Http\Controllers\Authentication::class, 'logout'])->name('logout')->middleware('auth');

    Route::get('register', [App\Http\Controllers\RegisterController::class, 'form'])->name('register.form');
    Route::post('register', [App\Http\Controllers\RegisterController::class, 'register'])->name('register');

    Route::get('home', App\Http\Controllers\HomeController::class)->name('home')->middleware('auth');

    Route::group(['prefix' => 'dashboard', 'middleware' => 'auth', 'as' => 'dashboard.'], function()
    {
        Route::get('', [App\Http\Controllers\Dashboard::class, 'index'])->name('home');

        Route::resource('users', App\Http\Controllers\User::class)->middleware('role:admin');

        Route::get('profile', [App\Http\Controllers\User::class, 'showAuthUserProfile'])->name('user.profile.show');
        Route::put('profile/{user}', [App\Http\Controllers\User::class, 'updateAuthUserProfile'])->name('user.profile.update');
    });
*/
