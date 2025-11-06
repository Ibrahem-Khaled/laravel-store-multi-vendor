<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('components.seo')

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4a2f85;
            --primary-dark: #36205d;
            --primary-light: #6b4da8;
            --secondary-color: #ff6b6b;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
            --text-dark: #2d3748;
            --text-light: #718096;
            --bg-light: #f7fafc;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        /* Auth Container */
        .auth-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            backdrop-filter: blur(10px);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-left {
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .auth-right {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .auth-right::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .auth-right-content {
            position: relative;
            z-index: 1;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container img {
            width: 150px;
            height: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        .auth-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-subtitle {
            color: var(--text-light);
            font-size: 15px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .form-label i {
            color: var(--primary-color);
            font-size: 16px;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background: white;
            outline: none;
        }

        .form-control::placeholder {
            color: #cbd5e0;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 16px;
            pointer-events: none;
        }

        .input-with-icon {
            padding-right: 50px;
        }

        .btn-primary-custom {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 18px;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary-custom:hover::before {
            left: 100%;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-primary-custom:active {
            transform: translateY(0);
        }

        .alert-custom {
            border-radius: 12px;
            padding: 16px;
            border: none;
            box-shadow: var(--shadow-sm);
            animation: shake 0.5s ease-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .auth-right-icon {
            font-size: 120px;
            margin-bottom: 30px;
            opacity: 0.9;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .auth-right-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .auth-right-text {
            font-size: 18px;
            opacity: 0.95;
            line-height: 1.8;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .login-link p {
            color: var(--text-light);
            margin-bottom: 15px;
        }

        .login-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            border-radius: 2px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .password-strength.weak .password-strength-bar {
            width: 33%;
            background: #ef4444;
        }

        .password-strength.medium .password-strength-bar {
            width: 66%;
            background: #f59e0b;
        }

        .password-strength.strong .password-strength-bar {
            width: 100%;
            background: #10b981;
        }

        @media (max-width: 991px) {
            .auth-right {
                display: none;
            }
            
            .auth-left {
                padding: 35px 25px;
            }
        }

        /* Custom Scrollbar */
        .auth-left::-webkit-scrollbar {
            width: 8px;
        }

        .auth-left::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .auth-left::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .auth-left::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="row g-0">
                <!-- Left Side - Register Form -->
                <div class="col-lg-6 auth-left">
                    <div class="logo-container">
                        <img src="{{ asset('assets/img/logo-ct-dark.png') }}" alt="الشعار">
                        <h1 class="auth-title">إنشاء حساب جديد</h1>
                        <p class="auth-subtitle">املأ البيانات التالية لإنشاء حسابك</p>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger alert-custom" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-custom" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>يرجى إصلاح الأخطاء التالية:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customRegister') }}" id="registerForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="name">
                                        <i class="fas fa-user"></i>
                                        الاسم الكامل
                                    </label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               name="name" 
                                               id="name"
                                               class="form-control input-with-icon" 
                                               placeholder="أدخل اسمك الكامل"
                                               value="{{ old('name') }}"
                                               required>
                                        <i class="fas fa-user input-icon"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">
                                        <i class="fas fa-envelope"></i>
                                        البريد الإلكتروني
                                    </label>
                                    <div class="position-relative">
                                        <input type="email" 
                                               name="email" 
                                               id="email"
                                               class="form-control input-with-icon" 
                                               placeholder="أدخل بريدك الإلكتروني"
                                               value="{{ old('email') }}"
                                               required>
                                        <i class="fas fa-at input-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="phone">
                                        <i class="fas fa-phone"></i>
                                        رقم الهاتف
                                    </label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               name="phone" 
                                               id="phone"
                                               class="form-control input-with-icon" 
                                               placeholder="أدخل رقم هاتفك"
                                               value="{{ old('phone') }}"
                                               required>
                                        <i class="fas fa-mobile-alt input-icon"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">
                                        <i class="fas fa-map-marker-alt"></i>
                                        العنوان
                                    </label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               name="address" 
                                               id="address"
                                               class="form-control input-with-icon" 
                                               placeholder="أدخل عنوانك"
                                               value="{{ old('address') }}"
                                               required>
                                        <i class="fas fa-home input-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="password">
                                        <i class="fas fa-lock"></i>
                                        كلمة المرور
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="password" 
                                               id="password"
                                               class="form-control input-with-icon" 
                                               placeholder="أدخل كلمة المرور"
                                               required
                                               autocomplete="new-password">
                                        <i class="fas fa-key input-icon"></i>
                                    </div>
                                    <div class="password-strength" id="passwordStrength">
                                        <div class="password-strength-bar"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="password_confirmation">
                                        <i class="fas fa-lock"></i>
                                        تأكيد كلمة المرور
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="password_confirmation"
                                               id="password_confirmation"
                                               class="form-control input-with-icon" 
                                               placeholder="أعد إدخال كلمة المرور"
                                               required
                                               autocomplete="new-password">
                                        <i class="fas fa-check-circle input-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-user-plus me-2"></i>
                            إنشاء حساب
                        </button>
                    </form>

                    <div class="login-link">
                        <p>لديك حساب بالفعل؟</p>
                        <a href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            تسجيل الدخول
                        </a>
                    </div>
                </div>

                <!-- Right Side - Decorative -->
                <div class="col-lg-6 auth-right">
                    <div class="auth-right-content">
                        <i class="fas fa-user-plus auth-right-icon"></i>
                        <h2 class="auth-right-title">انضم إلى مجتمعنا</h2>
                        <p class="auth-right-text">
                            إذا كنت تواجه أي مشاكل خلال عملية التسجيل، يرجى التواصل معنا وسنقوم بمساعدتك بكل سرور. 
                            نحن هنا لمساعدتك في كل خطوة.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');
        
        if (passwordInput && passwordStrength) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strengthBar = passwordStrength.querySelector('.password-strength-bar');
                
                // Remove all strength classes
                passwordStrength.classList.remove('weak', 'medium', 'strong');
                
                if (password.length === 0) {
                    strengthBar.style.width = '0%';
                    return;
                }
                
                let strength = 0;
                
                // Check password strength
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;
                
                if (strength <= 2) {
                    passwordStrength.classList.add('weak');
                } else if (strength === 3) {
                    passwordStrength.classList.add('medium');
                } else {
                    passwordStrength.classList.add('strong');
                }
            });
        }

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            
            if (password !== passwordConfirmation) {
                e.preventDefault();
                alert('كلمة المرور وتأكيدها غير متطابقين');
                return false;
            }
        });

        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.01)';
                });
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>

</html>
