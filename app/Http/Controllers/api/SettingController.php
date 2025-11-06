<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * الحصول على جميع الإعدادات العامة (Public)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $group = $request->get('group', null);
            
            if ($group) {
                // الحصول على إعدادات مجموعة محددة
                $settings = Setting::where('group', $group)
                    ->where('is_public', true)
                    ->orderBy('order')
                    ->get(['key', 'value', 'type', 'label', 'description']);
            } else {
                // الحصول على جميع الإعدادات العامة
                $settings = Setting::where('is_public', true)
                    ->orderBy('group')
                    ->orderBy('order')
                    ->get(['key', 'value', 'group', 'type', 'label', 'description']);
            }

            // تجميع الإعدادات حسب المجموعة
            $groupedSettings = $settings->groupBy('group')->map(function ($groupSettings) {
                return $groupSettings->map(function ($setting) {
                    return [
                        'key' => $setting->key,
                        'value' => $setting->value,
                        'type' => $setting->type,
                        'label' => $setting->label,
                        'description' => $setting->description,
                    ];
                });
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب الإعدادات بنجاح',
                'data' => $groupedSettings,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإعدادات',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على إعداد محدد
     * 
     * @param string $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($key)
    {
        try {
            $setting = Setting::where('key', $key)
                ->where('is_public', true)
                ->first(['key', 'value', 'type', 'label', 'description', 'group']);

            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'الإعداد غير موجود أو غير متاح',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم جلب الإعداد بنجاح',
                'data' => [
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'label' => $setting->label,
                    'description' => $setting->description,
                    'group' => $setting->group,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإعداد',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على إعدادات مجموعة محددة
     * 
     * @param string $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroup($group)
    {
        try {
            $settings = Setting::where('group', $group)
                ->where('is_public', true)
                ->orderBy('order')
                ->get(['key', 'value', 'type', 'label', 'description']);

            $formattedSettings = $settings->map(function ($setting) {
                return [
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'label' => $setting->label,
                    'description' => $setting->description,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب إعدادات المجموعة بنجاح',
                'data' => $formattedSettings,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب إعدادات المجموعة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على إعدادات خاصة بالموقع (معلومات عامة)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function siteInfo()
    {
        try {
            $generalSettings = Setting::where('group', 'general')
                ->whereIn('key', ['site_name', 'site_logo', 'site_favicon', 'site_email', 'site_phone', 'site_address', 'site_currency', 'site_language'])
                ->where('is_public', true)
                ->pluck('value', 'key');

            $socialSettings = Setting::where('group', 'social')
                ->where('is_public', true)
                ->pluck('value', 'key');

            return response()->json([
                'success' => true,
                'message' => 'تم جلب معلومات الموقع بنجاح',
                'data' => [
                    'general' => $generalSettings,
                    'social' => $socialSettings,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب معلومات الموقع',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على سياسة الخصوصية
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function privacyPolicy()
    {
        try {
            $privacyPolicy = Setting::where('key', 'privacy_policy')
                ->where('is_public', true)
                ->first(['value', 'updated_at']);

            if (!$privacyPolicy) {
                return response()->json([
                    'success' => false,
                    'message' => 'سياسة الخصوصية غير متاحة',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم جلب سياسة الخصوصية بنجاح',
                'data' => [
                    'content' => $privacyPolicy->value,
                    'updated_at' => $privacyPolicy->updated_at->format('Y-m-d H:i:s'),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب سياسة الخصوصية',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على شروط الاستخدام
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function termsOfService()
    {
        try {
            $terms = Setting::where('key', 'terms_of_service')
                ->where('is_public', true)
                ->first(['value', 'updated_at']);

            if (!$terms) {
                return response()->json([
                    'success' => false,
                    'message' => 'شروط الاستخدام غير متاحة',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم جلب شروط الاستخدام بنجاح',
                'data' => [
                    'content' => $terms->value,
                    'updated_at' => $terms->updated_at->format('Y-m-d H:i:s'),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب شروط الاستخدام',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على معلومات "عن الموقع"
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function aboutUs()
    {
        try {
            $aboutSettings = Setting::where('group', 'about')
                ->where('is_public', true)
                ->pluck('value', 'key');

            return response()->json([
                'success' => true,
                'message' => 'تم جلب معلومات "عن الموقع" بنجاح',
                'data' => $aboutSettings,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب معلومات "عن الموقع"',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
