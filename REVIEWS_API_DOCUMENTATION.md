# 📝 Product Reviews API Documentation

## نظرة عامة / Overview

API شامل لإدارة تقييمات المنتجات مع نظام موافقة تلقائي يعتمد على عدد المشتريات.
Comprehensive API for managing product reviews with automatic approval system based on purchase count.

---

## 🔐 Authentication

جميع الـ endpoints (عدا جلب التقييمات) تتطلب مصادقة.
All endpoints (except fetching reviews) require authentication.

**Header Required:**
```
Authorization: Bearer {token}
```

---

## 📍 Base URL

```
/api/v2
```

---

## 🎯 Endpoints

### 1. إنشاء تقييم جديد / Create Review

**Endpoint:** `POST /products/reviews`

**Authentication:** ✅ Required

**Request Body:**
```json
{
  "product_id": 1,
  "rate": 5,
  "comment": "منتج رائع جداً!"
}
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `product_id` | integer | ✅ Yes | معرف المنتج |
| `rate` | integer | ✅ Yes | التقييم (1-5) |
| `comment` | string | ❌ No | التعليق (حد أقصى 1000 حرف) |

**Success Response (201):**
```json
{
  "success": true,
  "message": "تم إضافة التقييم بنجاح وتمت الموافقة عليه.",
  "data": {
    "review": {
      "id": 1,
      "user_id": 5,
      "product_id": 1,
      "rate": 5,
      "comment": "منتج رائع جداً!",
      "is_approved": true,
      "created_at": "2024-01-15 10:30:00"
    },
    "purchase_count": 2,
    "required_purchases": 1
  }
}
```

**Note:** إذا لم يكن لديك مشتريات كافية:
```json
{
  "message": "تم إضافة التقييم بنجاح ولكنه في انتظار الموافقة. تحتاج إلى شراء المنتج 2 مرة(مرات) للموافقة عليه."
}
```

**Error Responses:**

**422 - Validation Error:**
```json
{
  "success": false,
  "message": "البيانات غير صحيحة",
  "errors": {
    "product_id": ["The product id field is required."],
    "rate": ["The rate must be between 1 and 5."]
  }
}
```

**401 - Unauthorized:**
```json
{
  "success": false,
  "message": "يجب تسجيل الدخول أولاً"
}
```

---

### 2. جلب تقييمات منتج / Get Product Reviews

**Endpoint:** `GET /data/products/{productId}/reviews`

**Authentication:** ❌ Not Required (Public)

**Query Parameters:**
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `per_page` | integer | ❌ No | 10 | عدد التقييمات في الصفحة |
| `approved_only` | boolean | ❌ No | true | عرض فقط التقييمات الموافق عليها |

**Example Requests:**
```
GET /api/v2/data/products/1/reviews
GET /api/v2/data/products/1/reviews?per_page=20&approved_only=false
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "تم جلب التقييمات بنجاح",
  "data": [
    {
      "id": 1,
      "user": {
        "id": 5,
        "name": "أحمد محمد",
        "username": "ahmed123",
        "avatar": "http://example.com/storage/avatars/user.jpg"
      },
      "rate": 5,
      "comment": "منتج رائع جداً!",
      "is_approved": true,
      "created_at": "2024-01-15 10:30:00"
    },
    {
      "id": 2,
      "user": {
        "id": 8,
        "name": "سارة علي",
        "username": "sara456",
        "avatar": null
      },
      "rate": 4,
      "comment": "جيد جداً لكن السعر مرتفع",
      "is_approved": true,
      "created_at": "2024-01-14 15:20:00"
    }
  ],
  "statistics": {
    "total_reviews": 25,
    "approved_reviews": 20,
    "average_rating": 4.5,
    "rating_distribution": {
      "5": 12,
      "4": 5,
      "3": 2,
      "2": 1,
      "1": 0
    }
  },
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 10,
    "total": 20
  }
}
```

---

### 3. تحديث تقييم / Update Review

**Endpoint:** `PUT /products/reviews/{reviewId}`

**Authentication:** ✅ Required

**Request Body:**
```json
{
  "rate": 4,
  "comment": "تم تحديث التعليق"
}
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `rate` | integer | ❌ No | التقييم (1-5) |
| `comment` | string | ❌ No | التعليق |

**Success Response (200):**
```json
{
  "success": true,
  "message": "تم تحديث التقييم بنجاح وتمت الموافقة عليه.",
  "data": {
    "review": {
      "id": 1,
      "user_id": 5,
      "product_id": 1,
      "rate": 4,
      "comment": "تم تحديث التعليق",
      "is_approved": true,
      "updated_at": "2024-01-15 11:00:00"
    }
  }
}
```

**Error Responses:**

