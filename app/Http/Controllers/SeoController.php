<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SeoController extends Controller
{
    public function sitemap()
    {
        $url = rtrim(config('app.url'), '/');
        
        $pages = [
            '/',
            '/auth/login',
            '/auth/register',
            '/auth/role-selection',
        ];
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . $url . $page . '</loc>';
            $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>' . ($page === '/' ? '1.0' : '0.8') . '</priority>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';
        
        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function robots()
    {
        $url = rtrim(config('app.url'), '/');
        
        $txt = "User-agent: *\n";
        $txt .= "Allow: /\n";
        $txt .= "Disallow: /dashboard\n";
        $txt .= "Disallow: /meeting\n";
        $txt .= "Disallow: /profile\n";
        $txt .= "Disallow: /auth/google\n";
        $txt .= "Disallow: /logout\n";
        $txt .= "\nSitemap: {$url}/sitemap.xml\n";
        
        return Response::make($txt, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
