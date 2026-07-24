<div id="cookie-consent-banner" class="cookie-consent-banner" style="display: none;">
    <div class="cookie-content">
        <div class="cookie-text">
            <h5 class="cookie-title">
                <i class="bi bi-cookie"></i>
                @lang('We value your privacy')
            </h5>
            <p class="cookie-message mb-0">
                @lang('We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic. By clicking "Accept", you consent to our use of cookies.')
                <a href="{{ locale_route('privacy-policy') }}" class="text-white text-decoration-underline">@lang('Privacy Policy')</a>
            </p>
        </div>
        <div class="cookie-actions">
            <button type="button" class="btn btn-sm btn-outline-light" id="cookie-decline">
                @lang('Decline')
            </button>
            <button type="button" class="btn btn-sm btn-success" id="cookie-accept">
                @lang('Accept All')
            </button>
            <button type="button" class="btn btn-sm btn-link text-white" id="cookie-settings" data-bs-toggle="modal" data-bs-target="#cookie-settings-modal">
                @lang('Cookie Settings')
            </button>
        </div>
    </div>
</div>

<!-- Cookie Settings Modal -->
<div class="modal fade" id="cookie-settings-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-gear"></i> @lang('Cookie Settings')
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">@lang('Manage your cookie preferences. Some cookies are essential for the website to function properly and cannot be disabled.')</p>
                
                <div class="cookie-option mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>@lang('Essential Cookies')</strong>
                            <p class="text-muted small mb-0">@lang('Required for the website to function properly.')</p>
                        </div>
                        <span class="badge bg-success">@lang('Always Active')</span>
                    </div>
                </div>
                
                <div class="cookie-option mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cookie-analytics" checked>
                        <label class="form-check-label" for="cookie-analytics">
                            <strong>@lang('Analytics Cookies')</strong>
                            <p class="text-muted small mb-0">@lang('Help us understand how visitors interact with our website.')</p>
                        </label>
                    </div>
                </div>
                
                <div class="cookie-option mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cookie-marketing" checked>
                        <label class="form-check-label" for="cookie-marketing">
                            <strong>@lang('Marketing Cookies')</strong>
                            <p class="text-muted small mb-0">@lang('Used to deliver personalized advertisements.')</p>
                        </label>
                    </div>
                </div>
                
                <div class="cookie-option">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cookie-preferences" checked>
                        <label class="form-check-label" for="cookie-preferences">
                            <strong>@lang('Preference Cookies')</strong>
                            <p class="text-muted small mb-0">@lang('Remember your settings and preferences.')</p>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    @lang('Cancel')
                </button>
                <button type="button" class="btn btn-success" id="cookie-save-preferences">
                    @lang('Save Preferences')
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    'use strict';
    
    const STORAGE_KEY = 'cookie_consent';
    const COOKIE_NAME = 'cookie_consent_status';
    
    function getConsent() {
        return localStorage.getItem(STORAGE_KEY);
    }
    
    function setConsent(status, preferences) {
        const data = {
            status: status,
            preferences: preferences,
            timestamp: new Date().toISOString()
        };
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        
        // Set cookie for server-side access
        document.cookie = COOKIE_NAME + '=' + status + ';path=/;max-age=' + (365 * 24 * 60 * 60);
    }
    
    function showBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.display = 'block';
            banner.classList.add('show');
        }
    }
    
    function hideBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.display = 'none';
            banner.classList.remove('show');
        }
    }
    
    function acceptAll() {
        setConsent('accept', {
            analytics: true,
            marketing: true,
            preferences: true
        });
        hideBanner();
    }
    
    function declineAll() {
        setConsent('decline', {
            analytics: false,
            marketing: false,
            preferences: false
        });
        hideBanner();
    }
    
    function savePreferences() {
        const preferences = {
            analytics: document.getElementById('cookie-analytics').checked,
            marketing: document.getElementById('cookie-marketing').checked,
            preferences: document.getElementById('cookie-preferences').checked
        };
        
        setConsent('custom', preferences);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('cookie-settings-modal'));
        if (modal) modal.hide();
        
        hideBanner();
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        const consent = getConsent();
        
        if (!consent) {
            showBanner();
        }
        
        // Event listeners
        const acceptBtn = document.getElementById('cookie-accept');
        const declineBtn = document.getElementById('cookie-decline');
        const saveBtn = document.getElementById('cookie-save-preferences');
        
        if (acceptBtn) acceptBtn.addEventListener('click', acceptAll);
        if (declineBtn) declineBtn.addEventListener('click', declineAll);
        if (saveBtn) saveBtn.addEventListener('click', savePreferences);
    });
})();
</script>
@endpush

@push('styles')
<style>
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: white;
    padding: 20px 0;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.2);
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.cookie-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.cookie-text {
    flex: 1;
    min-width: 300px;
}

.cookie-title {
    color: white;
    margin-bottom: 8px;
}

.cookie-title i {
    margin-right: 8px;
}

.cookie-message {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.8);
}

.cookie-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.cookie-option {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}
</style>
@endpush
