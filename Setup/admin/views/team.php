<?php
/**
 * Great Endured Technology — Admin Team CMS Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">Team Management</h2>
    <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Showcase your agency's professional workforce, roles, and skills.</p>
</div>

<div class="grid grid-3" style="align-items: start; gap: 30px;">
    <!-- Create Team Member form -->
    <div style="grid-column: span 1;">
        <div class="card" style="padding: 25px; border-radius: 16px;">
            <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px; color: var(--color-cyan-pulse);">
                Add Team Member
            </h3>

            <div class="form-alert form-alert-success" id="add-team-success" style="font-size: 0.8rem; padding: 10px;"></div>
            <div class="form-alert form-alert-error" id="add-team-error" style="font-size: 0.8rem; padding: 10px;"></div>

            <form id="team-add-form" action="/admin/team/create" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. Ratul Chowdhury" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Role / Job Title</label>
                    <input type="text" name="role" class="form-input" placeholder="e.g. Lead Developer" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Skills (Comma-separated)</label>
                    <input type="text" name="skills" class="form-input" placeholder="e.g. PHP, Node.js, AI integration">
                </div>

                <div class="form-group">
                    <label class="form-label">WhatsApp Contact Phone</label>
                    <input type="text" name="phone" class="form-input" placeholder="e.g. +8801700000000">
                    <p style="font-size: 0.7rem; color: var(--color-mist); margin-top: 4px;">Must include country code without spaces.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Upload Profile Photo</label>
                    <input type="file" name="image" class="form-input" accept="image/*" style="border: 1px dashed var(--glass-border); padding: 12px; cursor: pointer;">
                    <p style="font-size: 0.7rem; color: var(--color-mist); margin-top: 4px;">Allowed formats: WebP, PNG, JPG, GIF.</p>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="form-label">Bio (Brief Description)</label>
                    <textarea name="bio" class="form-input" style="min-height: 80px;" placeholder="Describe qualifications and career achievements..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-full" id="add-team-submit">Add Member</button>
            </form>
        </div>
    </div>

    <!-- Active team list -->
    <div style="grid-column: span 2;">
        <div class="card" style="padding: 25px; border-radius: 16px;">
            <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px;">
                Active Team Showcase
            </h3>

            <?php if (empty($team)): ?>
                <p style="color: var(--color-mist); text-align: center; padding: 40px 0; margin: 0;">No team members found.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <?php foreach ($team as $m): ?>
                        <div class="flex-between" id="team-row-<?php echo htmlspecialchars($m['id']); ?>" 
                             style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 12px; border: 1px solid var(--glass-border); align-items: center; gap: 20px;">
                            <div style="display: flex; align-items: center; gap: 15px; flex-grow: 1;">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background-image: url('<?php echo htmlspecialchars($m['image']); ?>'); background-size: cover; background-position: center; border: 1px solid var(--glass-border);"></div>
                                <div style="flex-grow: 1;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <h4 style="margin: 0; color: var(--color-white); font-size: 1.05rem;"><?php echo htmlspecialchars($m['name']); ?></h4>
                                        <span style="font-size: 0.75rem; background: rgba(59, 130, 246, 0.1); color: var(--color-cyan-pulse); border: 1px solid rgba(59, 130, 246, 0.2); padding: 2px 8px; border-radius: 4px;">
                                            <?php echo htmlspecialchars($m['role']); ?>
                                        </span>
                                    </div>
                                    <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: var(--color-fog); line-height: 1.4;"><?php echo htmlspecialchars($m['bio']); ?></p>
                                    <div style="margin-top: 6px; display: flex; flex-wrap: wrap; gap: 6px;">
                                        <?php if (!empty($m['skills'])): ?>
                                            <?php foreach (explode(',', $m['skills']) as $skill): ?>
                                                <span style="font-size: 0.7rem; background: rgba(255,255,255,0.03); color: var(--color-mist); border: 1px solid rgba(255,255,255,0.05); padding: 1px 6px; border-radius: 3px;">
                                                    <?php echo htmlspecialchars(trim($skill)); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <?php if (!empty($m['phone'])): ?>
                                            <span style="font-size: 0.7rem; color: #4ade80; display: inline-flex; align-items: center; gap: 4px;">
                                                WhatsApp: <?php echo htmlspecialchars($m['phone']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <button class="btn btn-secondary btn-sm delete-team-btn" 
                                    data-id="<?php echo htmlspecialchars($m['id']); ?>"
                                    style="border-color: rgba(239, 68, 68, 0.2); color: #f87171; font-size: 0.8rem; padding: 6px 12px; height: fit-content; flex-shrink: 0;">
                                Remove
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addForm = document.getElementById('team-add-form');
    const successAlert = document.getElementById('add-team-success');
    const errorAlert = document.getElementById('add-team-error');
    const submitBtn = document.getElementById('add-team-submit');
    const csrfToken = "<?php echo csrf_token(); ?>";

    // Add team member
    addForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        successAlert.style.display = 'none';
        errorAlert.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Adding...';

        const formData = new FormData(addForm);

        try {
            const response = await fetch(addForm.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                successAlert.textContent = data.message;
                successAlert.style.display = 'block';
                setTimeout(() => location.reload(), 1000);
            } else {
                throw new Error(data.message || 'Adding team member failed.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Member';
        }
    });

    // Delete team member
    document.querySelectorAll('.delete-team-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to remove this team member permanently?')) {
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
                    throw new Error(data.message || 'Removal failed.');
                }
            } catch (err) {
                alert(err.message || 'Error occurred while deleting.');
                btn.disabled = false;
                btn.textContent = 'Remove';
            }
        });
    });
});
</script>
