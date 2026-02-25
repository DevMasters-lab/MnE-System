<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// 1. Unified Redirect: Everyone goes to the dashboard after logging in
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

// 2. Authentication Routes
Route::get('/login', [LoginController::class, 'show'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post')->middleware('guest');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// 3. Authenticated Routes (No permission gates applied)
Route::middleware('auth')->group(function () {
    
    // The main landing page for everyone
    Route::get('/dashboard', [MenuController::class, 'dashboard'])->name('dashboard');

    // Manage Users Routes (Open to all users)
    Route::get('/manage-user', [UserController::class, 'index'])->name('users.index');
    Route::post('/manage-user', [UserController::class, 'store'])->name('users.store');
    Route::get('/manage-user/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/manage-user/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/manage-user/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Menu Configuration (Open to all users)
    Route::get('/menu-options', [MenuController::class, 'index'])->name('menus.index');
    Route::post('/menu-options', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menu-options/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menu-options/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menu-options/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

    // Sub-card Display and Management
    Route::get('/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
    Route::post('/menus/{menu}/cards', [MenuController::class, 'storeCard'])->name('cards.store');
    Route::get('/cards/{card}/edit', [MenuController::class, 'editCard'])->name('cards.edit');
    Route::put('/cards/{card}', [MenuController::class, 'updateCard'])->name('cards.update');
    Route::delete('/cards/{card}', [MenuController::class, 'destroyCard'])->name('cards.destroy');
});