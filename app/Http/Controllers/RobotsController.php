<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RobotsController extends Controller
{
    public function robots(Request $request)
    {
        $sitemapUrl = url('sitemap.xml'); // Dynamically generate the sitemap URL

        $content = "User-agent: *\n";
        $content .= "Sitemap: $sitemapUrl\n";

        return response($content)->header('Content-Type', 'text/plain');
    }
}
