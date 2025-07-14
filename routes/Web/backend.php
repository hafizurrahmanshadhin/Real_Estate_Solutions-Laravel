<?php

use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\PackageController;
use App\Http\Controllers\Web\Backend\ServiceController;
use App\Http\Controllers\Web\Backend\ServicesAreaController;
use Illuminate\Support\Facades\Route;

// Route for Admin Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route for Services Area
Route::controller(ServicesAreaController::class)->prefix('service-area')->name('service-area.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/store', 'store')->name('store');
    Route::put('/update/{id}', 'update')->name('update');
    Route::get('/status/{id}', 'status')->name('status');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
});

// Route for Packages
Route::controller(PackageController::class)->prefix('package')->name('package.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/show/{id}', 'show')->name('show');
    Route::get('/edit/{id}', 'edit')->name('edit');
    Route::patch('/update/{id}', 'update')->name('update');
    Route::get('/status/{id}', 'status')->name('status');
    Route::get('/toggle-popular/{id}', 'togglePopular')->name('togglePopular');
});

// Route for Services
Route::controller(ServiceController::class)->prefix('service')->name('service.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/footage-sizes/store', 'storeSquareFootageSize')->name('footage-sizes.store');
    Route::put('/footage-sizes/update/{id}', 'updateSquareFootageSize')->name('footage-sizes.update');
    Route::get('/footage-sizes/status/{id}', 'statusSquareFootageSize')->name('footage-sizes.status');
    Route::delete('/footage-sizes/destroy/{id}', 'destroySquareFootageSize')->name('footage-sizes.destroy');

    Route::get('/item', 'indexItem')->name('item.index');
    Route::post('/item/store', 'storeServiceItem')->name('item.store');
    Route::put('/item/update/{id}', 'updateServiceItem')->name('item.update');
    Route::get('/item/status/{id}', 'statusServiceItem')->name('item.status');
    Route::delete('/item/destroy/{id}', 'destroyServiceItem')->name('item.destroy');
});
