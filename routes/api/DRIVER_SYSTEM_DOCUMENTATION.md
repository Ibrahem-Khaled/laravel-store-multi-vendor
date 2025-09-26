# Driver System API Documentation - v2

## نظام السواقين - Multi-Vendor Store

### 📋 نظرة عامة
تم إنشاء نظام شامل للسواقين مع إدارة ذكية للطلبات ونظام تأكيد مزدوج.

### 🔧 المميزات الرئيسية

#### **🚗 خوارزمية التوزيع الذكي:**
1. **الأولوية الأولى**: سواق من نفس المنطقة مع أقل عدد طلبات
2. **الأولوية الثانية**: سواق من نفس المدينة مع أقل عدد طلبات  
3. **الأولوية الثالثة**: أي سواق متاح مع أقل عدد طلبات

#### **👨‍💼 نظام المشرفين:**
- مشرفين على السواقين يمكنهم التحكم في التوزيع
- إمكانية التخصيص اليدوي للطلبات
- إعادة تخصيص الطلبات
- تأكيد التسليم من جانب الإدارة

#### **✅ نظام التأكيد المزدوج:**
- تأكيد السواق للتسليم
- تأكيد العميل أو الإدارة
- حماية من التسليمات المزيفة

---

## 🚗 Driver API

### **Base URL**: `/api/v2/driver/`

---

## 📊 Dashboard & Profile

### **GET** `/dashboard`
لوحة تحكم السواق

**Response:**
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
        },
        "recent_orders": [...]
    }
}
```

---

### **GET** `/profile`
الحصول على ملف السواق

### **PUT** `/profile`
تحديث ملف السواق

**Request Body:**
```json
{
    "license_number": "LIC123456",
    "vehicle_type": "car",
    "vehicle_model": "Toyota Camry",
    "vehicle_plate_number": "ABC123",
    "phone_number": "+1234567890",
    "city": "Riyadh",
    "neighborhood": "Al-Malaz",
    "working_hours": {
        "monday": {"start": "08:00", "end": "18:00"},
        "tuesday": {"start": "08:00", "end": "18:00"}
    },
    "service_areas": ["Al-Malaz", "Al-Olaya", "Al-Nasr"]
}
```

---

## 📦 Orders Management

### **GET** `/orders/current`
الطلبات الحالية للسواق

**Response:**
```json
{
    "status": true,
    "data": {
        "data": [
            {
                "id": 1,
                "order": {
                    "id": 1001,
                    "status": "pending",
                    "payment_method": "credit_card",
                    "grand_total": 250.00
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
                    "city": "Riyadh",
                    "neighborhood": "Al-Malaz",
                    "latitude": 24.7136,
                    "longitude": 46.6753
                },
                "driver": {
                    "id": 1,
                    "name": "Driver Name",
                    "phone": "+1234567890",
                    "vehicle_type": "car",
                    "vehicle_model": "Toyota Camry",
                    "vehicle_plate": "ABC123"
                },
                "status": "assigned",
                "delivery_fee": 15.00,
                "confirmation_status": {
                    "driver_confirmed": false,
                    "customer_confirmed": false,
                    "admin_confirmed": false,
                    "is_fully_confirmed": false
                }
            }
        ]
    }
}
```

---

### **GET** `/orders/history`
تاريخ الطلبات للسواق

**Parameters:**
- `status` (optional): حالة الطلب
- `date_from` (optional): تاريخ البداية
- `date_to` (optional): تاريخ النهاية
- `per_page` (optional): عدد النتائج في الصفحة

---

## 🎯 Order Actions

### **POST** `/orders/{orderId}/accept`
قبول الطلب

**Response:**
```json
{
    "status": true,
    "message": "Order accepted successfully",
    "data": {...}
}
```

---

### **POST** `/orders/{orderId}/pickup`
تأكيد استلام الطلب

**Response:**
```json
{
    "status": true,
    "message": "Order marked as picked up",
    "data": {...}
}
```

---

### **POST** `/orders/{orderId}/deliver`
تأكيد تسليم الطلب

**Request Body:**
```json
{
    "delivery_notes": "Delivered successfully",
    "confirmation_image": "base64_encoded_image"
}
```

**Response:**
```json
{
    "status": true,
    "message": "Order marked as delivered. Waiting for customer confirmation.",
    "data": {...}
}
```

---

### **POST** `/orders/{orderId}/cancel`
إلغاء الطلب

**Request Body:**
```json
{
    "reason": "Customer not available"
}
```

---

## 📍 Status & Location Updates

### **POST** `/availability`
تحديث حالة التوفر

**Request Body:**
```json
{
    "is_available": true
}
```

---

### **POST** `/location`
تحديث الموقع

**Request Body:**
```json
{
    "latitude": 24.7136,
    "longitude": 46.6753
}
```

---

## 👨‍💼 Driver Supervisor API

### **Base URL**: `/api/v2/supervisor/`

---

## 📊 Supervisor Dashboard

### **GET** `/dashboard`
لوحة تحكم المشرف

**Response:**
```json
{
    "status": true,
    "data": {
        "statistics": {
            "total_drivers": 25,
            "active_drivers": 20,
            "available_drivers": 15,
            "total_orders": 500,
            "pending_orders": 10,
            "in_progress_orders": 8,
            "completed_orders": 450,
            "cancelled_orders": 32
        },
        "recent_orders": [...],
        "drivers_needing_attention": [...]
    }
}
```

---

## 👥 Drivers Management

### **GET** `/drivers`
قائمة السواقين

**Parameters:**
- `city` (optional): المدينة
- `neighborhood` (optional): الحي
- `is_available` (optional): حالة التوفر
- `is_active` (optional): حالة النشاط
- `vehicle_type` (optional): نوع المركبة

---

### **GET** `/drivers/{driverId}`
تفاصيل سواق معين

---

### **GET** `/drivers/available`
السواقين المتاحين للتخصيص

---

### **PUT** `/drivers/{driverId}/status`
تحديث حالة السواق

**Request Body:**
```json
{
    "is_active": true,
    "is_available": true,
    "is_supervisor": false
}
```

---

## 📦 Orders Management

### **GET** `/orders`
جميع الطلبات

**Parameters:**
- `status` (optional): حالة الطلب
- `driver_id` (optional): معرف السواق
- `assignment_type` (optional): نوع التخصيص (auto/manual)
- `date_from` (optional): تاريخ البداية
- `date_to` (optional): تاريخ النهاية

---

### **POST** `/orders/assign`
تخصيص طلب يدوياً

**Request Body:**
```json
{
    "order_id": 1001,
    "driver_id": 5
}
```

---

### **POST** `/orders/{driverOrderId}/reassign`
إعادة تخصيص طلب

**Request Body:**
```json
{
    "new_driver_id": 8
}
```

---

### **POST** `/orders/{driverOrderId}/confirm`
تأكيد التسليم (من جانب الإدارة)

**Request Body:**
```json
{
    "notes": "Confirmed by supervisor"
}
```

---

### **POST** `/orders/{driverOrderId}/cancel`
إلغاء الطلب

**Request Body:**
```json
{
    "reason": "Customer cancelled"
}
```

---

## 🔄 Order Distribution Algorithm

### **الخوارزمية الذكية:**

1. **البحث في نفس الحي:**
   ```php
   $neighborhoodDrivers = Driver::available()
       ->inCity($city)
       ->inNeighborhood($neighborhood)
       ->where('is_working_now', true)
       ->orderBy('current_orders_count', 'asc')
       ->orderBy('rating', 'desc')
       ->first();
   ```

2. **البحث في نفس المدينة:**
   ```php
   $cityDrivers = Driver::available()
       ->inCity($city)
       ->where('is_working_now', true)
       ->orderBy('current_orders_count', 'asc')
       ->orderBy('rating', 'desc')
       ->first();
   ```

3. **البحث في أي مكان:**
   ```php
   $anyDriver = Driver::available()
       ->where('is_working_now', true)
       ->orderBy('current_orders_count', 'asc')
       ->orderBy('rating', 'desc')
       ->first();
   ```

---

## ✅ Double Confirmation System

### **نظام التأكيد المزدوج:**

1. **تأكيد السواق:**
   - السواق يؤكد التسليم
   - يرفق صورة أو ملاحظات

2. **تأكيد العميل أو الإدارة:**
   - العميل يؤكد الاستلام
   - أو الإدارة تؤكد التسليم

3. **التأكيد الكامل:**
   ```json
   {
       "driver_confirmed": true,
       "customer_confirmed": true,
       "admin_confirmed": false,
       "is_fully_confirmed": true
   }
   ```

---

## 🔒 Authentication & Authorization

### **المتطلبات:**
- **Driver Routes**: تسجيل دخول + دور سواق
- **Supervisor Routes**: تسجيل دخول + دور سواق + صلاحيات مشرف

### **Headers المطلوبة:**
```
Authorization: Bearer {jwt_token}
Content-Type: application/json
Accept: application/json
```

---

## 📊 Order Status Flow

```
assigned → accepted → picked_up → delivered → completed
    ↓         ↓          ↓           ↓
