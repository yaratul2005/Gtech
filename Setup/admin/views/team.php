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
    <button type="button" class="btn btn-primary" id="open-add-workspace" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #10b981; display: inline-flex; align-items: center; gap: 8px;">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Add Team Member
    </button>
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
                            <div style="margin-top: 6px; display: flex; flex-wrap: wrap; gap: 6px;">
                                <?php 
                                $skills = explode(',', $m['skills'] ?? '');
                                foreach ($skills as $skill):
                                    $skill = trim($skill);
                                    if (!empty($skill)):
                                ?>
                                    <span style="font-size: 0.7rem; background: rgba(255,255,255,0.03); color: var(--color-mist); padding: 2px 6px; border-radius: 3px; border: 1px solid rgba(255,255,255,0.05);">
                                        <?php echo htmlspecialchars($skill); ?>
                                    </span>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 8px; flex-shrink: 0;">
                        <button class="btn btn-secondary btn-sm edit-team-btn" 
                                data-id="<?php echo htmlspecialchars($m['id']); ?>"
                                data-name="<?php echo htmlspecialchars($m['name']); ?>"
                                data-role="<?php echo htmlspecialchars($m['role']); ?>"
                                data-skills="<?php echo htmlspecialchars($m['skills'] ?? ''); ?>"
                                data-phone="<?php echo htmlspecialchars($m['phone'] ?? ''); ?>"
                                data-theme="<?php echo htmlspecialchars($m['theme']); ?>"
                                data-bio="<?php echo htmlspecialchars($m['bio']); ?>"
                                style="border-color: rgba(59, 130, 246, 0.2); color: var(--color-cyan-pulse); font-size: 0.8rem; padding: 6px 12px; height: auto;">
                            Edit
                        </button>
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

<!-- Full-Page Writing Workspace Panel -->
<div id="workspace-overlay" class="workspace-panel" style="display: none;">
    <div class="workspace-header">
        <h3 id="workspace-title">Add Team Member</h3>
        <button type="button" class="btn btn-secondary workspace-close-btn" id="close-workspace-btn" style="height: 38px; display: inline-flex; align-items: center; padding: 0 16px;">
            ✕ Exit Workspace
        </button>
    </div>
    <div class="workspace-body">
        <div class="card" style="padding: 0px; border-radius: 16px; border: none; background: transparent;">
            <div class="form-alert form-alert-success" id="add-team-success" style="font-size: 0.82rem; padding: 12px; margin-bottom: 20px;"></div>
            <div class="form-alert form-alert-error" id="add-team-error" style="font-size: 0.82rem; padding: 12px; margin-bottom: 20px;"></div>

            <form id="team-add-form" action="/admin/team/create" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="id" id="member-id" value="">

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
                    <label class="form-label">Card Theme (CSS Gradient)</label>
                    <input type="text" name="theme" class="form-input" value="linear-gradient(135deg, #091a2f 0%, #030508 100%)" required>
                    <p style="font-size: 0.7rem; color: var(--color-mist); margin-top: 4px;">Custom CSS gradient for this person's 3D card background.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Upload Profile Photo</label>
                    <input type="file" name="image" class="form-input" accept="image/*" style="border: 1px dashed var(--glass-border); padding: 12px; cursor: pointer;">
                    <p style="font-size: 0.7rem; color: var(--color-mist); margin-top: 4px;">Allowed formats: WebP, PNG, JPG, GIF. (Leave empty to keep existing on Edit)</p>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="form-label">Bio (Brief Description)</label>
                    <textarea name="bio" class="form-input" style="min-height: 120px;" placeholder="Describe qualifications and career achievements..." required></textarea>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" id="add-team-submit" style="min-width: 150px;">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Workspace Scaffolding */
.workspace-panel {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: #030508;
    background-image: radial-gradient(circle at 50% 0%, var(--color-blue-deep) 0%, #030508 70%);
    z-index: 10000;
    display: flex;
    flex-direction: column;
    opacity: 0;
    transform: scale(0.98);
    transition: opacity 0.3s ease, transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.workspace-panel.show {
    opacity: 1;
    transform: scale(1);
}
.workspace-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    background: rgba(9, 13, 22, 0.85);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}
.workspace-header h3 {
    margin: 0;
    font-size: 1.35rem;
    font-family: 'Outfit', sans-serif;
    color: var(--color-white);
}
.workspace-body {
    flex-grow: 1;
    overflow-y: auto;
    padding: 40px;
    max-width: 1000px;
    width: 100%;
    margin: 0 auto;
    box-sizing: border-box;
}
.workspace-close-btn {
    border-color: rgba(239, 68, 68, 0.2) !important;
    color: #f87171 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addForm = document.getElementById('team-add-form');
    const successAlert = document.getElementById('add-team-success');
    const errorAlert = document.getElementById('add-team-error');
    const submitBtn = document.getElementById('add-team-submit');
    const csrfToken = "<?php echo csrf_token(); ?>";

    const workspaceOverlay = document.getElementById('workspace-overlay');
    const openAddWorkspace = document.getElementById('open-add-workspace');
    const closeWorkspaceBtn = document.getElementById('close-workspace-btn');
    const formHeading = document.getElementById('workspace-title');
    const memberId = document.getElementById('member-id');

    // Open Workspace for Creating
    openAddWorkspace.addEventListener('click', () => {
        addForm.reset();
        addForm.action = '/admin/team/create';
        memberId.value = '';
        formHeading.textContent = 'Add Team Member';
        submitBtn.textContent = 'Add Member';
        successAlert.style.display = 'none';
        errorAlert.style.display = 'none';

        workspaceOverlay.style.display = 'flex';
        workspaceOverlay.offsetHeight;
        workspaceOverlay.classList.add('show');
    });

    // Close Workspace
    closeWorkspaceBtn.addEventListener('click', () => {
        workspaceOverlay.classList.remove('show');
        setTimeout(() => {
            workspaceOverlay.style.display = 'none';
        }, 300);
    });

    // Add/Edit submit handler
    addForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        successAlert.style.display = 'none';
        errorAlert.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

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
                throw new Error(data.message || 'Saving team member failed.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.textContent = addForm.action.includes('update') ? 'Update Member' : 'Add Member';
        }
    });

    // Edit team member trigger
    document.querySelectorAll('.edit-team-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const role = btn.getAttribute('data-role');
            const skills = btn.getAttribute('data-skills');
            const phone = btn.getAttribute('data-phone');
            const theme = btn.getAttribute('data-theme');
            const bio = btn.getAttribute('data-bio');

            addForm.querySelector('[name="name"]').value = name;
            addForm.querySelector('[name="role"]').value = role;
            addForm.querySelector('[name="skills"]').value = skills;
            addForm.querySelector('[name="phone"]').value = phone;
            addForm.querySelector('[name="theme"]').value = theme;
            addForm.querySelector('[name="bio"]').value = bio;

            memberId.value = id;
            addForm.action = '/admin/team/update';
            formHeading.textContent = 'Edit Team Member';
            submitBtn.textContent = 'Update Member';

            successAlert.style.display = 'none';
            errorAlert.style.display = 'none';

            workspaceOverlay.style.display = 'flex';
            workspaceOverlay.offsetHeight;
            workspaceOverlay.classList.add('show');
        });
    });

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
