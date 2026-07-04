<?php
/**
 * Great Endured Technology — Admin Portfolio Editor View
 * Governed by RBP (AGENTS.md)
 */
declare(strict_types=1);

$isEdit = isset($item) && !empty($item);
?>

<div style="padding: 30px 40px; background: #030508; min-height: 100vh;">
    <!-- Top Back Navigation -->
    <div style="margin-bottom: 25px;">
        <a href="/admin/portfolio" style="color: var(--color-cyan-pulse); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
            ← Back to Portfolio List
        </a>
    </div>

    <form id="portfolio-editor-form" action="<?php echo $isEdit ? '/admin/portfolio/update' : '/admin/portfolio/create'; ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="id" value="<?php echo $isEdit ? htmlspecialchars($item['id']) : ''; ?>">

        <!-- 2-Column Editor Canvas Grid -->
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px; align-items: start;">
            
            <!-- Left Main Column (70%) -->
            <div>
                <!-- Project Title input (giant, borderless theme matching classic WP) -->
                <div style="margin-bottom: 25px;">
                    <input type="text" name="title" id="portfolio-title" 
                           placeholder="Enter project title here" 
                           value="<?php echo $isEdit ? htmlspecialchars($item['title']) : ''; ?>"
                           required
                           style="width: 100%; background: transparent; border: none; border-bottom: 1px solid rgba(255,255,255,0.08); font-size: 2.2rem; font-weight: 700; color: #fff; padding: 10px 0; outline: none; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='var(--color-cyan-pulse)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.08)'">
                </div>

                <!-- Case Study Description Canvas -->
                <div class="card" style="padding: 0; border-radius: 12px; margin-bottom: 30px; overflow: hidden; background: #0d1117;">
                    <div style="padding: 12px 20px; background: #161b22; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-between: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; text-transform: uppercase; font-weight: 600; color: var(--color-mist); letter-spacing: 0.05em;">Case Study Description</span>
                    </div>
                    <textarea name="description" id="portfolio-description" style="width:100%; min-height: 250px; background:transparent; border:none; color:#c9d1d9; padding:20px; outline:none; font-family:inherit; font-size:1rem; line-height:1.6;" placeholder="Describe the scope, goals, challenges, and architectural implementation details..."><?php echo $isEdit ? htmlspecialchars($item['description']) : ''; ?></textarea>
                </div>

                <!-- Theme / Styling box -->
                <div class="card" style="padding: 25px; border-radius: 12px;">
                    <h3 style="font-size: 1rem; margin-bottom: 20px; color: var(--color-cyan-pulse); display: flex; align-items: center; gap: 8px;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122A3 3 0 00.47 18.88a9 9 0 1118.06 0 3 3 0 00-9-2.758z"/></svg>
                        Design & Layout Theme
                    </h3>
                    
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Background CSS Gradient</label>
                        <input type="text" name="theme" class="form-input" 
                               value="<?php echo $isEdit ? htmlspecialchars($item['theme']) : 'linear-gradient(135deg, #090d16 0%, #0a2540 100%)'; ?>" required>
                        <p style="font-size: 0.7rem; color: var(--color-mist); margin-top: 5px;">Custom gradient for the case study 3D card background frame.</p>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column (300px) -->
            <div>
                <!-- Publish Control Panel widget -->
                <div class="card" style="padding: 20px; border-radius: 12px; margin-bottom: 25px; background: #0d1117;">
                    <h4 style="margin: 0 0 15px 0; font-size: 0.95rem; text-transform: uppercase; color: var(--color-mist); letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                        Case Study Actions
                    </h4>
                    
                    <div style="font-size: 0.85rem; color: var(--color-mist); display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                        <div>Status: <strong style="color: var(--color-cyan-pulse);"><?php echo $isEdit ? 'Active Showcase' : 'Draft Showcase'; ?></strong></div>
                    </div>

                    <div class="form-alert form-alert-success" id="portfolio-success" style="display: none; padding: 10px; font-size: 0.8rem; margin-bottom: 15px;"></div>
                    <div class="form-alert form-alert-error" id="portfolio-error" style="display: none; padding: 10px; font-size: 0.8rem; margin-bottom: 15px;"></div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="submit" class="btn btn-primary btn-full" id="portfolio-submit-btn">
                            <?php echo $isEdit ? 'Update Showcase' : 'Publish Showcase'; ?>
                        </button>
                        <a href="/admin/portfolio" class="btn btn-secondary btn-full" style="border-color: rgba(255,255,255,0.05); text-align: center; display: block; line-height: 20px;">
                            Exit Workspace
                        </a>
                    </div>
                </div>

                <!-- Showcase Attributes Widget -->
                <div class="card" style="padding: 20px; border-radius: 12px; background: #0d1117;">
                    <h4 style="margin: 0 0 15px 0; font-size: 0.95rem; text-transform: uppercase; color: var(--color-mist); letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                        Showcase Settings
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Category Label</label>
                        <input type="text" name="category" class="form-input" 
                               placeholder="e.g. Custom PHP App" 
                               value="<?php echo $isEdit ? htmlspecialchars($item['category']) : ''; ?>" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Vector Icon</label>
                        <select name="icon" class="form-input" style="background-color: var(--color-ink);">
                            <option value="globe" <?php echo ($isEdit && $item['icon'] === 'globe') ? 'selected' : ''; ?>>🌐 Globe / Network</option>
                            <option value="document" <?php echo ($isEdit && $item['icon'] === 'document') ? 'selected' : ''; ?>>📄 Document / File</option>
                            <option value="cube" <?php echo ($isEdit && $item['icon'] === 'cube') ? 'selected' : ''; ?>>📦 Cube / 3D Asset</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('portfolio-editor-form');
    const successAlert = document.getElementById('portfolio-success');
    const errorAlert = document.getElementById('portfolio-error');
    const submitBtn = document.getElementById('portfolio-submit-btn');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

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
                successAlert.textContent = data.message;
                successAlert.style.display = 'block';
                setTimeout(() => {
                    window.location.href = '/admin/portfolio';
                }, 1000);
            } else {
                throw new Error(data.message || 'Error occurred while saving case study.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.textContent = "<?php echo $isEdit ? 'Update Showcase' : 'Publish Showcase'; ?>";
        }
    });
});
</script>
