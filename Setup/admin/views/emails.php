<?php
/**
 * Great Endured Technology — Admin Email Composer View
 * Governed by RBP (AGENTS.md)
 */
declare(strict_types=1);
?>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">Email Outbox & Broadcast</h2>
    <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Compose and send branded HTML emails directly to leads or custom recipients.</p>
</div>

<div class="card" style="padding: 30px; border-radius: 16px; position: relative;">
    <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 25px; color: var(--color-cyan-pulse);">
        Compose Outbox Message
    </h3>

    <div class="form-alert form-alert-success" id="send-success" style="display: none; padding: 15px; margin-bottom: 20px;"></div>
    <div class="form-alert form-alert-error" id="send-error" style="display: none; padding: 15px; margin-bottom: 20px;"></div>

    <form id="email-compose-form" action="/admin/emails/send" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

        <div class="form-group">
            <label class="form-label">Select Recipient from Leads</label>
            <select id="recipient-select" class="form-input" style="background-color: var(--color-ink); margin-bottom: 10px;">
                <option value="">-- Choose from captured leads --</option>
                <?php foreach ($leads as $lead): ?>
                    <option value="<?php echo htmlspecialchars($lead['email']); ?>">
                        <?php echo htmlspecialchars($lead['name']); ?> (<?php echo htmlspecialchars($lead['email']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Recipient Email Address</label>
            <input type="email" name="to" id="recipient-email" class="form-input" placeholder="e.g. client@company.com" required>
        </div>

        <div class="form-group">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-input" placeholder="e.g. Follow up on your project inquiry" required>
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label class="form-label">Email Content (HTML Supported)</label>
            <textarea name="body" id="email-editor" class="form-input" style="min-height: 250px; font-family: monospace; font-size: 0.85rem;" placeholder="Write your message here..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary" id="send-btn" style="min-width: 150px;">
            Send Email
        </button>
    </form>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/editor.js"></script>
<style>
.ck-editor__editable {
    min-height: 350px;
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
    const form = document.getElementById('email-compose-form');
    const recipientSelect = document.getElementById('recipient-select');
    const recipientEmail = document.getElementById('recipient-email');
    const successAlert = document.getElementById('send-success');
    const errorAlert = document.getElementById('send-error');
    const sendBtn = document.getElementById('send-btn');

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

    // Update custom input when choosing from dropdown
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
        sendBtn.disabled = true;
        sendBtn.textContent = 'Sending email...';

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
                form.reset();
                if (editorInstance) {
                    editorInstance.setData('');
                }
            } else {
                throw new Error(data.message || 'Failed to deliver email.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
        } finally {
            sendBtn.disabled = false;
            sendBtn.textContent = 'Send Email';
        }
    });
});
</script>
