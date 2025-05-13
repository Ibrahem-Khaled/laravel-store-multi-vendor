<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة تعليمية | الملف الشخصي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8fafc;
        }
        
        .profile-card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .profile-card:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .tab-button {
            transition: all 0.3s ease;
        }
        
        .tab-button.active {
            border-bottom: 3px solid #4a2f85;
            color: #4a2f85;
        }
        
        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .progress-ring__circle {
            stroke-dasharray: 314;
            stroke-dashoffset: 314;
            transition: stroke-dashoffset 0.5s ease;
        }
        
        .course-card {
            transition: all 0.3s ease;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #1E3A8A, #4a2f85);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    @include('components.web.header')

    <!-- Profile Section -->
    <section class="py-12 bg-gradient-to-r from-blue-900 to-blue-600 text-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/4 flex justify-center mb-8 md:mb-0">
                    <div class="relative">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="الصورة الشخصية" 
                             class="w-40 h-40 rounded-full border-4 border-white shadow-lg object-cover">
                        <button class="absolute bottom-0 right-0 bg-blue-500 hover:bg-blue-600 text-white 
                                      rounded-full p-2 shadow-md">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                </div>
                <div class="md:w-3/4 text-center md:text-right">
                    <h1 class="text-3xl font-bold mb-2">أحمد محمد</h1>
                    <p class="text-lg opacity-90 mb-4">طالب في علوم الحاسب</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 mb-6">
                        <div class="flex items-center bg-blue-700 bg-opacity-30 px-4 py-2 rounded-full">
                            <i class="fas fa-medal text-yellow-400 mr-2"></i>
                            <span>المستوى: 3</span>
                        </div>
                        <div class="flex items-center bg-blue-700 bg-opacity-30 px-4 py-2 rounded-full">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            <span>نقاط: 1,245</span>
                        </div>
                        <div class="flex items-center bg-blue-700 bg-opacity-30 px-4 py-2 rounded-full">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            <span>12 دورة مكتملة</span>
                        </div>
                    </div>
                    <div class="flex justify-center md:justify-start space-x-reverse space-x-4">
                        <button class="bg-white hover:bg-gray-100 text-blue-900 font-bold py-2 px-6 rounded-lg">
                            تعديل الملف
                        </button>
                        <button class="bg-transparent hover:bg-blue-700 text-white border border-white font-bold py-2 px-6 rounded-lg">
                            مشاركة الملف
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar -->
                <div class="lg:w-1/4">
                    <div class="profile-card bg-white rounded-xl p-6 mb-6">
                        <h3 class="text-xl font-bold text-blue-900 mb-4 gradient-text">معلوماتي</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center">
                                <i class="fas fa-envelope text-blue-500 w-6 text-center mr-3"></i>
                                <span>ahmed@example.com</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone text-blue-500 w-6 text-center mr-3"></i>
                                <span>+966 50 123 4567</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-map-marker-alt text-blue-500 w-6 text-center mr-3"></i>
                                <span>الرياض، المملكة العربية السعودية</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-calendar-alt text-blue-500 w-6 text-center mr-3"></i>
                                <span>انضم في 15 يناير 2023</span>
                            </li>
                        </ul>
                    </div>

                    <div class="profile-card bg-white rounded-xl p-6 mb-6">
                        <h3 class="text-xl font-bold text-blue-900 mb-4 gradient-text">إحصائياتي</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">12</div>
                                <div class="text-gray-600">دورة مكتملة</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">5</div>
                                <div class="text-gray-600">دورة جارية</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">87%</div>
                                <div class="text-gray-600">معدل الإنجاز</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">1245</div>
                                <div class="text-gray-600">نقطة</div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card bg-white rounded-xl p-6">
                        <h3 class="text-xl font-bold text-blue-900 mb-4 gradient-text">مهاراتي</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700">برمجة JavaScript</span>
                                    <span class="text-blue-600">75%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700">تصميم UI/UX</span>
                                    <span class="text-blue-600">60%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 60%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700">تحليل البيانات</span>
                                    <span class="text-blue-600">45%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:w-3/4">
                    <!-- Tabs Navigation -->
                    <div class="flex overflow-x-auto border-b border-gray-200 mb-8">
                        <button class="tab-button px-6 py-3 font-medium text-lg active" data-tab="courses">
                            دوراتي
                        </button>
                        <button class="tab-button px-6 py-3 font-medium text-lg" data-tab="achievements">
                            إنجازاتي
                        </button>
                        <button class="tab-button px-6 py-3 font-medium text-lg" data-tab="certificates">
                            شهاداتي
                        </button>
                        <button class="tab-button px-6 py-3 font-medium text-lg" data-tab="activity">
                            النشاطات
                        </button>
                        <button class="tab-button px-6 py-3 font-medium text-lg" data-tab="settings">
                            الإعدادات
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div id="courses-tab" class="tab-content">
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-blue-900 mb-6 gradient-text">الدورات الجارية</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Course 1 -->
                                <div class="course-card bg-white rounded-xl overflow-hidden shadow-md">
                                    <div class="relative">
                                        <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" 
                                             alt="برمجة تطبيقات الويب" class="w-full h-40 object-cover">
                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                            <h4 class="text-white font-bold">برمجة تطبيقات الويب باستخدام React.js</h4>
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="flex justify-between items-center mb-4">
                                            <div class="flex items-center">
                                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="المدرب" 
                                                     class="w-8 h-8 rounded-full mr-2">
                                                <span class="text-sm">خالد أحمد</span>
                                            </div>
                                            <span class="text-yellow-500">
                                                <i class="fas fa-star"></i> 4.9
                                            </span>
                                        </div>
                                        <div class="mb-4">
                                            <div class="flex justify-between text-sm mb-1">
                                                <span>التقدم</span>
                                                <span>65%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: 65%"></div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between">
                                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                استكمال التعلم
                                            </a>
                                            <span class="text-gray-500 text-sm">12/18 درس</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Course 2 -->
                                <div class="course-card bg-white rounded-xl overflow-hidden shadow-md">
                                    <div class="relative">
                                        <img src="https://images.unsplash.com/photo-1547658719-da2b51169166?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" 
                                             alt="التسويق الرقمي" class="w-full h-40 object-cover">
                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                            <h4 class="text-white font-bold">الإستراتيجيات الحديثة في التسويق الرقمي</h4>
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="flex justify-between items-center mb-4">
                                            <div class="flex items-center">
                                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="المدرب" 
                                                     class="w-8 h-8 rounded-full mr-2">
                                                <span class="text-sm">سارة عبدالله</span>
                                            </div>
                                            <span class="text-yellow-500">
                                                <i class="fas fa-star"></i> 4.8
                                            </span>
                                        </div>
                                        <div class="mb-4">
                                            <div class="flex justify-between text-sm mb-1">
                                                <span>التقدم</span>
                                                <span>42%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: 42%"></div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between">
                                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                استكمال التعلم
                                            </a>
                                            <span class="text-gray-500 text-sm">8/19 درس</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold text-blue-900 mb-6 gradient-text">الدورات المكتملة</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Completed Course 1 -->
                                <div class="course-card bg-white rounded-xl overflow-hidden shadow-md">
                                    <div class="relative">
                                        <img src="https://images.unsplash.com/photo-1541462608143-67571c6738dd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" 
                                             alt="الذكاء الاصطناعي" class="w-full h-40 object-cover">
                                        <div class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">
                                            مكتمل
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <h4 class="font-bold mb-2">مقدمة في تعلم الآلة والذكاء الاصطناعي</h4>
                                        <div class="flex items-center mb-4">
                                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="المدرب" 
                                                 class="w-8 h-8 rounded-full mr-2">
                                            <span class="text-sm">خالد أحمد</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-yellow-500">
                                                <i class="fas fa-star"></i> 4.7
                                            </span>
                                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                عرض الشهادة
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Completed Course 2 -->
                                <div class="course-card bg-white rounded-xl overflow-hidden shadow-md">
                                    <div class="relative">
                                        <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" 
                                             alt="تحليل البيانات" class="w-full h-40 object-cover">
                                        <div class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">
                                            مكتمل
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <h4 class="font-bold mb-2">تحليل البيانات باستخدام Power BI</h4>
                                        <div class="flex items-center mb-4">
                                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="المدرب" 
                                                 class="w-8 h-8 rounded-full mr-2">
                                            <span class="text-sm">نورة السعيد</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-yellow-500">
                                                <i class="fas fa-star"></i> 4.6
                                            </span>
                                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                عرض الشهادة
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Completed Course 3 -->
                                <div class="course-card bg-white rounded-xl overflow-hidden shadow-md">
                                    <div class="relative">
                                        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" 
                                             alt="إدارة المشاريع" class="w-full h-40 object-cover">
                                        <div class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">
                                            مكتمل
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <h4 class="font-bold mb-2">إدارة المشاريع الاحترافية PMP</h4>
                                        <div class="flex items-center mb-4">
                                            <img src="https://randomuser.me/api/portraits/men/92.jpg" alt="المدرب" 
                                                 class="w-8 h-8 rounded-full mr-2">
                                            <span class="text-sm">محمد علي</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-yellow-500">
                                                <i class="fas fa-star"></i> 4.9
                                            </span>
                                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                عرض الشهادة
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Achievements Tab Content (Hidden by default) -->
                    <div id="achievements-tab" class="tab-content hidden">
                        <h3 class="text-2xl font-bold text-blue-900 mb-6 gradient-text">إنجازاتي</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Achievement 1 -->
                            <div class="bg-white rounded-xl p-6 shadow-md text-center">
                                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-trophy text-yellow-500 text-3xl"></i>
                                </div>
                                <h4 class="font-bold text-lg mb-2">المبتدئ المتميز</h4>
                                <p class="text-gray-600 mb-4">أكملت 5 دورات بنجاح</p>
                                <span class="text-sm text-gray-500">تم الحصول عليها في 15 مارس 2023</span>
                            </div>

                            <!-- Achievement 2 -->
                            <div class="bg-white rounded-xl p-6 shadow-md text-center">
                                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-medal text-blue-500 text-3xl"></i>
                                </div>
                                <h4 class="font-bold text-lg mb-2">المتعلم النشط</h4>
                                <p class="text-gray-600 mb-4">سجلت في 10 دروس خلال أسبوع واحد</p>
                                <span class="text-sm text-gray-500">تم الحصول عليها في 2 أبريل 2023</span>
                            </div>

                            <!-- Achievement 3 -->
                            <div class="bg-white rounded-xl p-6 shadow-md text-center">
                                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-star text-green-500 text-3xl"></i>
                                </div>
                                <h4 class="font-bold text-lg mb-2">المساهم المثالي</h4>
                                <p class="text-gray-600 mb-4">ساعدت 5 زملاء في منتديات النقاش</p>
                                <span class="text-sm text-gray-500">تم الحصول عليها في 8 مايو 2023</span>
                            </div>
                        </div>
                    </div>

                    <!-- Certificates Tab Content (Hidden by default) -->
                    <div id="certificates-tab" class="tab-content hidden">
                        <h3 class="text-2xl font-bold text-blue-900 mb-6 gradient-text">شهاداتي</h3>
                        <div class="space-y-6">
                            <!-- Certificate 1 -->
                            <div class="bg-white rounded-xl overflow-hidden shadow-md">
                                <div class="md:flex">
                                    <div class="md:w-1/3">
                                        <img src="https://images.unsplash.com/photo-1541462608143-67571c6738dd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" 
                                             alt="شهادة الذكاء الاصطناعي" class="w-full h-full object-cover">
                                    </div>
                                    <div class="md:w-2/3 p-6">
                                        <h4 class="font-bold text-xl mb-2">شهادة إتمام دورة الذكاء الاصطناعي</h4>
                                        <p class="text-gray-600 mb-4">تم منح هذه الشهادة لإتمامك بنجاح دورة "مقدمة في تعلم الآلة والذكاء الاصطناعي"</p>
                                        <div class="flex flex-wrap gap-4">
                                            <div>
                                                <p class="text-sm text-gray-500">تاريخ الإصدار</p>
                                                <p class="font-medium">15 يونيو 2023</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">المدرب</p>
                                                <p class="font-medium">خالد أحمد</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">المستوى</p>
                                                <p class="font-medium">متوسط</p>
                                            </div>
                                        </div>
                                        <div class="mt-6 flex space-x-reverse space-x-4">
                                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                                <i class="fas fa-download mr-2"></i> تحميل الشهادة
                                            </button>
                                            <button class="bg-white hover:bg-gray-100 text-blue-600 border border-blue-600 font-bold py-2 px-4 rounded-lg">
                                                <i class="fas fa-share-alt mr-2"></i> مشاركة
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Certificate 2 -->
                            <div class="bg-white rounded-xl overflow-hidden shadow-md">
                                <div class="md:flex">
                                    <div class="md:w-1/3">
                                        <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" 
                                             alt="شهادة تحليل البيانات" class="w-full h-full object-cover">
                                    </div>
                                    <div class="md:w-2/3 p-6">
                                        <h4 class="font-bold text-xl mb-2">شهادة إتمام دورة تحليل البيانات</h4>
                                        <p class="text-gray-600 mb-4">تم منح هذه الشهادة لإتمامك بنجاح دورة "تحليل البيانات باستخدام Power BI"</p>
                                        <div class="flex flex-wrap gap-4">
                                            <div>
                                                <p class="text-sm text-gray-500">تاريخ الإصدار</p>
                                                <p class="font-medium">5 مايو 2023</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">المدرب</p>
                                                <p class="font-medium">نورة السعيد</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">المستوى</p>
                                                <p class="font-medium">مبتدئ</p>
                                            </div>
                                        </div>
                                        <div class="mt-6 flex space-x-reverse space-x-4">
                                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                                <i class="fas fa-download mr-2"></i> تحميل الشهادة
                                            </button>
                                            <button class="bg-white hover:bg-gray-100 text-blue-600 border border-blue-600 font-bold py-2 px-4 rounded-lg">
                                                <i class="fas fa-share-alt mr-2"></i> مشاركة
                                            </button>
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

    @include('components.web.footer')

    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => content.classList.add('hidden'));
                    // Show the selected tab content
                    const tabId = this.getAttribute('data-tab') + '-tab';
                    document.getElementById(tabId).classList.remove('hidden');
                });
            });
            
            // Initialize progress circles
            const progressCircles = document.querySelectorAll('.progress-ring__circle');
            progressCircles.forEach(circle => {
                const radius = circle.r.baseVal.value;
                const circumference = 2 * Math.PI * radius;
                const percent = parseFloat(circle.getAttribute('data-percent'));
                
                circle.style.strokeDasharray = circumference;
                circle.style.strokeDashoffset = circumference - (percent / 100) * circumference;
            });
        });
    </script>
</body>

</html>