# 📚 دليل API للمطورين - Frontend Developer Guide

## 🔐 المصادقة (Authentication)

جميع الـ API endpoints التالية تتطلب مصادقة. يجب إرسال `Authorization` header مع كل طلب:

```
Authorization: Bearer {access_token}
```

أو يمكن استخدام:
```
Authorization: Token {access_token}
```

---

## 📦 1. تتبع الطلبات (Order Tracking)

### 1.1. قائمة طلبات العميل

**Endpoint:** `GET /api/v2/orders`

**الوصف:** الحصول على قائمة جميع طلبات العميل

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `status` | string | No | فلترة حسب الحالة (pending, paid, shipped, completed, cancelled) |
| `per_page` | integer | No | عدد النتائج في الصفحة (افتراضي: 15) |
| `page` | integer | No | رقم الصفحة |

**Response Success (200):**
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "status": "completed",
      "payment_method": "cash_on_delivery",
      "subtotal": 150.00,
      "shipping_total": 10.00,
      "discount_total": 0.00,
      "grand_total": 160.00,
      "items_count": 3,
      "items": [
        {
          "id": 1,
          "product": {
            "id": 10,
            "name": "منتج 1",
            "image": "https://example.com/image.jpg"
          },
          "quantity": 2,
          "unit_price": 50.00,
          "total": 100.00
        }
      ],
      "address": {
        "city": "الرياض",
        "neighborhood": "الحي الشمالي",
        "address": "شارع الملك فهد"
      },
      "driver": {
        "id": 5,
        "name": "أحمد محمد",
        "phone": "+966501234567",
        "vehicle_type": "motorcycle",
        "status": "delivered"
      },
      "has_returns": false,
      "created_at": "2025-11-17T10:30:00.000000Z",
      "updated_at": "2025-11-17T12:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Response Error (401):**
```json
{
  "status": false,
  "message": "Unauthenticated"
}
```

---

### 1.2. تفاصيل الطلب

**Endpoint:** `GET /api/v2/orders/{id}`

**الوصف:** الحصول على تفاصيل طلب محدد

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | رقم الطلب |

