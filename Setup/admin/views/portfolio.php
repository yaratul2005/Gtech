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
    <button type="button" class="btn btn-primary" id="open-add-workspace" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #10b981; display: inline-flex; align-items: center; gap: 8px;">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Add Case Study
    </button>
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
                        <button class="btn btn-secondary btn-sm edit-portfolio-btn" 
                                data-id="<?php echo htmlspecialchars($item['id']); ?>"
                                data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                data-category="<?php echo htmlspecialchars($item['category']); ?>"
                                data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                data-theme="<?php echo htmlspecialchars($item['theme']); ?>"
                                data-icon="<?php echo htmlspecialchars($item['icon']); ?>"
                                style="border-color: rgba(59, 130, 246, 0.2); color: var(--color-cyan-pulse); font-size: 0.8rem; padding: 6px 12px; height: auto;">
                            Edit
                        </button>
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

<!-- Full-Page Writing Workspace Panel -->
<div id="workspace-overlay" class="workspace-panel" style="display: none;">
    <div class="workspace-header">
        <h3 id="workspace-title">Add Case Study</h3>
        <button type="button" class="btn btn-secondary workspace-close-btn" id="close-workspace-btn" style="height: 38px; display: inline-flex; align-items: center; padding: 0 16px;">
            ✕ Exit Workspace
        </button>
    </div>
    <div class="workspace-body">
        <div class="card" style="padding: 0px; border-radius: 16px; border: none; background: transparent;">
            <div class="form-alert form-alert-success" id="add-success" style="font-size: 0.82rem; padding: 12px; margin-bottom: 20px;"></div>
            <div class="form-alert form-alert-error" id="add-error" style="font-size: 0.82rem; padding: 12px; margin-bottom: 20px;"></div>

            <form id="portfolio-add-form" action="/admin/portfolio/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="id" id="portfolio-id" value="">

                <div class="form-group">
                    <label class="form-label">Project Title</label>
                    <input type="text" name="title" class="form-input" placeholder="e.g. Acme Tech Portal" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Category Label</label>
                    <input type="text" name="category" class="form-input" placeholder="e.g. Custom PHP App" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Brief Description</label>
                    <textarea name="description" class="form-input" style="min-height: 120px;" placeholder="Summary of the scope and deliverables..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Header Theme (CSS Gradient)</label>
                    <input type="text" name="theme" class="form-input" value="linear-gradient(135deg, #090d16 0%, #0a2540 100%)" required>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="form-label">Vector Icon</label>
                    <select name="icon" class="form-input" style="background-color: var(--color-ink);">
                        <option value="globe">🌐 Globe / Network</option>
                        <option value="document">📄 Document / File</option>
                        <option value="cube">📦 Cube / 3D Asset</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" id="add-submit-btn" style="min-width: 150px;">Publish Showcase</button>
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
    const addForm = document.getElementById('portfolio-add-form');
    const successAlert = document.getElementById('add-success');
    const errorAlert = document.getElementById('add-error');
    const submitBtn = document.getElementById('add-submit-btn');
    const csrfToken = "<?php echo csrf_token(); ?>";

    const workspaceOverlay = document.getElementById('workspace-overlay');
    const openAddWorkspace = document.getElementById('open-add-workspace');
    const closeWorkspaceBtn = document.getElementById('close-workspace-btn');
    const formHeading = document.getElementById('workspace-title');
    const portfolioId = document.getElementById('portfolio-id');

    // Open Workspace for Creating
    openAddWorkspace.addEventListener('click', () => {
        addForm.reset();
        addForm.action = '/admin/portfolio/create';
        portfolioId.value = '';
        formHeading.textContent = 'Add Case Study';
        submitBtn.textContent = 'Publish Showcase';
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
                throw new Error(data.message || 'Publishing failed.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.textContent = addForm.action.includes('update') ? 'Update Showcase' : 'Publish Showcase';
        }
    });

    // Edit portfolio item trigger
    document.querySelectorAll('.edit-portfolio-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            const title = btn.getAttribute('data-title');
            const category = btn.getAttribute('data-category');
            const description = btn.getAttribute('data-description');
            const theme = btn.getAttribute('data-theme');
            const icon = btn.getAttribute('data-icon');

            addForm.querySelector('[name="title"]').value = title;
            addForm.querySelector('[name="category"]').value = category;
            addForm.querySelector('[name="description"]').value = description;
            addForm.querySelector('[name="theme"]').value = theme;
            addForm.querySelector('[name="icon"]').value = icon;

            portfolioId.value = id;
            addForm.action = '/admin/portfolio/update';
            formHeading.textContent = 'Edit Case Study';
            submitBtn.textContent = 'Update Showcase';

            successAlert.style.display = 'none';
            errorAlert.style.display = 'none';

            workspaceOverlay.style.display = 'flex';
            workspaceOverlay.offsetHeight;
            workspaceOverlay.classList.add('show');
        });
    });

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
                btn.textContent = 'Remove Project';
            }
        });
    });
});
</script>
