# Multi-Vendor Store API - دليل شامل للذكاء الاصطناعي

## 📋 نظرة عامة
هذا دليل شامل لـ API الخاص بنظام Multi-Vendor Store، مصمم خصيصاً للذكاء الاصطناعي لفهم كيفية عمل النظام والتفاعل معه.

---

## 🏗️ هيكل النظام

### نظام الإصدارات
- **API v1 (Legacy)**: `/api/v1/` أو `/api/` - محافظ عليه للتوافق مع الإصدارات السابقة
- **API v2 (Current)**: `/api/v2/` - الإصدار الحالي الموصى به للتطبيقات الجديدة

### الملفات الرئيسية
- `routes/api/v1.php` - المسارات القديمة (Legacy)
- `routes/api/v2.php` - المسارات الجديدة المنظمة
- `routes/api.php` - ملف الإصدارات الرئيسي

---

## 🔐 نظام المصادقة

### تسجيل الدخول المرن
**المسار**: `POST /api/v2/auth/login`

**المعاملات**:
- `login_field` (string): البريد الإلكتروني أو رقم الهاتف
- `password` (string): كلمة المرور (6 أحرف على الأقل)

**أمثلة**:
```json
// تسجيل الدخول بالبريد الإلكتروني
{
    "login_field": "user@example.com",
    "password": "password123"
}

// تسجيل الدخول برقم الهاتف
{
    "login_field": "+1234567890",
    "password": "password123"
}
```

**الاستجابة الناجحة (200)**:
```json
{
    "status": "success",
    "message": "تم تسجيل الدخول بنجاح",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
        "id": 1,
        "name": "اسم المستخدم",
        "email": "user@example.com",
        "phone": "+1234567890",
        "role": "user",
        "status": "active"
    }
}
```

### التسجيل مع رسائل خطأ محسنة
**المسار**: `POST /api/v2/auth/register`

**المعاملات**:
- `name` (string, مطلوب): اسم المستخدم
- `phone` (string, مطلوب): رقم الهاتف
- `email` (string, اختياري): البريد الإلكتروني
- `password` (string, مطلوب): كلمة المرور (6 أحرف على الأقل)
- `gender` (string, اختياري): `male` أو `female`
- `role` (string, اختياري): `user` أو `trader`

