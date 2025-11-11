<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * عرض الصفحة الرئيسية (التعريفية)
     */
    public function home()
    {
        $settings = Setting::getGroup('about');
        
        return view('pages.home', compact('settings'));
    }

    /**
     * عرض صفحة سياسة الخصوصية
     */
    public function privacy()
    {
        // استخدام privacy_policy_content إذا كان موجوداً، وإلا استخدام privacy_policy
        $privacyPolicy = Setting::get('privacy_policy_content', '');
        if (empty($privacyPolicy)) {
            $privacyPolicy = Setting::get('privacy_policy', '');
        }
        
        $updatedAt = Setting::get('privacy_policy_updated_at', date('Y-m-d'));
        
        return view('pages.privacy', compact('privacyPolicy', 'updatedAt'));
    }

    /**
     * عرض صفحة شروط الاستخدام
     */
    public function terms()
    {
        // استخدام terms_of_service_content إذا كان موجوداً، وإلا استخدام terms_of_service
        $termsContent = Setting::get('terms_of_service_content', '');
        if (empty($termsContent)) {
            $termsContent = Setting::get('terms_of_service', '');
        }
        
        $updatedAt = Setting::get('terms_of_service_updated_at', date('Y-m-d'));
        
        return view('pages.terms', compact('termsContent', 'updatedAt'));
    }

    /**
     * عرض صفحة الدعم والتواصل
     */
    public function support()
    {
        $settings = Setting::getGroup('general');
        $socialSettings = Setting::getGroup('social');
        
        return view('pages.support', compact('settings', 'socialSettings'));
    }

    /**
     * معالجة رسالة الدعم
     */
    public function contact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // هنا يمكنك إضافة منطق إرسال البريد الإلكتروني أو حفظ الرسالة في قاعدة البيانات
        
        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.'
        ]);
    }
}

