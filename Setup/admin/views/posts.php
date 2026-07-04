<?php
/**
 * Great Endured Technology — Admin Blog Posts CMS Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<div style="margin-bottom: 30px;" class="flex-between">
    <div>
        <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">Blog Content Management</h2>
        <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Publish, update, or delete blog articles.</p>
    </div>
    <button type="button" class="btn btn-primary" id="open-add-workspace" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #10b981; display: inline-flex; align-items: center; gap: 8px;">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Add New Post
    </button>
</div>

<div class="card" style="padding: 30px; border-radius: 16px;">
    <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px;">
        Published Articles
    </h3>

    <?php if (empty($posts)): ?>
        <p style="color: var(--color-mist); text-align: center; padding: 40px 0; margin: 0;">No articles published yet.</p>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <?php foreach ($posts as $p): ?>
                <div class="flex-between" id="post-row-<?php echo htmlspecialchars($p['slug']); ?>" 
                     style="background: rgba(255,255,255,0.01); padding: 15px 20px; border-radius: 10px; border: 1px solid var(--glass-border); align-items: center; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--color-cyan-pulse); box-shadow: 0 0 6px var(--color-cyan-pulse);"></div>
                        <div>
                            <h4 style="margin: 0; color: var(--color-white); font-size: 1rem;"><?php echo htmlspecialchars($p['title']); ?></h4>
                            <a href="/blog/<?php echo htmlspecialchars($p['slug']); ?>" target="_blank" style="font-size: 0.85rem; color: var(--color-cyan-pulse); text-decoration: none;">
                                /blog/<?php echo htmlspecialchars($p['slug']); ?> ↗
                            </a>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 8px;">
                        <button class="btn btn-secondary btn-sm edit-post-btn" 
                                data-title="<?php echo htmlspecialchars($p['title']); ?>"
                                data-slug="<?php echo htmlspecialchars($p['slug']); ?>"
                                data-meta-desc="<?php echo htmlspecialchars($p['meta_desc']); ?>"
                                style="border-color: rgba(59, 130, 246, 0.2); color: var(--color-cyan-pulse); font-size: 0.8rem; padding: 6px 12px; height: auto;">
                            Edit
                        </button>
                        <button class="btn btn-secondary btn-sm delete-post-btn" 
                                data-slug="<?php echo htmlspecialchars($p['slug']); ?>"
                                style="border-color: rgba(239, 68, 68, 0.2); color: #f87171; font-size: 0.8rem; padding: 6px 12px; height: auto;">
                            Delete
                        </button>
                    </div>
                    <textarea id="raw-content-<?php echo htmlspecialchars($p['slug']); ?>" style="display:none;"><?php echo htmlspecialchars($p['content']); ?></textarea>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Full-Page Writing Workspace Panel (Cyber HUD Canvas) -->
<div id="workspace-overlay" class="workspace-panel" style="display: none;">
    <div class="workspace-header">
        <h3 id="workspace-title">Publish Post</h3>
        <button type="button" class="btn btn-secondary workspace-close-btn" id="close-workspace-btn" style="height: 38px; display: inline-flex; align-items: center; padding: 0 16px;">
            ✕ Exit Workspace
        </button>
    </div>
    <div class="workspace-body">
        <div class="card" style="padding: 0px; border-radius: 16px; border: none; background: transparent;">
            <div class="form-alert form-alert-success" id="add-post-success" style="font-size: 0.82rem; padding: 12px; margin-bottom: 20px;"></div>
            <div class="form-alert form-alert-error" id="add-post-error" style="font-size: 0.82rem; padding: 12px; margin-bottom: 20px;"></div>

            <form id="post-add-form" action="/admin/posts/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="original_slug" id="post-original-slug" value="">

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
                    <textarea name="content" id="post-editor" class="form-input" style="min-height: 250px; font-family: monospace; font-size: 0.85rem;" placeholder="Write your article here..."></textarea>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" id="add-post-submit" style="min-width: 150px;">Publish Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/editor.js"></script>
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

    const workspaceOverlay = document.getElementById('workspace-overlay');
    const openAddWorkspace = document.getElementById('open-add-workspace');
    const closeWorkspaceBtn = document.getElementById('close-workspace-btn');
    const formHeading = document.getElementById('workspace-title');
    const postOriginalSlug = document.getElementById('post-original-slug');

    let editorInstance;
    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(document.querySelector('#post-editor'))
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });
    }

    // Open Workspace for Creating
    openAddWorkspace.addEventListener('click', () => {
        addForm.reset();
        addForm.action = '/admin/posts/create';
        postOriginalSlug.value = '';
        formHeading.textContent = 'Publish Post';
        submitBtn.textContent = 'Publish Post';
        if (editorInstance) {
            editorInstance.setData('');
        }
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

        if (editorInstance) {
            editorInstance.updateSourceElement();
        }
        
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
                throw new Error(data.message || 'Saving post failed.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.textContent = addForm.action.includes('update') ? 'Update Post' : 'Publish Post';
        }
    });

    // Edit post trigger
    document.querySelectorAll('.edit-post-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const title = btn.getAttribute('data-title');
            const slug = btn.getAttribute('data-slug');
            const metaDesc = btn.getAttribute('data-meta-desc');
            const rawContentTextarea = document.getElementById(`raw-content-${slug}`);
            const rawContent = rawContentTextarea ? rawContentTextarea.value : '';

            addForm.querySelector('[name="title"]').value = title;
            addForm.querySelector('[name="slug"]').value = slug;
            addForm.querySelector('[name="meta_desc"]').value = metaDesc;
            if (editorInstance) {
                editorInstance.setData(rawContent);
            } else {
                addForm.querySelector('[name="content"]').value = rawContent;
            }

            postOriginalSlug.value = slug;
            addForm.action = '/admin/posts/update';
            formHeading.textContent = 'Edit Post';
            submitBtn.textContent = 'Update Post';

            successAlert.style.display = 'none';
            errorAlert.style.display = 'none';

            workspaceOverlay.style.display = 'flex';
            workspaceOverlay.offsetHeight;
            workspaceOverlay.classList.add('show');
        });
    });

    // Delete post
    document.querySelectorAll('.delete-post-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const confirmed = await window.customConfirm('Delete Post', 'Are you sure you want to delete this blog post permanently? This action cannot be undone.');
            if (!confirmed) {
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
                btn.textContent = 'Delete';
            }
        });
    });
});
</script>