cancelled  cancelled  cancelled   cancelled
```

### **Status Descriptions:**
- **assigned**: تم تخصيص الطلب للسواق
- **accepted**: السواق قبل الطلب
- **picked_up**: السواق استلم الطلب
- **delivered**: السواق سلم الطلب
- **completed**: تم التأكيد النهائي
- **cancelled**: تم إلغاء الطلب

---

## 🚀 Usage Examples

### قبول طلب
```bash
curl -X POST "https://api.example.com/api/v2/driver/orders/1001/accept" \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Accept: application/json"
```

### تأكيد التسليم
```bash
curl -X POST "https://api.example.com/api/v2/driver/orders/1001/deliver" \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Content-Type: application/json" \
  -d '{
    "delivery_notes": "Delivered successfully",
    "confirmation_image": "base64_encoded_image"
  }'
```

### تخصيص طلب يدوياً
```bash
curl -X POST "https://api.example.com/api/v2/supervisor/orders/assign" \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": 1001,
    "driver_id": 5
  }'
```

---

## 📈 Performance & Optimization

- **فهرسة قاعدة البيانات** للاستعلامات السريعة
- **تخزين مؤقت** لإحصائيات السواقين
- **تحديث فوري** لعدد الطلبات الحالية
- **خوارزمية محسنة** لتوزيع الطلبات

---

## 🔧 Development Notes

- جميع المسارات تستخدم `api.auth.active` middleware
- البيانات مُنظمة باستخدام Resource classes
- معالجة شاملة للأخطاء
- تسجيل مفصل للعمليات
- نظام تأكيد مزدوج للحماية
