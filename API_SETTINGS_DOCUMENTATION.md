# 📱 API Documentation - Settings Endpoints

## Overview
This document describes the Settings API endpoints for the Multi-Vendor Store mobile application.

**Base URL:** `https://your-domain.com/api/v2/settings`

**Authentication:** Not required (Public endpoints)

---

## 📋 Table of Contents
1. [Get All Settings](#get-all-settings)
2. [Get Single Setting](#get-single-setting)
3. [Get Settings by Group](#get-settings-by-group)
4. [Get Site Information](#get-site-information)
5. [Get Privacy Policy](#get-privacy-policy)
6. [Get Terms of Service](#get-terms-of-service)
7. [Get About Us](#get-about-us)

---

## 1. Get All Settings

Get all public settings grouped by category.

### Endpoint
```
GET /api/v2/settings
```

### Query Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `group` | string | No | Filter by group (general, social, privacy, terms, about, seo, notifications) |

### Request Example
```http
GET /api/v2/settings
GET /api/v2/settings?group=general
```

### Response Success (200)
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

### Response Error (500)
```json
{
    "success": false,
    "message": "حدث خطأ أثناء جلب الإعدادات",
    "error": "Error message"
}
```

---

## 2. Get Single Setting

Get a specific setting by its key.

### Endpoint
```
GET /api/v2/settings/{key}
```

### URL Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `key` | string | Yes | The setting key (e.g., `site_name`, `social_facebook`) |

### Request Example
```http
GET /api/v2/settings/site_name
```

### Response Success (200)
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

### Response Error (404)
```json
{
    "success": false,
    "message": "الإعداد غير موجود أو غير متاح"
}
```

---

## 3. Get Settings by Group

Get all settings for a specific group.

### Endpoint
```
GET /api/v2/settings/group/{group}
```

### URL Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `group` | string | Yes | Group name (general, social, privacy, terms, about, seo, notifications) |

### Request Example
```http
GET /api/v2/settings/group/social
GET /api/v2/settings/group/general
```

### Response Success (200)
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

## 4. Get Site Information

Get general site information including name, logo, contact details, and social media links.

### Endpoint
```
GET /api/v2/settings/site/info
```

### Request Example
```http
GET /api/v2/settings/site/info
```

### Response Success (200)
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

**Note:** For images (logo, favicon), prepend the base URL:
- Full logo URL: `https://your-domain.com/storage/{site_logo}`
- Example: `https://your-domain.com/storage/settings/logo.png`

---

## 5. Get Privacy Policy

Get the privacy policy content.

### Endpoint
```
GET /api/v2/settings/privacy-policy
```

### Request Example
```http
GET /api/v2/settings/privacy-policy
```

### Response Success (200)
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

### Response Error (404)
```json
{
    "success": false,
    "message": "سياسة الخصوصية غير متاحة"
}
```

---

## 6. Get Terms of Service

Get the terms of service content.

### Endpoint
```
GET /api/v2/settings/terms-of-service
```

### Request Example
```http
GET /api/v2/settings/terms-of-service
```

### Response Success (200)
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

### Response Error (404)
```json
{
    "success": false,
    "message": "شروط الاستخدام غير متاحة"
}
```

---

## 7. Get About Us

Get information about the website/application.

### Endpoint
```
GET /api/v2/settings/about-us
```

### Request Example
```http
GET /api/v2/settings/about-us
```

### Response Success (200)
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

## 📱 Mobile Implementation Examples

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
      throw Exception('Failed to load settings');
    }
  }

  Future<Map<String, dynamic>> getSiteInfo() async {
    final response = await http.get(Uri.parse('$baseUrl/site/info'));
    
    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to load site info');
    }
  }
}
```

---

## 🔍 Setting Groups

| Group | Description | Common Keys |
|-------|-------------|-------------|
| `general` | General site settings | `site_name`, `site_logo`, `site_email`, `site_phone`, `site_address`, `site_currency`, `site_language` |
| `social` | Social media links | `social_facebook`, `social_twitter`, `social_instagram`, `social_linkedin`, `social_youtube`, `social_whatsapp` |
| `privacy` | Privacy policy | `privacy_policy`, `privacy_policy_updated_at` |
| `terms` | Terms of service | `terms_of_service`, `terms_of_service_updated_at` |
| `about` | About us information | `about_us`, `about_vision`, `about_mission` |
| `seo` | SEO settings | `seo_meta_title`, `seo_meta_description`, `seo_meta_keywords`, `seo_google_analytics` |
| `notifications` | Notification settings | `email_notifications_enabled`, `sms_notifications_enabled`, `push_notifications_enabled` |

---

## 🔑 Common Setting Keys

### General Settings
- `site_name` - Site name
- `site_logo` - Logo image path
- `site_favicon` - Favicon image path
- `site_email` - Contact email
- `site_phone` - Contact phone
- `site_address` - Physical address
- `site_currency` - Default currency (e.g., SAR)
- `site_language` - Default language (e.g., ar)

### Social Media
- `social_facebook` - Facebook page URL
- `social_twitter` - Twitter profile URL
- `social_instagram` - Instagram profile URL
- `social_linkedin` - LinkedIn company URL
- `social_youtube` - YouTube channel URL
- `social_whatsapp` - WhatsApp number

---

## ⚠️ Error Handling

All endpoints return a consistent error format:

```json
{
    "success": false,
    "message": "Error message in Arabic",
    "error": "Technical error details (optional)"
}
```

### HTTP Status Codes
- `200` - Success
- `404` - Resource not found
- `500` - Server error

---

## 📝 Notes

1. **Image URLs**: For image settings (logo, favicon), prepend the storage path:
   - Full URL: `{BASE_URL}/storage/{image_path}`
   - Example: `https://your-domain.com/storage/settings/logo.png`

2. **Caching**: Consider caching settings data on the mobile app to reduce API calls. Settings usually don't change frequently.

3. **RTL Support**: All text content is in Arabic (RTL). Ensure your app handles RTL layout properly.

4. **Content Format**: Privacy policy and terms of service may contain HTML content. Use appropriate HTML rendering in your mobile app.

---

## 📞 Support

For API support or questions, contact:
- Email: tech-support@example.com
- API Version: v2.0
- Last Updated: November 2025

---

## 🔐 Security Notes

- All settings endpoints are **PUBLIC** (no authentication required)
- Only settings marked as `is_public: true` are returned
- Sensitive settings are not exposed through the API
- Rate limiting may apply (check with backend team)

---

## 📊 Response Times

Expected response times:
- Settings list: < 200ms
- Single setting: < 100ms
- Site info: < 150ms

---

## ✅ Testing

### Test Endpoints
```bash
# Get all settings
curl -X GET "https://your-domain.com/api/v2/settings"

# Get general settings
curl -X GET "https://your-domain.com/api/v2/settings?group=general"

# Get site info
curl -X GET "https://your-domain.com/api/v2/settings/site/info"

# Get privacy policy
curl -X GET "https://your-domain.com/api/v2/settings/privacy-policy"
```

---

**Made with ❤️ for Mobile Developers**

