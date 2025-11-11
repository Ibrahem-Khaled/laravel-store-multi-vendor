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
        // إضافة إعدادات الصفحة التعريفية
        $aboutSettings = [
            // معلومات الصفحة الرئيسية
            ['key' => 'about_title', 'value' => 'مرحباً بك في متجرنا', 'group' => 'about', 'type' => 'text', 'label' => 'عنوان الصفحة الرئيسية', 'description' => 'العنوان الرئيسي الذي يظهر في الصفحة الرئيسية', 'order' => 10, 'is_public' => true],
            ['key' => 'about_subtitle', 'value' => 'نقدم لك أفضل المنتجات بأفضل الأسعار', 'group' => 'about', 'type' => 'text', 'label' => 'العنوان الفرعي', 'description' => 'العنوان الفرعي للصفحة الرئيسية', 'order' => 11, 'is_public' => true],
            ['key' => 'about_content', 'value' => null, 'group' => 'about', 'type' => 'textarea', 'label' => 'محتوى الصفحة التعريفية', 'description' => 'المحتوى الكامل للصفحة التعريفية (يدعم HTML)', 'order' => 12, 'is_public' => true],
            ['key' => 'about_hero_image', 'value' => null, 'group' => 'about', 'type' => 'image', 'label' => 'صورة البطل', 'description' => 'الصورة الرئيسية في أعلى الصفحة', 'order' => 13, 'is_public' => true],
            ['key' => 'about_image', 'value' => null, 'group' => 'about', 'type' => 'image', 'label' => 'صورة عن الموقع', 'description' => 'صورة في قسم عن الموقع', 'order' => 14, 'is_public' => true],
            
            // زر الدعوة للإجراء
            ['key' => 'about_cta_text', 'value' => 'ابدأ التسوق الآن', 'group' => 'about', 'type' => 'text', 'label' => 'نص زر الدعوة', 'description' => 'النص الذي يظهر على زر الدعوة للإجراء', 'order' => 15, 'is_public' => true],
            ['key' => 'about_cta_link', 'value' => '#', 'group' => 'about', 'type' => 'url', 'label' => 'رابط زر الدعوة', 'description' => 'الرابط الذي ينتقل إليه زر الدعوة للإجراء', 'order' => 16, 'is_public' => true],
            
            // قسم المميزات
            ['key' => 'about_features_enabled', 'value' => '1', 'group' => 'about', 'type' => 'boolean', 'label' => 'تفعيل قسم المميزات', 'description' => 'إظهار قسم المميزات في الصفحة الرئيسية', 'order' => 17, 'is_public' => true],
            ['key' => 'about_features_title', 'value' => 'مميزاتنا', 'group' => 'about', 'type' => 'text', 'label' => 'عنوان قسم المميزات', 'description' => 'عنوان قسم المميزات', 'order' => 18, 'is_public' => true],
            ['key' => 'about_features_subtitle', 'value' => 'لماذا تختارنا', 'group' => 'about', 'type' => 'text', 'label' => 'العنوان الفرعي لقسم المميزات', 'description' => 'العنوان الفرعي لقسم المميزات', 'order' => 19, 'is_public' => true],
            
            // قسم الإحصائيات
            ['key' => 'about_stats_enabled', 'value' => '1', 'group' => 'about', 'type' => 'boolean', 'label' => 'تفعيل قسم الإحصائيات', 'description' => 'إظهار قسم الإحصائيات في الصفحة الرئيسية', 'order' => 20, 'is_public' => true],
            ['key' => 'about_stat_1_value', 'value' => '1000+', 'group' => 'about', 'type' => 'text', 'label' => 'قيمة الإحصائية الأولى', 'description' => 'القيمة الرقمية للإحصائية الأولى', 'order' => 21, 'is_public' => true],
            ['key' => 'about_stat_1_label', 'value' => 'عميل سعيد', 'group' => 'about', 'type' => 'text', 'label' => 'تسمية الإحصائية الأولى', 'description' => 'التسمية للإحصائية الأولى', 'order' => 22, 'is_public' => true],
            ['key' => 'about_stat_2_value', 'value' => '500+', 'group' => 'about', 'type' => 'text', 'label' => 'قيمة الإحصائية الثانية', 'description' => 'القيمة الرقمية للإحصائية الثانية', 'order' => 23, 'is_public' => true],
            ['key' => 'about_stat_2_label', 'value' => 'منتج', 'group' => 'about', 'type' => 'text', 'label' => 'تسمية الإحصائية الثانية', 'description' => 'التسمية للإحصائية الثانية', 'order' => 24, 'is_public' => true],
            ['key' => 'about_stat_3_value', 'value' => '50+', 'group' => 'about', 'type' => 'text', 'label' => 'قيمة الإحصائية الثالثة', 'description' => 'القيمة الرقمية للإحصائية الثالثة', 'order' => 25, 'is_public' => true],
            ['key' => 'about_stat_3_label', 'value' => 'بائع', 'group' => 'about', 'type' => 'text', 'label' => 'تسمية الإحصائية الثالثة', 'description' => 'التسمية للإحصائية الثالثة', 'order' => 26, 'is_public' => true],
            ['key' => 'about_stat_4_value', 'value' => '24/7', 'group' => 'about', 'type' => 'text', 'label' => 'قيمة الإحصائية الرابعة', 'description' => 'القيمة الرقمية للإحصائية الرابعة', 'order' => 27, 'is_public' => true],
            ['key' => 'about_stat_4_label', 'value' => 'دعم فني', 'group' => 'about', 'type' => 'text', 'label' => 'تسمية الإحصائية الرابعة', 'description' => 'التسمية للإحصائية الرابعة', 'order' => 28, 'is_public' => true],
        ];

        // إضافة إعدادات صفحة الدعم
        $supportSettings = [
            ['key' => 'contact_email', 'value' => 'info@example.com', 'group' => 'general', 'type' => 'email', 'label' => 'البريد الإلكتروني للتواصل', 'description' => 'البريد الإلكتروني الذي يظهر في صفحة الدعم', 'order' => 11, 'is_public' => true],
            ['key' => 'contact_phone', 'value' => '+20 123 456 7890', 'group' => 'general', 'type' => 'text', 'label' => 'رقم الهاتف للتواصل', 'description' => 'رقم الهاتف الذي يظهر في صفحة الدعم', 'order' => 12, 'is_public' => true],
            ['key' => 'contact_address', 'value' => 'القاهرة، مصر', 'group' => 'general', 'type' => 'textarea', 'label' => 'عنوان التواصل', 'description' => 'العنوان الذي يظهر في صفحة الدعم', 'order' => 13, 'is_public' => true],
            ['key' => 'working_hours', 'value' => '9:00 صباحاً - 5:00 مساءً', 'group' => 'general', 'type' => 'text', 'label' => 'ساعات العمل', 'description' => 'ساعات العمل في الأسبوع', 'order' => 14, 'is_public' => true],
            ['key' => 'weekend_hours', 'value' => 'مغلق', 'group' => 'general', 'type' => 'text', 'label' => 'ساعات العمل في نهاية الأسبوع', 'description' => 'ساعات العمل في الجمعة والسبت', 'order' => 15, 'is_public' => true],
        ];

        // تحديث إعدادات سياسة الخصوصية وشروط الاستخدام
        $contentSettings = [
            ['key' => 'privacy_policy_content', 'value' => null, 'group' => 'privacy', 'type' => 'textarea', 'label' => 'محتوى سياسة الخصوصية', 'description' => 'المحتوى الكامل لسياسة الخصوصية (يدعم HTML)', 'order' => 3, 'is_public' => true],
            ['key' => 'terms_of_service_content', 'value' => null, 'group' => 'terms', 'type' => 'textarea', 'label' => 'محتوى شروط الاستخدام', 'description' => 'المحتوى الكامل لشروط الاستخدام (يدعم HTML)', 'order' => 3, 'is_public' => true],
        ];

        // تحديث إعدادات التواصل الاجتماعي
        $socialSettings = [
            ['key' => 'facebook_url', 'value' => null, 'group' => 'social', 'type' => 'url', 'label' => 'رابط فيسبوك', 'description' => 'رابط صفحة فيسبوك', 'order' => 7, 'is_public' => true],
            ['key' => 'twitter_url', 'value' => null, 'group' => 'social', 'type' => 'url', 'label' => 'رابط تويتر', 'description' => 'رابط حساب تويتر', 'order' => 8, 'is_public' => true],
            ['key' => 'instagram_url', 'value' => null, 'group' => 'social', 'type' => 'url', 'label' => 'رابط إنستغرام', 'description' => 'رابط حساب إنستغرام', 'order' => 9, 'is_public' => true],
            ['key' => 'linkedin_url', 'value' => null, 'group' => 'social', 'type' => 'url', 'label' => 'رابط لينكد إن', 'description' => 'رابط حساب لينكد إن', 'order' => 10, 'is_public' => true],
            ['key' => 'youtube_url', 'value' => null, 'group' => 'social', 'type' => 'url', 'label' => 'رابط يوتيوب', 'description' => 'رابط قناة يوتيوب', 'order' => 11, 'is_public' => true],
        ];

        $allSettings = array_merge($aboutSettings, $supportSettings, $contentSettings, $socialSettings);

        foreach ($allSettings as $setting) {
            // التحقق من عدم وجود الإعداد مسبقاً
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف الإعدادات المضافة
        $keysToDelete = [
            'about_title', 'about_subtitle', 'about_content', 'about_hero_image', 'about_image',
            'about_cta_text', 'about_cta_link', 'about_features_enabled', 'about_features_title', 'about_features_subtitle',
            'about_stats_enabled', 'about_stat_1_value', 'about_stat_1_label', 'about_stat_2_value', 'about_stat_2_label',
            'about_stat_3_value', 'about_stat_3_label', 'about_stat_4_value', 'about_stat_4_label',
            'contact_email', 'contact_phone', 'contact_address', 'working_hours', 'weekend_hours',
            'privacy_policy_content', 'terms_of_service_content',
            'facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url', 'youtube_url'
        ];

        DB::table('settings')->whereIn('key', $keysToDelete)->delete();
    }
};
