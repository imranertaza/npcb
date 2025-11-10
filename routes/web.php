<?php

use Illuminate\Support\Facades\Route;
// Serve the Vue SPA (frontend)
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
