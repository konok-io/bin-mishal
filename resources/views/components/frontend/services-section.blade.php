<?php
use App\Models\HeroTab;

// Default services fallback data
$defaultServices = [
    [
        'icon' => 'fas fa-plane',
        'label' => ['en' => 'Flight Booking', 'bn' => 'ফ্লাইট বুকিং', 'ar' => 'حجز الطيران'],
        'title' => ['en' => 'Air Ticket', 'bn' => 'এয়ার টিকেট', 'ar' => 'تذكرة الطيران'],
        'description' => [
            'en' => 'Book flights to Bangladesh, Saudi Arabia, UAE, Qatar and more destinations worldwide with the best fares.',
            'bn' => 'সেরা মূল্যে বাংলাদেশ, সৌদি আরব, সংযুক্ত আরব আমিরাত, কাতার সহ সারা বিশ্বের গন্তব্যে ফ্লাইট বুক করুন।',
            'ar' => 'احجز رحلات جوية إلى بنغلاديش والمملكة العربية السعودية والإمارات وقطر والمزيد من الوجهات بأفضل الأسعار.'
        ],
        'features' => [
            ['en' => '5000+ Routes Covered', 'bn' => '৫০০০+ রুট কভারড', 'ar' => '۵۰۰۰+ مسار مغطى'],
            ['en' => 'Best Price Guarantee', 'bn' => 'সেরা মূল্য নিশ্চয়তা', 'ar' => 'ضمان أفضل سعر'],
            ['en' => '24/7 Support', 'bn' => '২৪/৭ সাপোর্ট', 'ar' => 'دعم على مدار الساعة']
        ],
        'url' => 'services.airticket'
    ],
    [
        'icon' => 'fas fa-kaaba',
        'label' => ['en' => 'Umrah Packages', 'bn' => 'উমরাহ প্যাকেজ', 'ar' => 'باقات العمرة'],
        'title' => ['en' => 'Umrah Services', 'bn' => 'উমরাহ সেবা', 'ar' => 'خدمات العمرة'],
        'description' => [
            'en' => 'Complete Umrah packages including visa, hotel, transport, and guided tours for a spiritual journey.',
            'bn' => 'ভিসা, হোটেল, পরিবহন এবং গাইডেড ট্যুর সহ সম্পূর্ণ উমরাহ প্যাকেজ।',
            'ar' => 'باقات عمرة كاملة تشمل التأشيرة والفندق والنقل والجولات المصحوبة بجولة روحية.'
        ],
        'features' => [
            ['en' => 'Economy to VIP Packages', 'bn' => 'ইকোনমি থেকে ভিআইপি', 'ar' => 'باقات اقتصادية إلى فاخرة'],
            ['en' => 'Visa Processing', 'bn' => 'ভিসা প্রসেসিং', 'ar' => 'معالجة التأشيرة'],
            ['en' => 'Hotel Accommodation', 'bn' => 'হোটেল সুবিধা', 'ar' => 'الإقامة في الفندق']
        ],
        'url' => 'services.umrah'
    ],
    [
        'icon' => 'fas fa-passport',
        'label' => ['en' => 'Visa Service', 'bn' => 'ভিসা সেবা', 'ar' => 'خدمة التأشيرة'],
        'title' => ['en' => 'Visa Processing', 'bn' => 'ভিসা প্রসেসিং', 'ar' => 'معالجة التأشيرة'],
        'description' => [
            'en' => 'Professional visa processing for Saudi Arabia, UAE, Qatar, Bahrain, and other GCC countries.',
            'bn' => 'সৌদি আরব, সংযুক্ত আরব আমিরাত, কাতার, বাহরাইন এবং অন্যান্য জিসিসি দেশের জন্য পেশাদার ভিসা প্রসেসিং।',
            'ar' => 'معالجة تأشيرات احترافية للمملكة العربية السعودية والإمارات وقطر والبحرين ودول الخليج الأخرى.'
        ],
        'features' => [
            ['en' => 'Fast Processing', 'bn' => 'দ্রুত প্রসেসিং', 'ar' => 'معالجة سريعة'],
            ['en' => 'Document Assistance', 'bn' => 'ডকুমেন্ট সহায়তা', 'ar' => 'مساعدة في المستندات'],
            ['en' => 'Expert Guidance', 'bn' => 'বিশেষজ্ঞ গাইডেন্স', 'ar' => 'إرشاد خبير']
        ],
        'url' => 'services.visa'
    ],
    [
        'icon' => 'fas fa-shipping-fast',
        'label' => ['en' => 'Cargo Service', 'bn' => 'কার্গো সেবা', 'ar' => 'خدمة الشحن'],
        'title' => ['en' => 'Air Cargo', 'bn' => 'এয়ার কার্গো', 'ar' => 'الشحن الجوي'],
        'description' => [
            'en' => 'Fast and reliable air cargo services for documents, parcels, and commercial shipments worldwide.',
            'bn' => 'ডকুমেন্ট, পার্সেল এবং বাণিজ্যিক শিপমেন্টের জন্য দ্রুত এবং নির্ভরযোগ্য এয়ার কার্গো সেবা।',
            'ar' => 'خدمات شحن جوي سريعة وموثوقة للمستندات والطوابع والشحنات التجارية في جميع أنحاء العالم.'
        ],
        'features' => [
            ['en' => 'Door to Door Delivery', 'bn' => 'ডোর টু ডোর ডেলিভারি', 'ar' => 'التسليم من الباب إلى الباب'],
            ['en' => 'Real-time Tracking', 'bn' => 'রিয়েল-টাইম ট্র্যাকিং', 'ar' => 'تتبع في الوقت الفعلي'],
            ['en' => 'Competitive Rates', 'bn' => 'প্রতিযোগিতামূলক মূল্য', 'ar' => 'أسعار تنافسية']
        ],
        'url' => 'services.cargo'
    ],
    [
        'icon' => 'fas fa-hotel',
        'label' => ['en' => 'Hotel Booking', 'bn' => 'হোটেল বুকিং', 'ar' => 'حجز الفندق'],
        'title' => ['en' => 'Hotel Services', 'bn' => 'হোটেল সেবা', 'ar' => 'خدمات الفندق'],
        'description' => [
            'en' => 'Book hotels in Saudi Arabia, Bangladesh and worldwide at the best prices with instant confirmation.',
            'bn' => 'তাৎক্ষণিক নিশ্চিতকরণে সেরা মূল্যে সৌদি আরব, বাংলাদেশ এবং সারা বিশ্বে হোটেল বুক করুন।',
            'ar' => 'احجز فنادق في المملكة العربية السعودية وبنغلاديش وحول العالم بأفضل الأسعار مع تأكيد فوري.'
        ],
        'features' => [
            ['en' => 'Best Rate Guarantee', 'bn' => 'সেরা রেট গ্যারান্টি', 'ar' => 'ضمان أفضل سعر'],
            ['en' => 'Instant Confirmation', 'bn' => 'তাৎক্ষণিক নিশ্চিতকরণ', 'ar' => 'تأكيد فوري'],
            ['en' => 'Free Cancellation', 'bn' => 'ফ্রি ক্যান্সেলেশন', 'ar' => 'إلغاء مجاني']
        ],
        'url' => 'services.hotel'
    ],
    [
        'icon' => 'fas fa-handshake',
        'label' => ['en' => 'Business Visa', 'bn' => 'ব্যবসায়িক ভিসা', 'ar' => 'تأشيرة عمل'],
        'title' => ['en' => 'Investor Visa', 'bn' => 'বিনিয়োগকারী ভিসা', 'ar' => 'تأشيرة مستثمر'],
        'description' => [
            'en' => 'Specialized investor and business visa services for entrepreneurs looking to expand in Saudi Arabia.',
            'bn' => 'সৌদি আরবে সম্প্রসারণে আগ্রহী উদ্যোগপতিদের জন্য বিশেষায়িত বিনিয়োগকারী এবং ব্যবসায়িক ভিসা সেবা।',
            'ar' => 'خدمات تأشيرة رجال الأعمال والمستثمرين المتخصصة لرواد الأعمال الذين يتطلعون إلى التوسع في المملكة العربية السعودية.'
        ],
        'features' => [
            ['en' => 'Investment Consultation', 'bn' => 'বিনিয়োগ পরামর্শ', 'ar' => 'استشارة الاستثمار'],
            ['en' => 'Business Setup Support', 'bn' => 'ব্যবসা সেটআপ সাপোর্ট', 'ar' => 'دعم إنشاء الأعمال'],
            ['en' => 'Legal Documentation', 'bn' => 'আইনি ডকুমেন্টেশন', 'ar' => 'الوثائق القانونية']
        ],
        'url' => 'services.investor'
    ]
];

