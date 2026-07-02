<?php
/**
 * Great Endured Technology — Admin Services CMS Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 1.8rem; margin-bottom: 5px;">Services CMS Control</h2>
    <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Dynamically manage your agency's service descriptions and focus details.</p>
</div>

<div class="grid grid-2" style="align-items: start; gap: 30px;">
    <!-- Catalog list -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.25rem; margin-bottom: 20px;">Service Listings</h3>
            <div style="display: flex; flex-direction: column; gap: 10px;" id="services-nav">
                <?php foreach ($services as $i => $s): ?>
                    <button class="admin-sidebar-link <?php echo $i === 0 ? 'active' : ''; ?>" 
                            data-target="service-card-<?php echo htmlspecialchars($s['id']); ?>"
                            style="text-align: left; background: none; border: none; cursor: pointer; width: 100%;">
                        <svg style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 6px; color: var(--color-cyan-pulse);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg><span class="service-label-text"><?php echo htmlspecialchars($s['name']); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Edit Forms -->
    <div>
        <?php foreach ($services as $i => $s): ?>
            <div class="card service-edit-card" 
                 id="service-card-<?php echo htmlspecialchars($s['id']); ?>" 
                 style="padding: 30px; <?php echo $i === 0 ? 'display: block;' : 'display: none;'; ?>">
                
                <h3 style="margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px; color: var(--color-cyan-pulse);">
                    Edit Service: <?php echo htmlspecialchars($s['id']); ?>
                </h3>

                <!-- Feedback alerts -->
                <div class="form-alert form-alert-success" id="success-<?php echo htmlspecialchars($s['id']); ?>"></div>
                <div class="form-alert form-alert-error" id="error-<?php echo htmlspecialchars($s['id']); ?>"></div>

                <form class="service-cms-form" data-id="<?php echo htmlspecialchars($s['id']); ?>" action="/admin/services/update" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($s['id']); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

                    <div class="form-group">
                        <label class="form-label">Service Title</label>
                        <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($s['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-input" style="min-height: 100px;" required><?php echo htmlspecialchars($s['description']); ?></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 30px;">
                        <label class="form-label">Focus & Bullet Details</label>
                        <input type="text" name="focus" class="form-input" value="<?php echo htmlspecialchars($s['focus']); ?>" required>
                        <p style="font-size: 0.75rem; color: var(--color-mist); margin-top: 5px;">Summary highlights (e.g. "Bespoke theme widgets, sub-second optimizations").</p>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Save CMS Updates</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Navigation toggle
    const buttons = document.querySelectorAll('#services-nav button');
    const cards = document.querySelectorAll('.service-edit-card');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active states
            buttons.forEach(b => b.classList.remove('active'));
            cards.forEach(c => c.style.display = 'none');

            // Set active target
            btn.classList.add('active');
            const targetId = btn.getAttribute('data-target');
            document.getElementById(targetId).style.display = 'block';
        });
    });

    // 2. Submit forms via AJAX
    document.querySelectorAll('.service-cms-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const id = form.getAttribute('data-id');
            const successAlert = document.getElementById(`success-${id}`);
            const errorAlert = document.getElementById(`error-${id}`);
            const submitBtn = form.querySelector('button[type="submit"]');

            successAlert.style.display = 'none';
            errorAlert.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    successAlert.textContent = data.message || 'CMS updates saved successfully.';
                    successAlert.style.display = 'block';
                    
                    // Update navigation list button text dynamically
                    const navLabel = document.querySelector(`#services-nav button[data-target="service-card-${id}"] .service-label-text`);
                    if (navLabel) {
                        navLabel.textContent = form.querySelector('input[name="name"]').value;
                    }
                } else {
                    throw new Error(data.message || 'Saving updates failed.');
                }
            } catch (err) {
                errorAlert.textContent = err.message || 'Error occurred while saving.';
                errorAlert.style.display = 'block';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save CMS Updates';
            }
        });
    });
});
</script>