**الاستجابة الناجحة (201)**:
```json
{
    "status": "success",
    "message": "تم إنشاء الحساب بنجاح",
    "user": {
        "id": 1,
        "name": "أحمد محمد",
        "email": "ahmed@example.com",
        "phone": "+1234567890",
        "gender": "male",
        "role": "user",
        "status": "active",
        "created_at": "2024-12-13T21:30:00.000000Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

**رسائل الخطأ المحسنة (422)**:
```json
{
    "status": "error",
    "message": "فشل في إنشاء الحساب",
    "errors": {
        "name": ["الاسم مطلوب"],
        "phone": ["رقم الهاتف مطلوب"],
        "password": ["كلمة المرور يجب أن تكون 6 أحرف على الأقل"]
    },
    "error_summary": [
        "مشكلة في الاسم: الاسم مطلوب",
        "مشكلة في رقم الهاتف: رقم الهاتف مطلوب",
        "مشكلة في كلمة المرور: كلمة المرور يجب أن تكون 6 أحرف على الأقل"
    ]
}
```

---

## ⭐ نظام نقاط الولاء (Loyalty Points System)

### نظرة عامة
نظام نقاط الولاء يسمح للمستخدمين بكسب واستخدام النقاط في الطلبات. النقاط تُقسم بين المنصة والعميل بنسبة 70% للمنصة و30% للعميل.

### إدارة نقاط الولاء للمستخدمين
**المسار الأساسي**: `/api/v2/loyalty/`

#### الحصول على نقاط الولاء
**المسار**: `GET /api/v2/loyalty/points`

**Headers المطلوبة**:
```
Authorization: Bearer {jwt_token}
Accept: application/json
```

**الاستجابة الناجحة (200)**:
```json
{
    "status": "success",
    "data": {
        "user_id": 1,
        "total_points": 1500,
        "used_points": 300,
        "expired_points": 50,
        "available_points": 1150,
        "platform_contribution": 1050.00,
        "customer_contribution": 450.00,
        "last_earned_at": "2024-12-13T15:30:00.000000Z",
        "last_used_at": "2024-12-10T10:15:00.000000Z"
    }
}
```

#### الحصول على تاريخ المعاملات
**المسار**: `GET /api/v2/loyalty/transactions`

**المعاملات الاختيارية**:
- `type` (string): نوع المعاملة (`earned`, `used`, `expired`, `refunded`)
- `page` (integer): رقم الصفحة (افتراضي: 1)
- `per_page` (integer): عدد العناصر لكل صفحة (افتراضي: 20)

**الاستجابة الناجحة (200)**:
```json
{
    "status": "success",
    "data": {
        "transactions": [
            {
                "id": 1,
                "type": "earned",
                "points": 100,
                "amount": 1.00,
                "source": "order",
                "description": "كسب 100 نقطة من الطلب رقم #12345",
                "order_id": 12345,
                "expires_at": "2025-12-13T15:30:00.000000Z",
                "created_at": "2024-12-13T15:30:00.000000Z",
                "metadata": {
                    "order_number": "#12345",
                    "platform_contribution": 0.70,
                    "customer_contribution": 0.30
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 20,
            "total": 25,
            "last_page": 2
        }
    }
}
```

#### استخدام النقاط في الطلب
**المسار**: `POST /api/v2/loyalty/use`

**المعاملات**:
- `points` (integer, مطلوب): عدد النقاط المراد استخدامها
- `order_id` (integer, مطلوب): رقم الطلب

**مثال الطلب**:
```json
{
    "points": 50,
    "order_id": 12345
}
```

**الاستجابة الناجحة (200)**:
```json
{
    "status": "success",
    "message": "تم استخدام النقاط بنجاح",
    "data": {
        "points_used": 50,
        "points_value": 0.50,
        "remaining_points": 1100,
        "order_total": 99.50
    }
}
```

**رسائل الخطأ**:
```json
// نقاط غير كافية (400)
{
    "status": "error",
    "message": "النقاط المتاحة غير كافية",
    "available_points": 30
}

// طلب غير موجود (404)
{
    "status": "error",
    "message": "الطلب غير موجود أو لا يخصك"
}

// طلب غير صالح للاستخدام (400)
{
    "status": "error",
    "message": "لا يمكن استخدام النقاط في هذا الطلب"
}
```

### إدارة نقاط الولاء للمشرفين
**المسار الأساسي**: `/api/v2/loyalty/`

#### إضافة نقاط يدوياً (للمشرفين فقط)
**المسار**: `POST /api/v2/loyalty/add`

**المعاملات**:
- `user_id` (integer, مطلوب): معرف المستخدم
- `points` (integer, مطلوب): عدد النقاط المراد إضافتها
- `platform_contribution` (decimal, مطلوب): مساهمة المنصة بالريال
- `customer_contribution` (decimal, مطلوب): مساهمة العميل بالريال
- `description` (string, مطلوب): وصف المعاملة

**مثال الطلب**:
```json
{
    "user_id": 1,
    "points": 200,
    "platform_contribution": 140.00,
    "customer_contribution": 60.00,
    "description": "مكافأة العميل المميز"
}
```

**الاستجابة الناجحة (200)**:
```json
{
    "status": "success",
    "message": "تم إضافة النقاط بنجاح",
    "data": {
        "transaction_id": 123,
        "points_added": 200,
        "total_points": 1700,
        "available_points": 1350
    }
}
```

### حساب النقاط التلقائي
النقاط تُحسب تلقائياً عند إتمام الطلبات:

#### قاعدة الحساب
- **1 نقطة = 1 ريال** من قيمة الطلب
- **قيمة النقاط**: كل نقطة = 0.01 ريال عند الاستخدام
- **توزيع المساهمة**: 70% منصة، 30% عميل
- **انتهاء الصلاحية**: سنة واحدة من تاريخ الكسب

#### مثال على الحساب
```
طلب بقيمة 100 ريال:
├── النقاط المكتسبة: 100 نقطة
├── مساهمة المنصة: 70 ريال
├── مساهمة العميل: 30 ريال
└── انتهاء الصلاحية: بعد سنة من الإتمام
```

### أنواع المعاملات
- **`earned`**: نقاط مكتسبة من الطلبات أو الإضافة اليدوية
- **`used`**: نقاط مستخدمة في الطلبات
- **`expired`**: نقاط منتهية الصلاحية
- **`refunded`**: نقاط مستردة

### مصادر المعاملات
- **`order`**: من الطلبات المكتملة
- **`manual`**: إضافة يدوية من المشرفين
- **`refund`**: استرداد عند إلغاء الطلب
- **`expiry`**: انتهاء صلاحية تلقائي

### أمثلة على الاستخدام

#### JavaScript/Fetch
```javascript
// الحصول على نقاط الولاء
const getLoyaltyPoints = async () => {
    const response = await fetchWithAuth('/api/v2/loyalty/points');
    const data = await response.json();
    return data.data;
};

// استخدام النقاط
const useLoyaltyPoints = async (points, orderId) => {
    const response = await fetchWithAuth('/api/v2/loyalty/use', {
        method: 'POST',
        body: JSON.stringify({
            points: points,
            order_id: orderId
        })
    });
    return response.json();
};

// الحصول على تاريخ المعاملات
const getLoyaltyTransactions = async (type = null) => {
    const url = type ? `/api/v2/loyalty/transactions?type=${type}` : '/api/v2/loyalty/transactions';
    const response = await fetchWithAuth(url);
    return response.json();
};
```

#### cURL
```bash
# الحصول على النقاط
curl -X GET "http://your-domain.com/api/v2/loyalty/points" \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Accept: application/json"

# استخدام النقاط
curl -X POST "http://your-domain.com/api/v2/loyalty/use" \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Content-Type: application/json" \
  -d '{
    "points": 50,
    "order_id": 12345
  }'

# الحصول على المعاملات
curl -X GET "http://your-domain.com/api/v2/loyalty/transactions?type=earned" \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Accept: application/json"
```

### الأمان والصلاحيات
- **المستخدمون**: يمكنهم عرض واستخدام نقاطهم فقط
- **المشرفون**: يمكنهم إضافة النقاط لأي مستخدم
- **التحقق**: جميع المسارات محمية بـ JWT authentication
- **التحقق من الملكية**: المستخدمون يمكنهم استخدام نقاطهم فقط

### استكشاف الأخطاء

#### المشاكل الشائعة:
1. **خطأ 400 - نقاط غير كافية**:
   - تحقق من النقاط المتاحة للمستخدم
   - تأكد من أن النقاط لم تنته صلاحيتها

2. **خطأ 404 - طلب غير موجود**:
   - تأكد من أن الطلب يخص المستخدم
   - تحقق من حالة الطلب (يجب أن يكون pending)

3. **خطأ 403 - غير مصرح**:
   - تأكد من أن المستخدم نشط
   - تحقق من صلاحيات المشرف لإضافة النقاط

4. **خطأ 422 - بيانات غير صحيحة**:
   - تحقق من صحة البيانات المرسلة
   - تأكد من إرسال جميع الحقول المطلوبة

---

## 🛒 نظام التسوق والطلبات

### إدارة السلة
**المسارات**:
- `GET /api/v2/cart/` - عرض السلة
- `POST /api/v2/cart/` - إضافة منتج للسلة
- `DELETE /api/v2/cart/items/{product_id}` - حذف منتج من السلة
- `DELETE /api/v2/cart/` - حذف السلة بالكامل
- `POST /api/v2/cart/checkout` - إتمام الطلب

### إدارة العناوين
**المسارات**:
- `GET /api/v2/addresses/all` - عرض جميع العناوين
- `POST /api/v2/addresses/` - إضافة عنوان جديد
- `DELETE /api/v2/addresses/{address}` - حذف عنوان

---

## 🏪 نظام التجار (Merchant API)

### لوحة تحكم التاجر
**المسار الأساسي**: `/api/v2/merchant/`

#### الإحصائيات واللوحة الرئيسية
- `GET /dashboard` - إحصائيات شاملة
- `GET /monthly-stats` - الإحصائيات الشهرية

**استجابة لوحة التحكم**:
```json
{
    "status": true,
    "data": {
        "orders": {
            "pending": 15,
            "completed": 120,
            "cancelled": 5,
            "total": 140
        },
        "earnings": {
            "total_earnings": 15000.00,
            "monthly_earnings": 2500.00,
            "pending_earnings": 800.00,
            "total_commission": 1500.00
        },
        "withdrawals": {
            "total_withdrawn": 12000.00,
            "pending_withdrawals": 500.00,
            "available_balance": 3000.00
        }
    }
}
```

#### إدارة الطلبات
- `GET /orders/pending` - الطلبات المطلوب تجهيزها
- `GET /orders/history` - تاريخ الطلبات

#### الأرباح والمدفوعات
- `GET /earnings` - تقرير الأرباح
- `GET /withdrawals` - تاريخ عمليات السحب
- `POST /withdrawals/request` - طلب سحب أموال

**طلب السحب**:
```json
{
    "amount": 1000.00,
    "method": "bank_transfer",
    "reference": "REF123456"
}
```

#### إدارة الملف الشخصي
- `GET /profile` - الحصول على ملف التاجر
- `PUT /profile` - تحديث ملف التاجر

---

## 🚗 نظام السواقين (Driver System)

### لوحة تحكم السواق
**المسار الأساسي**: `/api/v2/driver/`

#### الإحصائيات والملف الشخصي
- `GET /dashboard` - لوحة تحكم السواق
- `GET /profile` - الحصول على ملف السواق
- `PUT /profile` - تحديث ملف السواق

**استجابة لوحة التحكم**:
```json
{
    "status": true,
    "data": {
        "driver": {
            "id": 1,
            "name": "Driver Name",
            "phone": "+1234567890",
            "vehicle_type": "car",
            "vehicle_model": "Toyota Camry",
            "vehicle_plate": "ABC123",
            "city": "Riyadh",
            "neighborhood": "Al-Malaz",
            "is_available": true,
            "rating": 4.5
        },
        "statistics": {
            "total_orders": 150,
            "completed_orders": 140,
            "active_orders": 2,
            "cancelled_orders": 8,
            "average_rating": 4.5,
            "total_deliveries": 140,
            "current_workload": 2,
            "is_available": true,
            "is_working_now": true
        }
    }
}
```

#### إدارة الطلبات
- `GET /orders/current` - الطلبات الحالية للسواق
- `GET /orders/history` - تاريخ الطلبات

#### إجراءات الطلبات
- `POST /orders/{orderId}/accept` - قبول الطلب
- `POST /orders/{orderId}/pickup` - تأكيد استلام الطلب
- `POST /orders/{orderId}/deliver` - تأكيد تسليم الطلب
- `POST /orders/{orderId}/cancel` - إلغاء الطلب

**تأكيد التسليم**:
```json
{
    "delivery_notes": "Delivered successfully",
    "confirmation_image": "base64_encoded_image"
}
```

#### تحديث الحالة والموقع
- `POST /availability` - تحديث حالة التوفر
- `POST /location` - تحديث الموقع

---

## 👨‍💼 نظام المشرفين (Supervisor System)

### لوحة تحكم المشرف
**المسار الأساسي**: `/api/v2/supervisor/`

#### الإحصائيات العامة
- `GET /dashboard` - لوحة تحكم المشرف

#### إدارة السواقين
- `GET /drivers` - قائمة السواقين
- `GET /drivers/{driverId}` - تفاصيل سواق معين
- `GET /drivers/available` - السواقين المتاحين للتخصيص
- `PUT /drivers/{driverId}/status` - تحديث حالة السواق

#### إدارة الطلبات
- `GET /orders` - جميع الطلبات
- `POST /orders/assign` - تخصيص طلب يدوياً
- `POST /orders/{driverOrderId}/reassign` - إعادة تخصيص طلب
- `POST /orders/{driverOrderId}/confirm` - تأكيد التسليم (من جانب الإدارة)
- `POST /orders/{driverOrderId}/cancel` - إلغاء الطلب

**تخصيص طلب يدوياً**:
```json
{
    "order_id": 1001,
    "driver_id": 5
}
```

---

## 🎯 خوارزمية التوزيع الذكي للسواقين

### الأولويات:
1. **الأولوية الأولى**: سواق من نفس المنطقة مع أقل عدد طلبات
2. **الأولوية الثانية**: سواق من نفس المدينة مع أقل عدد طلبات  
3. **الأولوية الثالثة**: أي سواق متاح مع أقل عدد طلبات

### نظام التأكيد المزدوج:
- تأكيد السواق للتسليم
- تأكيد العميل أو الإدارة
- حماية من التسليمات المزيفة

---

## 🔒 الأمان والمصادقة

### المتطلبات العامة:
- **Authentication**: تسجيل دخول صحيح
- **Active User**: حساب نشط وغير محظور
- **Role-based Access**: صلاحيات حسب الدور

### Headers المطلوبة:
```
Authorization: Bearer {jwt_token}
Content-Type: application/json
Accept: application/json
```

### Middleware المستخدم:
- `api.auth` - التحقق من المصادقة
- `api.auth.active` - التحقق من المصادقة والحساب النشط

---

## 📊 رسائل الخطأ والاستجابات

### رموز الحالة:
- `200` - نجح الطلب
- `201` - تم الإنشاء بنجاح
- `400` - طلب غير صحيح
- `401` - غير مصرح
- `403` - ممنوع (حساب غير نشط)
- `404` - غير موجود
- `422` - خطأ في التحقق من البيانات
- `500` - خطأ في الخادم

### هيكل رسائل الخطأ:
```json
{
    "status": "error",
    "message": "رسالة الخطأ العامة",
    "errors": {
        "field_name": ["رسالة الخطأ المحددة"]
    },
    "error_summary": [
        "ملخص الأخطاء باللغة العربية"
    ]
}
```

---

## 🛠️ لوحة التحكم الإدارية

### إدارة السواقين
**المسارات**:
- `/dashboard/driver-management/dashboard` - لوحة التحكم الرئيسية
- `/dashboard/driver-management/drivers` - قائمة السواقين
- `/dashboard/driver-management/drivers/create` - إضافة سواق جديد
- `/dashboard/driver-management/drivers/{id}` - تفاصيل السواق
- `/dashboard/driver-management/orders` - إدارة الطلبات
- `/dashboard/driver-management/orders/{id}` - تفاصيل الطلب

### المميزات:
- إحصائيات شاملة مع رسوم بيانية تفاعلية
- فلترة متقدمة للبحث والتصفية
- إعادة تخصيص الطلبات يدوياً
- تأكيد التسليم من جانب الإدارة
- إدارة حالة السواقين والصلاحيات

---

## 📱 المميزات الاجتماعية

### نظام المحادثة
**المسارات**:
- `POST /api/v2/social/conversations/` - بدء محادثة
- `GET /api/v2/social/conversations/` - الحصول على المحادثات
- `GET /api/v2/social/conversations/{conversation}/messages` - الحصول على الرسائل
- `POST /api/v2/social/conversations/{conversation}/messages` - إرسال رسالة

### نظام المتابعة
**المسارات**:
- `POST /api/v2/social/follow/{user}` - متابعة مستخدم
- `DELETE /api/v2/social/follow/{user}` - إلغاء المتابعة
- `GET /api/v2/social/follow/{user}/following` - المتابعين
- `GET /api/v2/social/follow/{user}/followers` - المتابعين

---

## 🔔 نظام الإشعارات

**المسارات**:
- `GET /api/v2/notifications/{type?}` - الحصول على الإشعارات
- `GET /api/v2/notifications/unread/count` - عدد الإشعارات غير المقروءة
- `POST /api/v2/notifications/{id}/mark-read` - تحديد الإشعار كمقروء
- `DELETE /api/v2/notifications/{id}` - حذف الإشعار

---

## 🛍️ إدارة المنتجات

### المنتجات العامة
**المسارات**:
- `GET /api/v2/products/` - قائمة المنتجات
- `GET /api/v2/products/{product}` - تفاصيل المنتج
- `GET /api/v2/products/featured/list` - المنتجات المميزة
- `GET /api/v2/products/{product}/similar` - منتجات مشابهة

### إدارة المنتجات (محمية)
**المسارات**:
- `POST /api/v2/products/create` - إنشاء منتج جديد
- `DELETE /api/v2/products/{product}` - حذف منتج
- `POST /api/v2/products/{product}/reviews` - إضافة تقييم
- `DELETE /api/v2/products/{product}/reviews` - حذف تقييم

### المفضلة
**المسارات**:
- `GET /api/v2/products/favorites/user` - منتجات المستخدم المفضلة
- `POST /api/v2/products/favorites/{product}` - إضافة للمفضلة
- `DELETE /api/v2/products/favorites/{product}` - إزالة من المفضلة

---

## 🌍 البيانات الجغرافية

**المسارات**:
- `GET /api/v2/data/cities` - قائمة المدن
- `GET /api/v2/data/categories` - التصنيفات الرئيسية
- `GET /api/v2/data/categories/{category}/sub-categories` - التصنيفات الفرعية
- `GET /api/v2/data/features` - الميزات الرئيسية للمنتجات

---

## 🔍 البحث

**المسار**: `GET /api/v2/data/search`

**المعاملات**:
- `q` - كلمة البحث
- `category` - التصنيف (اختياري)
- `city` - المدينة (اختياري)
- `min_price` - الحد الأدنى للسعر (اختياري)
- `max_price` - الحد الأقصى للسعر (اختياري)

---

## 📊 إدارة الأدوار

**المسارات**:
- `GET /api/v2/role/change-request` - عرض طلب تغيير الدور
- `POST /api/v2/role/change-request` - إرسال طلب تغيير الدور

---

## 🚀 أمثلة على الاستخدام

### JavaScript/Fetch
```javascript
// تسجيل الدخول
const login = async (credentials) => {
    const response = await fetch('/api/v2/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(credentials)
    });
    
    const data = await response.json();
    if (data.status === 'success') {
        localStorage.setItem('token', data.token);
        return data;
    }
    return null;
};

