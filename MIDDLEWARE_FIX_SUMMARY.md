# إصلاح مشكلة is_active في Middleware

## المشكلة
كانت الـ middleware تستخدم حقول `is_active` و `is_banned` التي لا توجد في جدول `users`. بدلاً من ذلك، يوجد حقل `status` من نوع enum.

## الحل
تم إصلاح الـ middleware لاستخدام حقل `status` بدلاً من الحقول غير الموجودة.

---

## التغييرات المطبقة

### 1. إصلاح CheckUserActive.php
**قبل**:
```php
if (!$user->is_active) {
    return response()->json([
        'status' => false,
        'message' => 'Your account is deactivated. Please contact support.',
        'code' => 'ACCOUNT_DEACTIVATED'
    ], 403);
}

if ($user->is_banned) {
    return response()->json([
        'status' => false,
        'message' => 'Your account has been banned. Please contact support.',
        'code' => 'ACCOUNT_BANNED'
    ], 403);
}
```

**بعد**:
```php
if ($user->status !== 'active') {
    $message = match($user->status) {
        'inactive' => 'Your account is deactivated. Please contact support.',
        'banned' => 'Your account has been banned. Please contact support.',
        default => 'Your account is not active. Please contact support.'
    };
    
    $code = match($user->status) {
        'inactive' => 'ACCOUNT_DEACTIVATED',
        'banned' => 'ACCOUNT_BANNED',
        default => 'ACCOUNT_INACTIVE'
    };
    
    return response()->json([
        'status' => false,
        'message' => $message,
        'code' => $code
    ], 403);
}
```

### 2. إصلاح ApiAuthAndActive.php
نفس التغييرات المطبقة على `CheckUserActive.php`

### 3. إصلاح الاختبارات
**قبل**:
```php
$user = User::factory()->create([
    'is_active' => true,
    'is_banned' => false,
]);
```

**بعد**:
```php
$user = User::factory()->create([
    'status' => 'active',
]);
```

---

## هيكل حقل status في جدول users

```php
$table->enum('status', ['active', 'inactive', 'banned'])->default('inactive');
```

### القيم الممكنة:
- **`active`**: المستخدم نشط ويمكنه الوصول للخدمات
- **`inactive`**: المستخدم غير نشط ولا يمكنه الوصول
- **`banned`**: المستخدم محظور

---

## رسائل الخطأ الجديدة

### للمستخدم غير النشط (inactive):
```json
{
    "status": false,
    "message": "Your account is deactivated. Please contact support.",
    "code": "ACCOUNT_DEACTIVATED"
}
```

### للمستخدم المحظور (banned):
```json
{
    "status": false,
    "message": "Your account has been banned. Please contact support.",
    "code": "ACCOUNT_BANNED"
}
```

### لحالات أخرى:
```json
{
    "status": false,
    "message": "Your account is not active. Please contact support.",
    "code": "ACCOUNT_INACTIVE"
}
```

---

## الملفات المحدثة

1. **app/Http/Middleware/CheckUserActive.php** - إصلاح التحقق من حالة المستخدم
2. **app/Http/Middleware/ApiAuthAndActive.php** - إصلاح التحقق من حالة المستخدم
3. **tests/Feature/Middleware/ApiMiddlewareTest.php** - إصلاح الاختبارات
4. **tests/Feature/Merchant/MerchantApiTest.php** - إصلاح اختبارات التاجر

---

## النتيجة

الآن الـ middleware يعمل بشكل صحيح مع هيكل قاعدة البيانات الفعلي:
- ✅ يستخدم حقل `status` بدلاً من `is_active`
- ✅ يتحقق من جميع حالات المستخدم بشكل صحيح
- ✅ يعطي رسائل خطأ واضحة ومفيدة
- ✅ الاختبارات تعمل بشكل صحيح

**المشكلة تم حلها بالكامل! 🎉**
