<?php
/**
 * Great Endured Technology — Services Page Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<section class="section-padding" style="padding-top: calc(var(--header-height) + 60px);">
    <div class="container">
        <div class="section-header" data-reveal>
            <span class="hero-tagline" style="box-shadow: none;">Our Expertise</span>
            <h1 style="margin-top: 15px;">Elite Agency Services</h1>
            <p>Comprehensive digital solutions engineered for growth, speed, and enduring brand equity.</p>
        </div>

        <div class="grid grid-2" style="margin-top: 50px;">
            <!-- Service 1: WordPress / Elementor Website -->
            <div class="card" data-reveal>
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="card-title">WordPress & Elementor Websites</h3>
                <p class="card-desc">High-converting, fully customized corporate and landing page designs built using Elementor Pro on WordPress. We develop bespoke themes, optimize load times to sub-second speeds, secure configurations, and set up drag-and-drop custom modules so your internal marketing teams can manage content effortlessly without code.</p>
                <ul style="color: var(--color-fog); list-style: inside circle; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                    <li>Bespoke Theme & Elementor Custom Widgets</li>
                    <li>Sub-second Mobile Load Speed Optimizations</li>
                    <li>Security Hardening & Automated Daily Backups</li>
                </ul>
                <a href="/contact?service=wordpress" class="btn btn-secondary btn-sm" style="align-self: flex-start;">Inquire Service</a>
            </div>

            <!-- Service 2: PHP Custom Website -->
            <div class="card" data-reveal data-delay="100">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
                <h3 class="card-title">PHP Custom Websites & Systems</h3>
                <p class="card-desc">When standard templates fall short, we build custom applications and MVC backends from scratch using secure, modern PHP (8.x). Optimized for low-cost shared hosting environments, our database architectures use prepared PDO statements to ensure maximum vulnerability protection while executing lightning-fast queries.</p>
                <ul style="color: var(--color-fog); list-style: inside circle; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                    <li>Custom Database Arch (MySQL/PDO)</li>
                    <li>Secure Admin Dashboards & Middleware Gates</li>
                    <li>Third-party API & webhook integrations</li>
                </ul>
                <a href="/contact?service=php" class="btn btn-secondary btn-sm" style="align-self: flex-start;">Inquire Service</a>
            </div>

            <!-- Service 3: SEO and Ranking -->
            <div class="card" data-reveal>
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <h3 class="card-title">SEO and Organic Search Ranking</h3>
                <p class="card-desc">Move up Google's search result pages. We execute technical audit reviews, fix crawlability constraints, optimize Core Web Vitals, and deploy schema markups. Followed by meticulous keyword targeting, competitor research, and high-quality backlink generation to position your site where prospects look.</p>
                <ul style="color: var(--color-fog); list-style: inside circle; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                    <li>Comprehensive Keyword Research & Mapping</li>
                    <li>Technical SEO audit & Schema markups</li>
                    <li>High-Quality Domain Authority backlinks</li>
                </ul>
                <a href="/contact?service=seo" class="btn btn-secondary btn-sm" style="align-self: flex-start;">Inquire Service</a>
            </div>

            <!-- Service 4: Digital Marketing -->
            <div class="card" data-reveal data-delay="100">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <h3 class="card-title">Digital Marketing & Ads Management</h3>
                <p class="card-desc">Stop burning budget on unoptimized ads. We manage performance campaigns on Google Ads, Meta Ads (Facebook/Instagram), and LinkedIn. Our strategies focus on laser-focused audience segmentation, landing page conversion tests, and robust conversion API tracking to ensure positive ROI for your ad spends.</p>
                <ul style="color: var(--color-fog); list-style: inside circle; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                    <li>Meta Pixels & Conversion API setups</li>
                    <li>A/B Landing Page split testing</li>
                    <li>Detailed monthly conversion reports</li>
                </ul>
                <a href="/contact?service=marketing" class="btn btn-secondary btn-sm" style="align-self: flex-start;">Inquire Service</a>
            </div>

            <!-- Service 5: Content Design & 3D Mockups -->
            <div class="card" data-reveal>
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                </div>
                <h3 class="card-title">Content & 3D AI Product Mockups</h3>
                <p class="card-desc">Empowered content creation. We set up easy-to-use Canva templates aligned with your brand style guidelines. Additionally, we use advanced neural rendering pipelines to generate high-fidelity 3D product mockups directly from your flat product photos, providing gorgeous, studio-quality commercial assets.</p>
                <ul style="color: var(--color-fog); list-style: inside circle; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                    <li>Custom Canva social media template kits</li>
                    <li>3D photorealistic product renderings</li>
                    <li>AI image enhancement & asset prep</li>
                </ul>
                <a href="/contact?service=content" class="btn btn-secondary btn-sm" style="align-self: flex-start;">Inquire Service</a>
            </div>

            <!-- Service 6: E-commerce Business Startup -->
            <div class="card" data-reveal data-delay="100">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h3 class="card-title">E-commerce Business Startups</h3>
                <p class="card-desc">Launch your storefront with peace of mind. We build and structure comprehensive online stores utilizing WooCommerce or Shopify. From configuring inventory syncing, payment gates (bKash/SSLCommerz/Stripe), automated tax calculations, SMS invoice delivery, to shipping partner integrations.</p>
                <ul style="color: var(--color-fog); list-style: inside circle; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                    <li>WooCommerce & Shopify configuration</li>
                    <li>Local & International payment gateways</li>
                    <li>Order management & SMS notification systems</li>
                </ul>
                <a href="/contact?service=ecommerce" class="btn btn-secondary btn-sm" style="align-self: flex-start;">Inquire Service</a>
            </div>

            <!-- Service 7: Business Solutions & Branding -->
            <div class="card" data-reveal style="grid-column: span 1;">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="card-title">Business Solutions & Branding</h3>
                <p class="card-desc">Establish a solid corporate identity. We engineer modern corporate logos, design stationery assets, write brand style books, and deploy digital workspaces (Google Workspace/Microsoft 365 setup). Our structural solutions streamline your workflow and secure your brand presence.</p>
                <ul style="color: var(--color-fog); list-style: inside circle; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                    <li>Corporate Logo & Identity systems</li>
                    <li>Brand Style Book definitions</li>
                    <li>Professional Google Workspace setup</li>
                </ul>
                <a href="/contact?service=branding" class="btn btn-secondary btn-sm" style="align-self: flex-start;">Inquire Service</a>
            </div>
        </div>
    </div>
</section>
