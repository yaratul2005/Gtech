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
    
    <!-- SEO Meta Tags (Web App Dev Guidelines & CMS Settings) -->
    <title><?php echo isset($title) ? htmlspecialchars($title) . " | " . (getenv('APP_NAME') ?: 'Great Endured Technology') : (getenv('APP_NAME') ?: 'Great Endured Technology') . " | Premium Tech Agency"; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(getenv('APP_DESC') ?: 'Great Endured Technology is a premier digital agency providing enterprise WordPress development, custom PHP solutions, SEO, digital marketing, Canva content, and 3D mockups.'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars(getenv('APP_KEYWORDS') ?: 'Great Endured Technology, digital agency, WordPress development, Elementor, PHP website, SEO ranking, Digital marketing, Canva design, 3D mockups, ecommerce startup'); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars(getenv('APP_URL') ?: 'https://greatentech.com') . htmlspecialchars($currentUri); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/Front/static/img/favicon.png?v=2">
    <link rel="shortcut icon" type="image/png" href="/Front/static/img/favicon.png?v=2">
    
    <!-- Fonts Integration (Resource.md: Outfit & Inter self-hosted/Google Fonts fast-swap) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Main Style Sheet (Compiled and Minified output from local dev) -->
    <link rel="stylesheet" href="/Front/static/css/app.css">

    <!-- Schema.org Dynamic JSON-LD Structured Data for SEO -->
    <?php if ($currentUri === '/'): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "<?php echo htmlspecialchars(getenv('APP_NAME') ?: 'Great Endured Technology'); ?>",
      "url": "<?php echo htmlspecialchars(getenv('APP_URL') ?: 'https://greatentech.com'); ?>",
      "logo": "<?php echo htmlspecialchars(getenv('APP_URL') ?: 'https://greatentech.com'); ?>/Front/static/img/logo.png",
      "sameAs": [
        "https://github.com/yaratul2005/Gtech"
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "<?php echo htmlspecialchars(getenv('APP_NAME') ?: 'Great Endured Technology'); ?>",
      "url": "<?php echo htmlspecialchars(getenv('APP_URL') ?: 'https://greatentech.com'); ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo htmlspecialchars(getenv('APP_URL') ?: 'https://greatentech.com'); ?>/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <?php else: ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "<?php echo htmlspecialchars($title ?? 'Great Endured Technology'); ?>",
      "description": "<?php echo htmlspecialchars(getenv('APP_DESC') ?: 'Great Endured Technology is a premier digital agency...'); ?>",
      "url": "<?php echo htmlspecialchars(getenv('APP_URL') ?: 'https://greatentech.com') . htmlspecialchars($currentUri); ?>"
    }
    </script>
    <?php endif; ?>

    <!-- Injected Custom Head Script (CMS Integration Tool) -->
    <?php echo getenv('HEADER_CODE') !== false ? getenv('HEADER_CODE') : ''; ?>
</head>
<body>
    <!-- Fluid Page Transition Loader -->
    <div id="fluid-wipe-overlay" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: linear-gradient(135deg, #091a2f 0%, #030508 100%);
        z-index: 999999;
        transform: translateY(0);
        transition: transform 0.6s cubic-bezier(0.85, 0, 0.15, 1);
        pointer-events: all;
        display: flex;
        align-items: center;
        justify-content: center;
    ">
        <div style="position: relative; display: flex; flex-direction: column; align-items: center; gap: 20px;">
            <div class="fluid-glow" style="
                width: 70px;
                height: 70px;
                border-radius: 50%;
                background: #00f2fe;
                box-shadow: 0 0 35px #00f2fe, 0 0 70px #4facfe;
                animation: fluidRotate 2.2s infinite alternate ease-in-out;
                filter: blur(1px);
            "></div>
            <div style="font-family: 'Outfit', sans-serif; font-size: 0.85rem; letter-spacing: 0.2em; text-transform: uppercase; color: #00f2fe; font-weight: 700; opacity: 0.8; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5);">
                Loading...
            </div>
        </div>
    </div>

    <style>
    @keyframes fluidRotate {
        0% { transform: scale(1) rotate(0deg); border-radius: 50% 50% 50% 50% / 50% 50% 50% 50%; }
        33% { transform: scale(1.1) rotate(120deg); border-radius: 40% 60% 45% 55% / 40% 45% 55% 60%; }
        66% { transform: scale(0.9) rotate(240deg); border-radius: 55% 45% 60% 40% / 50% 60% 40% 50%; }
        100% { transform: scale(1) rotate(360deg); border-radius: 50% 50% 50% 50% / 50% 50% 50% 50%; }
    }
    </style>

    <script>
    (function() {
        const hideLoader = () => {
            const overlay = document.getElementById('fluid-wipe-overlay');
            if (overlay) {
                overlay.style.transform = 'translateY(100%)';
                overlay.style.pointerEvents = 'none';
            }
        };

        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(hideLoader, 150);

            document.body.addEventListener('click', e => {
                const link = e.target.closest('a');
                if (link && link.href) {
                    const url = new URL(link.href, window.location.href);
                    const isInternal = url.origin === window.location.origin;
                    const isSpecial = link.getAttribute('target') === '_blank' || 
                                      link.getAttribute('href').startsWith('#') || 
                                      link.getAttribute('href').startsWith('javascript:') ||
                                      e.metaKey || e.ctrlKey || e.shiftKey || e.altKey;
                    
                    if (isInternal && !isSpecial) {
                        e.preventDefault();
                        const overlay = document.getElementById('fluid-wipe-overlay');
                        if (overlay) {
                            overlay.style.transition = 'none';
                            overlay.style.transform = 'translateY(-100%)';
                            overlay.offsetHeight;
                            overlay.style.transition = 'transform 0.6s cubic-bezier(0.85, 0, 0.15, 1)';
                            overlay.style.transform = 'translateY(0)';
                            overlay.style.pointerEvents = 'all';
                            setTimeout(() => {
                                window.location.href = link.href;
                            }, 550);
                        } else {
                            window.location.href = link.href;
                        }
                    }
                }
            });
        });

        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                hideLoader();
            }
        });
    })();
    </script>

    <!-- Premium Navigation Bar -->
    <nav class="navbar" id="navbar">
        <div class="container nav-container">
            <a href="/" class="logo" id="nav-logo">
                <img src="/Front/static/img/logo.png" alt="GET Logo">
                <span>GET.</span>
            </a>
            
            <ul class="nav-links" id="nav-menu">
                <li><a href="/" class="nav-link <?php echo isActive('/', $currentUri); ?>">Home</a></li>
                <li><a href="/about" class="nav-link <?php echo isActive('/about', $currentUri); ?>">About</a></li>
                <li><a href="/services" class="nav-link <?php echo isActive('/services', $currentUri); ?>">Services</a></li>
                <li><a href="/portfolio" class="nav-link <?php echo isActive('/portfolio', $currentUri); ?>">Portfolio</a></li>
                <li><a href="/team" class="nav-link <?php echo isActive('/team', $currentUri); ?>">Team</a></li>
                <li><a href="/blog" class="nav-link <?php echo isActive('/blog', $currentUri); ?>">Blog</a></li>
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
