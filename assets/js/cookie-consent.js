(function () {
    'use strict';

    var cookieName = 'hivex_cookie_consent';
    var cookieDays = 180;
    var sessionKey = 'hivex_cookie_banner_seen';
    var root = document.getElementById('cookie-consent');
    var dialog = root ? root.querySelector('.cookie-consent__dialog') : null;
    var preferences = document.getElementById('cookie-consent-preferences');
    var trigger = document.querySelector('.cookie-preferences-trigger');
    var preferencesInput = document.getElementById('cookie-preferences');
    var analyticsInput = document.getElementById('cookie-analytics');
    var marketingInput = document.getElementById('cookie-marketing');
    var lastFocused = null;
    var previousOverflow = '';

    if (!root || !dialog) return;

    function readConsent() {
        var entry = document.cookie.split('; ').find(function (item) { return item.indexOf(cookieName + '=') === 0; });
        if (!entry) return null;
        try {
            var value = JSON.parse(decodeURIComponent(entry.slice(cookieName.length + 1)));
            if (value && value.version === 1 && typeof value.preferences === 'boolean' && typeof value.analytics === 'boolean' && typeof value.marketing === 'boolean') return value;
        } catch (error) {}
        return null;
    }

    function saveConsent(functional, analytics, marketing) {
        var consent = { version: 1, necessary: true, preferences: !!functional, analytics: !!analytics, marketing: !!marketing, timestamp: new Date().toISOString() };
        var value = encodeURIComponent(JSON.stringify(consent));
        var secure = window.location.protocol === 'https:' ? '; Secure' : '';
        document.cookie = cookieName + '=' + value + '; Max-Age=' + (cookieDays * 86400) + '; Path=/; SameSite=Lax' + secure;
        window.dispatchEvent(new CustomEvent('hivex:consent-changed', { detail: consent }));
        loadOptionalScripts('preferences', !!functional);
        loadOptionalScripts('analytics', !!analytics);
        loadOptionalScripts('marketing', !!marketing);
    }

    function loadOptionalScripts(category, enabled) {
        if (!enabled) return;
        document.querySelectorAll('script[type="text/plain"][data-cookie-category="' + category + '"]').forEach(function (placeholder) {
            var script = document.createElement('script');
            Array.prototype.forEach.call(placeholder.attributes, function (attribute) {
                if (attribute.name !== 'type' && attribute.name !== 'data-cookie-category') script.setAttribute(attribute.name, attribute.value);
            });
            script.text = placeholder.textContent;
            placeholder.replaceWith(script);
        });
    }

    function setOpen(open) {
        root.hidden = !open;
        if (trigger) trigger.classList.toggle('is-visible', !open);
        root.classList.toggle('is-preferences', open && !preferences.hidden);
        document.body.classList.toggle('cookie-consent-open', open);
        document.body.style.overflow = open && !preferences.hidden ? 'hidden' : previousOverflow;
        if (open) {
            lastFocused = document.activeElement;
            dialog.focus();
        } else if (lastFocused && typeof lastFocused.focus === 'function') {
            lastFocused.focus();
        }
    }

    function showPreferences() {
        document.getElementById('cookie-consent-main').hidden = true;
        document.querySelector('.cookie-consent__actions').hidden = true;
        document.querySelector('.cookie-consent__policies').hidden = true;
        preferences.hidden = false;
        var consent = readConsent();
        analyticsInput.checked = !!(consent && consent.analytics);
        marketingInput.checked = !!(consent && consent.marketing);
        preferencesInput.checked = !!(consent && consent.preferences);
        root.classList.add('is-preferences');
        dialog.setAttribute('aria-modal', 'true');
        document.body.style.overflow = 'hidden';
        dialog.querySelector('.cookie-consent__back').focus();
    }

    function showMain() {
        document.getElementById('cookie-consent-main').hidden = false;
        document.querySelector('.cookie-consent__actions').hidden = false;
        document.querySelector('.cookie-consent__policies').hidden = false;
        preferences.hidden = true;
        root.classList.remove('is-preferences');
        dialog.setAttribute('aria-modal', 'false');
        document.body.style.overflow = previousOverflow;
        dialog.querySelector('[data-cookie-action="manage"]').focus();
    }

    function closeWithConsent(functional, analytics, marketing) {
        saveConsent(functional, analytics, marketing);
        setOpen(false);
    }

    root.addEventListener('click', function (event) {
        var actionElement = event.target.closest('[data-cookie-action]');
        if (!actionElement) return;
        var action = actionElement.getAttribute('data-cookie-action');
        if (action === 'accept') closeWithConsent(true, true, true);
        if (action === 'reject') closeWithConsent(false, false, false);
        if (action === 'manage') showPreferences();
        if (action === 'back') showMain();
        if (action === 'save') closeWithConsent(preferencesInput.checked, analyticsInput.checked, marketingInput.checked);
    });

    if (trigger) {
        trigger.addEventListener('click', function () {
            setOpen(true);
            showMain();
        });
    }

    document.addEventListener('keydown', function (event) {
        if (root.hidden) return;
        if (event.key === 'Escape') {
            if (!preferences.hidden) showMain();
            else event.preventDefault();
            return;
        }
        if (preferences.hidden) return;
        if (event.key !== 'Tab') return;
        var focusable = Array.prototype.filter.call(dialog.querySelectorAll('button, a, input:not([disabled])'), function (element) {
            return element.offsetParent !== null;
        });
        if (!focusable.length) return;
        var first = focusable[0];
        var last = focusable[focusable.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    });

    var consent = readConsent();
    if (consent) {
        if (trigger) trigger.classList.add('is-visible');
        preferencesInput.checked = !!consent.preferences;
        loadOptionalScripts('analytics', consent.analytics);
        loadOptionalScripts('marketing', consent.marketing);
    }

    var bannerSeen = false;
    try {
        bannerSeen = sessionStorage.getItem(sessionKey) === '1';
        if (!bannerSeen) sessionStorage.setItem(sessionKey, '1');
    } catch (error) {}

    if (!bannerSeen) {
        previousOverflow = document.body.style.overflow;
        setOpen(true);
    }
}());
