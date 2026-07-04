<?php
/**
 * Great Endured Technology — Admin Pages CMS Template
 * Governed by RBP (AGENTS.md)
 */
declare(strict_types=1);
?>
<div style="margin-bottom: 30px;" class="flex-between">
    <div>
        <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">Page Content Management</h2>
        <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Create, edit, or delete dynamic HTML content pages.</p>
    </div>
    <a href="/admin/pages?action=write" class="btn btn-primary" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #10b981; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; height: 38px; line-height: 20px;">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Add New Page
    </a>
</div>

<div class="card" style="padding: 30px; border-radius: 16px;">
    <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px;">
        Dynamic Pages
    </h3>

    <?php if (empty($pages)): ?>
        <p style="color: var(--color-mist); text-align: center; padding: 40px 0; margin: 0;">No dynamic pages found.</p>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <?php foreach ($pages as $p): ?>
                <div class="flex-between" id="page-row-<?php echo htmlspecialchars($p['slug']); ?>" 
                     style="background: rgba(255,255,255,0.01); padding: 15px 20px; border-radius: 10px; border: 1px solid var(--glass-border); align-items: center; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--color-cyan-pulse); box-shadow: 0 0 6px var(--color-cyan-pulse);"></div>
                        <div>
                            <h4 style="margin: 0; color: var(--color-white); font-size: 1rem;"><?php echo htmlspecialchars($p['title']); ?></h4>
                            <a href="/p/<?php echo htmlspecialchars($p['slug']); ?>" target="_blank" style="font-size: 0.85rem; color: var(--color-cyan-pulse); text-decoration: none;">
                                /p/<?php echo htmlspecialchars($p['slug']); ?> ↗
                            </a>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 8px;">
                        <a href="/admin/pages?action=write&slug=<?php echo htmlspecialchars($p['slug']); ?>" class="btn btn-secondary btn-sm" 
                           style="border-color: rgba(59, 130, 246, 0.2); color: var(--color-cyan-pulse); font-size: 0.8rem; padding: 6px 12px; height: auto; text-decoration: none; display: inline-block;">
                            Edit
                        </a>
                        <button class="btn btn-secondary btn-sm delete-page-btn" 
                                data-slug="<?php echo htmlspecialchars($p['slug']); ?>"
                                style="border-color: rgba(239, 68, 68, 0.2); color: #f87171; font-size: 0.8rem; padding: 6px 12px; height: auto;">
                            Delete
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = "<?php echo csrf_token(); ?>";

    // Delete page
    document.querySelectorAll('.delete-page-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const confirmed = await window.customConfirm('Delete Page', 'Are you sure you want to delete this dynamic page permanently? This action cannot be undone.');
            if (!confirmed) {
                return;
            }

            const slug = btn.getAttribute('data-slug');
            const row = document.getElementById(`page-row-${slug}`);

            btn.disabled = true;
            btn.textContent = 'Deleting...';

            const formData = new FormData();
            formData.append('slug', slug);
            formData.append('csrf_token', csrfToken);

            try {
                const response = await fetch('/admin/pages/delete', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(10px)';
                    setTimeout(() => row.remove(), 300);
                } else {
                    throw new Error(data.message || 'Deletion failed.');
                }
            } catch (err) {
                alert(err.message || 'Error occurred while deleting.');
                btn.disabled = false;
                btn.textContent = 'Delete';
            }
        });
    });
});
</script>
