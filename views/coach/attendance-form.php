<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حضور و غیاب - سیستم مدیریت باشگاه</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include APP_ROOT . '/views/layouts/coach.php'; ?>
    
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h1>حضور و غیاب</h1>
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
                    <h2>ثبت حضور و غیاب کلاس</h2>
                </div>
                <form action="/coach/attendance/save" method="POST" class="form">
                    <div class="form-group">
                        <label>تاریخ:</label>
                        <input type="text" name="date" value="۱۴۰۳/۰۹/۲۵" class="form-control" readonly>
                    </div>
                    
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>نام و نام خانوادگی</th>
                                <th>وضعیت حضور</th>
                                <th>توضیحات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>علی محمدی</td>
                                <td>
                                    <label><input type="radio" name="attendance[1]" value="present" checked> حاضر</label>
                                    <label><input type="radio" name="attendance[1]" value="absent"> غایب</label>
                                    <label><input type="radio" name="attendance[1]" value="late"> با تاخیر</label>
                                </td>
                                <td><input type="text" name="note[1]" class="form-control" placeholder="توضیحات..."></td>
                            </tr>
                            <tr>
                                <td>رضا کریمی</td>
                                <td>
                                    <label><input type="radio" name="attendance[2]" value="present" checked> حاضر</label>
                                    <label><input type="radio" name="attendance[2]" value="absent"> غایب</label>
                                    <label><input type="radio" name="attendance[2]" value="late"> با تاخیر</label>
                                </td>
                                <td><input type="text" name="note[2]" class="form-control" placeholder="توضیحات..."></td>
                            </tr>
                            <tr>
                                <td>حسین احمدی</td>
                                <td>
                                    <label><input type="radio" name="attendance[3]" value="present" checked> حاضر</label>
                                    <label><input type="radio" name="attendance[3]" value="absent"> غایب</label>
                                    <label><input type="radio" name="attendance[3]" value="late"> با تاخیر</label>
                                </td>
                                <td><input type="text" name="note[3]" class="form-control" placeholder="توضیحات..."></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">ذخیره حضور و غیاب</button>
                        <a href="/coach/classes" class="btn btn-secondary">انصراف</a>
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
