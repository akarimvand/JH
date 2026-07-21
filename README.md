# سیستم مدیریت باشگاه ورزشی 🏋️‍♂️

یک سیستم مدیریت باشگاه ورزشی کامل با PHP خالص، MySQL و طراحی ریسپانسیو فارسی.

## ویژگی‌ها ✨

### پنل مدیریت
- 📊 داشبورد با آمار و گزارش‌های لحظه‌ای
- 👥 مدیریت اعضا (تایید، رد، ویرایش، حذف)
- 📚 مدیریت کلاس‌ها و برنامه‌ریزی
- 🎯 مدیریت مربیان
- 💰 مدیریت پرداخت‌ها و امور مالی
- ⚙️ تنظیمات باشگاه (لوگو، رنگ‌بندی، اطلاعات)

### پنل اعضا (پرتال)
- 👤 پروفایل شخصی
- 📅 مشاهده برنامه کلاس‌ها
- ✅ حضور و غیاب
- 🛡️ بارگذاری مدارک بیمه
- 💳 تاریخچه پرداخت‌ها

### پنل مربی
- 📋 لیست کلاس‌ها
- 👥 لیست هنرجویان هر کلاس
- ✅ ثبت حضور و غیاب
- 📊 گزارش عملکرد

## ساختار پروژه 📁

```
/workspace
├── index.php                 # نقطه ورود اصلی
├── config/
│   ├── database.php         # تنظیمات دیتابیس
│   └── schema.sql           # ساختار جداول
├── controllers/
│   ├── AuthController.php   # احراز هویت
│   ├── AdminController.php  # پنل مدیریت
│   ├── MembersController.php # مدیریت اعضا
│   ├── ClassesController.php # مدیریت کلاس‌ها
│   └── SettingsController.php # تنظیمات
├── models/
│   └── Database.php         # اتصال به دیتابیس
├── views/
│   ├── layouts/main.php     # قالب اصلی
│   ├── auth/                # صفحات ورود/ثبت‌نام
│   ├── admin/               # پنل مدیریت
│   ├── members/             # مدیریت اعضا
│   ├── classes/             # مدیریت کلاس‌ها
│   └── portal/              # پنل اعضا
├── helpers/
│   ├── Auth.php             # توابع احراز هویت
│   ├── Functions.php        # توابع کمکی
│   └── PersianDate.php      # توابع تاریخ شمسی
└── public/
    ├── css/style.css        # استایل‌ها
    └── js/app.js            # اسکریپت‌ها
```

## نصب و راه‌اندازی 🚀

### پیش‌نیازها
- PHP 7.4 یا بالاتر
- MySQL 5.7 یا بالاتر
- Web Server (Apache/Nginx)

### مراحل نصب

1. کلون کردن پروژه:
```bash
git clone <repository-url>
cd gym-management
```

2. تنظیمات دیتابیس:
```bash
# ویرایش فایل config/database.php
# یا استفاده از متغیرهای محیطی:
export DB_HOST=localhost
export DB_DATABASE=gym_management
export DB_USERNAME=root
export DB_PASSWORD=your_password
```

3. ایمپورت دیتابیس:
```bash
mysql -u root -p gym_management < config/schema.sql
```

4. تنظیم مجوزها:
```bash
chmod -R 755 /workspace
chmod -R 777 /workspace/logs
chmod -R 777 /workspace/public/uploads
```

5. دسترسی به برنامه:
```
http://localhost/index.php
```

## نام کاربری پیش‌فرض 🔑

برای اولین ورود، می‌توانید از اطلاعات زیر استفاده کنید:
- نام کاربری/کد ملی: `1234567890`
- رمز عبور: نیاز به تنظیم در دیتابیس دارد

## امنیت 🔒

- هش کردن رمز عبور با password_hash
- محافظت CSRF در فرم‌ها
- اعتبارسنجی ورودی‌ها
- کنترل دسترسی بر اساس نقش‌ها
- جلوگیری از SQL Injection با PDO Prepared Statements

## تکنولوژی‌ها 🛠️

- **Backend**: PHP 7.4+ (Pure PHP, No Framework)
- **Database**: MySQL with PDO
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Design**: RTL, Responsive, Custom CSS Variables
- **Date**: Persian Calendar (Jalali)

## مجوز 📄

این پروژه برای اهداف آموزشی و تجاری قابل استفاده است.

## پشتیبانی 💬

برای گزارش مشکلات یا درخواست ویژگی‌های جدید، لطفاً issue ایجاد کنید.

---

**توسعه یافته با ❤️ برای جامعه فارسی‌زبان**
