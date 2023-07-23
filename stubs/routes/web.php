<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cms\UserController;
use App\Http\Controllers\Cms\UserLevelController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/cms/dashboard', function () {
        return view('cms.dashboard');
    })->name('cmsDashboard');
    
    Route::get('/cms/user-level', [UserLevelController::class, 'index'])->name('cmsUserLevel');
    Route::post('/cms/user-level', [UserLevelController::class, 'store']);
    Route::get('/cms/user-level/{id}', [UserLevelController::class, 'detail']);
    Route::put('/cms/user-level/{id}', [UserLevelController::class, 'update']);
    Route::delete('/cms/user-level/{id}', [UserLevelController::class, 'delete']);
    Route::get('/cms/user-level/{id}/setting', [UserLevelController::class, 'setting'])->name('cmsUserLevel.setting');
    Route::post('/cms/user-level/{id}/setting', [UserLevelController::class, 'updateSetting']);

    Route::get('/cms/user', [UserController::class, 'index'])->name('cmsUser');
    Route::post('/cms/user', [UserController::class, 'store']);
    Route::get('/cms/user/{id}', [UserController::class, 'detail']);
    Route::put('/cms/user/{id}', [UserController::class, 'update']);
    Route::put('/cms/user/{id}/update-active', [UserController::class, 'updateActive']);
    Route::delete('/cms/user/{id}', [UserController::class, 'delete']);

    Route::get('/cms/blank-space', function () {
        return view('cms.blank-space');
    })->name('cmsBlankSpace');

    Route::get('/cms/components', function () {
        return view('cms.components');
    })->name('cmsComponents');
});