// استخدام API مع التوكن
const fetchWithAuth = async (url, options = {}) => {
    const token = localStorage.getItem('token');
    return fetch(url, {
        ...options,
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...options.headers
        }
    });
};
```

### cURL
```bash
# تسجيل الدخول
curl -X POST http://your-domain.com/api/v2/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "login_field": "user@example.com",
    "password": "password123"
  }'

# استخدام API مع التوكن
curl -X GET http://your-domain.com/api/v2/user/profile \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Accept: application/json"
```

---

## 🔧 استكشاف الأخطاء

### المشاكل الشائعة:

1. **خطأ 401 Unauthorized**:
   - تأكد من صحة التوكن
   - تأكد من إرسال التوكن في Header
   - تحقق من انتهاء صلاحية التوكن

2. **خطأ 403 Forbidden**:
   - تأكد من أن الحساب نشط
   - تأكد من الصلاحيات المطلوبة
   - تحقق من حالة المستخدم

3. **خطأ 422 Validation Error**:
   - تحقق من صحة البيانات المرسلة
   - راجع رسائل الخطأ في `error_summary`
   - تأكد من إرسال جميع الحقول المطلوبة

4. **خطأ 500 Server Error**:
   - تحقق من سجل الأخطاء
   - تأكد من إعدادات قاعدة البيانات
   - راجع إعدادات الخادم

---

## 📈 الأداء والتحسين

### المميزات المحسنة:
- فهرسة قاعدة البيانات للاستعلامات السريعة
- تخزين مؤقت للإحصائيات المتكررة
- Pagination لتجنب تحميل البيانات الكبيرة
- تحديث فوري لعدد الطلبات الحالية

### نصائح الأداء:
- استخدم Pagination للقوائم الطويلة
- استخدم الفلترة لتقليل البيانات المرسلة
- احفظ التوكن محلياً لتجنب إعادة تسجيل الدخول
- استخدم WebSocket للإشعارات الفورية

---

## 🔮 التطويرات المستقبلية

### المميزات المخططة:
- نظام إشعارات متقدم
- تطبيق جوال للمشرفين
- تحليلات ذكية باستخدام AI
- نظام تقييم متقدم للعملاء
- تكامل مع خرائط GPS
- نظام دفع متقدم
- إدارة المخزون الذكية

---

## 📞 الدعم والمساعدة

### التوثيق المتاح:
- دليل المستخدم المفصل
- فيديوهات تعليمية
- أسئلة شائعة
- دليل استكشاف الأخطاء

### التدريب:
- جلسات تدريبية للمطورين
- ورش عمل للمستخدمين
- دعم فني مستمر
- تحديثات منتظمة

---

**تم إنشاء هذا الدليل ليكون مرجعاً شاملاً للذكاء الاصطناعي لفهم وتطوير نظام Multi-Vendor Store. النظام مصمم ليكون مرناً وقابلاً للتوسع مع دعم كامل للواجهات المختلفة.**
