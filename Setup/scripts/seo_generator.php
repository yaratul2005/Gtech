<?php
/**
 * SEO Generator
 * Automatically generates sitemap.xml and robots.txt based on dynamic pages and blog posts.
 * Part of Setup & Governance layer.
 */

declare(strict_types=1);

// Ensure bootstrap is loaded
require_once dirname(__DIR__, 2) . '/Back/core/bootstrap.php';

if (!function_exists('generateSeoFiles')) {
    function generateSeoFiles(): void {
        $baseUrl = getenv('APP_URL') ?: 'https://greatentech.com';
        $baseUrl = rtrim($baseUrl, '/');

        // 1. Generate Sitemap XML
        $sitemapPath = dirname(__DIR__, 2) . '/sitemap.xml';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static pages
        $statics = [
            '/' => '1.0',
            '/about' => '0.8',
            '/services' => '0.8',
            '/portfolio' => '0.8',
            '/contact' => '0.8',
            '/team' => '0.8',
            '/blog' => '0.8',
        ];

        foreach ($statics as $path => $priority) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($baseUrl . $path) . "</loc>\n";
            $xml .= "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>{$priority}</priority>\n";
            $xml .= "  </url>\n";
        }

        // Dynamic Pages from pages.json
        $pagesFile = dirname(__DIR__, 2) . '/Vault/content/pages.json';
        if (file_exists($pagesFile)) {
            $pages = json_decode(file_get_contents($pagesFile), true) ?: [];
            foreach ($pages as $p) {
                if (!empty($p['slug'])) {
                    $lastmod = !empty($p['created_at']) ? date('Y-m-d', strtotime($p['created_at'])) : date('Y-m-d');
                    $xml .= "  <url>\n";
                    $xml .= "    <loc>" . htmlspecialchars($baseUrl . '/p/' . $p['slug']) . "</loc>\n";
                    $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
                    $xml .= "    <changefreq>monthly</changefreq>\n";
                    $xml .= "    <priority>0.7</priority>\n";
                    $xml .= "  </url>\n";
                }
            }
        }

        // Dynamic Blog Posts from posts.json
        $postsFile = dirname(__DIR__, 2) . '/Vault/content/posts.json';
        if (file_exists($postsFile)) {
            $posts = json_decode(file_get_contents($postsFile), true) ?: [];
            foreach ($posts as $post) {
                if (!empty($post['slug'])) {
                    $lastmod = !empty($post['created_at']) ? date('Y-m-d', strtotime($post['created_at'])) : date('Y-m-d');
                    $xml .= "  <url>\n";
                    $xml .= "    <loc>" . htmlspecialchars($baseUrl . '/blog/' . $post['slug']) . "</loc>\n";
                    $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
                    $xml .= "    <changefreq>monthly</changefreq>\n";
                    $xml .= "    <priority>0.6</priority>\n";
                    $xml .= "  </url>\n";
                }
            }
        }

        $xml .= '</urlset>';

        file_put_contents($sitemapPath, $xml);

        // 2. Generate robots.txt
        $robotsPath = dirname(__DIR__, 2) . '/robots.txt';
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /Setup/\n";
        $robots .= "Disallow: /Back/\n";
        $robots .= "Disallow: /Vault/\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "\n";
        $robots .= "Sitemap: " . $baseUrl . "/sitemap.xml\n";

        file_put_contents($robotsPath, $robots);

        // Log the update to setup.log.md
        $logFile = dirname(__DIR__) . '/setup.log.md';
        if (file_exists($logFile)) {
            $entry = "\n## [" . date('Y-m-d H:i:s') . "] SEO Generator Run\n";
            $entry .= "- Automatically regenerated `sitemap.xml` and `robots.txt`\n";
            file_put_contents($logFile, $entry, FILE_APPEND);
        }
    }
}

// CLI or direct run execution check
if (php_sapi_name() === 'cli' || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/cron/seo') !== false)) {
    generateSeoFiles();
}