**Response Success (200):**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "status": "completed",
    "payment_method": "cash_on_delivery",
    "subtotal": 150.00,
    "shipping_total": 10.00,
    "discount_total": 0.00,
    "grand_total": 160.00,
    "items": [
      {
        "id": 1,
        "product": {
          "id": 10,
          "name": "منتج 1",
          "image": "https://example.com/image.jpg"
        },
        "quantity": 2,
        "unit_price": 50.00,
        "total": 100.00
      }
    ],
    "address": {
      "city": "الرياض",
      "neighborhood": "الحي الشمالي",
      "address": "شارع الملك فهد"
    },
    "driver": {
      "id": 5,
      "name": "أحمد محمد",
      "phone": "+966501234567",
      "vehicle_type": "motorcycle",
      "vehicle_plate": "أ ب ج 1234",
      "status": "delivered",
      "assigned_at": "2025-11-17T10:35:00.000000Z",
      "picked_up_at": "2025-11-17T11:00:00.000000Z",
      "delivered_at": "2025-11-17T12:00:00.000000Z"
    },
    "returns": [
      {
        "id": 1,
        "type": "return",
        "status": "pending",
        "reason": "المنتج معيب"
      }
    ],
    "created_at": "2025-11-17T10:30:00.000000Z",
    "updated_at": "2025-11-17T12:00:00.000000Z"
  }
}
```

**Response Error (404):**
```json
{
  "status": false,
  "message": "Order not found"
}
```

---

### 1.3. تتبع حالة الطلب (Order Tracking)

**Endpoint:** `GET /api/v2/orders/{id}/track`

**الوصف:** الحصول على Timeline تفصيلي لحالة الطلب

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | رقم الطلب |

**Response Success (200):**
```json
{
  "status": true,
  "data": {
    "order_id": 1,
    "order_status": "completed",
    "current_status": "delivered",
    "timeline": [
      {
        "status": "assigned",
        "title": "تم تعيين السائق",
        "description": "تم تعيين سائق للطلب",
        "date": "2025-11-17T10:35:00.000000Z"
      },
      {
        "status": "accepted",
        "title": "قبل السائق الطلب",
        "description": "قبل السائق الطلب وهو في الطريق",
        "date": "2025-11-17T10:40:00.000000Z"
      },
      {
        "status": "picked_up",
        "title": "تم استلام الطلب",
        "description": "استلم السائق الطلب وهو في الطريق إليك",
        "date": "2025-11-17T11:00:00.000000Z"
      },
      {
        "status": "delivered",
        "title": "تم التسليم",
        "description": "تم تسليم الطلب بنجاح",
        "date": "2025-11-17T12:00:00.000000Z"
      }
    ],
    "driver": {
      "id": 5,
      "name": "أحمد محمد",
      "phone": "+966501234567",
      "vehicle_type": "motorcycle"
    }
  }
}
```

**حالات الطلب (Status Values):**
- `not_assigned` - لم يتم تعيين سائق بعد
- `assigned` - تم تعيين السائق
- `accepted` - قبل السائق الطلب
- `picked_up` - استلم السائق الطلب
- `delivered` - تم التسليم

**Response Error (404):**
```json
{
  "status": false,
  "message": "Order not found"
}
```

---

### 1.4. تأكيد استلام الطلب

**Endpoint:** `POST /api/v2/orders/{id}/confirm-receipt`

**الوصف:** تأكيد استلام الطلب من قبل العميل

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | رقم الطلب |

**Request Body:** لا يحتاج body

**Response Success (200):**
```json
{
  "status": true,
  "message": "تم تأكيد استلام الطلب بنجاح",
  "data": {
    "order_id": 1,
    "status": "completed"
  }
}
```

**Response Error (400):**
```json
{
  "status": false,
  "message": "الطلب لم يتم تسليمه بعد"
}
```

**Response Error (404):**
```json
{
  "status": false,
  "message": "Order not found"
}
```

---

## 🔄 2. المرتجعات والاستبدال (Returns & Refunds)

### 2.1. قائمة المرتجعات

**Endpoint:** `GET /api/v2/returns`

**الوصف:** الحصول على قائمة جميع طلبات الإرجاع للعميل

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `status` | string | No | فلترة حسب الحالة (pending, approved, rejected, processing, completed, cancelled) |
| `per_page` | integer | No | عدد النتائج في الصفحة (افتراضي: 15) |
| `page` | integer | No | رقم الصفحة |

**Response Success (200):**
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "order_id": 10,
      "order_item_id": 25,
      "type": "return",
      "status": "approved",
      "reason": "المنتج معيب",
      "customer_notes": "المنتج به عيب في التصنيع",
      "admin_notes": "تم الموافقة على الإرجاع",
      "refund_amount": 50.00,
      "refund_method": "original_payment",
      "images": [
        "returns/image1.jpg",
        "returns/image2.jpg"
      ],
      "order": {
        "id": 10,
        "grand_total": 150.00,
        "status": "completed"
      },
      "order_item": {
        "id": 25,
        "product": {
          "id": 10,
          "name": "منتج 1"
        },
        "quantity": 1,
        "unit_price": 50.00
      },
      "replacement_order": null,
      "created_at": "2025-11-17T14:00:00.000000Z",
      "updated_at": "2025-11-17T15:00:00.000000Z",
      "processed_at": "2025-11-17T15:00:00.000000Z",
      "approved_at": "2025-11-17T15:00:00.000000Z",
      "rejected_at": null,
      "completed_at": null
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 42
  }
}
```

**أنواع المرتجعات (Type Values):**
- `return` - إرجاع المنتج
- `refund` - استرداد المبلغ
- `replacement` - استبدال المنتج

**حالات المرتجع (Status Values):**
- `pending` - قيد المراجعة
- `approved` - موافق عليها
- `rejected` - مرفوضة
- `processing` - قيد المعالجة
- `completed` - مكتملة
- `cancelled` - ملغاة

