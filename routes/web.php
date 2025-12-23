<?php

use App\Http\Controllers\FrontendController;
use App\Services\ImageService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "Application cache cleared!";
});
Route::controller(FrontendController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('match-fixtures', 'matchFixtures')->name('match-fixtures');
    Route::get('notice-board', 'noticeBoard')->name('notice-board');
    Route::get('tournament-result', 'tournamentResult')->name('tournament-result');
    Route::get('/photo-gallery', 'gallery')->name('gallery');
    Route::get('gallery-details/{id}', 'galleryDetails')->name('gallery-details');
    Route::get('news-and-updates', 'newsAndUpdates')->name('news-and-updates');
    Route::get('spotlights', 'spotlightNews')->name('spotlight-news');
    Route::get('news-and-updates/{slug}', 'newsAndUpdatesDetails')->name('news-and-updates-details');
    Route::get('blogs', 'blogs')->name('blogs');
    Route::get('blogs/{slug}', 'blogsDetails')->name('blogs-details');
    Route::get('post-categories/{slug}', 'postCategoryDetails')->name('post-categories');
    Route::get('sports/{slug}', 'postDetails')->name('sports-details');

    Route::get('running-events', 'runningEvents')->name('running-events');
    Route::get('upcoming-events', 'upcomingEvents')->name('upcoming-events');
    Route::get('running-events/{slug}', 'runningEventsDetails')->name('event-details');
    Route::get('executive-committee', 'committeeMembers')->name('committee-members');
    Route::post('contact-us', 'contactSubmit')->name('contact.submit');
    Route::prefix('pages')->name('page.')->group(function () {
        Route::get('/', 'pages')->name('index');
        Route::get('/{slug}', 'pageDetails')->name('details');
    });
    Route::get('/image/{width}/{height}/{format}/{path}', function ($width, $height, $format, $path) {
        $fullPath = $path;

        $url = ImageService::resizeAndCache($fullPath, (int) $width, (int) $height, $format);

        return redirect($url);
    })->where('path', '.*');
});
Route::get('/admin', function () {return redirect('/admin/dashboard');});
Route::get('admin/{any}', function () {
    return view('welcome');
})->where('any', '.*');
