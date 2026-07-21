<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد مربی - سیستم مدیریت باشگاه</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include APP_ROOT . '/views/layouts/coach.php'; ?>
    
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h1>داشبورد مربی</h1>
            </div>
            <div class="topbar-left">
                <span class="user-name"><?= auth()->user()['username'] ?? 'کاربر' ?></span>
                <form method="POST" action="/auth/logout" style="display: inline;">
                    <button type="submit" class="btn-logout">خروج</button>
                </form>
            </div>
        </header>
        
        <main class="content">
            <div class="dashboard-cards">
                <div class="card stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-info">
                        <h3>تعداد کلاس‌ها</h3>
                        <p class="stat-value">۵</p>
                    </div>
                </div>
                
                <div class="card stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-info">
                        <h3>تعداد هنرجویان</h3>
                        <p class="stat-value">۴۲</p>
                    </div>
                </div>
                
                <div class="card stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info">
                        <h3>حضور امروز</h3>
                        <p class="stat-value">۲۸</p>
                    </div>
                </div>
                
                <div class="card stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-info">
                        <h3>کلاس‌های امروز</h3>
                        <p class="stat-value">۳</p>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h2>کلاس‌های امروز</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>نام کلاس</th>
                            <th>ساعت</th>
                            <th>تعداد هنرجو</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>بدنسازی مقدماتی</td>
                            <td>۱۶:۰۰ - ۱۷:۳۰</td>
                            <td>۱۲ نفر</td>
                            <td><span class="badge badge-success">فعال</span></td>
                        </tr>
                        <tr>
                            <td>یوگا</td>
                            <td>۱۸:۰۰ - ۱۹:۰۰</td>
                            <td>۸ نفر</td>
                            <td><span class="badge badge-success">فعال</span></td>
                        </tr>
                        <tr>
                            <td>پیلاتس</td>
                            <td>۱۹:۳۰ - ۲۰:۳۰</td>
                            <td>۱۰ نفر</td>
                            <td><span class="badge badge-warning">در انتظار</span></td>
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
