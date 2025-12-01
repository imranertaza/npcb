<?php

use App\Http\Controllers\FrontendController;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Support\Facades\Route;


Route::controller(FrontendController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::prefix('pages')->name('page.')->group(function () {
            Route::get('/', 'pages')->name('index');
            Route::get('/{slug}','pageDetails')->name('details');
        });
    });
Route::get('admin/{any}', function () {
    return view('welcome');
})->where('any', '.*');

