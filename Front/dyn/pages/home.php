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
                    <div class="card slider-card hud-card" data-index="<?php echo $index; ?>">
                        <!-- HUD Corner bracket elements -->
                        <span class="hud-bracket hud-tl"></span>
                        <span class="hud-bracket hud-tr"></span>
                        <span class="hud-bracket hud-bl"></span>
                        <span class="hud-bracket hud-br"></span>
                        
                        <!-- Pulsing Tech Status indicator -->
                        <div class="hud-status">
                            <span class="hud-status-dot"></span>
                            <span class="hud-status-text">SYS ACTIVE // NODE_<?php echo sprintf("%02d", $index + 1); ?></span>
                        </div>

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
                        
                        <div class="hud-icon-container">
                            <div class="hud-icon-glow"></div>
                            <?php echo getServiceIconHtml($s['icon'] ?? ''); ?>
                        </div>
                        
                        <h3 class="card-title hud-title"><?php echo htmlspecialchars($s['name']); ?></h3>
                        <p class="card-desc hud-desc"><?php echo htmlspecialchars($s['description']); ?></p>
                        
                        <div style="flex-grow: 1;"></div>
                        
                        <a href="/contact?service=<?php echo urlencode($s['id']); ?>" class="hud-cta">
                            <span>Inquire Service</span>
                            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 14px; height: 14px; transition: transform 0.3s ease;"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button class="slider-nav-btn next-btn" aria-label="Next Slide">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Slider Dots Indicators -->
            <div class="slider-dots-container">
                <?php foreach ($services as $index => $s): ?>
                    <button class="slider-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>" aria-label="Go to slide <?php echo $index + 1; ?>"></button>
                <?php endforeach; ?>
            </div>
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
/* HUD Premium Card Redesign */
.hud-card {
    position: relative;
    background: linear-gradient(135deg, rgba(17, 22, 34, 0.4) 0%, rgba(9, 13, 22, 0.6) 100%) !important;
    backdrop-filter: blur(16px) !important;
    -webkit-backdrop-filter: blur(16px) !important;
    border: 1px solid rgba(255, 255, 255, 0.04) !important;
    border-radius: 16px;
    padding: 35px 30px;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
    overflow: hidden;
}
.hud-card:hover {
    transform: translateY(-8px) !important;
    border-color: rgba(0, 212, 255, 0.25) !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5), 0 0 25px rgba(0, 212, 255, 0.08) !important;
}

/* Corner HUD Brackets */
.hud-bracket {
    position: absolute;
    width: 12px;
    height: 12px;
    border-color: rgba(0, 212, 255, 0.15);
    border-style: solid;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    opacity: 0.5;
}
.hud-tl { top: 15px; left: 15px; border-width: 2px 0 0 2px; }
.hud-tr { top: 15px; right: 15px; border-width: 2px 2px 0 0; }
.hud-bl { bottom: 15px; left: 15px; border-width: 0 0 2px 2px; }
.hud-br { bottom: 15px; right: 15px; border-width: 0 2px 2px 0; }

.hud-card:hover .hud-bracket {
    opacity: 1;
    border-color: var(--color-cyan-pulse);
}
.hud-card:hover .hud-tl { top: 10px; left: 10px; }
.hud-card:hover .hud-tr { top: 10px; right: 10px; }
.hud-card:hover .hud-bl { bottom: 10px; left: 10px; }
.hud-card:hover .hud-br { bottom: 10px; right: 10px; }

/* Status Dot Indicator */
.hud-status {
    position: absolute;
    top: 20px;
    right: 25px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.62rem;
    font-family: monospace;
    color: var(--color-mist);
    letter-spacing: 0.1em;
}
.hud-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #10b981;
    box-shadow: 0 0 8px #10b981;
    animation: hud-status-pulse 2s infinite;
}
.hud-status-text {
    font-size: 0.6rem;
    font-weight: 600;
}
@keyframes hud-status-pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6); }
    70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Floating Icon Orbs */
.hud-icon-container {
    position: relative;
    display: inline-block;
    z-index: 2;
}
.hud-icon-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90px;
    height: 90px;
    background: radial-gradient(circle, rgba(0, 212, 255, 0.15) 0%, transparent 70%);
    z-index: 1;
    opacity: 0.5;
    transition: opacity 0.3s ease;
}
.hud-card:hover .hud-icon-glow {
    opacity: 1;
}
.hud-card .service-png-wrapper {
    background: rgba(255, 255, 255, 0.01) !important;
    border-color: rgba(255, 255, 255, 0.05) !important;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
}
.hud-card:hover .service-png-wrapper {
    transform: translateY(-4px) scale(1.05);
    border-color: rgba(0, 212, 255, 0.3) !important;
    background: rgba(0, 212, 255, 0.03) !important;
    box-shadow: 0 8px 24px rgba(0, 212, 255, 0.15);
}
.hud-card:hover .service-png-icon {
    transform: scale(1.1) rotate(2deg) !important;
    filter: drop-shadow(0 6px 12px rgba(0, 212, 255, 0.3)) !important;
}

