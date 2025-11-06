<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingController extends Controller
{
    use AuthorizesRequests;

    /**
     * عرض صفحة الإعدادات
     */
    public function index(Request $request)
    {
        $this->authorize('manage-settings');

        $group = $request->get('group', 'general');

        $groups = [
            'general' => [
                'name' => 'الإعدادات العامة',
                'icon' => 'fas fa-cog',
                'description' => 'الإعدادات الأساسية للموقع'
            ],
            'social' => [
                'name' => 'التواصل الاجتماعي',
                'icon' => 'fas fa-share-alt',
                'description' => 'روابط مواقع التواصل الاجتماعي'
            ],
            'privacy' => [
                'name' => 'سياسة الخصوصية',
                'icon' => 'fas fa-shield-alt',
                'description' => 'إدارة سياسة الخصوصية'
            ],
            'terms' => [
                'name' => 'شروط الاستخدام',
                'icon' => 'fas fa-file-contract',
                'description' => 'إدارة شروط الاستخدام'
            ],
            'about' => [
                'name' => 'عن الموقع',
                'icon' => 'fas fa-info-circle',
                'description' => 'معلومات عن الموقع'
            ],
            'seo' => [
                'name' => 'إعدادات SEO',
                'icon' => 'fas fa-search',
                'description' => 'إعدادات محركات البحث'
            ],
            'notifications' => [
                'name' => 'الإشعارات',
                'icon' => 'fas fa-bell',
                'description' => 'إعدادات الإشعارات'
            ],
        ];

        $settings = Setting::where('group', $group)
            ->orderBy('order')
            ->get();

        return view('dashboard.settings.index', compact('group', 'groups', 'settings'));
    }

    /**
     * حفظ الإعدادات
     */
    public function update(Request $request)
    {
        $this->authorize('manage-settings');

        $request->validate([
            'settings' => 'required|array',
            'group' => 'required|in:general,social,privacy,terms,about,seo,notifications',
        ]);

        $settings = $request->input('settings', []);
        $group = $request->input('group');

        // معالجة الصور
        if ($request->hasFile('settings')) {
            foreach ($request->file('settings') as $key => $file) {
                if ($file && $file->isValid()) {
                    $setting = Setting::where('key', $key)->first();
                    if ($setting && $setting->type === 'image') {
                        // حذف الصورة القديمة
                        if ($setting->value) {
                            Storage::disk('public')->delete($setting->value);
                        }
                        // حفظ الصورة الجديدة
                        $settings[$key] = $file->store('settings', 'public');
                    }
                }
            }
        }

        // حفظ الإعدادات
        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()
            ->route('settings.index', ['group' => $group])
            ->with('success', 'تم حفظ الإعدادات بنجاح.');
    }

    /**
     * إعادة تعيين الإعدادات إلى القيم الافتراضية
     */
    public function reset(Request $request)
    {
        $this->authorize('manage-settings');

        $group = $request->input('group', 'general');

        // يمكن إضافة منطق إعادة التعيين هنا إذا لزم الأمر

        return redirect()
            ->route('settings.index', ['group' => $group])
            ->with('success', 'تم إعادة تعيين الإعدادات بنجاح.');
    }

    /**
     * تصدير الإعدادات
     */
    public function export()
    {
        $this->authorize('manage-settings');

        $settings = Setting::all()->map(function ($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
                'group' => $setting->group,
            ];
        });

        return response()->json($settings);
    }

    /**
     * استيراد الإعدادات
     */
    public function import(Request $request)
    {
        $this->authorize('manage-settings');

        $request->validate([
            'settings_file' => 'required|file|mimes:json',
        ]);

        $settings = json_decode(file_get_contents($request->file('settings_file')->getRealPath()), true);

        if ($settings) {
            foreach ($settings as $setting) {
                Setting::set($setting['key'], $setting['value']);
            }
        }

        return redirect()
            ->route('settings.index')
            ->with('success', 'تم استيراد الإعدادات بنجاح.');
    }
}
