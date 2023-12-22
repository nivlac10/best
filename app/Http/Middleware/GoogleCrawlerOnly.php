<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class GoogleCrawlerOnly
{
    public function handle(Request $request, Closure $next)
    {
        // List of Google crawler user agents
        $googleAgents = [
            'Googlebot', // Google's main crawler
            'Googlebot-Image', // Google's image crawler
            'Mediapartners-Google', // AdSense crawler
            'AdsBot-Google', // Google Ads crawler
            // Add other Google crawlers if needed
        ];

        $userAgent = $request->header('User-Agent');

        foreach ($googleAgents as $agent) {
            if (str_contains($userAgent, $agent)) {
                return $next($request); // Allow Google crawlers
            }
        }

        // If not a Google crawler, block the request
        abort(403, 'Access denied');
    }
}
