<?php

use App\Http\Controllers\ResetController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::redirect('/', '/login');

// Route for Reset Database and Optimize Clear and Cache
Route::get('/reset', [ResetController::class, 'Reset'])->name('reset');
Route::get('/cache', [ResetController::class, 'Cache'])->name('cache');
