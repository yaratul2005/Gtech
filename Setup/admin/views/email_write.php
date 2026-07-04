<?php
/**
 * Great Endured Technology — Admin Email Broadcast Editor View
 * Governed by RBP (AGENTS.md)
 */
declare(strict_types=1);
?>

<div style="padding: 30px 40px; background: #030508; min-height: 100vh;">
    <!-- Top Back Navigation -->
    <div style="margin-bottom: 25px;">
        <a href="/admin/emails" style="color: var(--color-cyan-pulse); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
            ← Back to Broadcast Logs
        </a>
    </div>

    <form id="email-editor-form" action="/admin/emails/send" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

        <!-- 2-Column Editor Canvas Grid -->
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px; align-items: start;">
            
            <!-- Left Main Column (70%) -->
            <div>
                <!-- Subject input (giant, borderless theme matching classic WP) -->
                <div style="margin-bottom: 25px;">
                    <input type="text" name="subject" id="email-subject" 
                           placeholder="Enter email subject here" 
                           required
                           style="width: 100%; background: transparent; border: none; border-bottom: 1px solid rgba(255,255,255,0.08); font-size: 2.2rem; font-weight: 700; color: #fff; padding: 10px 0; outline: none; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='var(--color-cyan-pulse)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.08)'">
                </div>

                <!-- Email Content Editor Canvas -->
                <div class="card" style="padding: 0; border-radius: 12px; margin-bottom: 30px; overflow: hidden; background: #0d1117;">
                    <div style="padding: 12px 20px; background: #161b22; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-between: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; text-transform: uppercase; font-weight: 600; color: var(--color-mist); letter-spacing: 0.05em;">Email Content (HTML Supported)</span>
                    </div>
                    <textarea name="body" id="email-editor" style="width: 100%; min-height: 450px; background-color: #0d1117; color: #c9d1d9; border: none; padding: 20px; font-family: inherit; font-size: 1rem; line-height: 1.6; outline: none; box-sizing: border-box; display: block; resize: vertical;"></textarea>
                </div>
            </div>

            <!-- Right Sidebar Column (300px) -->
            <div>
                <!-- Publish Control Panel widget -->
                <div class="card" style="padding: 20px; border-radius: 12px; margin-bottom: 25px; background: #0d1117;">
                    <h4 style="margin: 0 0 15px 0; font-size: 0.95rem; text-transform: uppercase; color: var(--color-mist); letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                        Broadcast Options
                    </h4>
                    
                    <div style="font-size: 0.85rem; color: var(--color-mist); display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                        <div>Status: <strong style="color: var(--color-cyan-pulse);">Draft Outbox</strong></div>
                        <div>Template: <strong style="color: #fff;">GET Branded HTML</strong></div>
                    </div>

                    <div class="form-alert form-alert-success" id="email-success" style="display: none; padding: 10px; font-size: 0.8rem; margin-bottom: 15px;"></div>
                    <div class="form-alert form-alert-error" id="email-error" style="display: none; padding: 10px; font-size: 0.8rem; margin-bottom: 15px;"></div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="submit" class="btn btn-primary btn-full" id="email-submit-btn">
                            Send Broadcast
                        </button>
                        <a href="/admin/emails" class="btn btn-secondary btn-full" style="border-color: rgba(255,255,255,0.05); text-align: center; display: block; line-height: 20px;">
                            Exit Workspace
                        </a>
                    </div>
                </div>

                <!-- Showcase Attributes Widget -->
                <div class="card" style="padding: 20px; border-radius: 12px; background: #0d1117;">
                    <h4 style="margin: 0 0 15px 0; font-size: 0.95rem; text-transform: uppercase; color: var(--color-mist); letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                        Recipient Settings
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Choose from Leads</label>
                        <select id="recipient-select" class="form-input" style="background-color: var(--color-ink); margin-bottom: 10px;">
                            <option value="">-- Capture Leads list --</option>
                            <?php foreach ($leads as $lead): ?>
                                <option value="<?php echo htmlspecialchars($lead['email']); ?>">
                                    <?php echo htmlspecialchars($lead['name']); ?> (<?php echo htmlspecialchars($lead['email']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Recipient Email</label>
                        <input type="email" name="to" id="recipient-email" class="form-input" placeholder="client@company.com" required>
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
    const form = document.getElementById('email-editor-form');
    const recipientSelect = document.getElementById('recipient-select');
    const recipientEmail = document.getElementById('recipient-email');
    const successAlert = document.getElementById('email-success');
    const errorAlert = document.getElementById('email-error');
    const submitBtn = document.getElementById('email-submit-btn');

    let editorInstance;
    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(document.querySelector('#email-editor'))
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });
    }

    recipientSelect.addEventListener('change', () => {
        if (recipientSelect.value !== '') {
            recipientEmail.value = recipientSelect.value;
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (editorInstance) {
            editorInstance.updateSourceElement();
        }

        successAlert.style.display = 'none';
        errorAlert.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending email...';

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
                    window.location.href = '/admin/emails';
                }, 1000);
            } else {
                throw new Error(data.message || 'Error occurred while sending broadcast.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.textContent = 'Send Broadcast';
        }
    });
});
</script>
