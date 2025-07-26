<?php

use App\Http\Controllers\Web\Backend\AddOnController;
use App\Http\Controllers\Web\Backend\CMS\ContactPageHeroSectionController;
use App\Http\Controllers\Web\Backend\CMS\HomePageHeroSectionController;
use App\Http\Controllers\Web\Backend\CMS\OtherPageHeroSectionController;
use App\Http\Controllers\Web\Backend\ContactMessageController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\FootageSizeController;
use App\Http\Controllers\Web\Backend\OtherServiceController;
use App\Http\Controllers\Web\Backend\PackageController;
use App\Http\Controllers\Web\Backend\ServiceController;
use App\Http\Controllers\Web\Backend\ServiceItemController;
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
Route::prefix('service')->name('service.')->group(function () {
    Route::controller(ServiceController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/form-data', 'getFormData')->name('form-data');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::put('/update/{id}', 'update')->name('update');
        Route::get('/status/{id}', 'status')->name('status');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    });

    Route::controller(FootageSizeController::class)->prefix('footage-sizes')->name('footage-sizes.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::put('/update/{id}', 'update')->name('update');
        Route::get('/status/{id}', 'status')->name('status');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    });

    Route::controller(ServiceItemController::class)->prefix('item')->name('item.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::put('/update/{id}', 'update')->name('update');
        Route::get('/status/{id}', 'status')->name('status');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    });

    Route::controller(AddOnController::class)->prefix('add-on')->name('add-on.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/form-data', 'getFormData')->name('form-data');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::put('/update/{id}', 'update')->name('update');
        Route::get('/status/{id}', 'status')->name('status');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    });
});

// Route for Other Services
Route::controller(OtherServiceController::class)->prefix('other-service')->name('other-service.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::patch('/', 'updateOtherService')->name('header.update');
    Route::get('/show/{id}', 'show')->name('show');
    Route::post('/store', 'store')->name('store');
    Route::put('/update/{id}', 'update')->name('update');
    Route::get('/status/{id}', 'status')->name('status');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
});

// Route for Contact Message
Route::controller(ContactMessageController::class)->prefix('contact-message')->name('contact-message.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/show/{id}', 'show')->name('show');
});

// CMS
Route::prefix('cms')->group(function () {
    Route::prefix('home-page')->name('home-page.')->group(function () {
        Route::controller(HomePageHeroSectionController::class)->prefix('hero-section')->name('hero-section.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('/', 'update')->name('update');
        });
    });

    Route::prefix('contact-page')->name('contact-page.')->group(function () {
        Route::controller(ContactPageHeroSectionController::class)->prefix('hero-section')->name('hero-section.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('/', 'update')->name('update');
        });
    });

    Route::prefix('other-page')->name('other-page.')->group(function () {
        Route::controller(OtherPageHeroSectionController::class)->prefix('hero-section')->name('hero-section.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('/', 'update')->name('update');
        });
    });
});
