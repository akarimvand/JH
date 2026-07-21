<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تاریخچه حضور و غیاب - سیستم مدیریت باشگاه</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include APP_ROOT . '/views/layouts/coach.php'; ?>
    
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h1>تاریخچه حضور و غیاب</h1>
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
                    <h2>تاریخچه حضور و غیاب کلاس‌ها</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>نام کلاس</th>
                            <th>حاضرین</th>
                            <th>غایبین</th>
                            <th>با تاخیر</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>۱۴۰۳/۰۹/۲۴</td>
                            <td>بدنسازی مقدماتی</td>
                            <td>۱۰ نفر</td>
                            <td>۱ نفر</td>
                            <td>۱ نفر</td>
                            <td><span class="badge badge-success">ثبت شده</span></td>
                        </tr>
                        <tr>
                            <td>۱۴۰۳/۰۹/۲۳</td>
                            <td>یوگا</td>
                            <td>۷ نفر</td>
                            <td>۱ نفر</td>
                            <td>۰ نفر</td>
                            <td><span class="badge badge-success">ثبت شده</span></td>
                        </tr>
                        <tr>
                            <td>۱۴۰۳/۰۹/۲۲</td>
                            <td>پیلاتس</td>
                            <td>۹ نفر</td>
                            <td>۱ نفر</td>
                            <td>۰ نفر</td>
                            <td><span class="badge badge-success">ثبت شده</span></td>
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
