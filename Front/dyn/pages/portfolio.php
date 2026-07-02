<?php
/**
 * Great Endured Technology — Portfolio Template (Dynamic CMS)
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

// Load portfolio items from Vault database (Resource.md)
$portfolioFile = __DIR__ . '/../../../Vault/content/portfolio.json';
$portfolio = [];
if (file_exists($portfolioFile)) {
    $portfolio = json_decode((string)file_get_contents($portfolioFile), true) ?: [];
}

// Map icons to SVG layouts
function getPortfolioIconSvg(string $icon): string
{
    switch ($icon) {
        case 'document':
            return '<svg style="width: 48px; height: 48px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>';
        case 'cube':
            return '<svg style="width: 48px; height: 48px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>';
        case 'globe':
        default:
            return '<svg style="width: 48px; height: 48px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.905 0-5.64-.5-8.14-1.418m15.686 0a8.997 8.997 0 01-7.843 4.582m-7.844-4.582a8.997 8.997 0 007.843 4.582m0 0V21"/></svg>';
    }
}
?>

<section class="section-padding" style="padding-top: calc(var(--header-height) + 60px);">
    <div class="container">
        <div class="section-header" data-reveal>
            <span class="hero-tagline" style="box-shadow: none;">Our Showcase</span>
            <h1 style="margin-top: 15px;">Featured Case Studies</h1>
            <p>A curated selection of our work across custom web applications, branding, and AI mockups.</p>
        </div>

        <!-- Portfolio grid -->
        <div class="grid grid-3" style="margin-top: 50px;">
            <?php if (empty($portfolio)): ?>
                <div style="grid-column: span 3; text-align: center; padding: 50px 0; color: var(--color-mist);">
                    <p>No case studies featured currently. Please check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach ($portfolio as $index => $item): ?>
                    <div class="card" data-reveal data-delay="<?php echo ($index % 3) * 100; ?>" style="padding: 0; overflow: hidden;">
                        <div style="height: 200px; background: <?php echo htmlspecialchars($item['theme'] ?? 'linear-gradient(135deg, #090d16 0%, #0a2540 100%)'); ?>; position: relative; border-bottom: 1px solid var(--glass-border); display: flex; align-items: center; justify-content: center; color: var(--color-cyan-pulse);">
                            <?php echo getPortfolioIconSvg($item['icon'] ?? 'globe'); ?>
                        </div>
                        <div style="padding: 30px; display: flex; flex-direction: column; min-height: 250px;">
                            <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--color-cyan-pulse); font-weight: 700; letter-spacing: 0.1em; margin-bottom: 5px;">
                                <?php echo htmlspecialchars($item['category']); ?>
                            </span>
                            <h3 style="font-size: 1.25rem; margin-top: 0; margin-bottom: 15px;">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </h3>
                            <p style="font-size: 0.9rem; color: var(--color-fog); margin-bottom: 20px; line-height: 1.5; flex-grow: 1;">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                            <a href="/contact?service=<?php echo urlencode($item['id']); ?>" class="card-link" style="font-size: 0.85rem; margin-top: auto;">
                                Request Info <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
