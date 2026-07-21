<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد عضو - سیستم مدیریت باشگاه</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include APP_ROOT . '/views/layouts/portal.php'; ?>
    
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h1>داشبورد من</h1>
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
                    <div class="stat-icon">📅</div>
                    <div class="stat-info">
                        <h3>وضعیت عضویت</h3>
                        <p class="stat-value">فعال</p>
                    </div>
                </div>
                
                <div class="card stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info">
                        <h3>حضور این ماه</h3>
                        <p class="stat-value">۱۲ روز</p>
                    </div>
                </div>
                
                <div class="card stat-card">
                    <div class="stat-icon">🏥</div>
                    <div class="stat-info">
                        <h3>وضعیت بیمه</h3>
                        <p class="stat-value">دارد</p>
                    </div>
                </div>
                
                <div class="card stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <h3>بدهی</h3>
                        <p class="stat-value">۰ تومان</p>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h2>کلاس‌های شما</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>نام کلاس</th>
                            <th>مربی</th>
                            <th>روزهای برگزاری</th>
                            <th>ساعت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>بدنسازی مقدماتی</td>
                            <td>علی رضایی</td>
                            <td>شنبه، دوشنبه، چهارشنبه</td>
                            <td>۱۶:۰۰ - ۱۷:۳۰</td>
                        </tr>
                        <tr>
                            <td>یوگا</td>
                            <td>مریم احمدی</td>
                            <td>یکشنبه، سه‌شنبه</td>
                            <td>۱۸:۰۰ - ۱۹:۰۰</td>
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