**404 - Not Found:**
```json
{
  "success": false,
  "message": "التقييم غير موجود أو ليس لديك صلاحية للتعديل"
}
```

---

### 4. حذف تقييم / Delete Review

**Endpoint:** `DELETE /products/reviews/{reviewId}`

**Authentication:** ✅ Required

**Success Response (200):**
```json
{
  "success": true,
  "message": "تم حذف التقييم بنجاح"
}
```

**Error Responses:**

**404 - Not Found:**
```json
{
  "success": false,
  "message": "التقييم غير موجود أو ليس لديك صلاحية للحذف"
}
```

---

### 5. جلب تقييماتي / Get My Reviews

**Endpoint:** `GET /products/reviews/my-reviews`

**Authentication:** ✅ Required

**Query Parameters:**
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `per_page` | integer | ❌ No | 10 | عدد التقييمات في الصفحة |

**Success Response (200):**
```json
{
  "success": true,
  "message": "تم جلب تقييماتك بنجاح",
  "data": [
    {
      "id": 1,
      "product": {
        "id": 1,
        "name": "منتج رائع",
        "images": [
          "http://example.com/storage/products/image1.jpg",
          "http://example.com/storage/products/image2.jpg"
        ]
      },
      "rate": 5,
      "comment": "منتج رائع جداً!",
      "is_approved": true,
      "created_at": "2024-01-15 10:30:00"
    },
    {
      "id": 2,
      "product": {
        "id": 3,
        "name": "منتج آخر",
        "images": []
      },
      "rate": 4,
      "comment": null,
      "is_approved": false,
      "created_at": "2024-01-14 15:20:00"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 2
  }
}
```

---

## 🔄 نظام الموافقة التلقائي / Automatic Approval System

### كيف يعمل النظام / How It Works

النظام يتحقق تلقائياً من عدد المشتريات المكتملة للمستخدم قبل الموافقة على التقييم:
The system automatically checks the number of completed purchases before approving reviews:

- **التقييم الأول** يحتاج **مشتر واحد** على الأقل
- **التقييم الثاني** يحتاج **مشترين** على الأقل
- **التقييم الثالث** يحتاج **3 مشتريات** على الأقل
- وهكذا...

**مثال:**
- إذا كان لديك **مشتر واحد** ← يمكنك كتابة **تقييم واحد** فقط (موافق عليه)
- إذا كان لديك **مشترين** ← يمكنك كتابة **تقييمين** (كلاهما موافق عليه)
- إذا كان لديك **3 مشتريات** ← يمكنك كتابة **3 تقييمات** (كلها موافق عليها)

**ملاحظة:** يمكنك كتابة تقييمات أكثر من عدد المشتريات، لكنها لن تكون موافق عليها حتى تشتري أكثر.

---

## 📱 Examples for Mobile Developers

### Swift (iOS)

```swift
import Foundation

// إنشاء تقييم جديد
func createReview(productId: Int, rate: Int, comment: String?) {
    let url = URL(string: "https://example.com/api/v2/products/reviews")!
    var request = URLRequest(url: url)
    request.httpMethod = "POST"
    request.setValue("Bearer \(authToken)", forHTTPHeaderField: "Authorization")
    request.setValue("application/json", forHTTPHeaderField: "Content-Type")
    
    var body: [String: Any] = [
        "product_id": productId,
        "rate": rate
    ]
    if let comment = comment {
        body["comment"] = comment
    }
    
    request.httpBody = try? JSONSerialization.data(withJSONObject: body)
    
    URLSession.shared.dataTask(with: request) { data, response, error in
        guard let data = data else { return }
        let result = try? JSONDecoder().decode(ReviewResponse.self, from: data)
        // Handle response
    }.resume()
}

// جلب تقييمات منتج
func getProductReviews(productId: Int, page: Int = 1) {
    let url = URL(string: "https://example.com/api/v2/data/products/\(productId)/reviews?page=\(page)")!
    var request = URLRequest(url: url)
    request.httpMethod = "GET"
    
    URLSession.shared.dataTask(with: request) { data, response, error in
        guard let data = data else { return }
        let result = try? JSONDecoder().decode(ProductReviewsResponse.self, from: data)
        // Handle response
    }.resume()
}
```

### Kotlin (Android)

