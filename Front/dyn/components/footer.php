<?php
/**
 * Great Endured Technology — Global Footer Component
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>
    <!-- Premium Footer Section -->
    <footer class="footer">
        <div class="container">
            <div class="grid footer-grid">
                <div class="footer-brand" data-reveal>
                    <a href="/" class="logo">
                        <img src="/Front/static/img/logo.png" alt="GET Logo">
                        <span>GET.</span>
                    </a>
                    <p>Great Endured Technology is a state-of-the-art digital agency building next-generation websites, digital experiences, and brand identities.</p>
                    <div class="footer-socials">
                        <a href="https://linkedin.com" target="_blank" class="footer-social-link" aria-label="LinkedIn">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                        <a href="https://twitter.com" target="_blank" class="footer-social-link" aria-label="Twitter">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="https://facebook.com" target="_blank" class="footer-social-link" aria-label="Facebook">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                        </a>
                    </div>
                </div>
                
                <div class="footer-col" data-reveal data-delay="100">
                    <h3 class="footer-title">Services</h3>
                    <ul class="footer-links">
                        <li><a href="/services">WordPress / Elementor</a></li>
                        <li><a href="/services">PHP Custom Websites</a></li>
                        <li><a href="/services">SEO & Positioning</a></li>
                        <li><a href="/services">Digital Marketing (Ads)</a></li>
                        <li><a href="/services">Content & 3D Mockups</a></li>
                    </ul>
                </div>
                
<?php
$pagesFile = __DIR__ . '/../../../Vault/content/pages.json';
$footerPages = [];
if (file_exists($pagesFile)) {
    $footerPages = json_decode((string)file_get_contents($pagesFile), true) ?: [];
}
?>
                <div class="footer-col" data-reveal data-delay="200">
                    <h3 class="footer-title">Company</h3>
                    <ul class="footer-links">
                        <li><a href="/about">About Us</a></li>
                        <li><a href="/portfolio">Portfolio</a></li>
                        <li><a href="/team">Our Team</a></li>
                        <li><a href="/blog">Blog Insights</a></li>
                        <?php foreach ($footerPages as $fp): ?>
                            <li><a href="/p/<?php echo htmlspecialchars($fp['slug']); ?>"><?php echo htmlspecialchars($fp['title']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="footer-col" data-reveal data-delay="300">
                    <h3 class="footer-title">Others</h3>
                    <ul class="footer-links">
                        <li><a href="/sitemap.xml" target="_blank">Sitemap XML</a></li>
                        <li><a href="/robots.txt" target="_blank">Robots.txt</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="flex-between footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Great Endured Technology. All rights reserved.</p>
                <p>Designed for Excellence.</p>
            </div>
        </div>
        
        <?php require __DIR__ . '/cookie-consent.php'; ?>
    </footer>

    <!-- App JavaScript Engine Bundle -->
    <script src="/Front/static/js/app.js"></script>
    <!-- Injected Custom Footer Scripts (CMS Integration Tool) -->
    <?php echo getenv('FOOTER_CODE') !== false ? getenv('FOOTER_CODE') : ''; ?>
</body>
</html>
