# Currency Management API Documentation

## Overview
This API provides endpoints for managing currencies and exchange rates. It includes both public endpoints (for regular users) and admin-only endpoints.

**Base URL:** `/api/v2`

---

## Public Endpoints (No Authentication Required)

### 1. Get All Active Currencies
Get a list of all active currencies with their details.

**Endpoint:** `GET /api/v2/currencies`

**Authentication:** Not required

**Response:**
```json
{
  "success": true,
  "message": "تم جلب العملات بنجاح",
  "data": {
    "base_currency": {
      "code": "USD",
      "name_ar": "دولار أمريكي",
      "name_en": "US Dollar",
      "symbol": "$"
    },
    "currencies": [
      {
        "id": 1,
        "code": "USD",
        "name_ar": "دولار أمريكي",
        "name_en": "US Dollar",
        "symbol": "$",
        "symbol_ar": "دولار",
        "exchange_rate": 1.0,
        "is_base_currency": true
      },
      {
        "id": 2,
        "code": "YER_NEW",
        "name_ar": "ريال يمني جديد",
        "name_en": "Yemeni Riyal (New)",
        "symbol": "ر.ي",
        "symbol_ar": "ريال",
        "exchange_rate": 530.0,
        "is_base_currency": false
      }
    ],
    "total": 18
  }
}
```

**cURL Example:**
```bash
curl -X GET "https://your-domain.com/api/v2/currencies"
```

**JavaScript Example:**
```javascript
fetch('https://your-domain.com/api/v2/currencies')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Error:', error));
```

---

### 2. Get Currency by Code
Get details of a specific currency by its code.

**Endpoint:** `GET /api/v2/currencies/{code}`

**Authentication:** Not required

**Parameters:**
- `code` (path parameter): Currency code (e.g., USD, YER_NEW, SAR)

**Response:**
```json
{
  "success": true,
  "message": "تم جلب العملة بنجاح",
  "data": {
    "currency": {
      "id": 2,
      "code": "YER_NEW",
      "name_ar": "ريال يمني جديد",
      "name_en": "Yemeni Riyal (New)",
      "symbol": "ر.ي",
      "symbol_ar": "ريال",
      "exchange_rate": 530.0,
      "is_base_currency": false
    }
  }
}
```

**cURL Example:**
```bash
curl -X GET "https://your-domain.com/api/v2/currencies/YER_NEW"
```

**JavaScript Example:**
```javascript
fetch('https://your-domain.com/api/v2/currencies/YER_NEW')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Error:', error));
```

---

### 3. Convert Currency
Convert an amount from one currency to another.

**Endpoint:** `POST /api/v2/currencies/convert`

**Authentication:** Not required

**Request Body:**
```json
{
  "amount": 100,
  "from": "USD",
  "to": "YER_NEW"
}
```

**Parameters:**
- `amount` (required): The amount to convert (numeric, min: 0.01)
- `from` (required): Source currency code (must exist in currencies table)
- `to` (required): Target currency code (must exist in currencies table)

**Response:**
```json
{
  "success": true,
  "message": "تم تحويل العملة بنجاح",
  "data": {
    "from": {
      "code": "USD",
      "name_ar": "دولار أمريكي",
      "name_en": "US Dollar",
      "symbol": "$",
      "amount": 100,
      "exchange_rate": 1.0
    },
    "to": {
      "code": "YER_NEW",
      "name_ar": "ريال يمني جديد",
      "name_en": "Yemeni Riyal (New)",
      "symbol": "ر.ي",
      "amount": 53000,
      "exchange_rate": 530.0
    },
    "conversion_rate": 530.0
  }
}
```

**cURL Example:**
```bash
curl -X POST "https://your-domain.com/api/v2/currencies/convert" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 100,
    "from": "USD",
    "to": "YER_NEW"
  }'
```

**JavaScript Example:**
```javascript
fetch('https://your-domain.com/api/v2/currencies/convert', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    amount: 100,
    from: 'USD',
    to: 'YER_NEW'
  })
})
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Error:', error));
```

---

### 4. Get Exchange Rates
Get all exchange rates in a simple format.

**Endpoint:** `GET /api/v2/settings/exchange-rates`

**Authentication:** Not required

**Response:**
```json
{
  "success": true,
  "message": "تم جلب أسعار الصرف بنجاح",
  "data": {
    "base_currency": "USD",
    "rates": {
      "USD": 1.0,
      "YER_NEW": 530.0,
      "YER_OLD": 1200.0,
      "SAR": 3.75,
      "AED": 3.67
    },
    "last_updated": "2025-11-16T20:00:00Z"
  }
}
```

**cURL Example:**
```bash
curl -X GET "https://your-domain.com/api/v2/settings/exchange-rates"
```

**JavaScript Example:**
```javascript
fetch('https://your-domain.com/api/v2/settings/exchange-rates')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Error:', error));
```

