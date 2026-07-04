<?php
/**
 * Great Endured Technology — Home Page Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

// Load dynamic services database from Vault
$servicesJson = json_decode((string)file_get_contents(__DIR__ . '/../../../Vault/content/services.json'), true);
$services = $servicesJson['services'] ?? [];

// Helper function to map icons to custom PNGs or fallbacks
if (!function_exists('getServiceIconHtml')) {
    function getServiceIconHtml(string $icon): string
    {
        $pngMap = [
            'wordpress' => '/Vault/assets/wordpress.png',
            'code' => '/Vault/assets/php.png',
            'trending-up' => '/Vault/assets/seo.png',
            'megaphone' => '/Vault/assets/ads.png',
            'cube' => '/Vault/assets/3D.png',
            'shopping-bag' => '/Vault/assets/woo.png',
            'cloudflare' => '/Vault/assets/flare.png',
        ];

        if (isset($pngMap[$icon])) {
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

        $svg = '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>';
        return '<div class="card-icon" style="margin-bottom: 25px;">' . $svg . '</div>';
    }
}
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

<!-- Services Preview Section (Horizontal Slider) -->
<section class="section-padding" style="position: relative; overflow: hidden;">
    <div class="container">
        <div class="section-header" data-reveal>
            <h2>Elite Services We Provide</h2>
            <p>We combine cutting-edge technology and beautiful aesthetics to deliver outstanding business results.</p>
        </div>
        
        <div class="services-slider-container" data-reveal data-delay="100">
            <!-- Navigation Buttons -->
            <button class="slider-nav-btn prev-btn" aria-label="Previous Slide">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            
            <div class="services-slider-track">
                <?php foreach ($services as $index => $s): ?>
                    <div class="card slider-card">
                        <!-- Google Search JSON-LD Structured Data for Service -->
                        <script type="application/ld+json">
                        {
                          "@context": "https://schema.org",
                          "@type": "Service",
                          "name": "<?php echo htmlspecialchars($s['name']); ?>",
                          "description": "<?php echo htmlspecialchars($s['description']); ?>",
                          "provider": {
                            "@type": "LocalBusiness",
                            "name": "Great Endured Technology",
                            "image": "https://greatentech.com/Front/static/img/logo.png",
                            "priceRange": "$$",
                            "address": {
                              "@type": "PostalAddress",
                              "addressLocality": "Dhaka",
                              "addressCountry": "BD"
                            }
                          },
                          "areaServed": "Worldwide",
                          "serviceType": "IT & Web Development Services"
                        }
                        </script>
                        
                        <?php echo getServiceIconHtml($s['icon'] ?? ''); ?>
                        <h3 class="card-title"><?php echo htmlspecialchars($s['name']); ?></h3>
                        <p class="card-desc"><?php echo htmlspecialchars($s['description']); ?></p>
                        <a href="/contact?service=<?php echo urlencode($s['id']); ?>" class="card-link">Inquire Service <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button class="slider-nav-btn next-btn" aria-label="Next Slide">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
        
        <div class="text-center" style="margin-top: 40px;" data-reveal>
            <a href="/services" class="btn btn-secondary">View All Dynamic Services</a>
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

/* Slider Scaffolding */
.services-slider-container {
    position: relative;
    width: 100%;
    margin-top: 40px;
    padding: 0 45px;
}
.services-slider-track {
    display: flex;
    gap: 30px;
    overflow-x: auto;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    scroll-snap-type: x mandatory;
    padding: 20px 0;
    scrollbar-width: none; /* Hide scrollbar Firefox */
}
.services-slider-track::-webkit-scrollbar {
    display: none; /* Hide scrollbar Chrome/Safari */
}
.slider-card {
    flex: 0 0 calc(33.333% - 20px);
    scroll-snap-align: start;
    min-width: 320px;
    display: flex;
    flex-direction: column;
    height: auto;
}
@media (max-width: 1024px) {
    .slider-card {
        flex: 0 0 calc(50% - 15px);
    }
}
@media (max-width: 768px) {
    .services-slider-container {
        padding: 0;
    }
    .slider-card {
        flex: 0 0 100%;
    }
    .slider-nav-btn {
        display: none !important;
    }
}
.slider-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(17, 22, 34, 0.85);
    border: 1px solid var(--glass-border);
    color: var(--color-white);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
.slider-nav-btn:hover {
    background: var(--color-cyan-pulse);
    color: var(--color-void);
    border-color: var(--color-cyan-pulse);
    box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
}
.slider-nav-btn svg {
    width: 20px;
    height: 20px;
}
.prev-btn {
    left: -10px;
}
.next-btn {
    right: -10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.services-slider-track');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    if (track && prevBtn && nextBtn) {
        // Scroll amount is width of one card + gap
        const getScrollAmount = () => {
            const card = track.querySelector('.slider-card');
            return card ? card.offsetWidth + 30 : 350;
        };

        prevBtn.addEventListener('click', () => {
            track.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            track.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
        });

        // Toggle buttons visibility based on scroll position
        const toggleButtons = () => {
            const scrollLeft = track.scrollLeft;
            const maxScroll = track.scrollWidth - track.clientWidth;
            
            prevBtn.style.opacity = scrollLeft <= 5 ? '0.3' : '1';
            prevBtn.style.pointerEvents = scrollLeft <= 5 ? 'none' : 'auto';
            
            nextBtn.style.opacity = scrollLeft >= maxScroll - 5 ? '0.3' : '1';
            nextBtn.style.pointerEvents = scrollLeft >= maxScroll - 5 ? 'none' : 'auto';
        };

        track.addEventListener('scroll', toggleButtons);
        window.addEventListener('resize', toggleButtons);
        // Initial check
        setTimeout(toggleButtons, 500);
    }
});
</script>
