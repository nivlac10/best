<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\File;

class IPWhitelistMiddleware
{
    public function handle($request, Closure $next)
    {
        $whitelistedIPs = ['175.176.37.142']; // Replace with your IP

        if (!in_array($request->ip(), $whitelistedIPs)) {
            return response($this->getRandomArticle(resource_path('articles')));
        }

        return $next($request);
    }

    private function getRandomArticle($path)
    {
        $allArticles = File::files($path);
        if (count($allArticles) > 0) {
            $randomArticle = $allArticles[array_rand($allArticles)];
            return File::get($randomArticle->getPathname());
        }

        return 'No articles available.';
    }
}