// Try to get services from database
try {
    $tabsCollection = \App\Models\HeroTab::where('is_active', 1)->orderBy('order')->get();
    $dbServices = $tabsCollection->map(function($tab) {
        return [
            'icon' => $tab->icon ?? 'fas fa-plane',
            'label' => is_string($tab->label) ? json_decode($tab->label, true) : ($tab->label ?? ['en' => 'Service']),
            'description' => is_string($tab->subtitle) ? json_decode($tab->subtitle, true) : ($tab->subtitle ?? ['en' => '']),
            'features' => is_string($tab->features) ? json_decode($tab->features, true) : ($tab->features ?? []),
            'url' => $tab->route_name ?? 'services',
        ];
    })->toArray();
    $services = !empty($dbServices) ? $dbServices : $defaultServices;
} catch (\Exception $e) {
    $services = $defaultServices;
}

// Helper function to get localized value
function getLocalized($array, $locale = 'en') {
    if (is_array($array)) {
        return $array[$locale] ?? $array['en'] ?? reset($array);
    }
    return $array;
}
?>

<!-- Services Section Component -->
<section class="services-section py-5" id="servicesSection">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-badge">@lang('services.our_services')</span>
            <h2 class="section-title">@lang('services.title')</h2>
            <p class="section-subtitle">@lang('services.subtitle')</p>
        </div>
        
        <!-- Services Grid -->
        <div class="row g-4">
            @foreach($services as $index => $service)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="{{ is_array($service) ? ($service['icon'] ?? 'fas fa-plane') : ($service->icon ?? 'fas fa-plane') }}"></i>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">
                            {{ is_array($service) ? getLocalized($service['label']) : ($service->translated_label ?? 'Service') }}
                        </h3>
                        <p class="service-description">
                            {{ is_array($service) ? getLocalized($service['description']) : ($service->translated_description ?? '') }}
                        </p>
                        
                        <!-- Features List -->
                        @php
                            $features = is_array($service) ? ($service['features'] ?? []) : ($service->translated_features ?? []);
                            // Generate service URL - use method for objects, route name for arrays
                            if (is_array($service)) {
                                $serviceUrl = route($service['url'] ?? 'services', ['locale' => app()->getLocale()]);
                            } else {
                                $serviceUrl = $service->getButtonUrlResolved() ?? route('services', ['locale' => app()->getLocale()]);
                            }
                        @endphp
                        @if(!empty($features))
                            <ul class="service-features">
                                @foreach($features as $feature)
                                    <li>
                                        <i class="fas fa-check-circle"></i> 
                                        {{ is_array($feature) ? getLocalized($feature) : $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        
                        <a href="{{ $serviceUrl }}" class="btn btn-outline-primary service-btn">
                            @lang('common.learn_more') <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-overlay"></div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- CTA -->
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="btn btn-primary btn-lg px-5">
                <i class="fas fa-th-large me-2"></i> @lang('services.view_all_services')
            </a>
        </div>
    </div>
</section>

<style>
/* Services Section */
.services-section {
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
    position: relative;
    overflow: hidden;
}

.services-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23343C90' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.section-header {
    margin-bottom: 50px;
    position: relative;
    z-index: 1;
}

.section-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary-color, #E05522), var(--secondary-color, #343C90));
    color: #fff;
    padding: 8px 24px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(52, 60, 144, 0.3);
}

