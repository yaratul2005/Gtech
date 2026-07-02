<?php
/**
 * Great Endured Technology — Team Showcase Page Template
 * Governed by RBP (AGENTS.md) & Web App Development Guidelines
 */

declare(strict_types=1);

// Load team members from Vault database
$teamFile = __DIR__ . '/../../../Vault/content/team.json';
$team = [];
if (file_exists($teamFile)) {
    $team = json_decode((string)file_get_contents($teamFile), true) ?: [];
}
?>

<section class="section-padding" style="padding-top: calc(var(--header-height) + 60px); overflow: hidden;">
    <div class="container">
        <div class="section-header" data-reveal>
            <span class="hero-tagline" style="box-shadow: none;">Our Workforce</span>
            <h1 style="margin-top: 15px;">Meet Our Experts</h1>
            <p>A team of visionaries, developers, and designers crafting next-gen digital solutions.</p>
        </div>

        <div class="grid grid-3" style="margin-top: 60px; gap: 40px;">
            <?php if (empty($team)): ?>
                <div style="grid-column: span 3; text-align: center; padding: 50px 0; color: var(--color-fog);">
                    <p>No team members showcased at this time. Please check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach ($team as $index => $m): ?>
                    <!-- Person Schema for Search Indexing -->
                    <script type="application/ld+json">
                    {
                      "@context": "https://schema.org",
                      "@type": "Person",
                      "name": "<?php echo htmlspecialchars($m['name']); ?>",
                      "jobTitle": "<?php echo htmlspecialchars($m['role']); ?>",
                      "description": "<?php echo htmlspecialchars($m['bio']); ?>",
                      "image": "<?php echo htmlspecialchars(getenv('APP_URL') ?: 'https://greatentech.com') . htmlspecialchars($m['image']); ?>",
                      "url": "<?php echo htmlspecialchars(getenv('APP_URL') ?: 'https://greatentech.com'); ?>/team#member-<?php echo htmlspecialchars($m['id']); ?>",
                      "knowsAbout": <?php echo json_encode(array_map('trim', explode(',', $m['skills']))); ?><?php if (!empty($m['phone'])): ?>,
                      "telephone": "<?php echo htmlspecialchars($m['phone']); ?>"
                      <?php endif; ?>
                    }
                    </script>

                    <div class="team-card-wrapper" id="member-<?php echo htmlspecialchars($m['id']); ?>" style="perspective: 1000px;" data-reveal data-delay="<?php echo ($index % 3) * 150; ?>">
                        <div class="card team-3d-card" style="padding: 0; overflow: hidden; transform-style: preserve-3d; transition: transform 0.15s ease, box-shadow 0.3s ease; background: <?php echo htmlspecialchars($m['theme'] ?? 'rgba(255, 255, 255, 0.015)'); ?>; border: 1px solid var(--glass-border); border-radius: 20px;">
                            
                            <!-- Profile Image with Overlay Role Highlight -->
                            <div style="position: relative; height: 260px; overflow: hidden; background: <?php echo htmlspecialchars($m['theme'] ?? 'linear-gradient(135deg, var(--color-blue-deep) 0%, var(--color-void) 100%)'); ?>; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid var(--glass-border); transform-style: preserve-3d;">
                                <div style="position: absolute; inset: 0; background-image: url('<?php echo htmlspecialchars($m['image']); ?>'); background-size: cover; background-position: center; filter: brightness(0.85); transition: transform 0.5s ease;" class="team-profile-pic"></div>
                                
                                <!-- Highlighted Role Badge (3D popout) -->
                                <span class="team-3d-card-floating-badge" style="position: absolute; bottom: 15px; left: 15px; font-size: 0.78rem; text-transform: uppercase; font-weight: 800; letter-spacing: 0.08em; background: rgba(9, 13, 22, 0.75); color: var(--color-cyan-pulse); border: 1px solid rgba(59, 130, 246, 0.35); padding: 6px 14px; border-radius: 30px; backdrop-filter: blur(12px); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3); transform: translateZ(45px); pointer-events: none;">
                                    <?php echo htmlspecialchars($m['role']); ?>
                                </span>
                            </div>

                            <!-- Bio & Skills Content -->
                            <div style="padding: 30px; display: flex; flex-direction: column; transform-style: preserve-3d;">
                                <h3 class="team-3d-card-floating-title" style="font-size: 1.35rem; margin-top: 0; margin-bottom: 10px; font-family: 'Outfit', sans-serif; color: var(--color-white); font-weight: 700; transform: translateZ(35px);">
                                    <?php echo htmlspecialchars($m['name']); ?>
                                </h3>
                                
                                <p class="team-3d-card-floating-text" style="font-size: 0.9rem; color: var(--color-fog); line-height: 1.6; margin-bottom: 20px; min-height: 70px; transform: translateZ(25px);">
                                    <?php echo htmlspecialchars($m['bio']); ?>
                                </p>

                                <!-- Skills Listing -->
                                <?php if (!empty($m['skills'])): ?>
                                    <div class="team-3d-card-floating-text" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 25px; transform: translateZ(20px);">
                                        <?php foreach (explode(',', $m['skills']) as $skill): ?>
                                            <span style="font-size: 0.72rem; font-weight: 600; background: rgba(255, 255, 255, 0.03); color: var(--color-mist); border: 1px solid rgba(255, 255, 255, 0.05); padding: 3px 10px; border-radius: 20px;">
                                                <?php echo htmlspecialchars(trim($skill)); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Contact & Sharing CTA Bar (3D popout) -->
                                <div style="display: flex; gap: 10px; margin-top: auto; transform: translateZ(40px); width: 100%;">
                                    <?php if (!empty($m['phone'])): ?>
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $m['phone']); ?>" 
                                           target="_blank" 
                                           class="btn btn-secondary team-3d-card-floating-badge" 
                                           style="flex-grow: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; border-radius: 10px; background: rgba(74, 222, 128, 0.05); border-color: rgba(74, 222, 128, 0.15); color: #4ade80; font-size: 0.88rem; font-weight: 700; text-decoration: none; transition: all 0.3s ease;">
                                            <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.863-9.864.001-2.637-1.03-5.114-2.905-6.989-1.874-1.874-4.35-2.903-6.985-2.904-5.442 0-9.87 4.42-9.873 9.863-.001 1.748.472 3.447 1.378 4.969l-.982 3.585 3.678-.965zm12.387-7.81c-.267-.134-1.579-.78-1.823-.868-.243-.09-.419-.134-.596.135-.176.268-.68.868-.834 1.046-.153.178-.308.2-.575.066-.267-.134-1.127-.415-2.148-1.327-.79-.705-1.325-1.577-1.48-1.845-.155-.268-.016-.413.118-.546.12-.12.267-.312.4-.469.135-.156.179-.267.269-.446.09-.178.045-.334-.022-.469-.067-.134-.596-1.437-.817-1.97-.215-.522-.451-.452-.619-.452-.16-.003-.343-.003-.526-.003-.183 0-.48.068-.73.344-.25.276-.955.934-.955 2.28 0 1.345.98 2.64 1.116 2.82.137.178 1.928 2.946 4.672 4.129.653.28 1.162.448 1.56.574.656.208 1.253.179 1.725.108.527-.078 1.579-.645 1.801-1.238.222-.593.222-1.101.155-1.209-.067-.108-.244-.176-.511-.31z"/>
                                            </svg>
                                            WhatsApp
                                        </a>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-secondary share-btn team-3d-card-floating-badge" 
                                            data-share-name="<?php echo htmlspecialchars($m['name']); ?>"
                                            data-share-url="<?php echo htmlspecialchars(getenv('APP_URL') ?: 'https://greatentech.com'); ?>/team#member-<?php echo htmlspecialchars($m['id']); ?>"
                                            style="flex-shrink: 0; width: 45px; display: flex; align-items: center; justify-content: center; padding: 12px 0; border-radius: 10px; background: rgba(0, 242, 254, 0.05); border-color: rgba(0, 242, 254, 0.15); color: #00f2fe; cursor: pointer; transition: all 0.3s ease; transform: translateZ(40px);">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Toast Alert for Copying Links -->
