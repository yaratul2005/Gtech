<?php
/**
 * Great Endured Technology — GDPR/CCPA Cookie Consent Manager
 * Governed by RBP (AGENTS.md)
 */
declare(strict_types=1);
?>
<!-- Cookie Consent Modal Banner -->
<div id="cookie-consent-banner" class="cookie-banner" style="display: none;">
    <!-- Main summary view -->
    <div id="cookie-main-view" class="cookie-view">
        <div class="cookie-header">
            <div class="cookie-icon-wrapper">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 22px; height: 22px; color: var(--color-cyan-pulse);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-11.314l.707.707m11.314 11.314l.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
            </div>
            <h3>Cookie Consent</h3>
        </div>
        <div class="cookie-body">
            <p>We use cookies to optimize site performance, analyze traffic statistics, and personalize your experience according to GDPR and CCPA international guidelines. Customize your preferences below.</p>
        </div>
        <div class="cookie-actions">
            <button id="cookie-accept-all" class="btn btn-primary btn-sm">Accept All</button>
            <button id="cookie-customize" class="btn btn-secondary btn-sm" style="border-color: rgba(255,255,255,0.05); color: var(--color-white);">Customize</button>
            <button id="cookie-reject-all" class="btn btn-secondary btn-sm" style="border-color: rgba(255,255,255,0.05); color: var(--color-mist);">Necessary Only</button>
        </div>
    </div>

    <!-- Granular Preferences view (Switch Design) -->
    <div id="cookie-settings-view" class="cookie-view" style="display: none;">
        <div class="cookie-header">
            <h3>Cookie Preferences</h3>
        </div>
        <div class="cookie-settings-list">
            <!-- Necessary Toggle -->
            <div class="cookie-setting-item">
                <div class="cookie-setting-info">
                    <h4>Strictly Necessary</h4>
                    <p>Required for security, basic routing, and storing your consent preferences. Cannot be disabled.</p>
                </div>
                <div class="cookie-toggle-wrapper">
                    <label class="cookie-switch">
                        <input type="checkbox" id="cookie-toggle-necessary" checked disabled>
                        <span class="cookie-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Performance / Analytics Toggle -->
            <div class="cookie-setting-item">
                <div class="cookie-setting-info">
                    <h4>Analytics & Performance</h4>
                    <p>Allows us to monitor visitor traffic and aggregate metrics to optimize site performance.</p>
                </div>
                <div class="cookie-toggle-wrapper">
                    <label class="cookie-switch">
                        <input type="checkbox" id="cookie-toggle-analytics">
                        <span class="cookie-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Marketing / Ads Toggle -->
            <div class="cookie-setting-item">
                <div class="cookie-setting-info">
                    <h4>Targeting & Advertising</h4>
                    <p>Allows tracking conversion pixels and optimizing marketing campaigns across platforms (e.g. Meta Ads).</p>
                </div>
                <div class="cookie-toggle-wrapper">
                    <label class="cookie-switch">
                        <input type="checkbox" id="cookie-toggle-marketing">
                        <span class="cookie-slider"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="cookie-actions" style="margin-top: 15px;">
            <button id="cookie-save-settings" class="btn btn-primary btn-sm">Save Preferences</button>
            <button id="cookie-back" class="btn btn-secondary btn-sm" style="border-color: rgba(255,255,255,0.05); color: var(--color-white);">Back</button>
        </div>
    </div>
</div>

