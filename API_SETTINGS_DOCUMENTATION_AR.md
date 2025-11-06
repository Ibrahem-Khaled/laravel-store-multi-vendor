# 📱 توثيق API - إعدادات الموقع

## نظرة عامة
هذا التوثيق يشرح واجهات برمجة التطبيقات (API) الخاصة بإعدادات الموقع لتطبيق متجر متعدد البائعين.

**الرابط الأساسي:** `https://your-domain.com/api/v2/settings`

**المصادقة:** غير مطلوبة (Endpoints عامة)

---

## 📋 الفهرس
1. [جلب جميع الإعدادات](#1-جلب-جميع-الإعدادات)
2. [جلب إعداد واحد](#2-جلب-إعداد-واحد)
3. [جلب إعدادات مجموعة محددة](#3-جلب-إعدادات-مجموعة-محددة)
4. [جلب معلومات الموقع](#4-جلب-معلومات-الموقع)
5. [جلب سياسة الخصوصية](#5-جلب-سياسة-الخصوصية)
6. [جلب شروط الاستخدام](#6-جلب-شروط-الاستخدام)
7. [جلب معلومات عن الموقع](#7-جلب-معلومات-عن-الموقع)

---

## 1. جلب جميع الإعدادات

جلب جميع الإعدادات العامة مصنفة حسب المجموعات.

### Endpoint
```
GET /api/v2/settings
```

### معاملات البحث (Query Parameters)
| المعامل | النوع | مطلوب | الوصف |
|---------|-------|-------|--------|
| `group` | string | لا | تصفية حسب المجموعة (general, social, privacy, terms, about, seo, notifications) |

### مثال على الطلب
```http
GET /api/v2/settings
GET /api/v2/settings?group=general
```

### الاستجابة الناجحة (200)
```json
{
    "success": true,
    "message": "تم جلب الإعدادات بنجاح",
    "data": {
        "general": [
            {
                "key": "site_name",
                "value": "متجر متعدد البائعين",
                "type": "text",
                "label": "اسم الموقع",
                "description": "اسم الموقع الذي سيظهر في جميع الصفحات"
            },
            {
                "key": "site_email",
                "value": "info@example.com",
                "type": "email",
                "label": "البريد الإلكتروني",
                "description": "البريد الإلكتروني الرسمي للموقع"
            }
        ],
        "social": [
            {
                "key": "social_facebook",
                "value": "https://facebook.com/example",
                "type": "url",
                "label": "فيسبوك",
                "description": "رابط صفحة الفيسبوك"
            }
        ]
    }
}
```

### الاستجابة في حالة الخطأ (500)
```json
{
    "success": false,
    "message": "حدث خطأ أثناء جلب الإعدادات",
    "error": "تفاصيل الخطأ"
}
```

---

## 2. جلب إعداد واحد

جلب إعداد محدد باستخدام المفتاح (key).

### Endpoint
```
GET /api/v2/settings/{key}
```

### معاملات الرابط (URL Parameters)
| المعامل | النوع | مطلوب | الوصف |
|---------|-------|-------|--------|
| `key` | string | نعم | مفتاح الإعداد (مثل: `site_name`, `social_facebook`) |

### مثال على الطلب
```http
GET /api/v2/settings/site_name
```

### الاستجابة الناجحة (200)
```json
{
    "success": true,
    "message": "تم جلب الإعداد بنجاح",
    "data": {
        "key": "site_name",
        "value": "متجر متعدد البائعين",
        "type": "text",
        "label": "اسم الموقع",
        "description": "اسم الموقع الذي سيظهر في جميع الصفحات",
        "group": "general"
    }
}
```

### الاستجابة في حالة عدم الوجود (404)
```json
{
    "success": false,
    "message": "الإعداد غير موجود أو غير متاح"
}
```

---

## 3. جلب إعدادات مجموعة محددة

جلب جميع إعدادات مجموعة معينة.

### Endpoint
```
GET /api/v2/settings/group/{group}
```

### معاملات الرابط (URL Parameters)
| المعامل | النوع | مطلوب | الوصف |
|---------|-------|-------|--------|
| `group` | string | نعم | اسم المجموعة (general, social, privacy, terms, about, seo, notifications) |

### مثال على الطلب
```http
GET /api/v2/settings/group/social
GET /api/v2/settings/group/general
```

### الاستجابة الناجحة (200)
```json
{
    "success": true,
    "message": "تم جلب إعدادات المجموعة بنجاح",
    "data": [
        {
            "key": "social_facebook",
            "value": "https://facebook.com/example",
            "type": "url",
            "label": "فيسبوك",
            "description": "رابط صفحة الفيسبوك"
        },
        {
            "key": "social_twitter",
            "value": "https://twitter.com/example",
            "type": "url",
            "label": "تويتر",
            "description": "رابط حساب تويتر"
        }
    ]
}
```

---

## 4. جلب معلومات الموقع

جلب معلومات عامة عن الموقع بما في ذلك الاسم، الشعار، معلومات الاتصال، وروابط التواصل الاجتماعي.

### Endpoint
```
GET /api/v2/settings/site/info
```

### مثال على الطلب
```http
GET /api/v2/settings/site/info
```

### الاستجابة الناجحة (200)
```json
{
    "success": true,
    "message": "تم جلب معلومات الموقع بنجاح",
    "data": {
        "general": {
            "site_name": "متجر متعدد البائعين",
            "site_logo": "settings/logo.png",
            "site_favicon": "settings/favicon.png",
            "site_email": "info@example.com",
            "site_phone": "+966500000000",
            "site_address": "الرياض، المملكة العربية السعودية",
            "site_currency": "SAR",
            "site_language": "ar"
        },
        "social": {
            "social_facebook": "https://facebook.com/example",
            "social_twitter": "https://twitter.com/example",
            "social_instagram": "https://instagram.com/example",
            "social_linkedin": "https://linkedin.com/company/example",
            "social_youtube": "https://youtube.com/example",
            "social_whatsapp": "+966500000000"
        }
    }
}
```

**ملاحظة:** للصور (الشعار، الأيقونة)، قم بإضافة الرابط الأساسي:
- رابط الشعار الكامل: `https://your-domain.com/storage/{site_logo}`
- مثال: `https://your-domain.com/storage/settings/logo.png`

---

## 5. جلب سياسة الخصوصية

جلب محتوى سياسة الخصوصية.

### Endpoint
```
GET /api/v2/settings/privacy-policy
```

### مثال على الطلب
```http
GET /api/v2/settings/privacy-policy
```

### الاستجابة الناجحة (200)
```json
{
    "success": true,
    "message": "تم جلب سياسة الخصوصية بنجاح",
    "data": {
        "content": "سياسة الخصوصية الكاملة هنا...",
        "updated_at": "2025-11-06 12:00:00"
    }
}
```

### الاستجابة في حالة عدم الوجود (404)
```json
{
    "success": false,
    "message": "سياسة الخصوصية غير متاحة"
}
```

---

## 6. جلب شروط الاستخدام

جلب محتوى شروط الاستخدام.

### Endpoint
```
GET /api/v2/settings/terms-of-service
```

### مثال على الطلب
```http
GET /api/v2/settings/terms-of-service
```

### الاستجابة الناجحة (200)
```json
{
    "success": true,
    "message": "تم جلب شروط الاستخدام بنجاح",
    "data": {
        "content": "شروط الاستخدام الكاملة هنا...",
        "updated_at": "2025-11-06 12:00:00"
    }
}
```

### الاستجابة في حالة عدم الوجود (404)
```json
{
    "success": false,
    "message": "شروط الاستخدام غير متاحة"
}
```

---

## 7. جلب معلومات عن الموقع

جلب معلومات عن الموقع والتطبيق.

### Endpoint
```
GET /api/v2/settings/about-us
```

### مثال على الطلب
```http
GET /api/v2/settings/about-us
```

### الاستجابة الناجحة (200)
```json
{
    "success": true,
    "message": "تم جلب معلومات \"عن الموقع\" بنجاح",
    "data": {
        "about_us": "نحن متجر متعدد البائعين يوفر أفضل المنتجات والخدمات",
        "about_vision": "رؤية الموقع",
        "about_mission": "رسالة الموقع"
    }
}
```

---

## 📱 أمثلة التطبيق

### Swift (iOS)
```swift
import Foundation

struct Setting: Codable {
    let key: String
    let value: String?
    let type: String
    let label: String?
    let description: String?
}

struct SettingsResponse: Codable {
    let success: Bool
    let message: String
    let data: [String: [Setting]]
}

func fetchSettings(group: String? = nil) async throws -> SettingsResponse {
    var urlString = "https://your-domain.com/api/v2/settings"
    if let group = group {
        urlString += "?group=\(group)"
    }
    
    guard let url = URL(string: urlString) else {
        throw URLError(.badURL)
    }
    
    let (data, _) = try await URLSession.shared.data(from: url)
    return try JSONDecoder().decode(SettingsResponse.self, from: data)
}

// استخدام الدالة
Task {
    do {
        let settings = try await fetchSettings(group: "general")
        print("اسم الموقع: \(settings.data["general"]?.first(where: { $0.key == "site_name" })?.value ?? "غير محدد")")
    } catch {
        print("خطأ: \(error)")
    }
}
```

### Kotlin (Android)
```kotlin
import retrofit2.http.GET
import retrofit2.http.Query
import retrofit2.http.Path

interface SettingsApi {
    @GET("settings")
    suspend fun getSettings(
        @Query("group") group: String? = null
    ): Response<SettingsResponse>
    
    @GET("settings/{key}")
    suspend fun getSetting(
        @Path("key") key: String
    ): Response<SettingResponse>
    
    @GET("settings/site/info")
    suspend fun getSiteInfo(): Response<SiteInfoResponse>
    
    @GET("settings/privacy-policy")
    suspend fun getPrivacyPolicy(): Response<PrivacyPolicyResponse>
    
    @GET("settings/terms-of-service")
    suspend fun getTermsOfService(): Response<TermsResponse>
    
    @GET("settings/about-us")
    suspend fun getAboutUs(): Response<AboutUsResponse>
}

data class Setting(
    val key: String,
    val value: String?,
    val type: String,
    val label: String?,
    val description: String?
)

data class SettingsResponse(
    val success: Boolean,
    val message: String,
    val data: Map<String, List<Setting>>
)

// استخدام في Activity/Fragment
lifecycleScope.launch {
    try {
        val response = settingsApi.getSiteInfo()
        if (response.isSuccessful) {
            val siteInfo = response.body()?.data
            val siteName = siteInfo?.general?.get("site_name")
            val logoUrl = siteInfo?.general?.get("site_logo")
            // استخدام البيانات...
        }
    } catch (e: Exception) {
        // معالجة الخطأ
    }
}
```

### Dart (Flutter)
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class Setting {
  final String key;
  final String? value;
  final String type;
  final String? label;
  final String? description;

  Setting({
    required this.key,
    this.value,
    required this.type,
    this.label,
    this.description,
  });

  factory Setting.fromJson(Map<String, dynamic> json) {
    return Setting(
      key: json['key'],
      value: json['value'],
      type: json['type'],
      label: json['label'],
      description: json['description'],
    );
  }
}

class SettingsService {
  static const String baseUrl = 'https://your-domain.com/api/v2/settings';

  Future<Map<String, dynamic>> getSettings({String? group}) async {
    String url = baseUrl;
    if (group != null) {
      url += '?group=$group';
    }

    final response = await http.get(Uri.parse(url));
    
    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('فشل في جلب الإعدادات');
    }
  }

  Future<Map<String, dynamic>> getSiteInfo() async {
    final response = await http.get(Uri.parse('$baseUrl/site/info'));
    
    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('فشل في جلب معلومات الموقع');
    }
  }

  Future<String> getPrivacyPolicy() async {
    final response = await http.get(Uri.parse('$baseUrl/privacy-policy'));
    
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['data']['content'];
    } else {
      throw Exception('فشل في جلب سياسة الخصوصية');
    }
  }
}

// استخدام في Widget
class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  final _settingsService = SettingsService();
  String? siteName;

  @override
  void initState() {
    super.initState();
    _loadSiteInfo();
  }

  Future<void> _loadSiteInfo() async {
    try {
      final data = await _settingsService.getSiteInfo();
      setState(() {
        siteName = data['data']['general']['site_name'];
      });
    } catch (e) {
      print('خطأ: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(siteName ?? 'الموقع')),
      body: Center(child: Text('مرحباً بك في $siteName')),
    );
  }
}
```

---

## 🔍 مجموعات الإعدادات

| المجموعة | الوصف | المفاتيح الشائعة |
|----------|-------|-------------------|
| `general` | الإعدادات العامة | `site_name`, `site_logo`, `site_email`, `site_phone`, `site_address`, `site_currency`, `site_language` |
| `social` | روابط التواصل الاجتماعي | `social_facebook`, `social_twitter`, `social_instagram`, `social_linkedin`, `social_youtube`, `social_whatsapp` |
| `privacy` | سياسة الخصوصية | `privacy_policy`, `privacy_policy_updated_at` |
| `terms` | شروط الاستخدام | `terms_of_service`, `terms_of_service_updated_at` |
| `about` | معلومات عن الموقع | `about_us`, `about_vision`, `about_mission` |
| `seo` | إعدادات SEO | `seo_meta_title`, `seo_meta_description`, `seo_meta_keywords`, `seo_google_analytics` |
| `notifications` | إعدادات الإشعارات | `email_notifications_enabled`, `sms_notifications_enabled`, `push_notifications_enabled` |

---

## 🔑 المفاتيح الشائعة

### الإعدادات العامة
- `site_name` - اسم الموقع
- `site_logo` - مسار صورة الشعار
- `site_favicon` - مسار أيقونة الموقع
- `site_email` - البريد الإلكتروني للاتصال
- `site_phone` - رقم الهاتف للاتصال
- `site_address` - العنوان الفعلي
- `site_currency` - العملة الافتراضية (مثل: SAR)
- `site_language` - اللغة الافتراضية (مثل: ar)

### التواصل الاجتماعي
- `social_facebook` - رابط صفحة الفيسبوك
- `social_twitter` - رابط حساب تويتر
- `social_instagram` - رابط حساب إنستغرام
- `social_linkedin` - رابط صفحة لينكد إن
- `social_youtube` - رابط قناة يوتيوب
- `social_whatsapp` - رقم واتساب

---

## ⚠️ معالجة الأخطاء

جميع الـ endpoints ترجع نفس تنسيق الخطأ:

```json
{
    "success": false,
    "message": "رسالة الخطأ بالعربية",
    "error": "تفاصيل الخطأ التقنية (اختياري)"
}
```

### رموز الحالة HTTP
- `200` - نجاح
- `404` - المورد غير موجود
- `500` - خطأ في الخادم

---

## 📝 ملاحظات مهمة

1. **روابط الصور**: لإعدادات الصور (الشعار، الأيقونة)، أضف مسار التخزين:
   - الرابط الكامل: `{BASE_URL}/storage/{image_path}`
   - مثال: `https://your-domain.com/storage/settings/logo.png`

2. **التخزين المؤقت**: يُنصح بتخزين بيانات الإعدادات مؤقتاً في التطبيق لتقليل عدد طلبات API. الإعدادات عادة لا تتغير بكثرة.

3. **دعم RTL**: جميع النصوص بالعربية (RTL). تأكد من دعم التطبيق للتخطيط من اليمين لليسار.

4. **تنسيق المحتوى**: سياسة الخصوصية وشروط الاستخدام قد تحتوي على محتوى HTML. استخدم عرض HTML مناسب في التطبيق.

---

## 📞 الدعم الفني

للحصول على دعم API أو الأسئلة:
- البريد الإلكتروني: tech-support@example.com
- إصدار API: v2.0
- آخر تحديث: نوفمبر 2025

---

## 🔐 ملاحظات الأمان

- جميع واجهات الإعدادات **عامة** (لا تحتاج مصادقة)
- فقط الإعدادات المعلّمة بـ `is_public: true` يتم إرجاعها
- الإعدادات الحساسة غير معرّضة عبر API
- قد يتم تطبيق حد للطلبات (Rate Limiting) - تحقق من فريق الـ Backend

---

## 📊 أوقات الاستجابة المتوقعة

- قائمة الإعدادات: < 200ms
- إعداد واحد: < 100ms
- معلومات الموقع: < 150ms

---

## ✅ اختبار الـ Endpoints

### أوامر الاختبار
```bash
# جلب جميع الإعدادات
curl -X GET "https://your-domain.com/api/v2/settings"

# جلب الإعدادات العامة
curl -X GET "https://your-domain.com/api/v2/settings?group=general"

# جلب معلومات الموقع
curl -X GET "https://your-domain.com/api/v2/settings/site/info"

# جلب سياسة الخصوصية
curl -X GET "https://your-domain.com/api/v2/settings/privacy-policy"
```

---

## 🎯 حالات الاستخدام الشائعة

### 1. عرض معلومات الموقع في شاشة "حول التطبيق"
```swift
// Swift
let siteInfo = try await fetchSiteInfo()
let siteName = siteInfo.data.general["site_name"]
let aboutUs = try await fetchAboutUs()
```

### 2. عرض روابط التواصل الاجتماعي في Footer
```kotlin
// Kotlin
val socialSettings = settingsApi.getGroup("social").execute().body()?.data
val facebookUrl = socialSettings?.find { it.key == "social_facebook" }?.value
```

### 3. عرض سياسة الخصوصية عند التسجيل
```dart
// Flutter
final privacyPolicy = await settingsService.getPrivacyPolicy();
showDialog(
  context: context,
  builder: (context) => PrivacyPolicyDialog(content: privacyPolicy),
);
```

---

**صُنع بـ ❤️ للمطورين**

