<?php
/**
 * Great Endured Technology — Admin Login Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<div class="card" style="text-align: center; padding: 40px;">
    <h2 style="margin-bottom: 10px;">Admin Access</h2>
    <p style="color: var(--color-mist); font-size: 0.9rem; margin-bottom: 30px;">Authenticate to access site control settings.</p>

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
