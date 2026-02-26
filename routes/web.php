<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [MenuController::class, 'dashboard'])->name('dashboard');

    Route::group(['middleware' => ['can:MANAGE ROLE']], function () {
        Route::resource('manage-roles', RoleController::class)->names([
            'index'   => 'roles.index',
            'store'   => 'roles.store',
            'update'  => 'roles.update',
            'destroy' => 'roles.destroy',
        ]);
    });

    Route::group(['middleware' => ['can:MANAGE USER']], function () {
        Route::resource('manage-user', UserController::class)->names([
            'index'   => 'users.index',
            'store'   => 'users.store',
            'update'  => 'users.update',
            'destroy' => 'users.destroy',
        ]);
    });

    Route::group(['middleware' => ['can:MENU OPTION']], function () {
        Route::resource('menu-options', MenuController::class)->names([
            'index'   => 'menus.index',
            'store'   => 'menus.store',
            'update'  => 'menus.update',
            'destroy' => 'menus.destroy',
        ]);
        
        Route::post('/menus/{menu}/cards', [MenuController::class, 'storeCard'])->name('cards.store');
        Route::get('/cards/{card}/edit', [MenuController::class, 'editCard'])->name('cards.edit');
        Route::put('/cards/{card}', [MenuController::class, 'updateCard'])->name('cards.update');
        Route::delete('/cards/{card}', [MenuController::class, 'destroyCard'])->name('cards.destroy');
    });

    Route::get('/menus/{menu_option}', [MenuController::class, 'show'])->name('menus.show');
});