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

        <!-- Section 3: Database & Admin Access Configuration -->
        <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 25px; margin-top: 25px;">
            <div class="grid grid-2" style="gap: 25px;">
                <!-- DB config -->
                <div>
                    <h4 style="margin: 0 0 15px 0; color: var(--color-white); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">3. Database connection parameters</h4>
                    <div class="form-group">
                        <label class="form-label">Database Host</label>
                        <input type="text" name="db_host" class="form-input" value="<?php echo htmlspecialchars($s['db_host'] ?? 'localhost'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Database Port</label>
                        <input type="text" name="db_port" class="form-input" value="<?php echo htmlspecialchars($s['db_port'] ?? '3306'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Database Name</label>
                        <input type="text" name="db_name" class="form-input" value="<?php echo htmlspecialchars($s['db_name'] ?? 'great_endured_db'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Database User</label>
                        <input type="text" name="db_user" class="form-input" value="<?php echo htmlspecialchars($s['db_user'] ?? 'root'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Database Password</label>
                        <input type="password" name="db_pass" class="form-input" placeholder="•••••••••••• (leave empty to keep unchanged)">
                    </div>
                </div>

                <!-- Admin credentials -->
                <div>
                    <h4 style="margin: 0 0 15px 0; color: var(--color-white); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">4. Admin Authentication Credentials</h4>
                    <div class="form-group">
                        <label class="form-label">Admin Username</label>
                        <input type="text" name="admin_user" class="form-input" value="<?php echo htmlspecialchars($s['admin_user'] ?? 'admin'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Admin Password</label>
                        <input type="password" name="admin_pass" class="form-input" placeholder="•••••••••••• (leave empty to keep unchanged)">
                        <p style="font-size: 0.75rem; color: var(--color-mist); margin-top: 5px;">Leave empty to keep your active password.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Custom Code Injection -->
        <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 25px; margin-top: 25px;">
            <h4 style="margin: 0 0 15px 0; color: var(--color-white); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">5. Code Injection & Script Integrations</h4>
            <div class="grid grid-2" style="gap: 25px;">
                <div class="form-group">
                    <label class="form-label">Custom Header Code (in &lt;head&gt;)</label>
                    <textarea name="header_code" class="form-input" style="min-height: 100px; font-family: monospace; font-size: 0.85rem;" placeholder="e.g. <!-- Google Analytics --> <script>...</script>"><?php echo htmlspecialchars($s['header_code'] ?? ''); ?></textarea>
                    <p style="font-size: 0.75rem; color: var(--color-mist); margin-top: 5px;">This HTML/JS code will be printed at the bottom of the &lt;head&gt; on all pages.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Custom Footer Code (before &lt;/body&gt;)</label>
                    <textarea name="footer_code" class="form-input" style="min-height: 100px; font-family: monospace; font-size: 0.85rem;" placeholder="e.g. <script>console.log('GET Loaded');</script>"><?php echo htmlspecialchars($s['footer_code'] ?? ''); ?></textarea>
                    <p style="font-size: 0.75rem; color: var(--color-mist); margin-top: 5px;">This HTML/JS code will be printed right before the &lt;/body&gt; tag close on all pages.</p>
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
