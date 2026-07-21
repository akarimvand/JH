<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کلاس‌های من - سیستم مدیریت باشگاه</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include APP_ROOT . '/views/layouts/coach.php'; ?>
    
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h1>کلاس‌های من</h1>
            </div>
            <div class="topbar-left">
                <span class="user-name"><?= auth()->user()['username'] ?? 'کاربر' ?></span>
                <form method="POST" action="/auth/logout" style="display: inline;">
                    <button type="submit" class="btn-logout">خروج</button>
                </form>
            </div>
        </header>
        
        <main class="content">
            <div class="card">
                <div class="card-header">
                    <h2>لیست کلاس‌ها</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>نام کلاس</th>
                            <th>روزهای برگزاری</th>
                            <th>ساعت</th>
                            <th>ظرفیت</th>
                            <th>تعداد فعلی</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>بدنسازی مقدماتی</td>
                            <td>شنبه، دوشنبه، چهارشنبه</td>
                            <td>۱۶:۰۰ - ۱۷:۳۰</td>
                            <td>۱۵ نفر</td>
                            <td>۱۲ نفر</td>
                            <td>
                                <a href="/coach/class-students?class_id=1" class="btn-sm btn-primary">مشاهده هنرجویان</a>
                            </td>
                        </tr>
                        <tr>
                            <td>یوگا</td>
                            <td>یکشنبه، سه‌شنبه</td>
                            <td>۱۸:۰۰ - ۱۹:۰۰</td>
                            <td>۱۲ نفر</td>
                            <td>۸ نفر</td>
                            <td>
                                <a href="/coach/class-students?class_id=2" class="btn-sm btn-primary">مشاهده هنرجویان</a>
                            </td>
                        </tr>
                        <tr>
                            <td>پیلاتس</td>
                            <td>دوشنبه، چهارشنبه</td>
                            <td>۱۹:۳۰ - ۲۰:۳۰</td>
                            <td>۱۴ نفر</td>
                            <td>۱۰ نفر</td>
                            <td>
                                <a href="/coach/class-students?class_id=3" class="btn-sm btn-primary">مشاهده هنرجویان</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <script src="/public/js/app.js"></script>
    <script>
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });
    </script>
</body>
</html>
