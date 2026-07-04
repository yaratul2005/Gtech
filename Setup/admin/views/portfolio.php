<?php
/**
 * Great Endured Technology — Admin Portfolio CMS Template
 * Governed by RBP (AGENTS.md)
 */
declare(strict_types=1);
?>
<div style="margin-bottom: 30px;" class="flex-between">
    <div>
        <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">Portfolio CMS</h2>
        <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Add, modify, or delete featured agency case studies.</p>
    </div>
    <a href="/admin/portfolio?action=write" class="btn btn-primary" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #10b981; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; height: 38px; line-height: 20px;">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Add Case Study
    </a>
</div>

<div class="card" style="padding: 30px; border-radius: 16px;">
    <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px;">
        Active Showcases
    </h3>

    <?php if (empty($portfolio)): ?>
        <p style="color: var(--color-mist); text-align: center; padding: 40px 0; margin: 0;">No active portfolio items found.</p>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <?php foreach ($portfolio as $item): ?>
                <div class="flex-between" id="portfolio-item-<?php echo htmlspecialchars($item['id']); ?>" 
                     style="background: rgba(255,255,255,0.01); padding: 15px 20px; border-radius: 10px; border: 1px solid var(--glass-border); align-items: center; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--color-cyan-pulse); box-shadow: 0 0 6px var(--color-cyan-pulse);"></div>
                        <div>
                            <h4 style="margin: 0; color: var(--color-white); font-size: 1rem;"><?php echo htmlspecialchars($item['title']); ?></h4>
                            <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--color-cyan-pulse); font-weight: 600;"><?php echo htmlspecialchars($item['category']); ?></span>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 8px;">
                        <a href="/admin/portfolio?action=write&id=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-secondary btn-sm" 
                           style="border-color: rgba(59, 130, 246, 0.2); color: var(--color-cyan-pulse); font-size: 0.8rem; padding: 6px 12px; height: auto; text-decoration: none; display: inline-block;">
                            Edit
                        </a>
                        <button class="btn btn-secondary btn-sm delete-portfolio-btn" 
                                data-id="<?php echo htmlspecialchars($item['id']); ?>"
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

    // Delete portfolio item
    document.querySelectorAll('.delete-portfolio-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const confirmed = await window.customConfirm('Delete Showcase Project', 'Are you sure you want to delete this portfolio project from the dynamic showcase?');
            if (!confirmed) {
                return;
            }

            const id = btn.getAttribute('data-id');
            const row = document.getElementById(`portfolio-item-${id}`);

            btn.disabled = true;
            btn.textContent = 'Deleting...';

            const formData = new FormData();
            formData.append('id', id);
            formData.append('csrf_token', csrfToken);

            try {
                const response = await fetch('/admin/portfolio/delete', {
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
