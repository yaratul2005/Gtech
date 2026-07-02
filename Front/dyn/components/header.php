<?php
/**
 * Great Endured Technology — Global Header Component
 * Governed by RBP (AGENTS.md) & Stack Profile (Resource.md)
 */

declare(strict_types=1);

$currentUri = $_SERVER['REQUEST_URI'];
$position = strpos($currentUri, '?');
if ($position !== false) {
    $currentUri = substr($currentUri, 0, $position);
}
$currentUri = rtrim($currentUri, '/');
if (empty($currentUri)) {
    $currentUri = '/';
}

function isActive(string $path, string $currentUri): string
{
    if ($path === '/' && $currentUri === '') {
        return 'active';
    }
    return $currentUri === $path ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags (Web App Dev Guidelines) -->
    <title><?php echo isset($title) ? htmlspecialchars($title) . " | Great Endured Technology" : "Great Endured Technology | Premium Tech Agency"; ?></title>
    <meta name="description" content="Great Endured Technology is a premier digital agency providing enterprise WordPress development, custom PHP solutions, SEO, digital marketing, Canva content, and 3D mockups.">
    <meta name="keywords" content="Great Endured Technology, digital agency, WordPress development, Elementor, PHP website, SEO ranking, Digital marketing, Canva design, 3D mockups, ecommerce startup">
    <link rel="canonical" href="https://greatentech.com<?php echo htmlspecialchars($currentUri); ?>">
    
    <!-- Fonts Integration (Resource.md: Outfit & Inter self-hosted/Google Fonts fast-swap) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Main Style Sheet (Compiled and Minified output from local dev) -->
    <link rel="stylesheet" href="/Front/static/css/app.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/Front/static/img/favicon.png" onerror="this.style.display='none'">
</head>
<body>
    <!-- Premium Navigation Bar -->
    <nav class="navbar" id="navbar">
        <div class="container nav-container">
            <a href="/" class="logo" id="nav-logo">
                <span>GET.</span>
            </a>
            
            <ul class="nav-links" id="nav-menu">
                <li><a href="/" class="nav-link <?php echo isActive('/', $currentUri); ?>">Home</a></li>
                <li><a href="/about" class="nav-link <?php echo isActive('/about', $currentUri); ?>">About</a></li>
                <li><a href="/services" class="nav-link <?php echo isActive('/services', $currentUri); ?>">Services</a></li>
                <li><a href="/portfolio" class="nav-link <?php echo isActive('/portfolio', $currentUri); ?>">Portfolio</a></li>
                <li><a href="/contact" class="nav-link <?php echo isActive('/contact', $currentUri); ?>">Contact</a></li>
            </ul>
            
            <div class="nav-cta">
                <a href="/contact" class="btn btn-primary btn-sm">Get Started</a>
            </div>
            
            <button class="menu-toggle" id="menu-toggle" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>
