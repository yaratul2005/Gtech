<?php
/**
 * Great Endured Technology — Admin Database Tools View
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

// Securely mask password strings for safety
function maskPassword(?string $pass): string {
    if (empty($pass)) return '(none)';
    return substr($pass, 0, 2) . str_repeat('•', max(4, strlen($pass) - 2));
}
?>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">Database Maintenance Console</h2>
    <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Monitor active database servers, run migrations, and inspect parameters.</p>
</div>

<div class="grid grid-3" style="align-items: start; gap: 30px;">
    <!-- Active diagnostics status card -->
    <div style="grid-column: span 1; display: flex; flex-direction: column; gap: 20px;">
        <div class="card" style="padding: 25px; border-radius: 16px;">
            <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px;">
                Server Status
            </h3>

            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div class="flex-between" style="border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 10px;">
                    <span style="color: var(--color-fog); font-size: 0.9rem;">PDO Connection</span>
                    <?php if ($dbConnected): ?>
                        <span class="status-badge status-badge-active">Connected</span>
                    <?php else: ?>
                        <span class="status-badge status-badge-inactive">Disconnected</span>
                    <?php endif; ?>
                </div>

                <div class="flex-between" style="border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 10px;">
                    <span style="color: var(--color-fog); font-size: 0.9rem;">Server Latency</span>
                    <span style="color: var(--color-white); font-weight: 600; font-size: 0.95rem;">
                        <?php echo $dbConnected ? $latency . ' ms' : 'N/A'; ?>
                    </span>
                </div>

                <div class="flex-between" style="border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 10px;">
                    <span style="color: var(--color-fog); font-size: 0.9rem;">Table Inquiries</span>
                    <span style="color: var(--color-white); font-weight: 600; font-size: 0.95rem;">
                        <?php echo $dbConnected ? $leadsTableCount . ' records' : 'N/A'; ?>
                    </span>
                </div>
            </div>

            <?php if (!$dbConnected && !empty($dbError)): ?>
                <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.15); border-radius: 8px; padding: 12px; margin-top: 20px; font-size: 0.85rem; color: #f87171;">
                    <strong>Error Diagnostic:</strong><br>
                    <?php echo htmlspecialchars($dbError); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Active Configurations & SQL Actions -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 30px;">
        <!-- Config details card -->
        <div class="card" style="padding: 25px; border-radius: 16px;">
            <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px;">
                Environment Connection Settings
            </h3>
            
            <div style="background: rgba(0, 0, 0, 0.2); padding: 15px 20px; border-radius: 8px; border: 1px solid var(--glass-border); font-family: monospace; font-size: 0.85rem; display: flex; flex-direction: column; gap: 8px;">
                <div><span style="color: var(--color-cyan-pulse);">DB_HOST:</span> <?php echo htmlspecialchars(getenv('DB_HOST') ?: 'localhost'); ?></div>
                <div><span style="color: var(--color-cyan-pulse);">DB_PORT:</span> <?php echo htmlspecialchars(getenv('DB_PORT') ?: '3306'); ?></div>
                <div><span style="color: var(--color-cyan-pulse);">DB_NAME:</span> <?php echo htmlspecialchars(getenv('DB_NAME') ?: 'great_endured_db'); ?></div>
                <div><span style="color: var(--color-cyan-pulse);">DB_USER:</span> <?php echo htmlspecialchars(getenv('DB_USER') ?: ''); ?></div>
                <div><span style="color: var(--color-cyan-pulse);">DB_PASS:</span> <?php echo htmlspecialchars(maskPassword(getenv('DB_PASS') !== false ? getenv('DB_PASS') : '')); ?></div>
            </div>
            <p style="font-size: 0.75rem; color: var(--color-mist); margin-top: 10px; margin-bottom: 0;">Settings pulled directly from local environmental `.env` files.</p>
        </div>

        <!-- SQL Schema execution panel -->
        <div class="card" style="padding: 25px; border-radius: 16px;">
            <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 10px;">
                SQL Database Schema Management
            </h3>
            <p style="color: var(--color-fog); font-size: 0.85rem; margin-top: 0; margin-bottom: 20px;">Re-trigger structure tables configurations. Safely running migrations will re-scaffold the `leads` data schema in the database.</p>

            <div class="form-alert form-alert-success" id="migration-success"></div>
            <div class="form-alert form-alert-error" id="migration-error"></div>

            <div style="display: flex; gap: 15px;">
                <button class="btn btn-primary" id="run-migration-btn" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #10b981;">
                    Execute SQL Migration
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const migrationBtn = document.getElementById('run-migration-btn');
    const successAlert = document.getElementById('migration-success');
    const errorAlert = document.getElementById('migration-error');
    const csrfToken = "<?php echo csrf_token(); ?>";

    migrationBtn.addEventListener('click', async () => {
        if (!confirm('Warning: Re-running SQL migrations will create/verify tables schema. SQL changes will be logged in setup.log.md. Proceed?')) {
            return;
        }

        successAlert.style.display = 'none';
        errorAlert.style.display = 'none';
        migrationBtn.disabled = true;
        migrationBtn.textContent = 'Migrating tables...';

        const formData = new FormData();
        formData.append('csrf_token', csrfToken);

        try {
            const response = await fetch('/admin/database/migrate', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                successAlert.textContent = data.message;
                successAlert.style.display = 'block';
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(data.message || 'Migration execution failed.');
            }
        } catch (err) {
            errorAlert.textContent = err.message;
            errorAlert.style.display = 'block';
        } finally {
            migrationBtn.disabled = false;
            migrationBtn.textContent = 'Execute SQL Migration';
        }
    });
});
</script>
