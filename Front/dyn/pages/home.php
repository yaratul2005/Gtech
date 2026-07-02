<?php
/**
 * Great Endured Technology — Home Page Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<!-- Hero Section with Canvas Particles -->
<section class="hero">
    <?php require __DIR__ . '/../components/particle-bg.php'; ?>
    <div class="hero-bg-glow"></div>
    <div class="hero-bg-glow-alt"></div>
    
    <div class="container hero-content">
        <span class="hero-tagline" data-reveal>Digital Innovation Studio</span>
        <h1 class="hero-title" data-reveal data-delay="200">
            We Design and Build<br><span>Endured Digital Futures</span>
        </h1>
        <p class="hero-desc" data-reveal data-delay="400">
            A premium technology agency providing elite development, custom applications, branding solutions, and marketing strategies for forward-thinking brands.
        </p>
        <div class="hero-ctas" data-reveal data-delay="600">
            <a href="/services" class="btn btn-primary">Explore Services</a>
            <a href="/contact" class="btn btn-secondary">Get Free Proposal</a>
        </div>
    </div>
</section>

<!-- Services Preview Section -->
<section class="section-padding">
    <div class="container">
        <div class="section-header" data-reveal>
            <h2>Elite Services We Provide</h2>
            <p>We combine cutting-edge technology and beautiful aesthetics to deliver outstanding business results.</p>
        </div>
        
        <div class="grid grid-3">
            <!-- Service 1: Custom PHP & Web development -->
            <div class="card" data-reveal data-delay="100">
                <div class="service-png-wrapper" style="
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
                    <img src="/Vault/assets/php.png" alt="" style="
                        width: 100%;
                        height: 100%;
                        object-fit: contain;
                        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
                        transition: transform 0.3s ease;
                    " class="service-png-icon">
                </div>
                <h3 class="card-title">Custom PHP Systems</h3>
                <p class="card-desc">Robust, lightweight, database-driven web applications built from scratch, optimized for speed and tailored to your custom specifications.</p>
                <a href="/services" class="card-link">Learn More <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
            </div>

            <!-- Service 2: WordPress / Elementor -->
            <div class="card" data-reveal data-delay="200">
                <div class="service-png-wrapper" style="
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
                    <img src="/Vault/assets/wordpress.png" alt="" style="
                        width: 100%;
                        height: 100%;
                        object-fit: contain;
                        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
                        transition: transform 0.3s ease;
                    " class="service-png-icon">
                </div>
                <h3 class="card-title">WordPress & Elementor</h3>
                <p class="card-desc">Stunning, responsive corporate and portfolio websites built on WordPress, featuring high-speed optimization and complete client editability.</p>
                <a href="/services" class="card-link">Learn More <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
            </div>

            <!-- Service 3: Content Design & 3D AI Mockups -->
            <div class="card" data-reveal data-delay="300">
                <div class="service-png-wrapper" style="
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
                    <img src="/Vault/assets/3D.png" alt="" style="
                        width: 100%;
                        height: 100%;
                        object-fit: contain;
                        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
                        transition: transform 0.3s ease;
                    " class="service-png-icon">
                </div>
                <h3 class="card-title">3D AI Product Mockups</h3>
                <p class="card-desc">Advanced Canva design combined with state-of-the-art AI-driven 3D renders from your live product photos to create breathtaking assets.</p>
                <a href="/services" class="card-link">Learn More <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
            </div>
        </div>
        
        <div class="text-center" style="margin-top: 50px;" data-reveal>
            <a href="/services" class="btn btn-secondary">View All 7 Core Services</a>
        </div>
    </div>
</section>

<!-- About Section Teaser -->
<section class="section-padding" style="background-color: var(--color-ink); border-top: 1px solid var(--glass-border); border-bottom: 1px solid var(--glass-border);">
    <div class="container grid grid-2" style="align-items: center;">
        <div class="about-text" data-reveal>
            <span class="hero-tagline" style="box-shadow: none;">Our Philosophy</span>
            <h2 style="margin-top: 15px; margin-bottom: 20px;">Why Partners Choose Great Endured Technology</h2>
            <p>GET is built on the foundation of enduring partnerships and high-standard design. We do not believe in short-cuts or cookie-cutter solutions. We build applications with strict governance, premium performance, and state-of-the-art interactive motions.</p>
            <p>Our focus is to maximize the performance of your client-side devices while keeping server payloads minimal, ensuring ultra-fast execution times even on standard shared hosting environments.</p>
            <a href="/about" class="btn btn-primary" style="margin-top: 20px;">Read Our Story</a>
        </div>
        <div class="about-graphics flex-center" data-reveal data-delay="300" style="position: relative; height: 350px;">
            <div style="width: 250px; height: 250px; background: linear-gradient(135deg, var(--color-blue-core), var(--color-blue-glow)); border-radius: 24px; transform: rotate(-6deg); box-shadow: var(--glow-blue); position: absolute; z-index: 2; opacity: 0.85;"></div>
            <div class="pulse-glow-cyan" style="width: 230px; height: 230px; background: var(--glass-fill); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(0, 212, 255, 0.3); border-radius: 24px; transform: rotate(12deg); position: absolute; z-index: 3;">
                <div class="flex-center" style="height: 100%; flex-direction: column; gap: 10px;">
                    <span style="font-size: 3rem; font-weight: 800; color: var(--color-white);">GET.</span>
                    <span style="font-size: 0.8rem; letter-spacing: 0.2em; color: var(--color-cyan-pulse); text-transform: uppercase;">Est. 2026</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section-padding text-center" style="position: relative;">
    <div class="hero-bg-glow" style="width: 400px; height: 400px; top: 50%;"></div>
    <div class="container" style="position: relative; z-index: 10;" data-reveal>
        <h2 style="margin-bottom: 20px; font-size: 2.5rem;">Have a Project in Mind?</h2>
        <p style="max-width: 600px; margin: 0 auto 30px auto; color: var(--color-fog);">Let's discuss how we can transform your ideas into an enduring digital product. Request a quote or get in touch for a free project assessment.</p>
        <a href="/contact" class="btn btn-primary">Start a Conversation</a>
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
