<?php
/**
 * Great Endured Technology — Services Page Template (Dynamic CMS)
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

// Load dynamic services database from Vault (Resource.md)
$servicesJson = json_decode((string)file_get_contents(__DIR__ . '/../../../Vault/content/services.json'), true);
$services = $servicesJson['services'] ?? [];

// Helper function to map icons to SVGs in a unified format
function getServiceSvg(string $icon): string
{
    switch ($icon) {
        case 'wordpress':
            return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>';
        case 'code':
            return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>';
        case 'trending-up':
            return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>';
        case 'megaphone':
            return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>';
        case 'cube':
            return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';
        case 'shopping-bag':
            return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>';
        default:
            return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>';
    }
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
                    <div class="card-icon">
                        <?php echo getServiceSvg($s['icon'] ?? ''); ?>
                    </div>
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
