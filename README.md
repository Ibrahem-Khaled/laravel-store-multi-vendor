# **✨ دليل إحترافي لتثبيت وتشغيل مشروع Laravel Multi-Vendor ✨**  
### *مقدم من المبرمج **إبراهيم خالد***  

---

## **🚀 الخطوة 1: استنساخ المشروع (Clone)**
```bash
git clone https://github.com/Ibrahem-Khaled/laravel-store-multi-vendor.git
cd laravel-store-multi-vendor
```

---

## **🔧 الخطوة 2: تثبيت dependencies باستخدام Composer**
```bash
composer install
```

---

## **⚙️ الخطوة 3: إعداد ملف البيئة (`.env`)**
1. انسخ ملف `.env.example`:
   ```bash
   cp .env.example .env
   ```
2. اضبط إعدادات قاعدة البيانات والبريد وغيرها:
   ```env
   DB_DATABASE=اسم_قاعدة_البيانات
   DB_USERNAME=اسم_المستخدم
   DB_PASSWORD=كلمة_المرور
   ```

---

## **🔑 الخطوة 4: إنشاء مفتاح التطبيق (APP_KEY)**
```bash
php artisan key:generate
```

---

## **📦 الخطوة 5: إنشاء جداول قاعدة البيانات وتعبئتها**
```bash
php artisan migrate --seed
```

---

## **🔗 الخطوة 6: إنشاء رابط تخزين (Storage Link)**
```bash
php artisan storage:link
```

---

## **🚀 الخطوة 7: تشغيل السيرفر المحلي**
```bash
php artisan serve
```
ثم افتح المتصفح على:  
👉 [http://127.0.0.1:8000](http://127.0.0.1:8000)  

---

## **🎉 تهانينا! المشروع يعمل الآن بنجاح 🎉**  
### **📌 معلومات الدخول الافتراضية (لوحة التحكم):**  
- **البريد الإداري:** `admin@example.com`  
- **كلمة المرور:** `password`  

---

### **📢 ملاحظات إضافية:**  
- يمكنك استخدام `npm install && npm run dev` لتجميع ملفات **CSS/JS**.  
- لمزيد من التفاصيل، راجع ملف **`README.md`** في المشروع.  

---

### **📌 تابعني للمزيد من المشاريع الرائعة!**  
🔗 GitHub: [Ibrahem-Khaled](https://github.com/Ibrahem-Khaled)  

🎨 **تم التنسيق بإبداع بواسطة إبراهيم خالد** 💻✨  

--- 

**#Laravel #MultiVendor #PHP #IbrahemKhaled** 🚀