<style>
.cookie-banner {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 380px;
    max-width: calc(100vw - 60px);
    background: linear-gradient(135deg, rgba(17, 22, 34, 0.96) 0%, rgba(9, 13, 22, 0.99) 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 30px rgba(0, 212, 255, 0.08);
    padding: 25px;
    z-index: 9999;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease;
    opacity: 0;
    transform: translateY(30px);
}
.cookie-banner.show {
    opacity: 1;
    transform: translateY(0);
}
.cookie-view {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.cookie-header {
    display: flex;
    align-items: center;
    gap: 12px;
}
.cookie-header h3 {
    margin: 0;
    font-size: 1.1rem;
    color: var(--color-white);
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
}
.cookie-icon-wrapper {
    background: rgba(0, 212, 255, 0.05);
    border: 1px solid rgba(0, 212, 255, 0.15);
    border-radius: 10px;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cookie-body p {
    margin: 0;
    font-size: 0.82rem;
    color: var(--color-fog);
    line-height: 1.5;
}
.cookie-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.cookie-actions .btn {
    flex-grow: 1;
    font-size: 0.8rem;
    padding: 10px 16px;
    height: auto;
}
.cookie-settings-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-height: 250px;
    overflow-y: auto;
    padding-right: 5px;
}
.cookie-setting-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}
.cookie-setting-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.cookie-setting-info h4 {
    margin: 0 0 4px 0;
    font-size: 0.88rem;
    color: var(--color-white);
    font-family: 'Outfit', sans-serif;
}
.cookie-setting-info p {
    margin: 0;
    font-size: 0.72rem;
    color: var(--color-mist);
    line-height: 1.4;
}
.cookie-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}
.cookie-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.cookie-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
    transition: 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    border-radius: 24px;
}
.cookie-slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background-color: var(--color-mist);
    transition: 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
}
input:checked + .cookie-slider {
    background-color: rgba(0, 212, 255, 0.15);
    border-color: rgba(0, 212, 255, 0.35);
}
input:checked + .cookie-slider:before {
    transform: translateX(20px);
    background-color: var(--color-cyan-pulse);
    box-shadow: 0 0 8px rgba(0, 212, 255, 0.5);
}
input:disabled + .cookie-slider {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Custom Scrollbar for list */
.cookie-settings-list::-webkit-scrollbar {
    width: 4px;
}
.cookie-settings-list::-webkit-scrollbar-track {
    background: transparent;
}
.cookie-settings-list::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .cookie-banner {
        bottom: 20px;
        right: 20px;
        left: 20px;
        width: auto;
        max-width: none;
        padding: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('cookie-consent-banner');
    const mainView = document.getElementById('cookie-main-view');
    const settingsView = document.getElementById('cookie-settings-view');
    
    const acceptAllBtn = document.getElementById('cookie-accept-all');
    const rejectAllBtn = document.getElementById('cookie-reject-all');
    const customizeBtn = document.getElementById('cookie-customize');
    const saveSettingsBtn = document.getElementById('cookie-save-settings');
    const backBtn = document.getElementById('cookie-back');
    
    const toggleAnalytics = document.getElementById('cookie-toggle-analytics');
    const toggleMarketing = document.getElementById('cookie-toggle-marketing');

    // Read stored consent cookie/localStorage
    const savedConsent = JSON.parse(localStorage.getItem('cookie_consent') || 'null');

    if (!savedConsent) {
        // Slide up modal after 1.5 seconds delay for premium experience
        setTimeout(() => {
            banner.style.display = 'block';
            banner.offsetHeight; // trigger layout reflow
            banner.classList.add('show');
        }, 1500);
    } else {
        applyConsent(savedConsent);
    }

    customizeBtn.addEventListener('click', () => {
        mainView.style.display = 'none';
        settingsView.style.display = 'flex';
    });

    backBtn.addEventListener('click', () => {
        settingsView.style.display = 'none';
        mainView.style.display = 'flex';
    });

    acceptAllBtn.addEventListener('click', () => {
        const prefs = { necessary: true, analytics: true, marketing: true };
        saveConsent(prefs);
    });

    rejectAllBtn.addEventListener('click', () => {
        const prefs = { necessary: true, analytics: false, marketing: false };
        saveConsent(prefs);
    });

    saveSettingsBtn.addEventListener('click', () => {
        const prefs = {
            necessary: true,
            analytics: toggleAnalytics.checked,
            marketing: toggleMarketing.checked
        };
        saveConsent(prefs);
    });

    function saveConsent(prefs) {
        localStorage.setItem('cookie_consent', JSON.stringify(prefs));
        applyConsent(prefs);
        
        banner.classList.remove('show');
        setTimeout(() => {
            banner.style.display = 'none';
        }, 400);
    }

    function applyConsent(prefs) {
        // Dispatch standard event so other scripts can dynamically initialize
        window.dispatchEvent(new CustomEvent('cookieConsentUpdated', { detail: prefs }));
        
        window.allowedAnalytics = prefs.analytics;
        window.allowedMarketing = prefs.marketing;

        // Custom logic triggers can hook in here
        if (prefs.analytics) {
            console.log('[Consent] Analytics scripts active.');
        }
        if (prefs.marketing) {
            console.log('[Consent] Marketing/Tracking tags active.');
        }
    }
});
</script>
