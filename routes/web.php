<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PushController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [MainController::class, 'index']);
Route::post('/user/push', [PushController::class, 'store'])->middleware('auth:sanctum');
Route::get('/about', [MainController::class, 'about'])->name('about');
Route::get('/terms-of-use', [MainController::class, 'getTermsOfUse'])->name('terms-of-use');
Route::get('/privacy-policy', [MainController::class, 'getPrivacyPolicy'])->name('privacy-policy');
Route::get('/auth/login', [MainController::class, 'login'])->name('auth.login');

Route::get('/admin/auth/install', [AuthController::class, 'handleInstall']);
Route::middleware(['auth:sanctum', 'verified', 'active'])->group(function () {
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');

    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::get('/install', [AuthController::class, 'handleInstall']);
        Route::middleware(['roles:SuperAdmin'])->get('/reset', [AuthController::class, 'handleReset']);

        Route::get('/', [AdminController::class, 'index']);
        Route::prefix('users')->group(function () {
            Route::get('/', [AdminController::class, 'users']);
            Route::post('/login', [UserController::class, 'loginAs'])->name('login_as')->middleware(['password.confirm']);
            Route::prefix('{user}')->group(function () {
                Route::get('/', [AdminController::class, 'user']);
                Route::put('/activate', [UserController::class, 'activate']);
                Route::put('/deactivate', [UserController::class, 'deactivate']);
                Route::put('/verify', [UserController::class, 'verify']);
                Route::put('/unverify', [UserController::class, 'unverify']);
            });
        });
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'store']);
            Route::post('/users/{user}/detach', [RoleController::class, 'detachUserAll']);
            Route::post('/permissions/{permission}/detach', [RoleController::class, 'detachPermissionAll']);
            Route::prefix('{role}')->group(function () {
                Route::get('/', [RoleController::class, 'show']);
                Route::put('/', [RoleController::class, 'update']);
                Route::delete('/', [RoleController::class, 'destroy']);
                Route::post('/users/{user}/attach', [RoleController::class, 'attachUser']);
                Route::post('/permissions/{permission}/attach', [RoleController::class, 'attachPermission']);
                Route::post('/users/{user}/detach', [RoleController::class, 'detachUser']);
                Route::post('/permissions/{permission}/detach', [RoleController::class, 'detachPermission']);
            });
        });
        Route::prefix('permissions')->group(function () {
            Route::get('/', [AdminController::class, 'permissions']);
            Route::post('/users/{user}/detach', [PermissionController::class, 'detachUserAll']);
            Route::prefix('{permission}')->group(function () {
                Route::get('/', [PermissionController::class, 'show']);
                Route::post('/users/{user}/attach', [PermissionController::class, 'attachUser']);
                Route::post('/users/{user}/detach', [PermissionController::class, 'detachUser']);
            });
        });
    });
});

Route::middleware(['auth:sanctum', 'verified', 'active'])->group(function () {
    //
});