```kotlin
import retrofit2.Call
import retrofit2.http.*

interface ReviewService {
    @POST("products/reviews")
    fun createReview(
        @Header("Authorization") token: String,
        @Body review: CreateReviewRequest
    ): Call<ReviewResponse>
    
    @GET("data/products/{productId}/reviews")
    fun getProductReviews(
        @Path("productId") productId: Int,
        @Query("per_page") perPage: Int = 10,
        @Query("approved_only") approvedOnly: Boolean = true
    ): Call<ProductReviewsResponse>
    
    @PUT("products/reviews/{reviewId}")
    fun updateReview(
        @Header("Authorization") token: String,
        @Path("reviewId") reviewId: Int,
        @Body review: UpdateReviewRequest
    ): Call<ReviewResponse>
    
    @DELETE("products/reviews/{reviewId}")
    fun deleteReview(
        @Header("Authorization") token: String,
        @Path("reviewId") reviewId: Int
    ): Call<DeleteResponse>
    
    @GET("products/reviews/my-reviews")
    fun getMyReviews(
        @Header("Authorization") token: String,
        @Query("per_page") perPage: Int = 10
    ): Call<MyReviewsResponse>
}

// Data Classes
data class CreateReviewRequest(
    val product_id: Int,
    val rate: Int,
    val comment: String?
)

data class UpdateReviewRequest(
    val rate: Int? = null,
    val comment: String? = null
)
```

### Dart (Flutter)

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ReviewService {
  final String baseUrl = 'https://example.com/api/v2';
  final String? authToken;
  
  ReviewService(this.authToken);
  
  // إنشاء تقييم جديد
  Future<Map<String, dynamic>> createReview({
    required int productId,
    required int rate,
    String? comment,
  }) async {
    final url = Uri.parse('$baseUrl/products/reviews');
    final response = await http.post(
      url,
      headers: {
        'Authorization': 'Bearer $authToken',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({
        'product_id': productId,
        'rate': rate,
        if (comment != null) 'comment': comment,
      }),
    );
    
    return jsonDecode(response.body);
  }
  
  // جلب تقييمات منتج
  Future<Map<String, dynamic>> getProductReviews({
    required int productId,
    int perPage = 10,
    bool approvedOnly = true,
  }) async {
    final url = Uri.parse(
      '$baseUrl/data/products/$productId/reviews?per_page=$perPage&approved_only=$approvedOnly'
    );
    final response = await http.get(url);
    
    return jsonDecode(response.body);
  }
  
  // تحديث تقييم
  Future<Map<String, dynamic>> updateReview({
    required int reviewId,
    int? rate,
    String? comment,
  }) async {
    final url = Uri.parse('$baseUrl/products/reviews/$reviewId');
    final response = await http.put(
      url,
      headers: {
        'Authorization': 'Bearer $authToken',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({
        if (rate != null) 'rate': rate,
        if (comment != null) 'comment': comment,
      }),
    );
    
    return jsonDecode(response.body);
  }
  
  // حذف تقييم
  Future<Map<String, dynamic>> deleteReview(int reviewId) async {
    final url = Uri.parse('$baseUrl/products/reviews/$reviewId');
    final response = await http.delete(
      url,
      headers: {
        'Authorization': 'Bearer $authToken',
      },
    );
    
    return jsonDecode(response.body);
  }
  
  // جلب تقييماتي
  Future<Map<String, dynamic>> getMyReviews({int perPage = 10}) async {
    final url = Uri.parse(
      '$baseUrl/products/reviews/my-reviews?per_page=$perPage'
    );
    final response = await http.get(
      url,
      headers: {
        'Authorization': 'Bearer $authToken',
      },
    );
    
    return jsonDecode(response.body);
  }
}
```

---

## 📊 Response Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created Successfully |
| 400 | Bad Request |
| 401 | Unauthorized |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## 🔍 Testing Examples

### cURL Examples

**إنشاء تقييم:**
```bash
curl -X POST https://example.com/api/v2/products/reviews \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_id": 1,
    "rate": 5,
    "comment": "منتج رائع!"
  }'
```

**جلب تقييمات منتج:**
```bash
curl -X GET "https://example.com/api/v2/data/products/1/reviews?per_page=20"
```

**تحديث تقييم:**
```bash
curl -X PUT https://example.com/api/v2/products/reviews/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "rate": 4,
    "comment": "تم التحديث"
  }'
```

**حذف تقييم:**
```bash
curl -X DELETE https://example.com/api/v2/products/reviews/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## ⚠️ Important Notes

1. **Rate Limit:** يجب أن يكون التقييم بين 1 و 5 فقط
2. **Comment Length:** الحد الأقصى لطول التعليق هو 1000 حرف
3. **Approval System:** النظام يتحقق تلقائياً من عدد المشتريات قبل الموافقة
4. **Multiple Reviews:** يمكن للمستخدم كتابة أكثر من تقييم لنفس المنتج
5. **Ownership:** المستخدم يستطيع تعديل/حذف تقييماته فقط

---

## 📞 Support

إذا واجهت أي مشاكل أو لديك استفسارات، يرجى التواصل مع فريق التطوير.
If you encounter any issues or have questions, please contact the development team.

