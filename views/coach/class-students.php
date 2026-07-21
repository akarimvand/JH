<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>هنرجویان کلاس - سیستم مدیریت باشگاه</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include APP_ROOT . '/views/layouts/coach.php'; ?>
    
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h1>هنرجویان کلاس</h1>
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
                    <h2>لیست هنرجویان</h2>
                    <a href="/coach/attendance-form?class_id=1" class="btn btn-primary">ثبت حضور و غیاب</a>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>نام و نام خانوادگی</th>
                            <th>کد ملی</th>
                            <th>شماره تماس</th>
                            <th>تاریخ عضویت</th>
                            <th>وضعیت اشتراک</th>
                            <th>وضعیت بیمه</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>علی محمدی</td>
                            <td>۱۲۳۴۵۶۷۸۹۰</td>
                            <td>۰۹۱۲۳۴۵۶۷۸۹</td>
                            <td>۱۴۰۳/۰۱/۱۵</td>
                            <td><span class="badge badge-success">فعال</span></td>
                            <td><span class="badge badge-success">دارد</span></td>
                        </tr>
                        <tr>
                            <td>رضا کریمی</td>
                            <td>۰۹۸۷۶۵۴۳۲۱</td>
                            <td>۰۹۱۹۸۷۶۵۴۳۲</td>
                            <td>۱۴۰۳/۰۲/۲۰</td>
                            <td><span class="badge badge-warning">در حال اتمام</span></td>
                            <td><span class="badge badge-danger">ندارد</span></td>
                        </tr>
                        <tr>
                            <td>حسین احمدی</td>
                            <td>۱۱۲۲۳۳۴۴۵۵</td>
                            <td>۰۹۱۱۲۲۳۳۴۴۵</td>
                            <td>۱۴۰۳/۰۳/۱۰</td>
                            <td><span class="badge badge-success">فعال</span></td>
                            <td><span class="badge badge-success">دارد</span></td>
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