---

## Admin Endpoints (Authentication Required)

All admin endpoints require authentication with a valid JWT token and admin role.

**Header Required:**
```
Authorization: Bearer {your_jwt_token}
```

### 1. Get All Currencies (Admin)
Get all currencies including inactive ones.

**Endpoint:** `GET /api/v2/admin/currencies`

**Authentication:** Required (Admin only)

**Query Parameters:**
- `per_page` (optional): Number of items per page (default: 50)

**Response:**
```json
{
  "success": true,
  "message": "تم جلب العملات بنجاح",
  "data": {
    "currencies": [...],
    "pagination": {
      "total": 18,
      "per_page": 50,
      "current_page": 1,
      "last_page": 1
    }
  }
}
```

---

### 2. Get Currency Details (Admin)
Get detailed information about a specific currency including history.

**Endpoint:** `GET /api/v2/admin/currencies/{id}`

**Authentication:** Required (Admin only)

**Response:**
```json
{
  "success": true,
  "message": "تم جلب العملة بنجاح",
  "data": {
    "currency": {
      "id": 2,
      "code": "YER_NEW",
      "name_ar": "ريال يمني جديد",
      "name_en": "Yemeni Riyal (New)",
      "symbol": "ر.ي",
      "symbol_ar": "ريال",
      "is_active": true,
      "is_base_currency": false,
      "exchange_rate": 530.0,
      "created_at": "2025-11-16T20:00:00Z",
      "updated_at": "2025-11-16T20:00:00Z",
      "history": [...]
    }
  }
}
```

---

### 3. Create Currency (Admin)
Create a new currency.

**Endpoint:** `POST /api/v2/admin/currencies`

**Authentication:** Required (Admin only)

**Request Body:**
```json
{
  "code": "EUR",
  "name_ar": "يورو",
  "name_en": "Euro",
  "symbol": "€",
  "symbol_ar": "يورو",
  "exchange_rate": 0.92,
  "is_active": true
}
```

**Parameters:**
- `code` (required): Currency code (uppercase, letters and underscores only, unique)
- `name_ar` (required): Arabic name (max 100 characters)
- `name_en` (required): English name (max 100 characters)
- `symbol` (required): Currency symbol (max 10 characters)
- `symbol_ar` (optional): Arabic symbol (max 20 characters)
- `exchange_rate` (required): Exchange rate against USD (numeric, min: 0.0001)
- `is_active` (optional): Whether currency is active (boolean, default: true)

---

### 4. Update Currency (Admin)
Update an existing currency.

**Endpoint:** `PUT /api/v2/admin/currencies/{id}`

**Authentication:** Required (Admin only)

**Request Body:**
```json
{
  "name_ar": "ريال يمني جديد",
  "name_en": "Yemeni Riyal (New)",
  "symbol": "ر.ي",
  "exchange_rate": 535.0,
  "is_active": true
}
```

---

### 5. Update Exchange Rate Only (Admin)
Update only the exchange rate and save history.

**Endpoint:** `PATCH /api/v2/admin/currencies/{id}/exchange-rate`

**Authentication:** Required (Admin only)

**Request Body:**
```json
{
  "exchange_rate": 535.0,
  "notes": "تحديث سعر الصرف حسب السوق"
}
```

**Parameters:**
- `exchange_rate` (required): New exchange rate (numeric, min: 0.0001)
- `notes` (optional): Notes about the update (max 500 characters)

---

### 6. Toggle Currency Status (Admin)
Activate or deactivate a currency.

**Endpoint:** `PATCH /api/v2/admin/currencies/{id}/toggle-status`

**Authentication:** Required (Admin only)

**Response:**
```json
{
  "success": true,
  "message": "تم تحديث حالة العملة بنجاح",
  "data": {
    "currency": {
      "id": 2,
      "code": "YER_NEW",
      "is_active": false,
      "updated_at": "2025-11-16T20:00:00Z"
    }
  }
}
```

---

### 7. Get Exchange Rate History (Admin)
Get the exchange rate change history for a currency.

**Endpoint:** `GET /api/v2/admin/currencies/{id}/exchange-rate-history`

**Authentication:** Required (Admin only)

**Query Parameters:**
- `per_page` (optional): Number of items per page (default: 20)
- `page` (optional): Page number (default: 1)

**Response:**
```json
{
  "success": true,
  "message": "تم جلب السجل بنجاح",
  "data": {
    "history": [
      {
        "id": 1,
        "currency_id": 2,
        "exchange_rate": 535.0,
        "previous_rate": 530.0,
        "change_percentage": 0.94,
        "updated_by": {
          "id": 1,
          "name": "Admin User",
          "email": "admin@example.com"
        },
        "notes": "تحديث سعر الصرف",
        "created_at": "2025-11-16T20:00:00Z"
      }
    ],
    "pagination": {
      "total": 10,
      "per_page": 20,
      "current_page": 1,
      "last_page": 1
    }
  }
}
```