**طرق الاسترداد (Refund Method Values):**
- `original_payment` - نفس طريقة الدفع الأصلية
- `wallet` - المحفظة
- `bank_transfer` - تحويل بنكي

---

### 2.2. تفاصيل المرتجع

**Endpoint:** `GET /api/v2/returns/{id}`

**الوصف:** الحصول على تفاصيل مرتجع محدد

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | رقم المرتجع |

**Response Success (200):**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "order_id": 10,
    "order_item_id": 25,
    "type": "return",
    "status": "approved",
    "reason": "المنتج معيب",
    "customer_notes": "المنتج به عيب في التصنيع",
    "admin_notes": "تم الموافقة على الإرجاع",
    "refund_amount": 50.00,
    "refund_method": "original_payment",
    "images": [
      "returns/image1.jpg",
      "returns/image2.jpg"
    ],
    "order": {
      "id": 10,
      "grand_total": 150.00,
      "status": "completed",
      "items": [
        {
          "id": 25,
          "product": {
            "id": 10,
            "name": "منتج 1"
          },
          "quantity": 1,
          "unit_price": 50.00
        }
      ]
    },
    "order_item": {
      "id": 25,
      "product": {
        "id": 10,
        "name": "منتج 1"
      },
      "quantity": 1,
      "unit_price": 50.00
    },
    "replacement_order": null,
    "created_at": "2025-11-17T14:00:00.000000Z",
    "updated_at": "2025-11-17T15:00:00.000000Z",
    "processed_at": "2025-11-17T15:00:00.000000Z",
    "approved_at": "2025-11-17T15:00:00.000000Z",
    "rejected_at": null,
    "completed_at": null
  }
}
```

---

### 2.3. إنشاء طلب إرجاع

**Endpoint:** `POST /api/v2/returns`

**الوصف:** إنشاء طلب إرجاع جديد

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `order_id` | integer | Yes | رقم الطلب |
| `order_item_id` | integer | No | رقم عنصر الطلب (إذا كان الإرجاع لعنصر محدد، اتركه فارغاً لإرجاع كل الطلب) |
| `type` | string | Yes | نوع الطلب: `return`, `refund`, `replacement` |
| `reason` | string | Yes | سبب الإرجاع (حد أقصى 1000 حرف) |
| `customer_notes` | string | No | ملاحظات العميل (حد أقصى 1000 حرف) |
| `images[]` | file[] | No | صور المنتج المعيب (حد أقصى 5 صور، كل صورة حتى 2MB) |

**Request Example (JavaScript/Fetch):**
```javascript
const formData = new FormData();
formData.append('order_id', 10);
formData.append('order_item_id', 25); // اختياري
formData.append('type', 'return');
formData.append('reason', 'المنتج معيب');
formData.append('customer_notes', 'المنتج به عيب في التصنيع');

// إضافة الصور
for (let i = 0; i < images.length; i++) {
  formData.append('images[]', images[i]);
}

fetch('/api/v2/returns', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
  },
  body: formData
});
```

**Response Success (201):**
```json
{
  "status": true,
  "message": "تم إنشاء طلب الإرجاع بنجاح",
  "data": {
    "id": 1,
    "order_id": 10,
    "status": "pending",
    "type": "return"
  }
}
```

**Response Error (400):**
```json
{
  "status": false,
  "message": "يمكن إرجاع الطلبات المكتملة أو المشحونة فقط"
}
```

**Response Error (400):**
```json
{
  "status": false,
  "message": "يوجد طلب إرجاع معلق بالفعل لهذا العنصر"
}
```

**Response Error (422):**
```json
{
  "status": false,
  "message": "Validation failed",
  "errors": {
    "order_id": ["رقم الطلب مطلوب"],
    "type": ["نوع الطلب مطلوب"],
    "reason": ["سبب الإرجاع مطلوب"]
  }
}
```

---

### 2.4. إلغاء طلب الإرجاع

**Endpoint:** `POST /api/v2/returns/{id}/cancel`

**الوصف:** إلغاء طلب إرجاع معلق

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | رقم المرتجع |

**Request Body:** لا يحتاج body

**Response Success (200):**
```json
{
  "status": true,
  "message": "تم إلغاء طلب الإرجاع بنجاح"
}
```

**Response Error (404):**
```json
{
  "status": false,
  "message": "Return request not found"
}
```

**Response Error (400):**
```json
{
  "status": false,
  "message": "لا يمكن إلغاء هذا المرتجع"
}
```

---

## 🎁 3. نقاط الولاء (Loyalty Points)

### 3.1. الحصول على نقاط الولاء

**Endpoint:** `GET /api/v2/loyalty/points`

**الوصف:** الحصول على معلومات نقاط الولاء للعميل

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
  "status": "success",
  "data": {
    "user_id": 1,
    "total_points": 500,
    "available_points": 350,
    "used_points": 150,
    "expired_points": 0,
    "platform_contribution": 350.00,
    "customer_contribution": 150.00,
    "total_contribution": 500.00,
    "last_earned_at": "2025-11-17T12:00:00.000000Z",
    "last_used_at": "2025-11-15T10:30:00.000000Z"
  }
}
```

