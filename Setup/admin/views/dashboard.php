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
            <span class="metric-icon">
                <svg style="width: 24px; height: 24px; color: var(--color-cyan-pulse);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
            </span>
        </div>
        <div class="metric-num"><?php echo $leadsCount; ?></div>
        <p class="metric-desc">Submissions saved in Vault.</p>
    </div>
    
    <div class="metric-card">
        <div class="metric-header">
            <span class="metric-title">Agency Services</span>
            <span class="metric-icon">
                <svg style="width: 24px; height: 24px; color: var(--color-cyan-pulse);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
            </span>
        </div>
        <div class="metric-num"><?php echo $servicesCount; ?></div>
        <p class="metric-desc">Dynamic descriptions in catalog.</p>
    </div>
    
    <div class="metric-card">
        <div class="metric-header">
            <span class="metric-title">App Mode</span>
            <span class="metric-icon">
                <svg style="width: 24px; height: 24px; color: var(--color-cyan-pulse);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"/></svg>
            </span>
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
