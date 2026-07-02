<?php
/**
 * Great Endured Technology — Admin Dashboard Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 1.8rem; margin-bottom: 5px; font-family: 'Outfit', sans-serif;">System Dashboard</h2>
    <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">Real-time overview of Great Endured Technology operations.</p>
</div>

<!-- Metrics Cards Grid -->
<div class="metric-grid">
    <div class="metric-card">
        <div class="metric-header">
            <span class="metric-title">Lead Inquiries</span>
            <span class="metric-icon">✉️</span>
        </div>
        <div class="metric-num"><?php echo $leadsCount; ?></div>
        <p class="metric-desc">Submissions saved in Vault.</p>
    </div>
    
    <div class="metric-card">
        <div class="metric-header">
            <span class="metric-title">Agency Services</span>
            <span class="metric-icon">⚡</span>
        </div>
        <div class="metric-num"><?php echo $servicesCount; ?></div>
        <p class="metric-desc">Dynamic descriptions in catalog.</p>
    </div>
    
    <div class="metric-card">
        <div class="metric-header">
            <span class="metric-title">App Mode</span>
            <span class="metric-icon">⚙️</span>
        </div>
        <div class="metric-num" style="font-size: 1.5rem; margin: 28px 0 16px 0; text-transform: uppercase; color: var(--color-cyan-pulse);">
            <?php echo htmlspecialchars(getenv('APP_ENV') ?: 'Production'); ?>
        </div>
        <p class="metric-desc">Configured runtime target env.</p>
    </div>
</div>

<!-- Recent Leads table -->
<div class="card" style="padding: 30px; border-radius: 16px;">
    <div class="flex-between" style="margin-bottom: 25px; align-items: center;">
        <h3 style="font-size: 1.25rem; font-family: 'Outfit', sans-serif; margin: 0;">Recent Contact Submissions</h3>
        <a href="/admin/leads" class="btn btn-secondary btn-sm" style="font-size: 0.8rem; border-color: rgba(255,255,255,0.05);">Manage All Inquiries</a>
    </div>

    <?php if (empty($recentLeads)): ?>
        <p style="color: var(--color-mist); text-align: center; padding: 40px 0; margin: 0; font-size: 0.95rem;">No leads have been received yet.</p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Inquiry Service</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentLeads as $lead): ?>
                        <tr>
                            <td style="font-size: 0.85rem; white-space: nowrap;"><?php echo date('M d, Y H:i', strtotime($lead['created_at'])); ?></td>
                            <td><strong style="color: var(--color-white);"><?php echo htmlspecialchars($lead['name']); ?></strong></td>
                            <td><a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>" style="color: var(--color-cyan-pulse); text-decoration: none;"><?php echo htmlspecialchars($lead['email']); ?></a></td>
                            <td>
                                <span style="background: rgba(0, 212, 255, 0.05); padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; border: 1px solid rgba(0, 212, 255, 0.15); color: var(--color-cyan-pulse); font-weight: 500;">
                                    <?php echo htmlspecialchars($lead['service']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
