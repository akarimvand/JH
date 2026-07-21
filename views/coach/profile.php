<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پروفایل مربی - سیستم مدیریت باشگاه</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include APP_ROOT . '/views/layouts/coach.php'; ?>
    
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h1>پروفایل مربی</h1>
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
                    <h2>اطلاعات شخصی</h2>
                </div>
                <form action="/coach/profile/update" method="POST" class="form">
                    <div class="form-group">
                        <label>نام و نام خانوادگی:</label>
                        <input type="text" name="full_name" value="علی رضایی" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>ایمیل:</label>
                        <input type="email" name="email" value="coach@example.com" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>شماره تماس:</label>
                        <input type="tel" name="phone" value="۰۹۱۲۳۴۵۶۷۸۹" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>تخصص:</label>
                        <input type="text" name="specialization" value="بدنسازی و تناسب اندام" class="form-control">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                    </div>
                </form>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2>تغییر رمز عبور</h2>
                </div>
                <form action="/coach/password/change" method="POST" class="form">
                    <div class="form-group">
                        <label>رمز عبور فعلی:</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>رمز عبور جدید:</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>تکرار رمز عبور جدید:</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">تغییر رمز عبور</button>
                    </div>
                </form>
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
