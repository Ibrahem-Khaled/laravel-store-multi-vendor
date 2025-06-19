<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>احجزلي - احجز قاعة أحلامك بضغطة زر</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap');

        * {
            font-family: 'Cairo', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .glow {
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.4);
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) translateX(0px);
                opacity: 0;
            }

            50% {
                transform: translateY(-100px) translateX(50px);
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-gray-50 overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-effect">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center glow">
                        <i class="fas fa-crown text-white text-xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold gradient-text">احجزلي</h1>
                </div>
                <div class="hidden md:flex space-x-8 space-x-reverse">
                    <a href="#features" class="text-gray-700 hover:text-purple-600 transition-colors">المميزات</a>
                    <a href="#services" class="text-gray-700 hover:text-purple-600 transition-colors">الخدمات</a>
                    <a href="#contact" class="text-gray-700 hover:text-purple-600 transition-colors">تواصل معنا</a>
                </div>
                <button class="gradient-bg text-white px-6 py-2 rounded-full hover:shadow-lg transition-all glow">
                    حمل التطبيق
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center gradient-bg overflow-hidden">
        <!-- Animated Particles -->
        <div class="absolute inset-0">
            <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
            <div class="particle" style="left: 20%; animation-delay: 1s;"></div>
            <div class="particle" style="left: 30%; animation-delay: 2s;"></div>
            <div class="particle" style="left: 40%; animation-delay: 3s;"></div>
            <div class="particle" style="left: 50%; animation-delay: 4s;"></div>
            <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
            <div class="particle" style="left: 70%; animation-delay: 0.5s;"></div>
            <div class="particle" style="left: 80%; animation-delay: 1.5s;"></div>
            <div class="particle" style="left: 90%; animation-delay: 2.5s;"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 items-center gap-12">
                <div class="text-white fade-in">
                    <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                        احجز قاعة
                        <span class="text-yellow-300">أحلامك</span>
                        بضغطة زر
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-gray-200">
                        أول تطبيق في المنطقة لحجز قاعات الأفراح والفنادق الفاخرة بأسعار منافسة وخدمة استثنائية
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button
                            class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-full font-bold text-lg hover:bg-yellow-300 transition-all hover-scale glow">
                            <i class="fab fa-apple mr-2"></i>
                            حمل للآيفون
                        </button>
                        <button
                            class="bg-white text-purple-600 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition-all hover-scale">
                            <i class="fab fa-google-play mr-2"></i>
                            حمل للأندرويد
                        </button>
                    </div>
                </div>
                <div class="relative floating">
                    <div class="w-80 h-96 mx-auto relative">
                        <div class="absolute inset-0 bg-white rounded-3xl shadow-2xl transform rotate-6 opacity-20">
                        </div>
                        <div class="absolute inset-0 bg-white rounded-3xl shadow-2xl transform -rotate-6 opacity-40">
                        </div>
                        <div class="relative bg-white rounded-3xl shadow-2xl p-6 glow">
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-crown text-white text-2xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold gradient-text mb-2">احجزلي</h3>
                                <p class="text-gray-600 mb-6">احجز قاعتك المثالية</p>

                                <div class="space-y-4">
                                    <div class="bg-gradient-to-r from-purple-100 to-blue-100 p-4 rounded-xl">
                                        <div class="flex items-center justify-between">
                                            <div class="text-right">
                                                <p class="font-bold text-gray-800">قاعة الأميرة</p>
                                                <p class="text-sm text-gray-600">500 ضيف</p>
                                            </div>
                                            <div class="w-12 h-12 bg-purple-500 rounded-full"></div>
                                        </div>
                                    </div>

                                    <div class="bg-gradient-to-r from-yellow-100 to-orange-100 p-4 rounded-xl">
                                        <div class="flex items-center justify-between">
                                            <div class="text-right">
                                                <p class="font-bold text-gray-800">قصر الذهب</p>
                                                <p class="text-sm text-gray-600">800 ضيف</p>
                                            </div>
                                            <div class="w-12 h-12 bg-yellow-500 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-6">لماذا احجزلي؟</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    نوفر لك تجربة حجز استثنائية مع أفضل القاعات والفنادق في منطقتك
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-purple-50 to-blue-50 hover-scale">
                    <div class="w-20 h-20 gradient-bg rounded-full mx-auto mb-6 flex items-center justify-center glow">
                        <i class="fas fa-search text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">بحث ذكي</h3>
                    <p class="text-gray-600">ابحث عن القاعة المثالية حسب الموقع، السعة، والميزانية مع فلاتر متقدمة</p>
                </div>

                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-yellow-50 to-orange-50 hover-scale">
                    <div class="w-20 h-20 gradient-bg rounded-full mx-auto mb-6 flex items-center justify-center glow">
                        <i class="fas fa-credit-card text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">دفع آمن</h3>
                    <p class="text-gray-600">ادفع بأمان عبر جميع وسائل الدفع المحلية والعالمية مع ضمان كامل</p>
                </div>

                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-green-50 to-teal-50 hover-scale">
                    <div class="w-20 h-20 gradient-bg rounded-full mx-auto mb-6 flex items-center justify-center glow">
                        <i class="fas fa-headset text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">دعم 24/7</h3>
                    <p class="text-gray-600">فريق دعم متخصص متاح على مدار الساعة لمساعدتك في كل خطوة</p>
                </div>

                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-pink-50 to-rose-50 hover-scale">
                    <div class="w-20 h-20 gradient-bg rounded-full mx-auto mb-6 flex items-center justify-center glow">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">تقييمات حقيقية</h3>
                    <p class="text-gray-600">اطلع على تقييمات وآراء العملاء السابقين لتختار بثقة</p>
                </div>

                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 hover-scale">
                    <div class="w-20 h-20 gradient-bg rounded-full mx-auto mb-6 flex items-center justify-center glow">
                        <i class="fas fa-gift text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">عروض حصرية</h3>
                    <p class="text-gray-600">احصل على خصومات وعروض خاصة للحجوزات المبكرة والعملاء المميزين</p>
                </div>

                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-cyan-50 to-blue-50 hover-scale">
                    <div class="w-20 h-20 gradient-bg rounded-full mx-auto mb-6 flex items-center justify-center glow">
                        <i class="fas fa-calendar-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">إدارة الحجوزات</h3>
                    <p class="text-gray-600">تتبع وأدر حجوزاتك بسهولة مع إمكانية التعديل والإلغاء</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-6">خدماتنا المتميزة</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    نقدم باقة شاملة من الخدمات لجعل مناسبتك لا تُنسى
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div
                        class="flex items-start space-x-4 space-x-reverse p-6 bg-white rounded-2xl shadow-lg hover-scale">
                        <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-hotel text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2 text-gray-800">قاعات الأفراح الفاخرة</h3>
                            <p class="text-gray-600">مجموعة واسعة من قاعات الأفراح المجهزة بأحدث التقنيات والديكورات
                                الفاخرة</p>
                        </div>
                    </div>

                    <div
                        class="flex items-start space-x-4 space-x-reverse p-6 bg-white rounded-2xl shadow-lg hover-scale">
                        <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bed text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2 text-gray-800">حجز الفنادق</h3>
                            <p class="text-gray-600">احجز غرف فندقية فاخرة للضيوف والعائلة بأفضل الأسعار</p>
                        </div>
                    </div>

                    <div
                        class="flex items-start space-x-4 space-x-reverse p-6 bg-white rounded-2xl shadow-lg hover-scale">
                        <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-utensils text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2 text-gray-800">خدمات الطعام</h3>
                            <p class="text-gray-600">تنسيق وجبات فاخرة من أفضل الطهاة والمطاعم المحلية</p>
                        </div>
                    </div>

                    <div
                        class="flex items-start space-x-4 space-x-reverse p-6 bg-white rounded-2xl shadow-lg hover-scale">
                        <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-camera text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2 text-gray-800">خدمات التصوير</h3>
                            <p class="text-gray-600">احجز أفضل المصورين لتوثيق أجمل اللحظات في مناسبتك الخاصة</p>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div class="h-32 bg-gradient-to-br from-purple-400 to-pink-400 rounded-2xl glow"></div>
                            <div class="h-48 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-2xl glow"></div>
                        </div>
                        <div class="space-y-4 mt-8">
                            <div class="h-48 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-2xl glow"></div>
                            <div class="h-32 bg-gradient-to-br from-green-400 to-teal-400 rounded-2xl glow"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 gradient-bg">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 text-center text-white">
                <div class="fade-in">
                    <div class="text-5xl font-bold mb-2">10,000+</div>
                    <p class="text-xl text-gray-200">عميل سعيد</p>
                </div>
                <div class="fade-in">
                    <div class="text-5xl font-bold mb-2">500+</div>
                    <p class="text-xl text-gray-200">قاعة وفندق</p>
                </div>
                <div class="fade-in">
                    <div class="text-5xl font-bold mb-2">15</div>
                    <p class="text-xl text-gray-200">مدينة مختلفة</p>
                </div>
                <div class="fade-in">
                    <div class="text-5xl font-bold mb-2">99%</div>
                    <p class="text-xl text-gray-200">رضا العملاء</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-6">جاهز لتحويل حلمك إلى حقيقة؟</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                حمل تطبيق احجزلي الآن واستمتع بتجربة حجز لا تُنسى مع عروض حصرية للمستخدمين الجدد
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button
                    class="gradient-bg text-white px-8 py-4 rounded-full font-bold text-lg hover:shadow-lg transition-all hover-scale glow">
                    <i class="fab fa-apple mr-2"></i>
                    متاح على App Store
                </button>
                <button
                    class="bg-gray-800 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-700 transition-all hover-scale">
                    <i class="fab fa-google-play mr-2"></i>
                    متاح على Google Play
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-4 space-x-reverse mb-6">
                        <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center">
                            <i class="fas fa-crown text-white text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold">احجزلي</h3>
                    </div>
                    <p class="text-gray-400 mb-6">احجز قاعة أحلامك بضغطة زر مع أفضل الأسعار والخدمات</p>
                    <div class="flex space-x-4 space-x-reverse">
                        <a href="#"
                            class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-500 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center hover:bg-blue-300 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-4">خدماتنا</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">حجز قاعات الأفراح</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">حجز الفنادق</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">خدمات الطعام</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">التصوير والتوثيق</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-4">الدعم</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">مركز المساعدة</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">الأسئلة الشائعة</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">تواصل معنا</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">سياسة الخصوصية</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-4">تواصل معنا</h4>
                    <div class="space-y-4 text-gray-400">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-phone"></i>
                            <span>+20 123 456 7890</span>
                        </div>
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-envelope"></i>
                            <span>info@ehgezly.com</span>
                        </div>
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>القاهرة، مصر</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2025 احجزلي. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll effect to navigation
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.classList.add('bg-white', 'shadow-lg');
                nav.classList.remove('glass-effect');
            } else {
                nav.classList.remove('bg-white', 'shadow-lg');
                nav.classList.add('glass-effect');
            }
        });

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.hover-scale').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>

</html>
