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
                    <div class="team-card-wrapper" style="perspective: 1000px;" data-reveal data-delay="<?php echo ($index % 3) * 150; ?>">
                        <div class="card team-3d-card" style="padding: 0; overflow: hidden; transform-style: preserve-3d; transition: transform 0.15s ease, box-shadow 0.3s ease; background: rgba(255, 255, 255, 0.015); border: 1px solid var(--glass-border); border-radius: 20px;">
                            
                            <!-- Profile Image with Overlay Role Highlight -->
                            <div style="position: relative; height: 260px; overflow: hidden; background: linear-gradient(135deg, var(--color-blue-deep) 0%, var(--color-void) 100%); display: flex; align-items: center; justify-content: center; border-bottom: 1px solid var(--glass-border); transform-style: preserve-3d;">
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

                                <!-- WhatsApp Contact button (3D popout) -->
                                <?php if (!empty($m['phone'])): ?>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $m['phone']); ?>" 
                                       target="_blank" 
                                       class="btn btn-secondary team-3d-card-floating-badge" 
                                       style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; padding: 12px; border-radius: 10px; background: rgba(74, 222, 128, 0.05); border-color: rgba(74, 222, 128, 0.15); color: #4ade80; font-size: 0.88rem; font-weight: 700; text-decoration: none; transform: translateZ(40px); transition: all 0.3s ease;">
                                        <svg style="width: 18px; height: 18px; fill: currentColor;" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.863-9.864.001-2.637-1.03-5.114-2.905-6.989-1.874-1.874-4.35-2.903-6.985-2.904-5.442 0-9.87 4.42-9.873 9.863-.001 1.748.472 3.447 1.378 4.969l-.982 3.585 3.678-.965zm12.387-7.81c-.267-.134-1.579-.78-1.823-.868-.243-.09-.419-.134-.596.135-.176.268-.68.868-.834 1.046-.153.178-.308.2-.575.066-.267-.134-1.127-.415-2.148-1.327-.79-.705-1.325-1.577-1.48-1.845-.155-.268-.016-.413.118-.546.12-.12.267-.312.4-.469.135-.156.179-.267.269-.446.09-.178.045-.334-.022-.469-.067-.134-.596-1.437-.817-1.97-.215-.522-.451-.452-.619-.452-.16-.003-.343-.003-.526-.003-.183 0-.48.068-.73.344-.25.276-.955.934-.955 2.28 0 1.345.98 2.64 1.116 2.82.137.178 1.928 2.946 4.672 4.129.653.28 1.162.448 1.56.574.656.208 1.253.179 1.725.108.527-.078 1.579-.645 1.801-1.238.222-.593.222-1.101.155-1.209-.067-.108-.244-.176-.511-.31z"/>
                                        </svg>
                                        Chat on WhatsApp
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 3D Parallax Hover Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
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
