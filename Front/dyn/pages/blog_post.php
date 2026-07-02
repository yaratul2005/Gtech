<?php
/**
 * Great Endured Technology — Blog Post Single Page Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

$p = $post;
?>

<section class="section-padding" style="padding-top: calc(var(--header-height) + 60px);">
    <div class="container" style="max-width: 800px;">
        <!-- Breadcrumb / Meta -->
        <div style="margin-bottom: 25px;" data-reveal>
            <a href="/blog" style="color: var(--color-cyan-pulse); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 15px;">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 14px; height: 14px; transform: scaleX(-1);"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                Back to Blog
            </a>
            <div style="display: flex; gap: 15px; font-size: 0.85rem; color: var(--color-mist); margin-bottom: 10px;">
                <span>Published on <?php echo date('F d, Y', strtotime($p['created_at'])); ?></span>
                <span>•</span>
                <span>By Admin</span>
            </div>
            <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.25rem; font-weight: 800; line-height: 1.2; margin: 0; color: var(--color-white);">
                <?php echo htmlspecialchars($p['title']); ?>
            </h1>
        </div>

        <!-- Article Card Panel -->
        <article class="card" data-reveal style="padding: 40px; border-radius: 16px; background-color: rgba(255,255,255,0.02);">
            <div style="color: var(--color-fog); line-height: 1.7; font-size: 1.05rem;" class="blog-post-content">
                <?php echo $p['content']; ?>
            </div>
        </article>
    </div>
</section>

<style>
.blog-post-content h2, .blog-post-content h3, .blog-post-content h4 {
    color: var(--color-white);
    font-family: 'Outfit', sans-serif;
    margin-top: 30px;
    margin-bottom: 15px;
}
.blog-post-content p {
    margin-bottom: 20px;
}
.blog-post-content ul, .blog-post-content ol {
    margin-bottom: 20px;
    padding-left: 25px;
    color: var(--color-fog);
}
.blog-post-content li {
    margin-bottom: 8px;
}
</style>
