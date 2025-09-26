# API Middleware Documentation

## نظام الـ Middleware - Multi-Vendor Store API

### 📋 نظرة عامة
تم إنشاء نظام middleware متقدم للتحقق من المصادقة وحالة المستخدم في API.

### 🔧 الـ Middleware المتاحة

#### **1. ApiAuth**
- **الملف**: `app/Http/Middleware/ApiAuth.php`
- **الاستخدام**: `api.auth`
- **الوظيفة**: التحقق من تسجيل دخول المستخدم فقط
- **التحققات**:
  - وجود المستخدم المسجل دخوله
  - صحة الـ token

#### **2. CheckUserActive**
- **الملف**: `app/Http/Middleware/CheckUserActive.php`
- **الاستخدام**: `api.user.active`
- **الوظيفة**: التحقق من حالة المستخدم النشط
- **التحققات**:
  - المستخدم مسجل دخوله
  - الحساب نشط (`is_active = true`)
  - الحساب غير محظور (`is_banned = false`)
  - الحساب غير محذوف (`deleted_at = null`)

#### **3. ApiAuthAndActive**
- **الملف**: `app/Http/Middleware/ApiAuthAndActive.php`
- **الاستخدام**: `api.auth.active`
- **الوظيفة**: التحقق من المصادقة وحالة المستخدم معاً
- **التحققات**:
  - جميع تحققات `ApiAuth`
  - جميع تحققات `CheckUserActive`

### 🚀 كيفية الاستخدام

#### في ملفات المسارات:

```php
// للتحقق من المصادقة فقط
Route::middleware('api.auth')->group(function () {
    // مسارات تحتاج تسجيل دخول فقط
});

// للتحقق من حالة المستخدم النشط
Route::middleware('api.user.active')->group(function () {
    // مسارات تحتاج مستخدم نشط
});

// للتحقق من المصادقة وحالة المستخدم معاً (موصى به)
Route::middleware('api.auth.active')->group(function () {
    // مسارات تحتاج تسجيل دخول ومستخدم نشط
});
```

### 📊 رموز الاستجابة

#### **401 Unauthorized**
```json
{
    "status": false,
    "message": "Unauthenticated. Please login first.",
    "code": "UNAUTHENTICATED"
}
```

```json
{
    "status": false,
    "message": "Invalid authentication token.",
    "code": "INVALID_TOKEN"
}
```

#### **403 Forbidden**
```json
{
    "status": false,
    "message": "Your account is deactivated. Please contact support.",
    "code": "ACCOUNT_DEACTIVATED"
}
```

```json
{
    "status": false,
    "message": "Your account has been banned. Please contact support.",
    "code": "ACCOUNT_BANNED"
}
```

```json
{
    "status": false,
    "message": "Your account has been deleted.",
    "code": "ACCOUNT_DELETED"
}
```

### 🔍 أمثلة على الاستخدام

#### **API v1 (Legacy)**
```php
// يستخدم api.auth فقط
Route::middleware('api.auth')->group(function () {
    Route::post('/update-profile', [authController::class, 'update']);
});
```

#### **API v2 (Current)**
```php
// يستخدم api.auth.active (موصى به)
Route::middleware('api.auth.active')->group(function () {
    Route::post('/profile/update', [authController::class, 'update']);
});
```

### 🛡️ الأمان

#### **التحققات المطبقة:**
1. **المصادقة**: التحقق من وجود المستخدم المسجل دخوله
2. **صحة الـ Token**: التحقق من صحة الـ JWT token
3. **حالة النشاط**: التحقق من أن الحساب نشط
4. **عدم الحظر**: التحقق من أن الحساب غير محظور
5. **عدم الحذف**: التحقق من أن الحساب غير محذوف

#### **الاستجابات الآمنة:**
- رسائل خطأ واضحة ومفيدة
- رموز خطأ محددة للتطبيقات
- عدم كشف معلومات حساسة
- استجابات HTTP صحيحة

### 📈 الأداء

#### **التحسينات:**
- استخدام `Auth::guard('api')->check()` للأداء الأمثل
- التحقق من المستخدم مرة واحدة فقط
- إرجاع الاستجابة فوراً عند الفشل
- عدم تحميل بيانات غير ضرورية

### 🔧 التخصيص

#### **إضافة تحققات جديدة:**
```php
// في CheckUserActive.php
if ($user->custom_field) {
    return response()->json([
        'status' => false,
        'message' => 'Custom validation failed.',
        'code' => 'CUSTOM_VALIDATION_FAILED'
    ], 403);
}
```

#### **تخصيص الرسائل:**
```php
// يمكن تخصيص الرسائل حسب اللغة
$message = app()->getLocale() === 'ar' 
    ? 'حسابك غير نشط. يرجى التواصل مع الدعم.'
    : 'Your account is deactivated. Please contact support.';
```

### ⚠️ ملاحظات مهمة

1. **استخدم `api.auth.active`** للمسارات الجديدة
2. **احتفظ بـ `api.auth`** للمسارات القديمة
3. **اختبر** الـ middleware في بيئة التطوير
4. **وثق** أي تغييرات جديدة
5. **راقب** الأداء عند التطبيق

### 📞 الدعم

لأي استفسارات حول الـ middleware، يرجى التواصل مع فريق التطوير.
