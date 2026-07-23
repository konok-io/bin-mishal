<section class="newsletter-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="newsletter-box text-center">
                    <div class="newsletter-icon mb-3">
                        <i class="bi bi-envelope-paper"></i>
                    </div>
                    <h3 class="newsletter-title">@lang('Subscribe to Our Newsletter')</h3>
                    <p class="newsletter-text text-muted mb-4">
                        @lang('Get the latest travel deals, Umrah packages, and updates straight to your inbox.')
                    </p>
                    
                    <form id="newsletter-form" class="newsletter-form">
                        @csrf
                        <div class="row g-2 justify-content-center">
                            @auth
                                <div class="col-auto">
                                    <input type="email" name="email" class="form-control form-control-lg" 
                                           value="{{ auth()->user()->email }}" readonly
                                           placeholder="@lang('Your email')">
                                </div>
                            @else
                                <div class="col-md-4">
                                    <input type="text" name="name" class="form-control" 
                                           placeholder="@lang('Your name (optional)')">
                                </div>
                                <div class="col-md-5">
                                    <input type="email" name="email" class="form-control form-control-lg" 
                                           placeholder="@lang('Enter your email')" required>
                                </div>
                            @endauth
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success btn-lg w-100" id="subscribe-btn">
                                    <span class="btn-text">@lang('Subscribe')</span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <div id="newsletter-message" class="mt-3 d-none"></div>
                    
                    <p class="newsletter-privacy mt-3">
                        <i class="bi bi-shield-lock"></i>
                        @lang('We respect your privacy. Unsubscribe anytime.')
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.getElementById('newsletter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const messageEl = document.getElementById('newsletter-message');
    const btn = document.getElementById('subscribe-btn');
    const btnText = btn.querySelector('.btn-text');
    const btnLoading = btn.querySelector('.btn-loading');
    
    // Disable button
    btn.disabled = true;
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');
    messageEl.classList.add('d-none');
    
    const formData = new FormData(form);
    
    fetch('{{ route('newsletter.subscribe') }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageEl.classList.remove('d-none');
        
        if (data.success) {
            messageEl.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            form.reset();
        } else {
            messageEl.innerHTML = `<div class="alert alert-warning">${data.message}</div>`;
        }
    })
    .catch(error => {
        messageEl.classList.remove('d-none');
        messageEl.innerHTML = `<div class="alert alert-danger">@lang('Something went wrong. Please try again.')</div>`;
    })
    .finally(() => {
        btn.disabled = false;
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
    });
});
</script>
@endpush

@push('styles')
<style>
.newsletter-box {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
}
.newsletter-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.newsletter-icon i {
    font-size: 2.5rem;
    color: white;
}
.newsletter-title {
    color: #333;
    font-weight: 700;
}
.newsletter-text {
    max-width: 500px;
    margin: 0 auto;
}
.newsletter-privacy {
    font-size: 0.875rem;
    color: #6c757d;
}
.newsletter-privacy i {
    margin-right: 5px;
}
</style>
@endpush
