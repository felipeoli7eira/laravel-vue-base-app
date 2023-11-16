<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\ApiController::class, 'index']);

Route::get('login', [App\Http\Controllers\User::class, 'loginError'])->name('login');
Route::post('login',[App\Http\Controllers\User::class, 'login'])->middleware('guest');

// Grupo de rotas autenticadas:
Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('logout',[App\Http\Controllers\User::class, 'logout']);
    Route::get('/user', [App\Http\Controllers\User::class, 'getCurrentAuthUser']);

    // apiResource
    // Route::resource('users', App\Http\Controllers\User::class)->middleware('role:admin');
    Route::post('users', [App\Http\Controllers\User::class, 'store'])->middleware('can:create_user');
    Route::get('users', [App\Http\Controllers\User::class, 'index'])->middleware('can:read_user');
    Route::get('users/{id}', [App\Http\Controllers\User::class, 'findById'])->middleware('can:read_user');
    Route::put('users/{id}/update', [App\Http\Controllers\User::class, 'update'])->middleware('can:update_user');
    Route::delete('users/{id}', [App\Http\Controllers\User::class, 'destroy'])->middleware('can:delete_user');

    Route::get('roles', [App\Http\Controllers\RoleController::class, 'getRoleNames']);
});
