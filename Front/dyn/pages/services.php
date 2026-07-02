<?php
/**
 * Great Endured Technology — Services Page Template (Dynamic CMS)
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

// Load dynamic services database from Vault (Resource.md)
$servicesJson = json_decode((string)file_get_contents(__DIR__ . '/../../../Vault/content/services.json'), true);
$services = $servicesJson['services'] ?? [];

// Helper function to map icons to custom PNGs or fallbacks
function getServiceIconHtml(string $icon): string
{
    $pngMap = [
        'wordpress' => '/Vault/assets/wordpress.png',
        'code' => '/Vault/assets/php.png',
        'trending-up' => '/Vault/assets/seo.png',
        'megaphone' => '/Vault/assets/ads.png',
        'cube' => '/Vault/assets/3D.png',
        'shopping-bag' => '/Vault/assets/woo.png',
    ];

    if (isset($pngMap[$icon])) {
        // High fidelity floating PNG icon with glow effect
        return '<div class="service-png-wrapper" style="
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--glass-border);
            padding: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            position: relative;
            margin-bottom: 25px;
        ">
            <img src="' . htmlspecialchars($pngMap[$icon]) . '" alt="" style="
                width: 100%;
                height: 100%;
                object-fit: contain;
                filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
                transition: transform 0.3s ease;
            " class="service-png-icon">
        </div>';
    }

    // Fallback to SVGs
    $svg = '';
    switch ($icon) {
        case 'shield-check':
            $svg = '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>';
            break;
        default:
            $svg = '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>';
            break;
    }

    return '<div class="card-icon" style="margin-bottom: 25px;">' . $svg . '</div>';
}
?>

<section class="section-padding" style="padding-top: calc(var(--header-height) + 60px);">
    <div class="container">
        <div class="section-header" data-reveal>
            <span class="hero-tagline" style="box-shadow: none;">Our Expertise</span>
            <h1 style="margin-top: 15px;">Elite Agency Services</h1>
            <p>Comprehensive digital solutions engineered for growth, speed, and enduring brand equity.</p>
        </div>

        <div class="grid grid-2" style="margin-top: 50px;">
            <?php foreach ($services as $index => $s): ?>
                <div class="card" data-reveal data-delay="<?php echo ($index % 2) * 100; ?>">
                    <?php echo getServiceIconHtml($s['icon'] ?? ''); ?>
                    <h3 class="card-title"><?php echo htmlspecialchars($s['name']); ?></h3>
                    <p class="card-desc"><?php echo htmlspecialchars($s['description']); ?></p>
                    
                    <?php if (!empty($s['focus'])): ?>
                        <ul style="color: var(--color-fog); list-style: inside circle; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                            <?php 
                            $focusItems = explode(',', $s['focus']);
                            foreach ($focusItems as $item): 
                                $item = trim($item);
                                if (!empty($item)):
                            ?>
                                <li><?php echo htmlspecialchars($item); ?></li>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </ul>
                    <?php endif; ?>
                    
                    <a href="/contact?service=<?php echo urlencode($s['id']); ?>" class="btn btn-secondary btn-sm" style="align-self: flex-start;">Inquire Service</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.card:hover .service-png-icon {
    transform: scale(1.18) rotate(3deg);
    filter: drop-shadow(0 8px 16px rgba(0, 212, 255, 0.4)) !important;
}
.card:hover .service-png-wrapper {
    border-color: rgba(0, 212, 255, 0.4) !important;
    background: rgba(0, 212, 255, 0.04) !important;
    box-shadow: 0 0 15px rgba(0, 212, 255, 0.15);
}
</style>
