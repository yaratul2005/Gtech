<?php
/**
 * Great Endured Technology — Admin Layout Shell
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

$isLoggedIn = !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'GET Control Panel'); ?> | GET Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Main Style Sheet (Reuse app.css styling variables) -->
    <link rel="stylesheet" href="/Front/static/css/app.css">
    
    <style>
        .admin-nav {
            border-bottom: 1px solid var(--glass-border);
            padding: 15px 0;
            background-color: var(--color-ink);
        }
        .admin-wrapper {
            padding: 60px 0;
            min-height: calc(100vh - 80px - 100px);
        }
        .admin-sidebar-card {
            background-color: var(--glass-fill);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .admin-sidebar-link {
            padding: 10px 15px;
            border-radius: 8px;
            color: var(--color-fog);
            font-weight: 500;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-sidebar-link:hover, .admin-sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--color-white);
            border-left: 3px solid var(--color-cyan-pulse);
            padding-left: 12px;
        }
        .metric-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.02) 0%, rgba(255, 255, 255, 0.05) 100%);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
        }
        .metric-num {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--color-cyan-pulse);
            margin: 10px 0;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin-top: 20px;
        }
        .admin-table th {
            padding: 12px 15px;
            background-color: var(--color-ink);
            color: var(--color-white);
            border-bottom: 1px solid var(--glass-border);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .admin-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            color: var(--color-fog);
            font-size: 0.95rem;
        }
        .admin-table tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }
    </style>
</head>
<body style="background-color: var(--color-void); background-image: radial-gradient(circle at 50% -20%, var(--color-blue-deep) 0%, var(--color-void) 70%); min-height: 100vh;">

    <!-- Admin Header -->
    <header class="admin-nav">
        <div class="container flex-between">
            <a href="/admin/dashboard" class="logo">
                <span>GET CP.</span>
            </a>
            <?php if ($isLoggedIn): ?>
                <div class="flex" style="align-items: center; gap: 20px;">
                    <span style="font-size: 0.9rem; color: var(--color-mist);">Logged in as Admin</span>
                    <a href="/admin/logout" class="btn btn-secondary btn-sm">Log Out</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Admin Content -->
    <main class="admin-wrapper">
        <div class="container">
            <?php if ($isLoggedIn): ?>
                <div class="grid grid-4" style="align-items: start; gap: 30px;">
                    <!-- Sidebar menu -->
                    <div style="grid-column: span 1;">
                        <div class="admin-sidebar-card">
                            <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--color-mist); letter-spacing: 0.1em; margin-bottom: 10px;">Control Panel</h4>
                            <a href="/admin/dashboard" class="admin-sidebar-link <?php echo $title === 'Admin Dashboard' ? 'active' : ''; ?>">
                                Dashboard
                            </a>
                            <a href="/admin/leads" class="admin-sidebar-link <?php echo $title === 'Manage Leads' ? 'active' : ''; ?>">
                                Manage Leads
                            </a>
                            <a href="/admin/services" class="admin-sidebar-link <?php echo $title === 'Services CMS Control' ? 'active' : ''; ?>">
                                Services CMS
                            </a>
                            <a href="/" target="_blank" class="admin-sidebar-link" style="border-top: 1px solid rgba(255, 255, 255, 0.05); margin-top: 10px;">
                                View Site ↗
                            </a>
                        </div>
                    </div>
                    
                    <!-- Content area -->
                    <div style="grid-column: span 3;">
                        <?php echo $content; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Login shell -->
                <div style="max-width: 420px; margin: 40px auto 0 auto;">
                    <?php echo $content; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
