<?php

namespace App\Http\Controllers;

class RobotsController extends Controller
{
    public function index()
    {
        $content = "User-agent: *
Allow: /

# Sitemap
Sitemap: " . url('/sitemap.xml') . "

# Block admin and private areas
Disallow: /admin/
Disallow: /login
Disallow: /register
Disallow: /password/
Disallow: /storage/

# Allow public content
Allow: /blog/
Allow: /privacy-policy
Allow: /terms-of-service

# Crawl delay (optional)
Crawl-delay: 1";

        return response($content)
                ->header('Content-Type', 'text/plain');
    }
}