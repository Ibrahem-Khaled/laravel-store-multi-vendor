@extends('layouts.public')

@section('title', 'سياسة الخصوصية')

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
    
    .content-box {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 40px;
        margin-bottom: 30px;
    }
    
    .content-box h2 {
        color: #667eea;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .content-box h3 {
        color: #333;
        margin-top: 30px;
        margin-bottom: 15px;
    }
    
    .content-box p {
        line-height: 1.8;
        color: #555;
        margin-bottom: 15px;
    }
    
    .content-box ul {
        padding-right: 20px;
    }
    
    .content-box li {
        margin-bottom: 10px;
        line-height: 1.8;
        color: #555;
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
                    <i class="fas fa-shield-alt me-2"></i>
                    سياسة الخصوصية
                </h1>
                <p class="lead">نحن ملتزمون بحماية خصوصيتك ومعلوماتك الشخصية</p>
            </div>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="content-box">
                    @if($privacyPolicy)
                        {!! $privacyPolicy !!}
                    @else
                        <h2>مقدمة</h2>
                        <p>
                            نحن في {{ \App\Models\Setting::get('site_name', 'متجر متعدد البائعين') }} نلتزم بحماية خصوصيتك. 
                            تشرح سياسة الخصوصية هذه كيفية جمع واستخدام وحماية معلوماتك الشخصية.
                        </p>
                        
                        <h2>المعلومات التي نجمعها</h2>
                        <p>نقوم بجمع المعلومات التالية:</p>
                        <ul>
                            <li>الاسم وعنوان البريد الإلكتروني عند التسجيل</li>
                            <li>معلومات الدفع عند إتمام عملية الشراء</li>
                            <li>عنوان الشحن والتوصيل</li>
                            <li>معلومات الاستخدام والتفضيلات</li>
                        </ul>
                        
                        <h2>كيف نستخدم معلوماتك</h2>
                        <p>نستخدم المعلومات التي نجمعها لـ:</p>
                        <ul>
                            <li>معالجة الطلبات وتنفيذها</li>
                            <li>تحسين تجربة المستخدم</li>
                            <li>إرسال التحديثات والعروض الخاصة</li>
                            <li>توفير الدعم الفني</li>
                        </ul>
                        
                        <h2>حماية المعلومات</h2>
                        <p>
                            نستخدم تقنيات أمنية متقدمة لحماية معلوماتك الشخصية من الوصول غير المصرح به أو التغيير أو الكشف.
                        </p>
                        
                        <h2>مشاركة المعلومات</h2>
                        <p>
                            نحن لا نبيع أو نؤجر معلوماتك الشخصية لأطراف ثالثة. قد نشارك معلوماتك فقط مع شركاء موثوقين 
                            لمساعدتنا في تشغيل موقعنا الإلكتروني أو خدمتك، شريطة أن يوافقوا على الحفاظ على سرية هذه المعلومات.
                        </p>
                        
                        <h2>ملفات تعريف الارتباط</h2>
                        <p>
                            نستخدم ملفات تعريف الارتباط لتحسين تجربتك على موقعنا. يمكنك تعطيل ملفات تعريف الارتباط من إعدادات المتصفح.
                        </p>
                        
                        <h2>حقوقك</h2>
                        <p>لديك الحق في:</p>
                        <ul>
                            <li>الوصول إلى معلوماتك الشخصية</li>
                            <li>تصحيح معلوماتك الشخصية</li>
                            <li>حذف معلوماتك الشخصية</li>
                            <li>الاعتراض على معالجة معلوماتك</li>
                        </ul>
                        
                        <h2>التغييرات على سياسة الخصوصية</h2>
                        <p>
                            قد نحدث سياسة الخصوصية هذه من وقت لآخر. سنخطرك بأي تغييرات عن طريق نشر السياسة الجديدة على هذه الصفحة.
                        </p>
                        
                        <h2>اتصل بنا</h2>
                        <p>
                            إذا كان لديك أي أسئلة حول سياسة الخصوصية هذه، يرجى الاتصال بنا من خلال 
                            <a href="{{ route('support') }}">صفحة الدعم</a>.
                        </p>
                        
                        <p class="mt-4">
                            <strong>آخر تحديث:</strong> {{ $updatedAt ?? date('Y-m-d') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

