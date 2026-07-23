{{-- Top Bar with Contact Info and Social Links --}}
@php
    $socialLinks = \App\Models\SocialLink::visible()->ordered()->get();
    $whatsapp = \App\Models\CMS\Setting::getValue('contact_whatsapp', '');
    $phone = \App\Models\CMS\Setting::getValue('contact_phone', '');
    $email = \App\Models\CMS\Setting::getValue('contact_email', '');
    $workingHours = \App\Models\CMS\Setting::getValue('working_hours', '');
@endphp

<div class="top-bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="d-flex flex-wrap gap-3 gap-lg-4">
                    @if($phone)
                        <a href="tel:{{ $phone }}" class="text-white text-decoration-none small">
                            <i class="fas fa-phone me-1"></i>
                            <span class="d-none d-md-inline">{{ $phone }}</span>
                        </a>
                    @endif
                    @if($whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" class="text-white text-decoration-none small">
                            <i class="fab fa-whatsapp me-1"></i>
                            <span class="d-none d-lg-inline">WhatsApp</span>
                        </a>
                    @endif
                    @if($email)
                        <a href="mailto:{{ $email }}" class="text-white text-decoration-none small">
                            <i class="fas fa-envelope me-1"></i>
                            <span class="d-none d-lg-inline">{{ $email }}</span>
                        </a>
                    @endif
                    @if($workingHours)
                        <span class="text-white small d-none d-xl-inline">
                            <i class="fas fa-clock me-1"></i>
                            {{ $workingHours }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 mt-2 mt-lg-0">
                <div class="d-flex justify-content-lg-end align-items-center gap-3">
                    {{-- Social Links --}}
                    @if($socialLinks->count() > 0)
                        <div class="social-links d-flex gap-2">
                            @foreach($socialLinks as $link)
                                <a href="{{ $link->url }}" 
                                   target="_blank" 
                                   class="text-white"
                                   style="color: {{ $link->color ?? '#fff' }} !important;"
                                   title="{{ $link->translated_name }}">
                                    <i class="{{ $link->icon ?? 'fas fa-link' }}"></i>
                                </a>
                            @endforeach
                        </div>
                        <span class="text-white">|</span>
                    @endif
                    
                    {{-- Language Switcher --}}
                    <div class="lang-switcher d-flex gap-1">
                        @if(app()->getLocale() !== 'bn')
                            <a href="{{ route('language.switch', ['locale' => 'bn', 'redirect' => url()->current()]) }}" 
                               class="btn btn-sm {{ app()->getLocale() === 'bn' ? 'btn-light' : 'btn-outline-light' }}">
                                বাংলা
                            </a>
                        @endif
                        @if(app()->getLocale() !== 'en')
                            <a href="{{ route('language.switch', ['locale' => 'en', 'redirect' => url()->current()]) }}" 
                               class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-light' : 'btn-outline-light' }}">
                                EN
                            </a>
                        @endif
                        @if(app()->getLocale() !== 'ar')
                            <a href="{{ route('language.switch', ['locale' => 'ar', 'redirect' => url()->current()]) }}" 
                               class="btn btn-sm {{ app()->getLocale() === 'ar' ? 'btn-light' : 'btn-outline-light' }}">
                                العربية
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
