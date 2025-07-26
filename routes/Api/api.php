<?php

use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\FetchController;
use App\Http\Controllers\Api\HeaderAndFooterController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Support\Facades\Route;

// This route is for getting header and footer content.
Route::get('/header-footer', HeaderAndFooterController::class);

// This route is for getting the home page content.
Route::get('/home', HomeController::class);

// This route is for getting terms and conditions and privacy policy.
Route::get('contents', ContentController::class);

Route::controller(ContactUsController::class)->group(function () {
    // This route is for getting the contact page content.
    Route::get('contact-page', 'index');
    // This route is for submitting the contact form.
    Route::post('contact-us', 'store')->middleware(['throttle:5,1']);
});

Route::controller(FetchController::class)->group(function () {
    Route::get('zip-codes/list', 'FetchZipCodes');
    Route::get('square-footage-size/list', 'FetchSquareFootageSizes');
    Route::get('footage-sizes/{footage}/packages', 'FetchPackagesByFootageSize');
});
