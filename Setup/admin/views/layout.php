<?php
/**
 * Great Endured Technology — Admin Layout Shell
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

$isLoggedIn = !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$currentTitle = $title ?? 'GET Control Panel';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($currentTitle); ?> | GET Control Panel</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Main Style Sheet (app.css) -->
    <link rel="stylesheet" href="/Front/static/css/app.css">
    
    <style>
        :root {
            --admin-glow: rgba(0, 212, 255, 0.4);
            --sidebar-width: 260px;
        }

        body {
            background-color: var(--color-void);
            background-image: radial-gradient(circle at 50% -20%, var(--color-blue-deep) 0%, var(--color-void) 80%);
            min-height: 100vh;
            color: var(--color-white);
        }

        .admin-nav {
            border-bottom: 1px solid var(--glass-border);
            padding: 15px 0;
            background-color: rgba(9, 13, 22, 0.85);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-layout {
            display: flex;
            min-height: calc(100vh - 73px);
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 15px;
            gap: 30px;
        }

        .admin-sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0;
        }

        .admin-sidebar-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.01) 0%, rgba(255, 255, 255, 0.03) 100%);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: sticky;
            top: 103px;
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .admin-sidebar-header {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--color-cyan-pulse);
            letter-spacing: 0.15em;
            margin-bottom: 15px;
            font-weight: 700;
            padding-left: 10px;
            border-left: 2px solid var(--color-cyan-pulse);
        }

        .admin-sidebar-link {
            padding: 12px 15px;
            border-radius: 10px;
            color: var(--color-fog);
            font-weight: 500;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
            border-left: 3px solid transparent;
        }

        .admin-sidebar-link svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
            transition: stroke var(--transition-fast);
        }

        .admin-sidebar-link:hover, .admin-sidebar-link.active {
            background: linear-gradient(90deg, rgba(0, 212, 255, 0.06) 0%, rgba(0, 212, 255, 0.01) 100%);
            color: var(--color-white);
            border-left: 3px solid var(--color-cyan-pulse);
            box-shadow: inset 1px 0 0 rgba(0, 212, 255, 0.1);
        }

        .admin-sidebar-link.active svg {
            color: var(--color-cyan-pulse);
            filter: drop-shadow(0 0 4px var(--admin-glow));
        }

        .admin-main {
            flex-grow: 1;
            min-width: 0; /* Prevent flex overflow */
        }

        /* Stats Cards */
        .metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.01) 0%, rgba(255, 255, 255, 0.03) 100%);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: border var(--transition-fast), transform var(--transition-fast);
        }

        .metric-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
        }

        .metric-card:hover {
            border-color: rgba(0, 212, 255, 0.2);
            transform: translateY(-2px);
        }

        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .metric-title {
            font-size: 0.85rem;
            color: var(--color-mist);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }

        .metric-icon {
            color: var(--color-mist);
            opacity: 0.7;
        }

        .metric-num {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--color-white);
            margin: 15px 0 5px 0;
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        .metric-desc {
            font-size: 0.8rem;
            color: var(--color-fog);
        }

        /* Tabs & Styling */
        .admin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 15px;
        }

        .admin-table th {
            padding: 14px 16px;
            background-color: rgba(255, 255, 255, 0.01);
            color: var(--color-white);
            border-bottom: 1px solid var(--glass-border);
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .admin-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            color: var(--color-fog);
            font-size: 0.92rem;
            vertical-align: middle;
        }

        .admin-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.01);
            color: var(--color-white);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.05em;
        }

        .status-badge-active {
            background-color: rgba(16, 185, 129, 0.06);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.15);
        }

        .status-badge-inactive {
            background-color: rgba(239, 68, 68, 0.06);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.15);
        }

        @media (max-width: 900px) {
            .admin-layout {
                flex-direction: column;
            }
            .admin-sidebar {
                width: 100%;
            }
            .admin-sidebar-card {
                position: static;
                flex-direction: row;
                flex-wrap: wrap;
            }
            .admin-sidebar-link {
                flex-grow: 1;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Fluid Page Transition Loader -->
    <div id="fluid-wipe-overlay" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: #030508;
        z-index: 999999;
        transform: translateY(0);
        transition: transform 0.7s cubic-bezier(0.85, 0, 0.15, 1);
        pointer-events: all;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    ">
        <!-- Floating organic loader in center -->
        <div style="position: relative; display: flex; flex-direction: column; align-items: center; gap: 20px; z-index: 10;">
            <div class="fluid-glow" style="
                width: 70px;
                height: 70px;
                border-radius: 50%;
                background: #00f2fe;
                box-shadow: 0 0 35px #00f2fe, 0 0 70px #4facfe;
                animation: fluidRotate 2.2s infinite alternate ease-in-out;
                filter: blur(1px);
            "></div>
            <div style="font-family: 'Outfit', sans-serif; font-size: 0.85rem; letter-spacing: 0.2em; text-transform: uppercase; color: #00f2fe; font-weight: 700; opacity: 0.8; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5);">
                Loading...
            </div>
        </div>

        <!-- Animated Wave Background Layer 1 -->
        <div class="loader-wave wave1" style="
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 350px;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMjAwIDEyMCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PHBhdGggZD0iTTAsNjAgQzMwMCwxMjAgNjAwLDAgOTAwLDYwIEwxMjAwLDYwIEwxMjAwLDEyMCBMMCwxMjAgWiIgZmlsbD0iIzA4MWIyZiIgb3BhY2l0eT0iMC40Ii8+PC9zdmc+') repeat-x;
            background-size: 50% 100%;
            animation: waveFlow 12s linear infinite;
            z-index: 2;
        "></div>

        <!-- Animated Wave Background Layer 2 -->
        <div class="loader-wave wave2" style="
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 380px;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMjAwIDEyMCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PHBhdGggZD0iTTAsNTAgQzM1MCwxMTAgNTUwLDEwIDg1MCw2MCBMMTIwMCw2MCBMMTIwMCwxMjAgTDAsMTIwIFoiIGZpbGw9IiMwZDMyNTQiIG9wYWNpdHk9IjAuMyIvPjwvc3ZnPg==') repeat-x;
            background-size: 50% 100%;
            animation: waveFlowReverse 16s linear infinite;
            z-index: 1;
        "></div>

        <!-- Animated Wave Background Layer 3 (Foreground Deep Wave) -->
        <div class="loader-wave wave3" style="
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 320px;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMjAwIDEyMCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PHBhdGggZD0iTTAsNDAgQzI1MCw5MCA2NTAsMjAgOTUwLDUwIEwxMjAwLDYwIEwxMjAwLDEyMCBMMCwxMjAgWiIgZmlsbD0iIzAwZjJmZSIgb3BhY2l0eT0iMC4xNSIvPjwvc3ZnPg==') repeat-x;
            background-size: 50% 100%;
            animation: waveFlow 8s linear infinite;
            z-index: 3;
        "></div>

        <!-- Wavy bottom boundary for the curtain transition -->
        <div style="
            position: absolute;
            bottom: -98px;
            left: 0;
            width: 100%;
            height: 100px;
            pointer-events: none;
            z-index: 5;
        ">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width: 100%; height: 100%; display: block; fill: #030508;">
                <path d="M0,0 L1200,0 L1200,60 C900,120 600,0 300,60 L0,30 Z"></path>
            </svg>
        </div>
    </div>

    <style>
    @keyframes fluidRotate {
        0% { transform: scale(1) rotate(0deg); border-radius: 50% 50% 50% 50% / 50% 50% 50% 50%; }
        33% { transform: scale(1.1) rotate(120deg); border-radius: 40% 60% 45% 55% / 40% 45% 55% 60%; }
        66% { transform: scale(0.9) rotate(240deg); border-radius: 55% 45% 60% 40% / 50% 60% 40% 50%; }
        100% { transform: scale(1) rotate(360deg); border-radius: 50% 50% 50% 50% / 50% 50% 50% 50%; }
    }
    @keyframes waveFlow {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    @keyframes waveFlowReverse {
        0% { transform: translateX(-50%); }
        100% { transform: translateX(0); }
    }
    </style>

    <script>
    (function() {
        const hideLoader = () => {
            const overlay = document.getElementById('fluid-wipe-overlay');
            if (overlay) {
                overlay.style.transform = 'translateY(100%)';
                overlay.style.pointerEvents = 'none';
            }
        };

        // Failsafe: force hide loader after 1.5 seconds max
        setTimeout(hideLoader, 1500);

        // Immediate check if document is already ready
        if (document.readyState === 'interactive' || document.readyState === 'complete') {
            setTimeout(hideLoader, 150);
        } else {
            window.addEventListener('DOMContentLoaded', () => {
                setTimeout(hideLoader, 150);
            });
        }

        // Intercept clicks on document (safe to register in head)
        document.addEventListener('click', e => {
            const link = e.target.closest('a');
            if (!link) return;
            
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
                return;
            }

            try {
                const url = new URL(link.href, window.location.href);
                const isInternal = url.origin === window.location.origin;
                const isSpecial = link.getAttribute('target') === '_blank' || 
                                  e.metaKey || e.ctrlKey || e.shiftKey || e.altKey;
                
                if (isInternal && !isSpecial) {
                    e.preventDefault();
                    const overlay = document.getElementById('fluid-wipe-overlay');
                    if (overlay) {
                        overlay.style.transition = 'none';
                        overlay.style.transform = 'translateY(-100%)';
                        overlay.offsetHeight;
                        overlay.style.transition = 'transform 0.6s cubic-bezier(0.85, 0, 0.15, 1)';
                        overlay.style.transform = 'translateY(0)';
                        overlay.style.pointerEvents = 'all';
                        setTimeout(() => {
                            window.location.href = link.href;
                        }, 550);
                    } else {
                        window.location.href = link.href;
                    }
                }
            } catch (err) {
                // Fallback to normal navigation if URL parsing fails
            }
        });

        // Trigger on form submits to show loading
        document.addEventListener('submit', e => {
            const form = e.target.closest('form');
            if (!form) return;
            const action = form.getAttribute('action') || '';
            if (!action.includes('delete') && !action.includes('update')) {
                const overlay = document.getElementById('fluid-wipe-overlay');
                if (overlay) {
                    overlay.style.transition = 'none';
                    overlay.style.transform = 'translateY(-100%)';
                    overlay.offsetHeight;
                    overlay.style.transition = 'transform 0.6s cubic-bezier(0.85, 0, 0.15, 1)';
                    overlay.style.transform = 'translateY(0)';
                    overlay.style.pointerEvents = 'all';
                }
            }
        });

        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                hideLoader();
            }
        });
    })();
    </script>

    <!-- Admin Header -->
    <header class="admin-nav">
        <div class="container flex-between">
            <a href="/admin/dashboard" class="logo" style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.35rem; display: flex; align-items: center; gap: 8px;">
                <span style="background: linear-gradient(135deg, var(--color-cyan-pulse) 0%, var(--color-blue-glow) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">GET</span>
                <span style="color: var(--color-white); font-size: 0.8rem; font-weight: 600; border: 1px solid var(--glass-border); padding: 2px 6px; border-radius: 4px; background: rgba(255,255,255,0.03);">CP</span>
            </a>
            <?php if ($isLoggedIn): ?>
                <div class="flex" style="align-items: center; gap: 15px;">
                    <a href="/" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.8rem; border-color: rgba(255,255,255,0.05);">Live Site ↗</a>
                    <a href="/admin/logout" class="btn btn-primary btn-sm" style="font-size: 0.8rem; background: var(--color-ink); border-color: var(--glass-border);">Log Out</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Content Shell -->
    <div class="admin-layout">
        <?php if ($isLoggedIn): ?>
            <!-- Left Navigation Sidebar -->
            <aside class="admin-sidebar">
                <div class="admin-sidebar-card">
                    <div class="admin-sidebar-header">Core CMS Console</div>
                    
                    <a href="/admin/dashboard" class="admin-sidebar-link <?php echo $currentTitle === 'Admin Dashboard' ? 'active' : ''; ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                        Dashboard
                    </a>
                    
                    <a href="/admin/leads" class="admin-sidebar-link <?php echo $currentTitle === 'Manage Leads' ? 'active' : ''; ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        Lead Inquiries
                    </a>
                    
                    <a href="/admin/services" class="admin-sidebar-link <?php echo $currentTitle === 'Services CMS Control' ? 'active' : ''; ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        Services CMS
                    </a>

                    <a href="/admin/portfolio" class="admin-sidebar-link <?php echo $currentTitle === 'Portfolio Manager' ? 'active' : ''; ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.9 2.9m-12.487-6.75H19.5a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75a2.25 2.25 0 012.25-2.25z"/></svg>
                        Portfolio CMS
                    </a>

                    <a href="/admin/team" class="admin-sidebar-link <?php echo $currentTitle === 'Team Manager' ? 'active' : ''; ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A9.642 9.642 0 009.75 20.25a9.655 9.655 0 00-5.25-1.013v-.109m0 .109c0-1.113.285-2.16.786-3.07M4.875 19.128c-.621 0-1.125-.504-1.125-1.125V18a4.125 4.125 0 017.533-2.493M15 9.75a3 3 0 11-6 0 3 3 0 016 0zM7.5 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Team CMS
                    </a>

                    <a href="/admin/pages" class="admin-sidebar-link <?php echo $currentTitle === 'Page CMS Manager' ? 'active' : ''; ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        Page CMS
                    </a>

                    <a href="/admin/posts" class="admin-sidebar-link <?php echo $currentTitle === 'Blog CMS Manager' ? 'active' : ''; ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18V6.125C3 5.504 3.504 5 4.125 5H8.25v2.25A2.25 2.25 0 0010.5 9.5H12v-2z"/></svg>
                        Blog CMS
                    </a>
                    
                    <div class="admin-sidebar-header" style="margin-top: 15px;">Site Controls</div>

                    <a href="/admin/settings" class="admin-sidebar-link <?php echo $currentTitle === 'Settings & SMTP CP' ? 'active' : ''; ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"/></svg>
                        Site & SMTP Settings
                    </a>

                    <a href="/admin/database" class="admin-sidebar-link <?php echo $currentTitle === 'Database Tools' ? 'active' : ''; ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0v3.75m-16.5-3.75v3.75"/></svg>
                        Database Tools
                    </a>
                </div>
            </aside>
            
            <!-- Content Panel -->
            <main class="admin-main">
                <?php echo $content; ?>
            </main>
        <?php else: ?>
            <!-- Login template -->
            <main class="admin-main" style="max-width: 420px; margin: 40px auto 0 auto;">
                <?php echo $content; ?>
            </main>
        <?php endif; ?>
    </div>
</body>
</html>
