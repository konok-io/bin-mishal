<?php
use App\Models\FeatureCard;
?>

<!-- Statistics Section Component -->
<section class="statistics-section py-5" id="statisticsSection">
    <div class="container">
        <div class="row g-4">
            @foreach(FeatureCard::getActive() as $stat)
                <div class="col-lg-3 col-md-6">
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
            
            <!-- Default Stats if no data -->
            @if(FeatureCard::getActive()->isEmpty())
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-content">
                            <div class="stat-number"><span class="counter" data-target="50000">0</span><span class="stat-suffix">+</span></div>
                            <h4 class="stat-title">@lang('stats.happy_customers')</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-plane"></i></div>
                        <div class="stat-content">
                            <div class="stat-number"><span class="counter" data-target="15000">0</span><span class="stat-suffix">+</span></div>
                            <h4 class="stat-title">@lang('stats.flights_booked')</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-home"></i></div>
                        <div class="stat-content">
                            <div class="stat-number"><span class="counter" data-target="5000">0</span><span class="stat-suffix">+</span></div>
                            <h4 class="stat-title">@lang('stats.umrah_packages')</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-globe"></i></div>
                        <div class="stat-content">
                            <div class="stat-number"><span class="counter" data-target="25">0</span><span class="stat-suffix">+</span></div>
                            <h4 class="stat-title">@lang('stats.years_experience')</h4>
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
    background: linear-gradient(135deg, var(--primary-color, #343C90) 0%, #252E72 100%);
    color: #fff;
    position: relative;
    overflow: hidden;
}

.statistics-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='50' cy='50' r='40' stroke='%23ffffff' stroke-width='0.5' fill='none' opacity='0.1'/%3E%3C/svg%3E");
    background-size: 100px 100px;
}

.stat-card {
    background: rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 30px 25px;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.4s ease;
    position: relative;
    z-index: 1;
}

.stat-card:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-5px);
}

.stat-icon {
    width: 70px;
    height: 70px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    transition: all 0.4s ease;
}

.stat-icon i {
    font-size: 28px;
    color: #fff;
}

.stat-card:hover .stat-icon {
    background: var(--secondary-color, #E05522);
    transform: rotate(10deg) scale(1.1);
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 10px;
    display: flex;
    align-items: baseline;
    justify-content: center;
}

.counter {
    font-variant-numeric: tabular-nums;
}

.stat-suffix {
    font-size: 2rem;
    margin-left: 5px;
    color: var(--secondary-color, #E05522);
}

.stat-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0;
    color: rgba(255,255,255,0.9);
}

.stat-description {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.7);
    margin-top: 8px;
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 991px) {
    .stat-card {
        padding: 25px 20px;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
    
    .stat-suffix {
        font-size: 1.5rem;
    }
}

@media (max-width: 767px) {
    .stat-number {
        font-size: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Counter Animation
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
    
    // Intersection Observer for counter animation
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