---

### 8. Bulk Update Exchange Rates (Admin)
Update multiple exchange rates at once.

**Endpoint:** `POST /api/v2/admin/currencies/bulk-update-rates`

**Authentication:** Required (Admin only)

**Request Body:**
```json
{
  "rates": [
    {
      "currency_id": 2,
      "exchange_rate": 535.0,
      "notes": "تحديث الريال اليمني الجديد"
    },
    {
      "currency_id": 3,
      "exchange_rate": 1210.0,
      "notes": "تحديث الريال اليمني القديم"
    }
  ]
}
```

**Parameters:**
- `rates` (required): Array of rate updates
  - `currency_id` (required): Currency ID (must exist)
  - `exchange_rate` (required): New exchange rate (numeric, min: 0.0001)
  - `notes` (optional): Notes about the update (max 500 characters)

---

### 9. Delete Currency (Admin)
Delete a currency (cannot delete base currency).

**Endpoint:** `DELETE /api/v2/admin/currencies/{id}`

**Authentication:** Required (Admin only)

**Response:**
```json
{
  "success": true,
  "message": "تم حذف العملة بنجاح"
}
```

---

## Error Responses

All endpoints may return the following error responses:

### 400 Bad Request
```json
{
  "error": "يرجى إدخال معرف المستخدم وكلمة المرور"
}
```

### 401 Unauthorized
```json
{
  "error": "بيانات الاعتماد غير صحيحة"
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "ليس لديك صلاحية للوصول إلى هذا المورد",
  "errors": {
    "permission": ["يجب أن تكون مدير للوصول إلى هذه الصفحة"]
  }
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "العملة غير موجودة",
  "errors": {
    "currency": ["لا توجد عملة بهذا المعرف"]
  }
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "خطأ في التحقق من البيانات",
  "errors": {
    "exchange_rate": ["The exchange rate field is required."]
  }
}
```

### 500 Server Error
```json
{
  "success": false,
  "message": "حدث خطأ أثناء جلب العملات",
  "error": "Error message details"
}
```

---

## Available Currency Codes

The system includes the following currencies by default:

- **USD** - US Dollar (Base Currency)
- **YER_NEW** - Yemeni Riyal (New)
- **YER_OLD** - Yemeni Riyal (Old)
- **SAR** - Saudi Riyal
- **AED** - UAE Dirham
- **KWD** - Kuwaiti Dinar
- **BHD** - Bahraini Dinar
- **OMR** - Omani Rial
- **QAR** - Qatari Riyal
- **JOD** - Jordanian Dinar
- **EGP** - Egyptian Pound
- **IQD** - Iraqi Dinar
- **LBP** - Lebanese Pound
- **LYD** - Libyan Dinar
- **DZD** - Algerian Dinar
- **MAD** - Moroccan Dirham
- **TND** - Tunisian Dinar
- **SYP** - Syrian Pound

---

## Usage Examples

### React Native Example
```javascript
import axios from 'axios';

const API_BASE_URL = 'https://your-domain.com/api/v2';

// Get all currencies
const getCurrencies = async () => {
  try {
    const response = await axios.get(`${API_BASE_URL}/currencies`);
    return response.data;
  } catch (error) {
    console.error('Error fetching currencies:', error);
    throw error;
  }
};

// Convert currency
const convertCurrency = async (amount, from, to) => {
  try {
    const response = await axios.post(`${API_BASE_URL}/currencies/convert`, {
      amount,
      from,
      to
    });
    return response.data;
  } catch (error) {
    console.error('Error converting currency:', error);
    throw error;
  }
};

// Usage
getCurrencies().then(data => {
  console.log('Currencies:', data);
});

convertCurrency(100, 'USD', 'YER_NEW').then(data => {
  console.log('Converted:', data.data.to.amount);
});
```

### Flutter Example
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class CurrencyAPI {
  static const String baseUrl = 'https://your-domain.com/api/v2';

  static Future<Map<String, dynamic>> getCurrencies() async {
    final response = await http.get(Uri.parse('$baseUrl/currencies'));
    
    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to load currencies');
    }
  }

  static Future<Map<String, dynamic>> convertCurrency(
    double amount,
    String from,
    String to,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl/currencies/convert'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode({
        'amount': amount,
        'from': from,
        'to': to,
      }),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to convert currency');
    }
  }
}
```

---

## Notes

1. All exchange rates are relative to USD (base currency).
2. Currency codes are case-insensitive but stored in uppercase.
3. Only active currencies are returned in public endpoints.
4. Exchange rate history is automatically saved when rates are updated via admin endpoints.
5. The base currency cannot be deleted or deactivated.
6. All timestamps are in ISO 8601 format (UTC).

---

## Support

For issues or questions, please contact the development team.

