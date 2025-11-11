<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'متجر متعدد البائعين')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: "Tajawal", sans-serif !important;
            direction: rtl;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 2rem 0;
            margin-top: auto;
        }
        
        .main-content {
            min-height: calc(100vh - 200px);
        }
    </style>
    
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <strong>{{ \App\Models\Setting::get('site_name', 'متجر متعدد البائعين') }}</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('support') }}">الدعم</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('privacy') }}">سياسة الخصوصية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('terms') }}">شروط الاستخدام</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'متجر متعدد البائعين') }}. جميع الحقوق محفوظة.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('privacy') }}" class="text-muted me-3">سياسة الخصوصية</a>
                    <a href="{{ route('terms') }}" class="text-muted me-3">شروط الاستخدام</a>
                    <a href="{{ route('support') }}" class="text-muted">الدعم</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>

