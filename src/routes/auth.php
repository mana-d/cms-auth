<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Cms\Auth\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'showLogin']);
    Route::post('/login', [AuthenticatedSessionController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
});
