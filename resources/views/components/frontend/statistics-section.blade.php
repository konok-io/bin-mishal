<?php
use App\Models\FeatureCard;

// Get active stats from database or use defaults
try {
    $dbStats = FeatureCard::getActive();
    $hasStats = $dbStats->isNotEmpty();
} catch (\Exception $e) {
    $hasStats = false;
    $dbStats = collect();
}
?>

<!-- Statistics Section Component -->
<section class="statistics-section py-5" id="statisticsSection">
    <div class="container">
        <div class="row g-4">
            @if($hasStats)
                @foreach($dbStats as $index => $stat)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="{{ $stat->icon ?? 'fas fa-chart-line' }}"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    <span class="counter" data-target="{{ $stat->number ?? 0 }}">0</span>
                                    <span class="stat-suffix">{{ $stat->number_suffix ?? '+' }}</span>
                                </div>
                                <h4 class="stat-title">{{ $stat->title }}</h4>
                                @if($stat->description)
                                    <p class="stat-description">{{ $stat->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Default Stats -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-content">
                            <div class="stat-number"><span class="counter" data-target="50000">0</span><span class="stat-suffix">+</span></div>
                            <h4 class="stat-title">@lang('stats.happy_customers')</h4>
                            <p class="stat-description">Satisfied travelers worldwide</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-plane-departure"></i></div>
                        <div class="stat-content">
                            <div class="stat-number"><span class="counter" data-target="15000">0</span><span class="stat-suffix">+</span></div>
                            <h4 class="stat-title">@lang('stats.flights_booked')</h4>
                            <p class="stat-description">Domestic & international flights</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-kaaba"></i></div>
                        <div class="stat-content">
                            <div class="stat-number"><span class="counter" data-target="5000">0</span><span class="stat-suffix">+</span></div>
                            <h4 class="stat-title">@lang('stats.umrah_packages')</h4>
                            <p class="stat-description">Hajj & Umrah packages sold</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                        <div class="stat-content">
                            <div class="stat-number"><span class="counter" data-target="25">0</span><span class="stat-suffix">+</span></div>
                            <h4 class="stat-title">@lang('stats.years_experience')</h4>
                            <p class="stat-description">Years of trusted service</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
/* Statistics Section */
.statistics-section {
    background: linear-gradient(135deg, var(--primary-color, #E05522) 0%, #C94718 100%);
    color: #fff;
    position: relative;
    overflow: hidden;
    padding: 80px 0;
}

.statistics-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='50' cy='50' r='40' stroke='%23ffffff' stroke-width='0.5' fill='none' opacity='0.08'/%3E%3C/svg%3E");
    background-size: 80px 80px;
}

.statistics-section::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(224,85,34,0.15) 0%, transparent 70%);
    border-radius: 50%;
}

.stat-card {
    background: rgba(255,255,255,0.08);
    border-radius: 24px;
    padding: 40px 30px;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.12);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    z-index: 1;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--secondary-color, #343C90), transparent);
    opacity: 0;
    transition: all 0.4s ease;
}

.stat-card:hover {
    background: rgba(255,255,255,0.15);
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.2);
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--secondary-color, #343C90), #C94718);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 10px 30px rgba(224, 85, 34, 0.3);
}

.stat-icon i {
    font-size: 32px;
    color: #fff;
}

.stat-card:hover .stat-icon {
    transform: rotate(15deg) scale(1.1);
    box-shadow: 0 15px 40px rgba(224, 85, 34, 0.4);
}

.stat-number {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 15px;
    display: flex;
    align-items: baseline;
    justify-content: center;
    font-family: 'Poppins', sans-serif;
}

.counter {
    font-variant-numeric: tabular-nums;
}

.stat-suffix {
    font-size: 2.5rem;
    margin-left: 5px;
    color: var(--secondary-color, #343C90);
    font-weight: 700;
}

.stat-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: rgba(255,255,255,0.95);
}

.stat-description {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.65);
    margin-top: 0;
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 1199px) {
    .stat-card {
        padding: 35px 25px;
    }
    
    .stat-number {
        font-size: 3rem;
    }
}

@media (max-width: 991px) {
    .statistics-section {
        padding: 60px 0;
    }
    
    .stat-card {
        padding: 30px 20px;
    }
    
    .stat-icon {
        width: 70px;
        height: 70px;
    }
    
    .stat-icon i {
        font-size: 28px;
    }
    
    .stat-number {
        font-size: 2.8rem;
    }
    
    .stat-suffix {
        font-size: 2rem;
    }
}

@media (max-width: 767px) {
    .stat-number {
        font-size: 2.5rem;
    }
    
    .stat-suffix {
        font-size: 1.8rem;
    }
    
    .stat-title {
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Counter Animation with Intersection Observer
    const counters = document.querySelectorAll('.counter');
    const speed = 200;
    
    const animateCounter = (counter) => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;
        const inc = target / speed;
        
        if (count < target) {
            counter.innerText = Math.ceil(count + inc);
            setTimeout(() => animateCounter(counter), 1);
        } else {
            counter.innerText = target.toLocaleString();
        }
    };
    
    const observerOptions = {
        threshold: 0.5
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                animateCounter(counter);
                observer.unobserve(counter);
            }
        });
    }, observerOptions);
    
    counters.forEach(counter => {
        observer.observe(counter);
    });
});
</script>
