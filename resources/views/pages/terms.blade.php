@extends('layouts.public')

@section('title', 'شروط الاستخدام')

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
                    <i class="fas fa-file-contract me-2"></i>
                    شروط الاستخدام
                </h1>
                <p class="lead">يرجى قراءة شروط الاستخدام بعناية قبل استخدام موقعنا</p>
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
                    @if($termsContent)
                        {!! $termsContent !!}
                    @else
                        <h2>القبول بالشروط</h2>
                        <p>
                            من خلال الوصول إلى واستخدام موقع {{ \App\Models\Setting::get('site_name', 'متجر متعدد البائعين') }}، 
                            فإنك تقبل وتوافق على الالتزام بشروط وأحكام الاستخدام هذه. إذا كنت لا توافق على أي جزء من هذه الشروط، 
                            فيجب عليك عدم استخدام موقعنا.
                        </p>
                        
                        <h2>استخدام الموقع</h2>
                        <p>يجب عليك:</p>
                        <ul>
                            <li>استخدام الموقع للأغراض القانونية فقط</li>
                            <li>تقديم معلومات دقيقة وصحيحة عند التسجيل</li>
                            <li>الحفاظ على سرية معلومات حسابك</li>
                            <li>الإبلاغ عن أي استخدام غير مصرح به لحسابك</li>
                        </ul>
                        
                        <p>يُمنع عليك:</p>
                        <ul>
                            <li>استخدام الموقع لأي غرض غير قانوني</li>
                            <li>محاولة الوصول غير المصرح به إلى الموقع أو أنظمته</li>
                            <li>نشر محتوى مسيء أو غير قانوني</li>
                            <li>انتهاك حقوق الملكية الفكرية</li>
                        </ul>
                        
                        <h2>الحسابات والمعلومات الشخصية</h2>
                        <p>
                            أنت مسؤول عن الحفاظ على سرية معلومات حسابك وكلمة المرور. 
                            أنت توافق على قبول المسؤولية عن جميع الأنشطة التي تحدث تحت حسابك.
                        </p>
                        
                        <h2>المنتجات والأسعار</h2>
                        <p>
                            نحن نحتفظ بالحق في تغيير الأسعار والمنتجات في أي وقت دون إشعار مسبق. 
                            نحن نبذل قصارى جهدنا لضمان دقة المعلومات، ولكن قد تحدث أخطاء.
                        </p>
                        
                        <h2>الطلبات والدفع</h2>
                        <p>
                            عند تقديم طلب، فإنك توافق على دفع السعر المذكور للمنتج. 
                            نحن نحتفظ بالحق في رفض أو إلغاء أي طلب لأي سبب كان.
                        </p>
                        
                        <h2>الاسترجاع والاستبدال</h2>
                        <p>
                            يمكنك إرجاع المنتجات خلال فترة محددة وفقاً لسياسة الإرجاع الخاصة بنا. 
                            يجب أن تكون المنتجات في حالتها الأصلية مع جميع الملحقات.
                        </p>
                        
                        <h2>الملكية الفكرية</h2>
                        <p>
                            جميع المحتويات الموجودة على الموقع، بما في ذلك النصوص والرسومات والشعارات والصور، 
                            هي ملك لنا أو لشركائنا ومحمية بموجب قوانين حقوق النشر والعلامات التجارية.
                        </p>
                        
                        <h2>إخلاء المسؤولية</h2>
                        <p>
                            نحن لا نضمن أن الموقع سيعمل بدون أخطاء أو انقطاع. 
                            نحن غير مسؤولين عن أي أضرار قد تنتج عن استخدام الموقع.
                        </p>
                        
                        <h2>التعديلات</h2>
                        <p>
                            نحتفظ بالحق في تعديل هذه الشروط في أي وقت. 
                            سيتم إشعارك بأي تغييرات عن طريق نشر الشروط المحدثة على هذه الصفحة.
                        </p>
                        
                        <h2>القانون الحاكم</h2>
                        <p>
                            تخضع هذه الشروط وتفسر وفقاً لقوانين الدولة التي نعمل فيها، 
                            دون اعتبار لأحكام تعارض القوانين.
                        </p>
                        
                        <h2>اتصل بنا</h2>
                        <p>
                            إذا كان لديك أي أسئلة حول شروط الاستخدام هذه، يرجى الاتصال بنا من خلال 
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

