@extends('layouts.public')

@section('title', \App\Models\Setting::get('site_name', 'متجر متعدد البائعين') . ' - الرئيسية')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 0;
        margin-bottom: 60px;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }
    
    .feature-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
    }
    
    .feature-icon {
        font-size: 3rem;
        color: #667eea;
        margin-bottom: 1rem;
    }
    
    .about-section {
        padding: 60px 0;
        background-color: #f8f9fa;
    }
    
    .content-section {
        padding: 40px 0;
    }
    
    .content-section img {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">
                    {{ $settings['about_title'] ?? 'مرحباً بك في متجرنا' }}
                </h1>
                <p class="hero-subtitle">
                    {{ $settings['about_subtitle'] ?? 'نقدم لك أفضل المنتجات بأفضل الأسعار' }}
                </p>
                @if(isset($settings['about_cta_text']) && isset($settings['about_cta_link']))
                <a href="{{ $settings['about_cta_link'] }}" class="btn btn-light btn-lg">
                    {{ $settings['about_cta_text'] }}
                </a>
                @endif
            </div>
            <div class="col-lg-6">
                @if(isset($settings['about_hero_image']))
                <img src="{{ asset('storage/' . $settings['about_hero_image']) }}" alt="Hero Image" class="img-fluid">
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
@if(isset($settings['about_features_enabled']) && $settings['about_features_enabled'])
<section class="content-section">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="mb-3">{{ $settings['about_features_title'] ?? 'مميزاتنا' }}</h2>
                <p class="text-muted">{{ $settings['about_features_subtitle'] ?? '' }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h5 class="card-title">توصيل سريع</h5>
                        <p class="card-text text-muted">نوصل طلبك في أسرع وقت ممكن</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="card-title">آمن ومضمون</h5>
                        <p class="card-text text-muted">معاملات آمنة ومضمونة 100%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h5 class="card-title">دعم فني 24/7</h5>
                        <p class="card-text text-muted">فريق دعم متاح على مدار الساعة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- About Content Section -->
<section class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if(isset($settings['about_content']))
                <div class="content-section">
                    {!! $settings['about_content'] !!}
                </div>
                @else
                <div class="text-center">
                    <h2 class="mb-4">عن المتجر</h2>
                    <p class="lead text-muted">
                        متجر متعدد البائعين يوفر لك تجربة تسوق مميزة مع مجموعة واسعة من المنتجات من مختلف البائعين.
                    </p>
                </div>
                @endif
            </div>
        </div>
        
        @if(isset($settings['about_image']))
        <div class="row mt-5">
            <div class="col-lg-12 text-center">
                <img src="{{ asset('storage/' . $settings['about_image']) }}" alt="About Image" class="img-fluid">
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Statistics Section -->
@if(isset($settings['about_stats_enabled']) && $settings['about_stats_enabled'])
<section class="content-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <h3 class="text-primary">{{ $settings['about_stat_1_value'] ?? '1000+' }}</h3>
                <p class="text-muted">{{ $settings['about_stat_1_label'] ?? 'عميل سعيد' }}</p>
            </div>
            <div class="col-md-3 mb-4">
                <h3 class="text-primary">{{ $settings['about_stat_2_value'] ?? '500+' }}</h3>
                <p class="text-muted">{{ $settings['about_stat_2_label'] ?? 'منتج' }}</p>
            </div>
            <div class="col-md-3 mb-4">
                <h3 class="text-primary">{{ $settings['about_stat_3_value'] ?? '50+' }}</h3>
                <p class="text-muted">{{ $settings['about_stat_3_label'] ?? 'بائع' }}</p>
            </div>
            <div class="col-md-3 mb-4">
                <h3 class="text-primary">{{ $settings['about_stat_4_value'] ?? '24/7' }}</h3>
                <p class="text-muted">{{ $settings['about_stat_4_label'] ?? 'دعم فني' }}</p>
            </div>
        </div>
    </div>
</section>
@endif
@endsection

