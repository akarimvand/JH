<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اعلانات - سیستم مدیریت باشگاه</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include APP_ROOT . '/views/layouts/coach.php'; ?>
    
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h1>اعلانات</h1>
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
                    <h2>اعلانات و پیام‌ها</h2>
                </div>
                <div class="notifications-list">
                    <div class="notification-item notification-unread">
                        <div class="notification-icon">📢</div>
                        <div class="notification-content">
                            <h4>تغییر ساعت کلاس یوگا</h4>
                            <p>ساعت کلاس یوگا روز سه‌شنبه از ۱۸:۰۰ به ۱۹:۰۰ تغییر یافت.</p>
                            <span class="notification-date">۱۴۰۳/۰۹/۲۴ - ۱۰:۳۰</span>
                        </div>
                    </div>
                    <div class="notification-item notification-unread">
                        <div class="notification-icon">🎉</div>
                        <div class="notification-content">
                            <h4>عضویت جدید در کلاس بدنسازی</h4>
                            <p>آقای محمد حسینی به کلاس بدنسازی مقدماتی پیوست.</p>
                            <span class="notification-date">۱۴۰۳/۰۹/۲۳ - ۱۶:۴۵</span>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon">ℹ️</div>
                        <div class="notification-content">
                            <h4>بروزرسانی سیستم</h4>
                            <p>سیستم حضور و غیاب به‌روزرسانی شد. لطفاً از صحت اطلاعات اطمینان حاصل کنید.</p>
                            <span class="notification-date">۱۴۰۳/۰۹/۲۰ - ۰۹:۰۰</span>
                        </div>
                    </div>
                </div>
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
