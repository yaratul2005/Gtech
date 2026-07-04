<?php
/**
 * Great Endured Technology — Admin Team Member Editor View
 * Governed by RBP (AGENTS.md)
 */
declare(strict_types=1);

$isEdit = isset($member) && !empty($member);
?>

<div style="padding: 30px 40px; background: #030508; min-height: 100vh;">
    <!-- Top Back Navigation -->
    <div style="margin-bottom: 25px;">
        <a href="/admin/team" style="color: var(--color-cyan-pulse); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
            ← Back to Team List
        </a>
    </div>

    <form id="team-editor-form" action="<?php echo $isEdit ? '/admin/team/update' : '/admin/team/create'; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="id" value="<?php echo $isEdit ? htmlspecialchars($member['id']) : ''; ?>">

        <!-- 2-Column Editor Canvas Grid -->
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px; align-items: start;">
            
            <!-- Left Main Column (70%) -->
            <div>
                <!-- Name input (giant, borderless theme matching classic WP) -->
                <div style="margin-bottom: 25px;">
                    <input type="text" name="name" id="member-name" 
                           placeholder="Enter full name here" 
                           value="<?php echo $isEdit ? htmlspecialchars($member['name']) : ''; ?>"
                           required
                           style="width: 100%; background: transparent; border: none; border-bottom: 1px solid rgba(255,255,255,0.08); font-size: 2.2rem; font-weight: 700; color: #fff; padding: 10px 0; outline: none; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='var(--color-cyan-pulse)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.08)'">
                </div>

                <!-- Bio Description Canvas -->
                <div class="card" style="padding: 0; border-radius: 12px; margin-bottom: 30px; overflow: hidden; background: #0d1117;">
                    <div style="padding: 12px 20px; background: #161b22; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-between: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; text-transform: uppercase; font-weight: 600; color: var(--color-mist); letter-spacing: 0.05em;">Member Bio / Profile Summary</span>
                    </div>
                    <textarea name="bio" id="member-bio" style="width:100%; min-height: 250px; background:transparent; border:none; color:#c9d1d9; padding:20px; outline:none; font-family:inherit; font-size:1rem; line-height:1.6;" placeholder="Write member credentials, biography, and professional experiences..." required><?php echo $isEdit ? htmlspecialchars($member['bio']) : ''; ?></textarea>
                </div>

                <!-- Contact & Design style settings -->
                <div class="card" style="padding: 25px; border-radius: 12px;">
                    <h3 style="font-size: 1rem; margin-bottom: 20px; color: var(--color-cyan-pulse); display: flex; align-items: center; gap: 8px;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        Socials & Styling Settings
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">WhatsApp Contact Phone</label>
                        <input type="text" name="phone" class="form-input" 
                               placeholder="e.g. +8801700000000" 
                               value="<?php echo $isEdit ? htmlspecialchars($member['phone'] ?? '') : ''; ?>">
                        <p style="font-size: 0.7rem; color: var(--color-mist); margin-top: 5px;">Must include country code without spaces or dashes.</p>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Card Theme Gradient</label>
                        <input type="text" name="theme" class="form-input" 
                               value="<?php echo $isEdit ? htmlspecialchars($member['theme']) : 'linear-gradient(135deg, #091a2f 0%, #030508 100%)'; ?>" required>
                        <p style="font-size: 0.7rem; color: var(--color-mist); margin-top: 5px;">Custom gradient background for this team member's 3D card wrapper.</p>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column (300px) -->
            <div>
                <!-- Publish Control Panel widget -->
                <div class="card" style="padding: 20px; border-radius: 12px; margin-bottom: 25px; background: #0d1117;">
                    <h4 style="margin: 0 0 15px 0; font-size: 0.95rem; text-transform: uppercase; color: var(--color-mist); letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                        Member Actions
                    </h4>
                    
                    <div style="font-size: 0.85rem; color: var(--color-mist); display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                        <div>Status: <strong style="color: var(--color-cyan-pulse);"><?php echo $isEdit ? 'Active Member' : 'Draft Member'; ?></strong></div>
                    </div>

                    <div class="form-alert form-alert-success" id="team-success" style="display: none; padding: 10px; font-size: 0.8rem; margin-bottom: 15px;"></div>
                    <div class="form-alert form-alert-error" id="team-error" style="display: none; padding: 10px; font-size: 0.8rem; margin-bottom: 15px;"></div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="submit" class="btn btn-primary btn-full" id="team-submit-btn">
                            <?php echo $isEdit ? 'Update Member' : 'Add Member'; ?>
                        </button>
                        <a href="/admin/team" class="btn btn-secondary btn-full" style="border-color: rgba(255,255,255,0.05); text-align: center; display: block; line-height: 20px;">
                            Exit Workspace
                        </a>
                    </div>
                </div>

                <!-- Showcase Attributes Widget -->
                <div class="card" style="padding: 20px; border-radius: 12px; background: #0d1117; margin-bottom: 25px;">
                    <h4 style="margin: 0 0 15px 0; font-size: 0.95rem; text-transform: uppercase; color: var(--color-mist); letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                        Member Settings
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Role / Job Title</label>
                        <input type="text" name="role" class="form-input" 
                               placeholder="e.g. Lead Developer" 
                               value="<?php echo $isEdit ? htmlspecialchars($member['role']) : ''; ?>" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Skills (Comma-separated)</label>
                        <input type="text" name="skills" class="form-input" 
                               placeholder="e.g. PHP, Laravel, Node.js" 
                               value="<?php echo $isEdit ? htmlspecialchars($member['skills'] ?? '') : ''; ?>">
                    </div>
                </div>

                <!-- Photo Upload Widget -->
                <div class="card" style="padding: 20px; border-radius: 12px; background: #0d1117;">
                    <h4 style="margin: 0 0 15px 0; font-size: 0.95rem; text-transform: uppercase; color: var(--color-mist); letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                        Profile Photo
                    </h4>

                    <?php if ($isEdit && !empty($member['image'])): ?>
                        <div style="text-align: center; margin-bottom: 15px;">
                            <img src="<?php echo htmlspecialchars($member['image']); ?>" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid var(--glass-border);">
                        </div>
                    <?php endif; ?>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Upload Photo</label>
                        <input type="file" name="image" class="form-input" accept="image/*" style="border: 1px dashed var(--glass-border); padding: 10px; cursor: pointer;">
                        <p style="font-size: 0.7rem; color: var(--color-mist); margin-top: 5px;">Supports: WebP, PNG, JPG, GIF.</p>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('team-editor-form');
    const successAlert = document.getElementById('team-success');
    const errorAlert = document.getElementById('team-error');
    const submitBtn = document.getElementById('team-submit-btn');

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
                    window.location.href = '/admin/team';
                }, 1000);
            } else {
                throw new Error(data.message || 'Error occurred while saving team member.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.textContent = "<?php echo $isEdit ? 'Update Member' : 'Add Member'; ?>";
        }
    });
});
</script>