<div id="share-toast" style="
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: rgba(9, 13, 22, 0.95);
    border: 1px solid var(--color-cyan-pulse);
    box-shadow: 0 0 20px rgba(0, 242, 254, 0.25);
    color: var(--color-white);
    padding: 12px 24px;
    border-radius: 8px;
    font-family: 'Outfit', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    z-index: 999999;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.3s ease, transform 0.3s ease;
    pointer-events: none;
">
    Link copied to clipboard!
</div>

<!-- Targeted card highlight CSS -->
<style>
.team-card-wrapper {
    transition: transform 0.4s ease;
}
.team-card-wrapper:target {
    scroll-margin-top: 120px;
}
.team-card-wrapper:target .team-3d-card {
    border-color: var(--color-cyan-pulse) !important;
    box-shadow: 0 0 30px rgba(0, 242, 254, 0.25) !important;
    transform: scale(1.02) !important;
}
</style>

<!-- 3D Parallax Hover Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // Share buttons handler
    const toast = document.getElementById('share-toast');
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.stopPropagation();
            const name = btn.getAttribute('data-share-name');
            const url = btn.getAttribute('data-share-url');

            if (navigator.share) {
                try {
                    await navigator.share({
                        title: `Meet ${name} | Great Endured Technology`,
                        text: `Check out ${name}'s profile at Great Endured Technology!`,
                        url: url
                    });
                    return;
                } catch (err) {
                    // Fallback to clipboard if sharing got canceled
                }
            }

            // Clipboard fallback
            navigator.clipboard.writeText(url).then(() => {
                if (toast) {
                    toast.textContent = `Link copied for ${name}!`;
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateY(0)';
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(20px)';
                    }, 2500);
                }
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        });
    });

    // Skip 3D tilting on mobile devices for performance and touch friendliness
    if (isMobile) return;

    document.querySelectorAll('.team-3d-card').forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const xc = rect.width / 2;
            const yc = rect.height / 2;
            
            // Calculate tilt angle (max 12 degrees)
            const angleX = (yc - y) / 12;
            const angleY = (x - xc) / 12;
            
            // Apply 3D rotation transform
            card.style.transform = `rotateX(${angleX}deg) rotateY(${angleY}deg)`;
            card.style.boxShadow = `${-angleY * 1.5}px ${angleX * 1.5}px 35px rgba(0, 242, 254, 0.12), 0 15px 30px rgba(0,0,0,0.5)`;
            
            // Zoom profile pic slightly on hover
            const pic = card.querySelector('.team-profile-pic');
            if (pic) {
                pic.style.transform = 'scale(1.08)';
            }
        });
        
        card.addEventListener('mouseleave', () => {
            // Smoothly reset tilt transformation
            card.style.transform = 'rotateX(0deg) rotateY(0deg)';
            card.style.boxShadow = 'none';
            
            const pic = card.querySelector('.team-profile-pic');
            if (pic) {
                pic.style.transform = 'scale(1)';
            }
        });
    });
});
</script>
