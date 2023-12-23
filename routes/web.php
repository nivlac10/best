<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SpiderTrackingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

Route::middleware(['google.spider'])->group(function () {
    // Main domain routes
    Route::get('/', function () {
        return view('home');
    });

    //Robots.txt routes
    Route::get('robots.txt', [RobotsController::class, 'robots']);

    // Route for articles on the main domain
    Route::get('/article/{articleName}', [ArticleController::class, 'show']);

    // Sitemap routes
    Route::get('/sitemap.xml', [SitemapController::class, 'index']);
    Route::get('/sitemap-{index}.xml', [SitemapController::class, 'sitemap']);

    // Subdomain routes
    // Assuming {domain} captures the full domain (excluding subdomain)
    Route::domain('{keyword}.{domain}')->group(function () {
        Route::get('/', [ArticleController::class, 'show']);
    });
    Route::fallback([ArticleController::class, 'show']);
});

Route::get('/spider-tracking', [SpiderTrackingController::class, 'index'])->middleware('ip.whitelist');

