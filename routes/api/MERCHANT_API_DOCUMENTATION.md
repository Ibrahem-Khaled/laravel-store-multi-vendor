# Merchant API Documentation - v2

## نظام API التاجر - Multi-Vendor Store

### 📋 نظرة عامة
تم إنشاء نظام API شامل للتاجر يشمل إدارة الطلبات والإحصائيات والأرباح وعمليات السحب.

### 🔧 المسارات المتاحة

#### **Base URL**: `/api/v2/merchant/`

---

## 📊 Dashboard & Statistics

### **GET** `/dashboard`
الحصول على إحصائيات لوحة التحكم للتاجر

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
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
        },
        "recent_orders": [
            {
                "id": 1,
                "order_id": 1001,
                "product": {
                    "id": 1,
                    "name": "Product Name",
                    "image": "image.jpg",
                    "price": 100.00
                },
                "customer": {
                    "id": 1,
                    "name": "Customer Name",
                    "email": "customer@email.com",
                    "phone": "+1234567890"
                },
                "quantity": 2,
                "unit_price": 100.00,
                "total_price": 200.00,
                "commission_rate": 10.00,
                "commission_amount": 20.00,
                "payout_amount": 180.00,
                "order_status": "completed",
                "payment_method": "credit_card",
                "order_date": "2024-01-15T10:30:00Z",
                "created_at": "2024-01-15T10:30:00Z",
                "updated_at": "2024-01-15T10:30:00Z"
            }
        ]
    }
}
```

---

### **GET** `/monthly-stats`
الحصول على الإحصائيات الشهرية

**Parameters:**
- `months` (optional): عدد الأشهر المطلوبة (افتراضي: 12)

**Response:**
```json
{
    "status": true,
    "data": [
        {
            "month": "2024-01",
            "month_name": "January 2024",
            "orders_count": 25,
            "total_earnings": 2500.00,
            "total_commission": 250.00,
            "average_order_value": 100.00
        }
    ]
}
```

---

## 📦 Orders Management

### **GET** `/orders/pending`
الحصول على الطلبات المطلوب تجهيزها

**Parameters:**
- `status` (optional): حالة الطلب (pending, processing, confirmed)
- `date_from` (optional): تاريخ البداية (YYYY-MM-DD)
- `date_to` (optional): تاريخ النهاية (YYYY-MM-DD)
- `per_page` (optional): عدد النتائج في الصفحة (افتراضي: 15)

**Response:**
```json
{
    "status": true,
    "data": {
        "data": [
            {
                "id": 1,
                "order_id": 1001,
                "product": {
                    "id": 1,
                    "name": "Product Name",
                    "image": "image.jpg",
                    "price": 100.00
                },
                "customer": {
                    "id": 1,
                    "name": "Customer Name",
                    "email": "customer@email.com",
                    "phone": "+1234567890"
                },
                "delivery_address": {
                    "id": 1,
                    "address": "123 Main St",
                    "city": "City Name",
                    "neighborhood": "Neighborhood Name"
                },
                "quantity": 2,
                "unit_price": 100.00,
                "total_price": 200.00,
                "commission_rate": 10.00,
                "commission_amount": 20.00,
                "payout_amount": 180.00,
                "order_status": "pending",
                "payment_method": "credit_card",
                "order_date": "2024-01-15T10:30:00Z",
                "created_at": "2024-01-15T10:30:00Z",
                "updated_at": "2024-01-15T10:30:00Z"
            }
        ],
        "current_page": 1,
        "per_page": 15,
        "total": 25
    }
}
```

---

### **GET** `/orders/history`
الحصول على تاريخ الطلبات

**Parameters:**
- `status` (optional): حالة الطلب
- `date_from` (optional): تاريخ البداية
- `date_to` (optional): تاريخ النهاية
- `per_page` (optional): عدد النتائج في الصفحة

**Response:** نفس هيكل `/orders/pending`

---

## 💰 Earnings & Payments

### **GET** `/earnings`
الحصول على تقرير الأرباح

**Parameters:**
- `date_from` (optional): تاريخ البداية
- `date_to` (optional): تاريخ النهاية
- `per_page` (optional): عدد النتائج في الصفحة

**Response:**
```json
{
    "status": true,
    "data": {
        "summary": {
            "total_earnings": 15000.00,
            "total_commission": 1500.00,
            "total_orders": 120,
            "average_order_value": 125.00
        },
        "earnings": {
            "data": [
                {
                    "id": 1,
                    "order_id": 1001,
                    "product": {
                        "id": 1,
                        "name": "Product Name"
                    },
                    "quantity": 2,
                    "unit_price": 100.00,
                    "commission_rate": 10.00,
                    "commission_amount": 20.00,
                    "payout_amount": 180.00,
                    "order": {
                        "id": 1001,
                        "status": "completed",
                        "created_at": "2024-01-15T10:30:00Z"
                    }
                }
            ]
        }
    }
}
```

---

### **GET** `/withdrawals`
الحصول على تاريخ عمليات السحب

**Parameters:**
- `status` (optional): حالة السحب (paid, pending)
- `per_page` (optional): عدد النتائج في الصفحة

**Response:**
```json
{
    "status": true,
    "data": {
        "data": [
            {
                "id": 1,
                "amount": 1000.00,
                "method": "bank_transfer",
                "reference": "REF123456",
                "status": "paid",
                "requested_at": "2024-01-15T10:30:00Z",
                "paid_at": "2024-01-16T14:20:00Z",
                "meta": {
                    "requested_at": "2024-01-15T10:30:00Z",
                    "status": "pending"
                }
            }
        ]
    }
}
```

---

### **POST** `/withdrawals/request`
طلب سحب أموال

**Request Body:**
```json
{
    "amount": 1000.00,
    "method": "bank_transfer",
    "reference": "REF123456"
}
```

**Validation:**
- `amount`: مطلوب، رقم، الحد الأدنى 1
- `method`: مطلوب، نص، يجب أن يكون bank_transfer أو paypal
- `reference`: اختياري، نص

**Response:**
```json
{
    "status": true,
    "message": "Withdrawal request submitted successfully",
    "data": {
        "id": 1,
        "amount": 1000.00,
        "method": "bank_transfer",
        "reference": "REF123456",
        "status": "pending",
        "requested_at": "2024-01-15T10:30:00Z",
        "paid_at": null,
        "meta": {
            "requested_at": "2024-01-15T10:30:00Z",
            "status": "pending"
        }
    }
}
```

---

## 👤 Profile Management

### **GET** `/profile`
الحصول على ملف التاجر

**Response:**
```json
{
    "status": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "default_commission_rate": 10.00,
        "payout_bank_name": "Bank Name",
        "payout_account_name": "Account Name",
        "payout_account_iban": "IBAN123456789",
        "created_at": "2024-01-01T00:00:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
    }
}
```

---

### **PUT** `/profile`
تحديث ملف التاجر

**Request Body:**
```json
{
    "default_commission_rate": 12.00,
    "payout_bank_name": "New Bank Name",
    "payout_account_name": "New Account Name",
    "payout_account_iban": "NEWIBAN123456789"
}
```

**Validation:**
- `default_commission_rate`: اختياري، رقم، بين 0 و 100
- `payout_bank_name`: اختياري، نص، الحد الأقصى 255 حرف
- `payout_account_name`: اختياري، نص، الحد الأقصى 255 حرف
- `payout_account_iban`: اختياري، نص، الحد الأقصى 255 حرف

**Response:**
```json
{
    "status": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "user_id": 1,
        "default_commission_rate": 12.00,
        "payout_bank_name": "New Bank Name",
        "payout_account_name": "New Account Name",
        "payout_account_iban": "NEWIBAN123456789",
        "created_at": "2024-01-01T00:00:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
    }
}
```

---

## 🔒 Authentication

جميع المسارات تتطلب:
- **Authentication**: تسجيل دخول صحيح
- **Active User**: حساب نشط وغير محظور
- **Merchant Role**: دور تاجر

**Headers المطلوبة:**
```
Authorization: Bearer {jwt_token}
Content-Type: application/json
Accept: application/json
```

---

## 📊 Error Responses

### **401 Unauthorized**
```json
{
    "status": false,
    "message": "Unauthenticated. Please login first.",
    "code": "UNAUTHENTICATED"
}
```

### **403 Forbidden**
```json
{
    "status": false,
    "message": "Your account is deactivated. Please contact support.",
    "code": "ACCOUNT_DEACTIVATED"
}
```

### **400 Bad Request**
```json
{
    "status": false,
    "message": "Insufficient balance. Available balance: 500.00"
}
```

### **404 Not Found**
```json
{
    "status": false,
    "message": "Merchant profile not found"
}
```

---

## 🚀 Usage Examples

### الحصول على لوحة التحكم
```bash
curl -X GET "https://api.example.com/api/v2/merchant/dashboard" \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Accept: application/json"
```

### طلب سحب أموال
```bash
curl -X POST "https://api.example.com/api/v2/merchant/withdrawals/request" \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "amount": 1000.00,
    "method": "bank_transfer",
    "reference": "REF123456"
  }'
```

### الحصول على الطلبات المعلقة
```bash
curl -X GET "https://api.example.com/api/v2/merchant/orders/pending?status=pending&per_page=10" \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Accept: application/json"
```

---

## 📈 Performance Notes

- جميع الاستعلامات محسنة للأداء
- استخدام Pagination لتجنب تحميل البيانات الكبيرة
- فهرسة قاعدة البيانات للاستعلامات السريعة
- تخزين مؤقت للإحصائيات المتكررة

---

## 🔧 Development Notes

- جميع المسارات تستخدم `api.auth.active` middleware
- البيانات مُنظمة باستخدام Resource classes
- التحقق من صحة البيانات باستخدام Form Requests
- معالجة الأخطاء بشكل شامل
- توثيق شامل للكود
