<?php
/**
 * Great Endured Technology — Admin Emails List Template
 * Governed by RBP (AGENTS.md)
 */
declare(strict_types=1);
?>
<div style="margin-bottom: 30px;" class="flex-between">
    <div>
        <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">Email Outbox & Broadcast</h2>
        <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">View broadcast history or compose a new email broadcast to leads.</p>
    </div>
    <a href="/admin/emails?action=write" class="btn btn-primary" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #10b981; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; height: 38px; line-height: 20px;">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Compose Broadcast
    </a>
</div>

<div class="card" style="padding: 30px; border-radius: 16px;">
    <h3 style="font-size: 1.15rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px;">
        Outbox Log History
    </h3>

    <?php if (empty($sentEmails)): ?>
        <p style="color: var(--color-mist); text-align: center; padding: 40px 0; margin: 0;">No broadcast messages sent yet.</p>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <?php foreach ($sentEmails as $email): ?>
                <div class="flex-between" 
                     style="background: rgba(255,255,255,0.01); padding: 15px 20px; border-radius: 10px; border: 1px solid var(--glass-border); align-items: center; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="color: var(--color-cyan-pulse);">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 22px; height: 22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                        </div>
                        <div>
                            <h4 style="margin: 0; color: var(--color-white); font-size: 1rem;"><?php echo htmlspecialchars($email['subject']); ?></h4>
                            <span style="font-size: 0.85rem; color: var(--color-mist);">To: <strong style="color: var(--color-cyan-pulse);"><?php echo htmlspecialchars($email['to']); ?></strong></span>
                        </div>
                    </div>
                    
                    <div style="font-size: 0.8rem; color: var(--color-mist); font-weight: 500;">
                        <?php echo htmlspecialchars($email['date']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
