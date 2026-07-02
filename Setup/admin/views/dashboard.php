<?php
/**
 * Great Endured Technology — Admin Dashboard Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);
?>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 1.8rem; margin-bottom: 5px;">Dashboard Overview</h2>
    <p style="color: var(--color-mist); font-size: 0.95rem; margin: 0;">System status and recent activities.</p>
</div>

<!-- Metrics Grids -->
<div class="grid grid-2" style="margin-bottom: 40px;">
    <div class="metric-card">
        <h3 style="font-size: 1rem; color: var(--color-mist); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 500;">Total Contact Leads</h3>
        <div class="metric-num"><?php echo $leadsCount; ?></div>
        <p style="font-size: 0.85rem; color: var(--color-mist); margin: 0;">Inquiries saved in file database.</p>
    </div>
    
    <div class="metric-card">
        <h3 style="font-size: 1rem; color: var(--color-mist); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 500;">Active Agency Services</h3>
        <div class="metric-num"><?php echo $servicesCount; ?></div>
        <p style="font-size: 0.85rem; color: var(--color-mist); margin: 0;">Defined services in CMS catalog.</p>
    </div>
</div>

<!-- Recent Leads table -->
<div class="card" style="padding: 30px;">
    <div class="flex-between" style="margin-bottom: 20px;">
        <h3 style="font-size: 1.25rem;">Recent Inquiries</h3>
        <a href="/admin/leads" class="btn btn-secondary btn-sm">Manage All Leads</a>
    </div>

    <?php if (empty($recentLeads)): ?>
        <p style="color: var(--color-mist); text-align: center; padding: 30px 0; margin: 0;">No inquiries received yet.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Service</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentLeads as $lead): ?>
                    <tr>
                        <td style="font-size: 0.85rem; white-space: nowrap;"><?php echo date('M d, Y H:i', strtotime($lead['created_at'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($lead['name']); ?></strong></td>
                        <td><a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>" style="color: var(--color-cyan-pulse);"><?php echo htmlspecialchars($lead['email']); ?></a></td>
                        <td><span style="background: rgba(0, 212, 255, 0.05); padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; border: 1px solid rgba(0,212,255,0.1);"><?php echo htmlspecialchars($lead['service']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