**شرح الحقول:**
- `total_points` - إجمالي النقاط المكتسبة
- `available_points` - النقاط المتاحة للاستخدام (إجمالي - مستخدمة - منتهية)
- `used_points` - النقاط المستخدمة
- `expired_points` - النقاط المنتهية الصلاحية
- `platform_contribution` - مساهمة المنصة في النقاط (بالريال)
- `customer_contribution` - مساهمة العميل في النقاط (بالريال)
- `total_contribution` - إجمالي المساهمة

---

### 3.2. تاريخ معاملات نقاط الولاء

**Endpoint:** `GET /api/v2/loyalty/transactions`

**الوصف:** الحصول على تاريخ جميع معاملات نقاط الولاء

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `type` | string | No | فلترة حسب النوع: `earned`, `used`, `expired`, `refunded` |
| `per_page` | integer | No | عدد النتائج في الصفحة (افتراضي: 15) |
| `page` | integer | No | رقم الصفحة |

**Response Success (200):**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "type": "earned",
      "points": 100,
      "amount": 100.00,
      "source": "order",
      "description": "كسب 100 نقطة من الطلب رقم #123",
      "status": "active",
      "order_id": 10,
      "order_number": "#123",
      "processed_by": null,
      "expires_at": "2026-11-17T12:00:00.000000Z",
      "created_at": "2025-11-17T12:00:00.000000Z",
      "metadata": {
        "order_number": "#123",
        "order_total": 100.00,
        "platform_contribution": 70.00,
        "customer_contribution": 30.00,
        "earned_at": "2025-11-17T12:00:00.000Z"
      }
    },
    {
      "id": 2,
      "type": "used",
      "points": 50,
      "amount": 0.50,
      "source": "order",
      "description": "استخدام 50 نقطة في الطلب رقم #124",
      "status": "completed",
      "order_id": 11,
      "order_number": "#124",
      "processed_by": null,
      "expires_at": null,
      "created_at": "2025-11-15T10:30:00.000000Z",
      "metadata": {
        "order_number": "#124",
        "points_value": 0.50,
        "used_at": "2025-11-15T10:30:00.000Z"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 25,
    "last_page": 2
  }
}
```

**أنواع المعاملات (Type Values):**
- `earned` - نقاط مكتسبة
- `used` - نقاط مستخدمة
- `expired` - نقاط منتهية الصلاحية
- `refunded` - نقاط مستردة

**مصادر النقاط (Source Values):**
- `order` - من الطلبات
- `manual` - إضافة يدوية من الإدارة
- `refund` - من المرتجعات

---

### 3.3. استخدام نقاط الولاء

**Endpoint:** `POST /api/v2/loyalty/use`

**الوصف:** استخدام نقاط الولاء في طلب محدد

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

**Request Body:**
```json
{
  "points": 100,
  "order_id": 10
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `points` | integer | Yes | عدد النقاط المراد استخدامها (يجب أن يكون أكبر من 0) |
| `order_id` | integer | Yes | رقم الطلب |

**Response Success (200):**
```json
{
  "status": "success",
  "message": "تم استخدام النقاط بنجاح",
  "data": {
    "points_used": 100,
    "points_value": 1.00,
    "remaining_points": 250,
    "order_total": 149.00
  }
}
```

**ملاحظات مهمة:**
- كل نقطة = 0.01 ريال (100 نقطة = 1 ريال)
- يمكن استخدام النقاط فقط في الطلبات بحالة `pending`
- يجب أن تكون النقاط المتاحة كافية
- يتم خصم قيمة النقاط من إجمالي الطلب تلقائياً

**Response Error (400):**
```json
{
  "status": "error",
  "message": "النقاط المتاحة غير كافية",
  "available_points": 50
}
```

**Response Error (400):**
```json
{
  "status": "error",
  "message": "لا يمكن استخدام النقاط في هذا الطلب"
}
```

**Response Error (422):**
```json
{
  "status": "error",
  "message": "بيانات غير صحيحة",
  "errors": {
    "points": ["عدد النقاط مطلوب"],
    "order_id": ["رقم الطلب مطلوب"]
  }
}
```

---

## 📊 4. أمثلة على الاستخدام (Code Examples)

### 4.1. JavaScript/React Example

```javascript
// تتبع الطلب
async function trackOrder(orderId) {
  try {
    const response = await fetch(`/api/v2/orders/${orderId}/track`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });
    
    const data = await response.json();
    
    if (data.status) {
      // عرض Timeline
      data.data.timeline.forEach(step => {
        console.log(`${step.title}: ${step.date}`);
      });
      
      // عرض معلومات السائق
      if (data.data.driver) {
        console.log(`السائق: ${data.data.driver.name}`);
        console.log(`الهاتف: ${data.data.driver.phone}`);
      }
    }
  } catch (error) {
    console.error('Error:', error);
  }
}

// تأكيد الاستلام
async function confirmReceipt(orderId) {
  try {
    const response = await fetch(`/api/v2/orders/${orderId}/confirm-receipt`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });
    
    const data = await response.json();
    
    if (data.status) {
      alert('تم تأكيد الاستلام بنجاح');
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}

// إنشاء طلب إرجاع
async function createReturn(orderId, orderItemId, type, reason, images) {
  const formData = new FormData();
  formData.append('order_id', orderId);
  if (orderItemId) formData.append('order_item_id', orderItemId);
  formData.append('type', type);
  formData.append('reason', reason);
  
  images.forEach(image => {
    formData.append('images[]', image);
  });
  
  try {
    const response = await fetch('/api/v2/returns', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      body: formData
    });
    
    const data = await response.json();
    
    if (data.status) {
      alert('تم إنشاء طلب الإرجاع بنجاح');
      return data.data;
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}

// الحصول على نقاط الولاء
async function getLoyaltyPoints() {
  try {
    const response = await fetch('/api/v2/loyalty/points', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });
    
    const data = await response.json();
    
    if (data.status === 'success') {
      console.log(`النقاط المتاحة: ${data.data.available_points}`);
      console.log(`إجمالي النقاط: ${data.data.total_points}`);
      return data.data;
    }
  } catch (error) {
    console.error('Error:', error);
  }
}

// استخدام نقاط الولاء
async function useLoyaltyPoints(orderId, points) {
  try {
    const response = await fetch('/api/v2/loyalty/use', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        order_id: orderId,
        points: points
      })
    });
    
    const data = await response.json();
    
    if (data.status === 'success') {
      alert(`تم استخدام ${points} نقطة بنجاح`);
      console.log(`قيمة الخصم: ${data.data.points_value} ريال`);
      console.log(`إجمالي الطلب بعد الخصم: ${data.data.order_total} ريال`);
      return data.data;
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}
```

---

### 4.2. React Native Example

```javascript
import axios from 'axios';

const API_BASE_URL = 'https://your-api-domain.com/api/v2';

// إعداد axios
const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
});

// إضافة token للطلبات
apiClient.interceptors.request.use(config => {
  const token = AsyncStorage.getItem('access_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// تتبع الطلب
export const trackOrder = async (orderId) => {
  try {
    const response = await apiClient.get(`/orders/${orderId}/track`);
    return response.data;
  } catch (error) {
    throw error.response?.data || error;
  }
};

// تأكيد الاستلام
export const confirmReceipt = async (orderId) => {
  try {
    const response = await apiClient.post(`/orders/${orderId}/confirm-receipt`);
    return response.data;
  } catch (error) {
    throw error.response?.data || error;
  }
};

// إنشاء طلب إرجاع
export const createReturn = async (returnData) => {
  const formData = new FormData();
  formData.append('order_id', returnData.order_id);
  if (returnData.order_item_id) {
    formData.append('order_item_id', returnData.order_item_id);
  }
  formData.append('type', returnData.type);
  formData.append('reason', returnData.reason);
  if (returnData.customer_notes) {
    formData.append('customer_notes', returnData.customer_notes);
  }
  
  if (returnData.images && returnData.images.length > 0) {
    returnData.images.forEach((image, index) => {
      formData.append('images[]', {
        uri: image.uri,
        type: 'image/jpeg',
        name: `image_${index}.jpg`
      });
    });
  }
  
  try {
    const response = await apiClient.post('/returns', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    return response.data;
  } catch (error) {
    throw error.response?.data || error;
  }
};

// الحصول على نقاط الولاء
export const getLoyaltyPoints = async () => {
  try {
    const response = await apiClient.get('/loyalty/points');
    return response.data;
  } catch (error) {
    throw error.response?.data || error;
  }
};

// استخدام نقاط الولاء
export const useLoyaltyPoints = async (orderId, points) => {
  try {
    const response = await apiClient.post('/loyalty/use', {
      order_id: orderId,
      points: points
    });
    return response.data;
  } catch (error) {
    throw error.response?.data || error;
  }
};
```

---

## 🔍 5. حالات الأخطاء الشائعة (Common Error Codes)

| HTTP Code | المعنى | الحل |
|-----------|--------|------|
| `200` | نجاح | الطلب تم بنجاح |
| `201` | تم الإنشاء | تم إنشاء المورد بنجاح |
| `400` | طلب خاطئ | تحقق من البيانات المرسلة |
| `401` | غير مصرح | تأكد من إرسال token صحيح |
| `403` | ممنوع | ليس لديك صلاحية |
| `404` | غير موجود | المورد المطلوب غير موجود |
| `422` | خطأ في التحقق | تحقق من صحة البيانات |
| `500` | خطأ في الخادم | تواصل مع الدعم الفني |

---

## 📝 6. ملاحظات مهمة

### 6.1. تتبع الطلبات
- يمكن تتبع الطلبات فقط بعد تعيين سائق
- Timeline يعرض جميع المراحل التي مر بها الطلب
- حالة `delivered` تعني أن السائق سلم الطلب، لكن يحتاج تأكيد من العميل

### 6.2. المرتجعات
- يمكن إرجاع الطلبات بحالة `completed` أو `shipped` فقط
- يمكن إرجاع عنصر محدد أو كل الطلب
- الصور اختيارية لكن مفضلة لإثبات العيب
- لا يمكن إنشاء أكثر من طلب إرجاع معلق لنفس العنصر

### 6.3. نقاط الولاء
- كل نقطة = 0.01 ريال (100 نقطة = 1 ريال)
- النقاط تنتهي صلاحيتها بعد سنة من الحصول عليها
- يمكن استخدام النقاط فقط في الطلبات بحالة `pending`
- النقاط تُضاف تلقائياً عند إتمام الطلب (1 نقطة لكل ريال)

---

## 🚀 7. Base URL

```
Production: https://your-domain.com/api/v2
Development: http://localhost:8000/api/v2
```

---

## 📞 8. الدعم الفني

للمساعدة أو الاستفسارات:
- Email: support@example.com
- Phone: +966500000000

---

**آخر تحديث:** 2025-11-17  
**الإصدار:** 1.0

