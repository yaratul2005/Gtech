<?php
/**
 * Great Endured Technology — About Page Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<section class="section-padding" style="padding-top: calc(var(--header-height) + 60px);">
    <div class="container">
        <div class="section-header" data-reveal>
            <span class="hero-tagline" style="box-shadow: none;">Our Identity</span>
            <h1 style="margin-top: 15px;">Great Endured Technology</h1>
            <p>A digital agency committed to constructing robust digital platforms and premium designs.</p>
        </div>

        <div class="grid grid-2" style="margin-top: 60px; align-items: center;">
            <div class="about-img" data-reveal style="position: relative; height: 350px; background-color: var(--color-ink); border: 1px solid var(--glass-border); border-radius: 16px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <div class="hero-bg-glow" style="width: 250px; height: 250px;"></div>
                <div style="z-index: 10; text-align: center;">
                    <div style="font-size: 5rem; font-weight: 900; background: linear-gradient(135deg, var(--color-white), var(--color-cyan-pulse)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">GET.</div>
                    <p style="letter-spacing: 0.15em; font-size: 0.85rem; color: var(--color-mist); text-transform: uppercase; margin-top: 10px;">Engineering Excellence</p>
                </div>
            </div>
            
            <div class="about-details" data-reveal data-delay="200">
                <h2 style="margin-bottom: 20px; font-size: 2rem;">Built to Endure</h2>
                <p>Great Endured Technology was established to solve a persistent issue in the market: digital platforms that look good but perform poorly under stress, or custom systems that are too heavy for standard hosting limits.</p>
                <p>We combine design principles with lightweight code. Our implementation rules require that visual features (such as custom particle canvas drawings and page transition effects) run entirely on the client's GPU, minimizing server payloads. This results in ultra-fast, smooth websites that feel premium and organic.</p>
                <p>Whether it is an Elementor site, a custom database backend, or a marketing pipeline, we apply strict build protocols to ensure security, high contrast, and responsive layout fidelity.</p>
            </div>
        </div>

        <!-- Vision and Mission -->
        <div class="grid grid-2" style="margin-top: 80px; gap: 50px;">
            <div class="card" data-reveal>
                <h3 style="margin-bottom: 15px; color: var(--color-cyan-pulse);">Our Mission</h3>
                <p style="margin: 0;">To build high-performing, secure, and visually stunning digital products that enable brands to establish an enduring and professional presence on the web without unnecessary server overhead.</p>
            </div>
            
            <div class="card" data-reveal data-delay="100">
                <h3 style="margin-bottom: 15px; color: var(--color-cyan-pulse);">Our Vision</h3>
                <p style="margin: 0;">To become a global leader in high-impact motion design and lightweight web engineering, bridging the gap between immersive aesthetics and robust code efficiency.</p>
            </div>
        </div>

        <!-- Core Values -->
        <div style="margin-top: 100px;">
            <div class="section-header" data-reveal>
                <h2>Core Values</h2>
                <p>The foundations guiding our build protocol and partner relationships.</p>
            </div>

            <div class="grid grid-4" style="margin-top: 50px;">
                <div class="card text-center" style="padding: 30px 20px;" data-reveal>
                    <h4 style="margin-bottom: 10px;">Endurance</h4>
                    <p style="font-size: 0.9rem; color: var(--color-mist); margin: 0;">We write robust, clean code and build architectures that stand the test of time and traffic.</p>
                </div>
                
                <div class="card text-center" style="padding: 30px 20px;" data-reveal data-delay="100">
                    <h4 style="margin-bottom: 10px;">Precision</h4>
                    <p style="font-size: 0.9rem; color: var(--color-mist); margin: 0;">Every pixel, margin, and interaction is engineered deliberately to create a premium feel.</p>
                </div>
                
                <div class="card text-center" style="padding: 30px 20px;" data-reveal data-delay="200">
                    <h4 style="margin-bottom: 10px;">Transparency</h4>
                    <p style="font-size: 0.9rem; color: var(--color-mist); margin: 0;">Clear contracts, logged endpoints, and structured deliverables ensure client trust.</p>
                </div>
                
                <div class="card text-center" style="padding: 30px 20px;" data-reveal data-delay="300">
                    <h4 style="margin-bottom: 10px;">Performance</h4>
                    <p style="font-size: 0.9rem; color: var(--color-mist); margin: 0;">Optimized asset delivery, fast queries, and zero server bloating form our default stack.</p>
                </div>
            </div>
        </div>
    </div>
</section>