/* Titles and description overrides */
.hud-title {
    font-size: 1.2rem !important;
    margin: 15px 0 10px 0 !important;
    color: var(--color-white) !important;
    font-weight: 700;
    transition: color 0.3s ease;
    z-index: 2;
}
.hud-card:hover .hud-title {
    color: var(--color-cyan-pulse) !important;
}
.hud-desc {
    color: var(--color-fog) !important;
    font-size: 0.85rem !important;
    line-height: 1.6 !important;
    margin-bottom: 20px !important;
    z-index: 2;
}

/* Premium Tech Link Button */
.hud-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-cyan-pulse);
    text-decoration: none;
    transition: all 0.3s ease;
    z-index: 2;
    margin-top: 10px;
}
.hud-cta:hover {
    color: var(--color-white);
    text-shadow: 0 0 8px rgba(0, 212, 255, 0.6);
}
.hud-cta:hover svg {
    transform: translateX(4px);
    color: var(--color-cyan-pulse);
}

/* Slider Layout & Progress Toggles */
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
    scrollbar-width: none;
}
.services-slider-track::-webkit-scrollbar {
    display: none;
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
.prev-btn { left: -10px; }
.next-btn { right: -10px; }

/* Progress Dots Pagination */
.slider-dots-container {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 30px;
}
.slider-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.05);
    padding: 0;
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.slider-dot.active {
    width: 24px;
    border-radius: 4px;
    background: var(--color-cyan-pulse);
    border-color: var(--color-cyan-pulse);
    box-shadow: 0 0 8px rgba(0, 212, 255, 0.4);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.services-slider-track');
    const container = document.querySelector('.services-slider-container');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const dots = document.querySelectorAll('.slider-dot');

    if (track && prevBtn && nextBtn) {
        let autoplayInterval;
        let isPaused = false;

        const getScrollAmount = () => {
            const card = track.querySelector('.slider-card');
            return card ? card.offsetWidth + 30 : 350; // card width + gap
        };

        const updateDots = () => {
            const cardWidth = getScrollAmount();
            // Calculate active index based on scroll position
            const scrollLeft = track.scrollLeft;
            const activeIndex = Math.round(scrollLeft / cardWidth);
            
            dots.forEach((dot, index) => {
                if (index === activeIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        };

        const toggleButtons = () => {
            const scrollLeft = track.scrollLeft;
            const maxScroll = track.scrollWidth - track.clientWidth;
            
            prevBtn.style.opacity = scrollLeft <= 5 ? '0.3' : '1';
            prevBtn.style.pointerEvents = scrollLeft <= 5 ? 'none' : 'auto';
            
            nextBtn.style.opacity = scrollLeft >= maxScroll - 5 ? '0.3' : '1';
            nextBtn.style.pointerEvents = scrollLeft >= maxScroll - 5 ? 'none' : 'auto';
        };

        const slideNext = () => {
            const maxScroll = track.scrollWidth - track.clientWidth;
            if (track.scrollLeft >= maxScroll - 10) {
                // Wrap around to start smoothly
                track.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                track.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
            }
        };

        const startAutoplay = () => {
            stopAutoplay(); // clear existing
            autoplayInterval = setInterval(() => {
                if (!isPaused) {
                    slideNext();
                }
            }, 3500);
        };

        const stopAutoplay = () => {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
            }
        };

        // Navigation actions
        prevBtn.addEventListener('click', () => {
            track.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', slideNext);

        // Dot navigation clicks
        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                const targetIndex = parseInt(dot.getAttribute('data-slide'));
                track.scrollTo({
                    left: targetIndex * getScrollAmount(),
                    behavior: 'smooth'
                });
            });
        });

        // Hover events to pause/resume autoplay
        container.addEventListener('mouseenter', () => { isPaused = true; });
        container.addEventListener('mouseleave', () => { isPaused = false; });
        container.addEventListener('touchstart', () => { isPaused = true; }, { passive: true });

        // Scroll event updates UI indicators
        track.addEventListener('scroll', () => {
            updateDots();
            toggleButtons();
        });

        window.addEventListener('resize', () => {
            updateDots();
            toggleButtons();
        });

        // Initialize
        setTimeout(() => {
            updateDots();
            toggleButtons();
            startAutoplay();
        }, 500);
    }
});
</script>
