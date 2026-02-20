<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Display the login view
Route::get('/login', [LoginController::class, 'show'])->name('login')->middleware('guest');

// Process the form submission
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post')->middleware('guest');

// Example dashboard route to redirect to upon success
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');