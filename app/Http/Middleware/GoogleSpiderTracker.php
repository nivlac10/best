<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Models\Domain;

class GoogleSpiderTracker
{
    public function handle($request, Closure $next)
    {
        $userAgent = strtolower($request->userAgent());
        $googleAgents = [
            'googlebot', // Google's main crawler
            'googlebot-image', // Google's image crawler
            'mediapartners-google', // AdSense crawler
            'adsbot-google', // Google Ads crawler
        ];

        foreach ($googleAgents as $agent) {
            if (str_contains($userAgent, $agent)) {
                // Log Google crawler activity
                Log::channel('google_spiders')->info('Google Spider Detected', [
                    'url' => $request->fullUrl(),
                    'userAgent' => $userAgent,
                    'ip' => $request->ip(),
                ]);

                // Update domain record
                $this->updateDomainRecord($request->getHost());

                return $next($request); // Allow Google crawlers
            }
        }

        // If not a Google crawler, block the request
        abort(403, 'Access denied');
    }

    private function updateDomainRecord($domainName)
    {
        $domain = Domain::firstOrCreate(['name' => $domainName]);
        $domain->update(['spider_last_seen' => now()]);
    }
}
