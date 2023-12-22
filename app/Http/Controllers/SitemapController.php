<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $articlesPath = resource_path('articles');
        $files = File::files($articlesPath);
        $sitemapCount = ceil(count($files) / 2000);

        return response()->view('sitemaps.index', compact('sitemapCount'))
                         ->header('Content-Type', 'application/xml');
    }

    public function sitemap($index)
    {
        $articlesPath = resource_path('articles');
        $allFiles = File::files($articlesPath);
        $files = array_slice($allFiles, ($index - 1) * 2000, 2000);

        $lastmod = now()->toDateString();

        return response()->view('sitemaps.sitemap', compact('files', 'lastmod'))
                         ->header('Content-Type', 'application/xml');
    }
}
