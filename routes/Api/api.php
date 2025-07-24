<?php

use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\HeaderAndFooterController;
use Illuminate\Support\Facades\Route;

// This route is for getting header and footer content.
Route::get('/header-footer', HeaderAndFooterController::class);

// This route is for getting terms and conditions and privacy policy.
Route::get('contents', [ContentController::class, 'index']);

Route::controller(ContactUsController::class)->group(function () {
    // This route is for getting the contact page content.
    Route::get('contact-page', 'index');
    Route::post('contact-us', 'store')->middleware(['throttle:5,1']);
});
