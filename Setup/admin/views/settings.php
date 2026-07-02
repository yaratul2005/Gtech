<?php
/**
 * Great Endured Technology — Admin Settings CMS Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

$s = $settings;
?>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">System Settings</h2>
    <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Configure global metadata parameters and SMTP mail credentials.</p>
</div>

<div class="card" style="padding: 30px; border-radius: 16px; margin-bottom: 30px;">
    <h3 style="font-size: 1.25rem; font-family: 'Outfit', sans-serif; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 12px; margin-bottom: 25px; color: var(--color-cyan-pulse);">
        SMTP Mailer & Core Site Configuration
    </h3>

    <!-- Status Alerts -->
    <div class="form-alert form-alert-success" id="settings-success"></div>
    <div class="form-alert form-alert-error" id="settings-error"></div>

    <form id="settings-cms-form" action="/admin/settings/update" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

        <div class="grid grid-2" style="gap: 25px;">
            <!-- Left Side: Site Info -->
            <div>
                <h4 style="margin: 0 0 15px 0; color: var(--color-white); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">1. Branding & Metadata</h4>
                
                <div class="form-group">
                    <label class="form-label">Agency Name</label>
                    <input type="text" name="app_name" class="form-input" value="<?php echo htmlspecialchars($s['app_name'] ?? 'Great Endured Technology'); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Site Domain URL</label>
                    <input type="url" name="app_url" class="form-input" value="<?php echo htmlspecialchars($s['app_url'] ?? 'https://greatentech.com'); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="app_desc" class="form-input" style="min-height: 80px;" required><?php echo htmlspecialchars($s['app_desc'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">SEO Keywords</label>
                    <textarea name="app_keywords" class="form-input" style="min-height: 60px;" placeholder="comma, separated, keywords" required><?php echo htmlspecialchars($s['app_keywords'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Right Side: SMTP Details -->
            <div>
                <h4 style="margin: 0 0 15px 0; color: var(--color-white); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">2. SMTP Gateway credentials</h4>
                
                <div class="form-group">
                    <label class="form-label">SMTP Mail Server Host</label>
                    <input type="text" name="smtp_host" class="form-input" value="<?php echo htmlspecialchars($s['smtp_host'] ?? ''); ?>" placeholder="mail.yourdomain.com">
                </div>

                <div class="form-group">
                    <label class="form-label">SMTP Port</label>
                    <input type="number" name="smtp_port" class="form-input" value="<?php echo htmlspecialchars($s['smtp_port'] ?? '587'); ?>" placeholder="587">
                </div>

                <div class="form-group">
                    <label class="form-label">SMTP Username</label>
                    <input type="text" name="smtp_user" class="form-input" value="<?php echo htmlspecialchars($s['smtp_user'] ?? ''); ?>" placeholder="sender@yourdomain.com">
                </div>

                <div class="form-group">
                    <label class="form-label">SMTP Password</label>
                    <input type="password" name="smtp_pass" class="form-input" value="<?php echo htmlspecialchars($s['smtp_pass'] ?? ''); ?>" placeholder="••••••••••••">
                </div>

                <div class="form-group">
                    <label class="form-label">Sender Email Address</label>
                    <input type="email" name="smtp_from" class="form-input" value="<?php echo htmlspecialchars($s['smtp_from'] ?? 'contact@greatentech.com'); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Sender Display Name</label>
                    <input type="text" name="smtp_from_name" class="form-input" value="<?php echo htmlspecialchars($s['smtp_from_name'] ?? 'Great Endured Technology'); ?>">
                </div>
            </div>
        </div>

        <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 25px; margin-top: 25px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="min-width: 180px;" id="save-settings-btn">Save Configurations</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('settings-cms-form');
    const successAlert = document.getElementById('settings-success');
    const errorAlert = document.getElementById('settings-error');
    const submitBtn = document.getElementById('save-settings-btn');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        successAlert.style.display = 'none';
        errorAlert.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving configs...';

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
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                throw new Error(data.message || 'Saving configuration failed.');
            }
        } catch (err) {
            errorAlert.textContent = err.message || 'Error saving settings.';
            errorAlert.style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Configurations';
        }
    });
});
</script>
