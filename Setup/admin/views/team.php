<?php
/**
 * Great Endured Technology — Admin Team CMS Template
 * Governed by RBP (AGENTS.md)
 */
declare(strict_types=1);
?>
<div style="margin-bottom: 30px;" class="flex-between">
    <div>
        <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">Team Management</h2>
        <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Showcase your agency's professional workforce, roles, and skills.</p>
    </div>
    <a href="/admin/team?action=write" class="btn btn-primary" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #10b981; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; height: 38px; line-height: 20px;">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Add Team Member
    </a>
</div>

<div class="card" style="padding: 30px; border-radius: 16px;">
    <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px;">
        Active Team Showcase
    </h3>

    <?php if (empty($team)): ?>
        <p style="color: var(--color-mist); text-align: center; padding: 40px 0; margin: 0;">No team members found.</p>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <?php foreach ($team as $m): ?>
                <div class="flex-between" id="team-row-<?php echo htmlspecialchars($m['id']); ?>" 
                     style="background: rgba(255,255,255,0.01); padding: 20px; border-radius: 12px; border: 1px solid var(--glass-border); align-items: center; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 15px; flex-grow: 1;">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background-image: url('<?php echo htmlspecialchars($m['image']); ?>'); background-size: cover; background-position: center; border: 1px solid var(--glass-border); flex-shrink: 0;"></div>
                        <div style="flex-grow: 1;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <h4 style="margin: 0; color: var(--color-white); font-size: 1.05rem;"><?php echo htmlspecialchars($m['name']); ?></h4>
                                <span style="font-size: 0.75rem; background: rgba(59, 130, 246, 0.1); color: var(--color-cyan-pulse); border: 1px solid rgba(59, 130, 246, 0.2); padding: 2px 8px; border-radius: 4px;">
                                    <?php echo htmlspecialchars($m['role']); ?>
                                </span>
                            </div>
                            <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: var(--color-fog); line-height: 1.4;"><?php echo htmlspecialchars($m['bio']); ?></p>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 8px; flex-shrink: 0;">
                        <a href="/admin/team?action=write&id=<?php echo htmlspecialchars($m['id']); ?>" class="btn btn-secondary btn-sm" 
                           style="border-color: rgba(59, 130, 246, 0.2); color: var(--color-cyan-pulse); font-size: 0.8rem; padding: 6px 12px; height: auto; text-decoration: none; display: inline-block;">
                            Edit
                        </a>
                        <button class="btn btn-secondary btn-sm delete-team-btn" 
                                data-id="<?php echo htmlspecialchars($m['id']); ?>"
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

    // Delete team member
    document.querySelectorAll('.delete-team-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const confirmed = await window.customConfirm('Remove Team Member', 'Are you sure you want to remove this team member permanently?');
            if (!confirmed) {
                return;
            }

            const id = btn.getAttribute('data-id');
            const row = document.getElementById(`team-row-${id}`);

            btn.disabled = true;
            btn.textContent = 'Removing...';

            const formData = new FormData();
            formData.append('id', id);
            formData.append('csrf_token', csrfToken);

            try {
                const response = await fetch('/admin/team/delete', {
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