.section-title {
    font-size: 2.8rem;
    font-weight: 800;
    color: #1a1a2e;
    margin-bottom: 20px;
    position: relative;
}

.section-title::after {
    content: '';
    display: block;
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color, #E05522), var(--secondary-color, #343C90));
    margin: 20px auto 0;
    border-radius: 2px;
}

.section-subtitle {
    font-size: 1.15rem;
    color: #666;
    max-width: 650px;
    margin: 0 auto;
    line-height: 1.8;
}

/* Service Cards */
.service-card {
    background: #fff;
    border-radius: 24px;
    padding: 40px 35px;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 10px 40px rgba(0,0,0,0.06);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(0,0,0,0.04);
}

.service-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 25px 60px rgba(52, 60, 144, 0.15);
    border-color: transparent;
}

.service-icon {
    width: 90px;
    height: 90px;
    background: linear-gradient(145deg, var(--primary-color, #E05522), #C94718);
    border-radius: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 28px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 25px rgba(52, 60, 144, 0.25);
}

.service-icon i {
    font-size: 36px;
    color: #fff;
    transition: all 0.4s ease;
}

.service-card:hover .service-icon {
    transform: scale(1.1) rotate(8deg);
    background: linear-gradient(145deg, var(--secondary-color, #343C90), #C94718);
    box-shadow: 0 12px 35px rgba(224, 85, 34, 0.35);
}

.service-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 15px;
}

.service-description {
    color: #666;
    line-height: 1.8;
    margin-bottom: 25px;
    flex-grow: 1;
    font-size: 0.95rem;
}

.service-features {
    list-style: none;
    padding: 0;
    margin: 0 0 25px;
}

.service-features li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    color: #555;
    font-size: 14px;
    border-bottom: 1px solid #f5f5f5;
    transition: all 0.3s ease;
}

.service-features li:last-child {
    border-bottom: none;
}

.service-features li:hover {
    color: var(--primary-color, #E05522);
    padding-left: 5px;
}

.service-features li i {
    color: #22c55e;
    font-size: 14px;
    transition: all 0.3s ease;
}

.service-features li:hover i {
    color: var(--primary-color, #E05522);
    transform: scale(1.2);
}

.service-btn {
    padding: 14px 28px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: transparent;
    border: 2px solid var(--primary-color, #E05522);
    color: var(--primary-color, #E05522);
}

.service-btn:hover {
    background: var(--primary-color, #E05522);
    border-color: var(--primary-color, #E05522);
    color: #fff;
    transform: translateX(5px);
    box-shadow: 0 8px 25px rgba(52, 60, 144, 0.3);
}

.service-btn i {
    transition: transform 0.3s ease;
}

.service-btn:hover i {
    transform: translateX(5px);
}

.service-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, var(--primary-color, #E05522), var(--secondary-color, #343C90), var(--primary-color, #E05522));
    background-size: 200% 100%;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.service-card:hover .service-overlay {
    transform: scaleX(1);
    background-position: 100% 0;
}

/* Responsive */
@media (max-width: 1199px) {
    .service-card {
        padding: 35px 30px;
    }
}

@media (max-width: 991px) {
    .section-title {
        font-size: 2.2rem;
    }
    
    .service-card {
        padding: 30px 25px;
    }
    
    .service-icon {
        width: 75px;
        height: 75px;
    }
    
    .service-icon i {
        font-size: 30px;
    }
}

@media (max-width: 767px) {
    .section-title {
        font-size: 1.9rem;
    }
    
    .section-subtitle {
        font-size: 1rem;
    }
    
    .service-card {
        text-align: center;
        padding: 30px 20px;
    }
    
    .service-icon {
        margin-left: auto;
        margin-right: auto;
    }
    
    .service-features {
        text-align: left;
    }
    
    .service-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
