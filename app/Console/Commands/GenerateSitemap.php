<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap files';

    public function handle()
    {
        $articlesPath = resource_path('articles');
        $articlesPerSitemap = 2000;

        $allArticles = File::files($articlesPath);
        $totalArticles = count($allArticles);
        $sitemapCount = ceil($totalArticles / $articlesPerSitemap);

        for ($i = 0; $i < $sitemapCount; $i++) {
            $articlesBatch = array_slice($allArticles, $i * $articlesPerSitemap, $articlesPerSitemap);
            // Logic to create and save individual sitemap file (e.g., sitemap-1.xml)
        }

        // Logic to create and save the main sitemap index file
    }
}
