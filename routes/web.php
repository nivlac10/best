<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SitemapController;

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
});
