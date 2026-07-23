<div class="social-share">
    <span class="social-share-label">@lang('Share'):</span>
    <div class="social-share-buttons">
        <a href="{{ $getFacebookUrl() }}" target="_blank" rel="noopener" class="btn btn-sm btn-social btn-facebook" title="@lang('Share on Facebook')">
            <i class="bi bi-facebook"></i>
        </a>
        <a href="{{ $getTwitterUrl() }}" target="_blank" rel="noopener" class="btn btn-sm btn-social btn-twitter" title="@lang('Share on X')">
            <i class="bi bi-twitter-x"></i>
        </a>
        <a href="{{ $getLinkedInUrl() }}" target="_blank" rel="noopener" class="btn btn-sm btn-social btn-linkedin" title="@lang('Share on LinkedIn')">
            <i class="bi bi-linkedin"></i>
        </a>
        <a href="{{ $getWhatsAppUrl() }}" target="_blank" rel="noopener" class="btn btn-sm btn-social btn-whatsapp" title="@lang('Share on WhatsApp')">
            <i class="bi bi-whatsapp"></i>
        </a>
        <button type="button" class="btn btn-sm btn-social btn-copy" onclick="copyToClipboard('{{ $url }}')" title="@lang('Copy Link')">
            <i class="bi bi-link-45deg"></i>
        </button>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show a toast or notification
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.textContent = '@lang("Link copied!")';
        toast.style.cssText = 'position:fixed;bottom:20px;right:20px;background:#198754;color:white;padding:10px 20px;border-radius:5px;z-index:9999;animation:fadeIn 0.3s';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}
</script>
@endpush

@push('styles')
<style>
.social-share {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 0;
}
.social-share-label {
    font-weight: 600;
    color: #333;
}
.social-share-buttons {
    display: flex;
    gap: 8px;
}
.btn-social {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    transition: all 0.3s;
}
.btn-social:hover {
    transform: translateY(-2px);
}
.btn-facebook { background: #1877f2; color: white; }
.btn-twitter { background: #000; color: white; }
.btn-linkedin { background: #0a66c2; color: white; }
.btn-whatsapp { background: #25d366; color: white; }
.btn-copy { background: #6c757d; color: white; }
</style>
@endpush
