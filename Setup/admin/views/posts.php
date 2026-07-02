<?php
/**
 * Great Endured Technology — Admin Blog Posts CMS Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">Blog Content Management</h2>
    <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Publish, update, or delete blog articles.</p>
</div>

<div class="grid grid-3" style="align-items: start; gap: 30px;">
    <!-- Publish Blog Post form -->
    <div style="grid-column: span 2;">
        <div class="card" style="padding: 25px; border-radius: 16px;">
            <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px; color: var(--color-cyan-pulse);">
                Publish Post
            </h3>

            <div class="form-alert form-alert-success" id="add-post-success" style="font-size: 0.8rem; padding: 10px;"></div>
            <div class="form-alert form-alert-error" id="add-post-error" style="font-size: 0.8rem; padding: 10px;"></div>

            <form id="post-add-form" action="/admin/posts/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

                <div class="form-group">
                    <label class="form-label">Article Title</label>
                    <input type="text" name="title" class="form-input" placeholder="e.g. Speed Optimizations" required>
                </div>

                <div class="form-group">
                    <label class="form-label">URL Slug</label>
                    <input type="text" name="slug" class="form-input" placeholder="e.g. speed-optimizations" required>
                    <p style="font-size: 0.7rem; color: var(--color-mist); margin-top: 4px;">Access path: /blog/speed-optimizations</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_desc" class="form-input" style="min-height: 60px;" placeholder="SEO description for search snippets..."></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="form-label">Post Content</label>
                    <textarea name="content" id="post-editor" class="form-input" style="min-height: 150px; font-family: monospace; font-size: 0.85rem;" placeholder="Write your article here..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-full" id="add-post-submit">Publish Post</button>
            </form>
        </div>
    </div>

    <!-- Active posts list -->
    <div style="grid-column: span 1;">
        <div class="card" style="padding: 25px; border-radius: 16px;">
            <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px;">
                Published Articles
            </h3>

            <?php if (empty($posts)): ?>
                <p style="color: var(--color-mist); text-align: center; padding: 40px 0; margin: 0;">No articles published yet.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <?php foreach ($posts as $p): ?>
                        <div class="flex-between" id="post-row-<?php echo htmlspecialchars($p['slug']); ?>" 
                             style="background: rgba(255,255,255,0.02); padding: 15px 20px; border-radius: 10px; border: 1px solid var(--glass-border); align-items: center; gap: 20px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--color-cyan-pulse); box-shadow: 0 0 6px var(--color-cyan-pulse);"></div>
                                <div>
                                    <h4 style="margin: 0; color: var(--color-white); font-size: 1rem;"><?php echo htmlspecialchars($p['title']); ?></h4>
                                    <a href="/blog/<?php echo htmlspecialchars($p['slug']); ?>" target="_blank" style="font-size: 0.85rem; color: var(--color-cyan-pulse); text-decoration: none;">
                                        /blog/<?php echo htmlspecialchars($p['slug']); ?> ↗
                                    </a>
                                </div>
                            </div>
                            
                            <button class="btn btn-secondary btn-sm delete-post-btn" 
                                    data-slug="<?php echo htmlspecialchars($p['slug']); ?>"
                                    style="border-color: rgba(239, 68, 68, 0.2); color: #f87171; font-size: 0.8rem; padding: 6px 12px;">
                                Delete Post
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/editor.js"></script>
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
    const addForm = document.getElementById('post-add-form');
    const successAlert = document.getElementById('add-post-success');
    const errorAlert = document.getElementById('add-post-error');
    const submitBtn = document.getElementById('add-post-submit');
    const csrfToken = "<?php echo csrf_token(); ?>";

    let editorInstance;
    ClassicEditor
        .create(document.querySelector('#post-editor'))
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    // Add post
    addForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (editorInstance) {
            editorInstance.updateSourceElement();
        }
        
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
                throw new Error(data.message || 'Publishing article failed.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Publish Post';
        }
    });

    // Delete post
    document.querySelectorAll('.delete-post-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to delete this blog post permanently? This action cannot be undone.')) {
                return;
            }

            const slug = btn.getAttribute('data-slug');
            const row = document.getElementById(`post-row-${slug}`);

            btn.disabled = true;
            btn.textContent = 'Deleting...';

            const formData = new FormData();
            formData.append('slug', slug);
            formData.append('csrf_token', csrfToken);

            try {
                const response = await fetch('/admin/posts/delete', {
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
                btn.textContent = 'Delete Post';
            }
        });
    });
});
</script>
