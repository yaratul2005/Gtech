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
</div>

<div class="grid grid-3" style="align-items: start; gap: 30px;">
    <!-- Add new showcase item -->
    <div style="grid-column: span 1;">
        <div class="card" style="padding: 25px; border-radius: 16px;">
            <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px; color: var(--color-cyan-pulse);">
                Add Case Study
            </h3>

            <div class="form-alert form-alert-success" id="add-success" style="font-size: 0.8rem; padding: 10px;"></div>
            <div class="form-alert form-alert-error" id="add-error" style="font-size: 0.8rem; padding: 10px;"></div>

            <form id="portfolio-add-form" action="/admin/portfolio/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

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
                    <textarea name="description" class="form-input" style="min-height: 80px;" placeholder="Summary of the scope and deliverables..." required></textarea>
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

                <button type="submit" class="btn btn-primary btn-full" id="add-submit-btn">Publish Showcase</button>
            </form>
        </div>
    </div>

    <!-- Listings -->
    <div style="grid-column: span 2;">
        <div class="card" style="padding: 25px; border-radius: 16px;">
            <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px;">
                Active Showcases
            </h3>

            <?php if (empty($portfolio)): ?>
                <p style="color: var(--color-mist); text-align: center; padding: 40px 0; margin: 0;">No active portfolio items found.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <?php foreach ($portfolio as $item): ?>
                        <div class="flex-between" id="portfolio-item-<?php echo htmlspecialchars($item['id']); ?>" 
                             style="background: rgba(255,255,255,0.02); padding: 15px 20px; border-radius: 10px; border: 1px solid var(--glass-border); align-items: center; gap: 20px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--color-cyan-pulse); box-shadow: 0 0 6px var(--color-cyan-pulse);"></div>
                                <div>
                                    <h4 style="margin: 0; color: var(--color-white); font-size: 1rem;"><?php echo htmlspecialchars($item['title']); ?></h4>
                                    <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--color-cyan-pulse); font-weight: 600;"><?php echo htmlspecialchars($item['category']); ?></span>
                                </div>
                            </div>
                            
                            <button class="btn btn-secondary btn-sm delete-portfolio-btn" 
                                    data-id="<?php echo htmlspecialchars($item['id']); ?>"
                                    style="border-color: rgba(239, 68, 68, 0.2); color: #f87171; font-size: 0.8rem; padding: 6px 12px;">
                                Remove Project
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
    const addForm = document.getElementById('portfolio-add-form');
    const successAlert = document.getElementById('add-success');
    const errorAlert = document.getElementById('add-error');
    const submitBtn = document.getElementById('add-submit-btn');
    const csrfToken = "<?php echo csrf_token(); ?>";

    // Add portfolio item
    addForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        successAlert.style.display = 'none';
        errorAlert.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Publishing...';

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
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Publish Showcase';
        }
    });

    // Delete portfolio item
    document.querySelectorAll('.delete-portfolio-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to delete this portfolio project from the dynamic showcase?')) {
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
