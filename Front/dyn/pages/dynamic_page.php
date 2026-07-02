<?php
/**
 * Great Endured Technology — Dynamic Page Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

$pg = $page;
?>

<section class="section-padding" style="padding-top: calc(var(--header-height) + 60px);">
    <div class="container" style="max-width: 800px;">
        <div style="margin-bottom: 25px;" data-reveal>
            <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.25rem; font-weight: 800; line-height: 1.2; margin: 0; color: var(--color-white);">
                <?php echo htmlspecialchars($pg['title']); ?>
            </h1>
        </div>

        <article class="card" data-reveal style="padding: 40px; border-radius: 16px; background-color: rgba(255,255,255,0.02);">
            <div style="color: var(--color-fog); line-height: 1.7; font-size: 1.05rem;" class="dynamic-page-content">
                <?php echo $pg['content']; ?>
            </div>
        </article>
    </div>
</section>

<style>
.dynamic-page-content h2, .dynamic-page-content h3, .dynamic-page-content h4 {
    color: var(--color-white);
    font-family: 'Outfit', sans-serif;
    margin-top: 30px;
    margin-bottom: 15px;
}
.dynamic-page-content p {
    margin-bottom: 20px;
}
.dynamic-page-content ul, .dynamic-page-content ol {
    margin-bottom: 20px;
    padding-left: 25px;
    color: var(--color-fog);
}
.dynamic-page-content li {
    margin-bottom: 8px;
}
</style>
