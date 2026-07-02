<?php
/**
 * Great Endured Technology — Blog Archive Page Template (Dynamic CMS)
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

// Load posts from Vault database
$postsFile = __DIR__ . '/../../../Vault/content/posts.json';
$posts = [];
if (file_exists($postsFile)) {
    $posts = json_decode((string)file_get_contents($postsFile), true) ?: [];
}
?>

<section class="section-padding" style="padding-top: calc(var(--header-height) + 60px);">
    <div class="container">
        <div class="section-header" data-reveal>
            <span class="hero-tagline" style="box-shadow: none;">Our Insights</span>
            <h1 style="margin-top: 15px;">Agency Insights & Blog</h1>
            <p>Thought leadership, tech announcements, and digital strategy guides.</p>
        </div>

        <div class="grid grid-3" style="margin-top: 50px;">
            <?php if (empty($posts)): ?>
                <div style="grid-column: span 3; text-align: center; padding: 50px 0; color: var(--color-fog);">
                    <p>No blog articles published yet. Please check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach (array_reverse($posts) as $index => $post): ?>
                    <div class="card" data-reveal data-delay="<?php echo ($index % 3) * 100; ?>" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                        <div style="height: 180px; background: linear-gradient(135deg, var(--color-blue-deep) 0%, var(--color-void) 100%); border-bottom: 1px solid var(--glass-border); display: flex; align-items: center; justify-content: center; color: var(--color-cyan-pulse);">
                            <span style="font-size: 3.5rem;">📰</span>
                        </div>
                        <div style="padding: 25px; display: flex; flex-direction: column; flex-grow: 1;">
                            <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--color-cyan-pulse); font-weight: 700; letter-spacing: 0.08em; margin-bottom: 8px;">
                                Published <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                            </span>
                            <h3 style="font-size: 1.15rem; margin-top: 0; margin-bottom: 12px; font-family: 'Outfit', sans-serif;">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </h3>
                            <p style="font-size: 0.88rem; color: var(--color-fog); line-height: 1.5; margin-bottom: 20px; flex-grow: 1;">
                                <?php echo htmlspecialchars($post['meta_desc'] ?: substr(strip_tags($post['content']), 0, 120) . '...'); ?>
                            </p>
                            <a href="/blog/<?php echo htmlspecialchars($post['slug']); ?>" class="card-link" style="font-size: 0.85rem; margin-top: auto; display: inline-flex; align-items: center; gap: 5px;">
                                Read Article <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
