<?php
$cookieConsentScript = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$cookieConsentBase = rtrim(dirname($cookieConsentScript), '/');
$cookieConsentDirectory = basename($cookieConsentBase);
if ($cookieConsentDirectory === 'users' || $cookieConsentDirectory === 'admin') {
    $cookieConsentBase = dirname($cookieConsentBase);
}
if ($cookieConsentBase === '/' || $cookieConsentBase === '.') {
    $cookieConsentBase = '';
}
$cookieConsentBase = htmlspecialchars($cookieConsentBase, ENT_QUOTES, 'UTF-8');
?>
<link rel="stylesheet" href="<?= $cookieConsentBase ?>/assets/css/cookie-consent.css">
<div class="cookie-consent" id="cookie-consent" hidden>
    <div class="cookie-consent__overlay" aria-hidden="true"></div>
    <section class="cookie-consent__dialog" role="dialog" aria-modal="false" aria-labelledby="cookie-consent-title" aria-describedby="cookie-consent-description" tabindex="-1">
        <div class="cookie-consent__header">
            <h2 id="cookie-consent-title">We value your privacy</h2>
            <p id="cookie-consent-description">We use cookies to keep the website working and improve your experience.</p>
        </div>

        <div class="cookie-consent__choices" id="cookie-consent-main" hidden></div>

        <div class="cookie-consent__actions">
            <button type="button" class="cookie-consent__button cookie-consent__button--primary" data-cookie-action="accept">Accept All</button>
            <button type="button" class="cookie-consent__button cookie-consent__button--secondary" data-cookie-action="reject">Reject Non-Essential</button>
            <button type="button" class="cookie-consent__button cookie-consent__button--text" data-cookie-action="manage">Manage Preferences</button>
        </div>
        <p class="cookie-consent__policies">Read our <a href="<?= $cookieConsentBase ?>/users/privacy.php">Privacy Policy</a> and <a href="<?= $cookieConsentBase ?>/users/cookie-policy.php">Cookie Policy</a>.</p>

        <div class="cookie-consent__preferences" id="cookie-consent-preferences" hidden>
            <button type="button" class="cookie-consent__back" data-cookie-action="back">Back to consent options</button>
            <h3>Manage your preferences</h3>
            <p>Choose which optional categories you allow. Necessary cookies remain enabled.</p>
            <label class="cookie-consent__toggle"><span><strong>Necessary cookies</strong><small>Required for core site functions.</small></span><input type="checkbox" checked disabled><i aria-hidden="true"></i></label>
            <label class="cookie-consent__toggle"><span><strong>Functional cookies</strong><small>Remember optional preferences between visits.</small></span><input type="checkbox" id="cookie-preferences"><i aria-hidden="true"></i></label>
            <label class="cookie-consent__toggle"><span><strong>Analytics cookies</strong><small>Help us understand how visitors use the site.</small></span><input type="checkbox" id="cookie-analytics"><i aria-hidden="true"></i></label>
            <label class="cookie-consent__toggle"><span><strong>Marketing cookies</strong><small>Support advertising and campaign measurement.</small></span><input type="checkbox" id="cookie-marketing"><i aria-hidden="true"></i></label>
            <button type="button" class="cookie-consent__button cookie-consent__button--primary" data-cookie-action="save">Save Preferences</button>
        </div>
    </section>
</div>
<script src="<?= $cookieConsentBase ?>/assets/js/cookie-consent.js" defer></script>
