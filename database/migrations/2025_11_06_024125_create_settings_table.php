<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general'); // general, social, privacy, terms, about, seo, notifications
            $table->string('type')->default('text'); // text, textarea, image, boolean, number, email, url
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_public')->default(false); // يمكن الوصول إليه من خارج النظام
            $table->timestamps();
        });

        // إدخال الإعدادات الافتراضية
        $defaultSettings = [
            // الإعدادات العامة
            ['key' => 'site_name', 'value' => 'متجر متعدد البائعين', 'group' => 'general', 'type' => 'text', 'label' => 'اسم الموقع', 'description' => 'اسم الموقع الذي سيظهر في جميع الصفحات', 'order' => 1],
            ['key' => 'site_logo', 'value' => null, 'group' => 'general', 'type' => 'image', 'label' => 'شعار الموقع', 'description' => 'شعار الموقع الرئيسي', 'order' => 2],
            ['key' => 'site_favicon', 'value' => null, 'group' => 'general', 'type' => 'image', 'label' => 'أيقونة الموقع', 'description' => 'أيقونة الموقع التي تظهر في المتصفح', 'order' => 3],
            ['key' => 'site_email', 'value' => 'info@example.com', 'group' => 'general', 'type' => 'email', 'label' => 'البريد الإلكتروني', 'description' => 'البريد الإلكتروني الرسمي للموقع', 'order' => 4],
            ['key' => 'site_phone', 'value' => '+966500000000', 'group' => 'general', 'type' => 'text', 'label' => 'رقم الهاتف', 'description' => 'رقم الهاتف الرسمي للموقع', 'order' => 5],
            ['key' => 'site_address', 'value' => null, 'group' => 'general', 'type' => 'textarea', 'label' => 'عنوان الموقع', 'description' => 'العنوان الفعلي للموقع', 'order' => 6],
            ['key' => 'site_currency', 'value' => 'SAR', 'group' => 'general', 'type' => 'text', 'label' => 'العملة', 'description' => 'العملة المستخدمة في الموقع', 'order' => 7],
            ['key' => 'site_language', 'value' => 'ar', 'group' => 'general', 'type' => 'text', 'label' => 'اللغة الافتراضية', 'description' => 'اللغة الافتراضية للموقع', 'order' => 8],
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'general', 'type' => 'boolean', 'label' => 'وضع الصيانة', 'description' => 'تفعيل وضع الصيانة للموقع', 'order' => 9],
            ['key' => 'maintenance_message', 'value' => 'الموقع قيد الصيانة حالياً، نعتذر عن الإزعاج', 'group' => 'general', 'type' => 'textarea', 'label' => 'رسالة الصيانة', 'description' => 'الرسالة التي تظهر في وضع الصيانة', 'order' => 10],

            // إعدادات التواصل الاجتماعي
            ['key' => 'social_facebook', 'value' => null, 'group' => 'social', 'type' => 'url', 'label' => 'فيسبوك', 'description' => 'رابط صفحة الفيسبوك', 'order' => 1],
            ['key' => 'social_twitter', 'value' => null, 'group' => 'social', 'type' => 'url', 'label' => 'تويتر', 'description' => 'رابط حساب تويتر', 'order' => 2],
            ['key' => 'social_instagram', 'value' => null, 'group' => 'social', 'type' => 'url', 'label' => 'إنستغرام', 'description' => 'رابط حساب إنستغرام', 'order' => 3],
            ['key' => 'social_linkedin', 'value' => null, 'group' => 'social', 'type' => 'url', 'label' => 'لينكد إن', 'description' => 'رابط حساب لينكد إن', 'order' => 4],
            ['key' => 'social_youtube', 'value' => null, 'group' => 'social', 'type' => 'url', 'label' => 'يوتيوب', 'description' => 'رابط قناة يوتيوب', 'order' => 5],
            ['key' => 'social_whatsapp', 'value' => null, 'group' => 'social', 'type' => 'text', 'label' => 'واتساب', 'description' => 'رقم واتساب', 'order' => 6],

            // سياسة الخصوصية
            ['key' => 'privacy_policy', 'value' => 'سياسة الخصوصية', 'group' => 'privacy', 'type' => 'textarea', 'label' => 'سياسة الخصوصية', 'description' => 'نص سياسة الخصوصية', 'order' => 1, 'is_public' => true],
            ['key' => 'privacy_policy_updated_at', 'value' => now()->toDateString(), 'group' => 'privacy', 'type' => 'text', 'label' => 'تاريخ آخر تحديث', 'description' => 'تاريخ آخر تحديث لسياسة الخصوصية', 'order' => 2],

            // شروط الاستخدام
            ['key' => 'terms_of_service', 'value' => 'شروط الاستخدام', 'group' => 'terms', 'type' => 'textarea', 'label' => 'شروط الاستخدام', 'description' => 'نص شروط الاستخدام', 'order' => 1, 'is_public' => true],
            ['key' => 'terms_of_service_updated_at', 'value' => now()->toDateString(), 'group' => 'terms', 'type' => 'text', 'label' => 'تاريخ آخر تحديث', 'description' => 'تاريخ آخر تحديث لشروط الاستخدام', 'order' => 2],

            // عن الموقع
            ['key' => 'about_us', 'value' => 'نحن متجر متعدد البائعين يوفر أفضل المنتجات والخدمات', 'group' => 'about', 'type' => 'textarea', 'label' => 'عن الموقع', 'description' => 'معلومات عن الموقع', 'order' => 1, 'is_public' => true],
            ['key' => 'about_vision', 'value' => null, 'group' => 'about', 'type' => 'textarea', 'label' => 'الرؤية', 'description' => 'رؤية الموقع', 'order' => 2],
            ['key' => 'about_mission', 'value' => null, 'group' => 'about', 'type' => 'textarea', 'label' => 'الرسالة', 'description' => 'رسالة الموقع', 'order' => 3],

            // إعدادات SEO
            ['key' => 'seo_meta_title', 'value' => null, 'group' => 'seo', 'type' => 'text', 'label' => 'عنوان SEO', 'description' => 'العنوان الذي سيظهر في محركات البحث', 'order' => 1],
            ['key' => 'seo_meta_description', 'value' => null, 'group' => 'seo', 'type' => 'textarea', 'label' => 'وصف SEO', 'description' => 'الوصف الذي سيظهر في محركات البحث', 'order' => 2],
            ['key' => 'seo_meta_keywords', 'value' => null, 'group' => 'seo', 'type' => 'text', 'label' => 'الكلمات المفتاحية', 'description' => 'الكلمات المفتاحية للبحث', 'order' => 3],
            ['key' => 'seo_google_analytics', 'value' => null, 'group' => 'seo', 'type' => 'text', 'label' => 'Google Analytics ID', 'description' => 'معرف Google Analytics', 'order' => 4],

            // إعدادات الإشعارات
            ['key' => 'email_notifications_enabled', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean', 'label' => 'تفعيل إشعارات البريد', 'description' => 'تفعيل إرسال الإشعارات عبر البريد الإلكتروني', 'order' => 1],
            ['key' => 'sms_notifications_enabled', 'value' => '0', 'group' => 'notifications', 'type' => 'boolean', 'label' => 'تفعيل إشعارات SMS', 'description' => 'تفعيل إرسال الإشعارات عبر SMS', 'order' => 2],
            ['key' => 'push_notifications_enabled', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean', 'label' => 'تفعيل الإشعارات الفورية', 'description' => 'تفعيل الإشعارات الفورية للتطبيق', 'order' => 3],
        ];

        foreach ($defaultSettings as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
