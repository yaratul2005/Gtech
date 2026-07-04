<?php
/**
 * Great Endured Technology — Admin Page Editor View
 * Governed by RBP (AGENTS.md)
 */
declare(strict_types=1);

$isEdit = isset($page) && !empty($page);
?>

<div style="padding: 30px 40px; background: #030508; min-height: 100vh;">
    <!-- Top Back Navigation -->
    <div style="margin-bottom: 25px;">
        <a href="/admin/pages" style="color: var(--color-cyan-pulse); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
            ← Back to Dynamic Pages List
        </a>
    </div>

    <form id="page-editor-form" action="<?php echo $isEdit ? '/admin/pages/update' : '/admin/pages/create'; ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="original_slug" value="<?php echo $isEdit ? htmlspecialchars($page['slug']) : ''; ?>">

        <!-- 2-Column Editor Canvas Grid -->
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px; align-items: start;">
            
            <!-- Left Main Column (70%) -->
            <div>
                <!-- Title input (giant, borderless theme matching classic WP) -->
                <div style="margin-bottom: 25px;">
                    <input type="text" name="title" id="page-title" 
                           placeholder="Enter page title here" 
                           value="<?php echo $isEdit ? htmlspecialchars($page['title']) : ''; ?>"
                           required
                           style="width: 100%; background: transparent; border: none; border-bottom: 1px solid rgba(255,255,255,0.08); font-size: 2.2rem; font-weight: 700; color: #fff; padding: 10px 0; outline: none; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='var(--color-cyan-pulse)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.08)'">
                </div>

                <!-- Main Content Editor Canvas -->
                <div class="card" style="padding: 0; border-radius: 12px; margin-bottom: 30px; overflow: hidden; background: #0d1117;">
                    <div style="padding: 12px 20px; background: #161b22; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-between: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; text-transform: uppercase; font-weight: 600; color: var(--color-mist); letter-spacing: 0.05em;">Writing Canvas (HTML Supported)</span>
                    </div>
                    <textarea name="content" id="page-editor" style="width: 100%; min-height: 450px; background-color: #0d1117; color: #c9d1d9; border: none; padding: 20px; font-family: inherit; font-size: 1rem; line-height: 1.6; outline: none; box-sizing: border-box; display: block; resize: vertical;"><?php echo $isEdit ? htmlspecialchars($page['content']) : ''; ?></textarea>
                </div>

                <!-- Collapsible Meta Box: SEO settings -->
                <div class="card" style="padding: 25px; border-radius: 12px;">
                    <h3 style="font-size: 1rem; margin-bottom: 20px; color: var(--color-cyan-pulse); display: flex; align-items: center; gap: 8px;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        SEO & Metadata Settings
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">URL Slug</label>
                        <input type="text" name="slug" id="page-slug" class="form-input" 
                               placeholder="e.g. terms-of-service" 
                               value="<?php echo $isEdit ? htmlspecialchars($page['slug']) : ''; ?>" required>
                        <p style="font-size: 0.7rem; color: var(--color-mist); margin-top: 5px;">Access path: /p/<span id="slug-preview"><?php echo $isEdit ? htmlspecialchars($page['slug']) : 'terms-of-service'; ?></span></p>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_desc" class="form-input" style="min-height: 80px;" placeholder="Search snippet preview text (150-160 characters)..."><?php echo $isEdit ? htmlspecialchars($page['meta_desc']) : ''; ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column (300px) -->
            <div>
                <!-- Publish Control Panel widget -->
                <div class="card" style="padding: 20px; border-radius: 12px; margin-bottom: 25px; background: #0d1117;">
                    <h4 style="margin: 0 0 15px 0; font-size: 0.95rem; text-transform: uppercase; color: var(--color-mist); letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                        Page Actions
                    </h4>
                    
                    <div style="font-size: 0.85rem; color: var(--color-mist); display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                        <div>Status: <strong style="color: var(--color-cyan-pulse);"><?php echo $isEdit ? 'Published' : 'New Page'; ?></strong></div>
                        <div>Type: <strong style="color: #fff;">Dynamic Layout</strong></div>
                    </div>

                    <div class="form-alert form-alert-success" id="page-success" style="display: none; padding: 10px; font-size: 0.8rem; margin-bottom: 15px;"></div>
                    <div class="form-alert form-alert-error" id="page-error" style="display: none; padding: 10px; font-size: 0.8rem; margin-bottom: 15px;"></div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="submit" class="btn btn-primary btn-full" id="page-submit-btn">
                            <?php echo $isEdit ? 'Update Page' : 'Create Page'; ?>
                        </button>
                        <a href="/admin/pages" class="btn btn-secondary btn-full" style="border-color: rgba(255,255,255,0.05); text-align: center; display: block; line-height: 20px;">
                            Exit Workspace
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@39.0.2/build/ckeditor.js"></script>
<style>
/* Adjust CKEditor styles for dark mode alignment */
.ck-editor__editable {
    min-height: 450px;
    background-color: #0d1117 !important;
    color: #c9d1d9 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
}
.ck-toolbar {
    background-color: #161b22 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
}
.ck-toolbar * {
    color: #c9d1d9 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('page-editor-form');
    const titleInput = document.getElementById('page-title');
    const slugInput = document.getElementById('page-slug');
    const slugPreview = document.getElementById('slug-preview');
    const successAlert = document.getElementById('page-success');
    const errorAlert = document.getElementById('page-error');
    const submitBtn = document.getElementById('page-submit-btn');

    let editorInstance;
    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(document.querySelector('#page-editor'))
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });
    }

    // Auto-generate slug from title (on creating a new page only)
    <?php if (!$isEdit): ?>
    titleInput.addEventListener('input', () => {
        const slug = titleInput.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        slugInput.value = slug;
        slugPreview.textContent = slug || 'terms-of-service';
    });
    <?php endif; ?>

    slugInput.addEventListener('input', () => {
        const slug = slugInput.value
            .toLowerCase()
            .replace(/[^a-z0-9-]/g, '-');
        slugInput.value = slug;
        slugPreview.textContent = slug || 'terms-of-service';
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (editorInstance) {
            editorInstance.updateSourceElement();
        }

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
                    window.location.href = '/admin/pages';
                }, 1000);
            } else {
                throw new Error(data.message || 'Error occurred while saving page.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.textContent = "<?php echo $isEdit ? 'Update Page' : 'Create Page'; ?>";
        }
    });
});
</script>
