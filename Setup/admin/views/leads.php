<?php
/**
 * Great Endured Technology — Admin Leads List Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<div style="margin-bottom: 30px;" class="flex-between">
    <div>
        <h2 style="font-size: 1.8rem; margin-bottom: 5px;">Manage Inquiries</h2>
        <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Review and manage user submissions.</p>
    </div>
</div>

<div class="card" style="padding: 30px;">
    <?php if (empty($leads)): ?>
        <p style="color: var(--color-mist); text-align: center; padding: 40px 0; margin: 0;">No inquiries found in database.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Service</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leads as $lead): ?>
                    <tr id="lead-row-<?php echo htmlspecialchars($lead['id']); ?>">
                        <td style="font-size: 0.85rem; white-space: nowrap;"><?php echo date('M d, Y H:i', strtotime($lead['created_at'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($lead['name']); ?></strong></td>
                        <td><a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>" style="color: var(--color-cyan-pulse);"><?php echo htmlspecialchars($lead['email']); ?></a></td>
                        <td><span style="background: rgba(0, 212, 255, 0.05); padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; border: 1px solid rgba(0,212,255,0.1);"><?php echo htmlspecialchars($lead['service']); ?></span></td>
                        <td><div style="max-width: 250px; font-size: 0.85rem; word-break: break-word;"><?php echo nl2br(htmlspecialchars($lead['message'])); ?></div></td>
                        <td>
                            <button class="btn btn-secondary btn-sm delete-btn" data-id="<?php echo htmlspecialchars($lead['id']); ?>" style="border-color: rgba(239, 68, 68, 0.2); color: #f87171;">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = "<?php echo csrf_token(); ?>";

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const confirmed = await window.customConfirm('Delete Inquiry', 'Are you sure you want to delete this inquiry permanently?');
            if (!confirmed) {
                return;
            }

            const id = btn.getAttribute('data-id');
            const row = document.getElementById(`lead-row-${id}`);

            btn.disabled = true;
            btn.textContent = 'Deleting...';

            const formData = new FormData();
            formData.append('id', id);
            formData.append('csrf_token', csrfToken);

            try {
                const response = await fetch('/admin/leads/delete', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
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
