<?php
/**
 * Great Endured Technology — Admin Login Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

// Diagnostics Check (Shared hosting environment validation)
$warnings = [];

if (version_compare(phpversion(), '8.0.0', '<')) {
    $warnings[] = "PHP Version is " . phpversion() . ". PHP 8.0.0 or higher is required. Please change your PHP version in cPanel.";
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    $warnings[] = "PHP session is not active. Check session settings.";
} else {
    $_SESSION['sys_sess_test'] = true;
    if (empty($_SESSION['sys_sess_test'])) {
        $warnings[] = "PHP sessions are not persisting. Verify session save path with your host.";
    }
}

$cacheDir = __DIR__ . '/../../../Vault/cache';
if (!is_writable($cacheDir)) {
    $warnings[] = "Cache folder is not writable. Set `/Vault/cache` permissions to 0755 in cPanel File Manager.";
}

?>

<div class="card" style="text-align: center; padding: 40px;">
    <h2 style="margin-bottom: 10px;">Admin Access</h2>
    <p style="color: var(--color-mist); font-size: 0.9rem; margin-bottom: 30px;">Authenticate to access site control settings.</p>

    <!-- Diagnostics Warnings -->
    <?php foreach ($warnings as $warn): ?>
        <div class="form-alert form-alert-error" style="display: block; font-size: 0.8rem; padding: 10px; margin-bottom: 15px; text-align: left; background-color: rgba(239,68,68,0.05);">
            ⚠️ <?php echo htmlspecialchars($warn); ?>
        </div>
    <?php endforeach; ?>

    <?php if (isset($error)): ?>
        <div class="form-alert form-alert-error" style="display: block; margin-bottom: 20px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="/admin/login" method="POST">
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-input" placeholder="Enter username" required autofocus>
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-input" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-full">Log In</button>
    </form>
</div>
