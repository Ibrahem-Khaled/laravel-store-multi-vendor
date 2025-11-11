@extends('layouts.public')

@section('title', 'الدعم والتواصل')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
        margin-bottom: 40px;
    }
    
    .page-content {
        padding: 40px 0;
    }
    
    .contact-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        padding: 30px;
        text-align: center;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
    }
    
    .contact-icon {
        font-size: 3rem;
        color: #667eea;
        margin-bottom: 20px;
    }
    
    .contact-form {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 40px;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-primary {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .btn-primary:hover {
        background-color: #5568d3;
        border-color: #5568d3;
    }
    
    .social-links {
        margin-top: 30px;
    }
    
    .social-links a {
        display: inline-block;
        width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        border-radius: 50%;
        background-color: #667eea;
        color: white;
        margin: 0 10px;
        transition: all 0.3s ease;
    }
    
    .social-links a:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 mb-3">
                    <i class="fas fa-headset me-2"></i>
                    الدعم والتواصل
                </h1>
                <p class="lead">نحن هنا لمساعدتك في أي وقت</p>
            </div>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="page-content">
    <div class="container">
        <!-- Contact Information Cards -->
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>البريد الإلكتروني</h4>
                    <p class="text-muted">
                        {{ $settings['contact_email'] ?? 'info@example.com' }}
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4>الهاتف</h4>
                    <p class="text-muted">
                        {{ $settings['contact_phone'] ?? '+20 123 456 7890' }}
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4>العنوان</h4>
                    <p class="text-muted">
                        {{ $settings['contact_address'] ?? 'القاهرة، مصر' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Form and Social Media -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="contact-form">
                    <h3 class="mb-4">أرسل لنا رسالة</h3>
                    <form action="{{ route('support.contact') }}" method="POST" id="contactForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">الموضوع</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">الرسالة</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>
                            إرسال الرسالة
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-form">
                    <h3 class="mb-4">تابعنا</h3>
                    <p class="text-muted mb-4">
                        تابعنا على وسائل التواصل الاجتماعي للحصول على آخر الأخبار والعروض
                    </p>
                    <div class="social-links text-center">
                        @if(isset($socialSettings['facebook_url']) && $socialSettings['facebook_url'])
                        <a href="{{ $socialSettings['facebook_url'] }}" target="_blank" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif
                        @if(isset($socialSettings['twitter_url']) && $socialSettings['twitter_url'])
                        <a href="{{ $socialSettings['twitter_url'] }}" target="_blank" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                        @if(isset($socialSettings['instagram_url']) && $socialSettings['instagram_url'])
                        <a href="{{ $socialSettings['instagram_url'] }}" target="_blank" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        @if(isset($socialSettings['linkedin_url']) && $socialSettings['linkedin_url'])
                        <a href="{{ $socialSettings['linkedin_url'] }}" target="_blank" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        @endif
                        @if(isset($socialSettings['youtube_url']) && $socialSettings['youtube_url'])
                        <a href="{{ $socialSettings['youtube_url'] }}" target="_blank" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        @endif
                    </div>
                    
                    <div class="mt-5">
                        <h5 class="mb-3">ساعات العمل</h5>
                        <p class="mb-2">
                            <strong>الأحد - الخميس:</strong><br>
                            {{ $settings['working_hours'] ?? '9:00 صباحاً - 5:00 مساءً' }}
                        </p>
                        <p class="mb-2">
                            <strong>الجمعة - السبت:</strong><br>
                            {{ $settings['weekend_hours'] ?? 'مغلق' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
                this.reset();
            } else {
                alert('حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.');
        });
    });
</script>
@endpush
@endsection

