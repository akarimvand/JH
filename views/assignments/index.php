<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تخصیص اعضا - سیستم مدیریت باشگاه</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include APP_ROOT . '/views/layouts/main.php'; ?>
    
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h1>تخصیص اعضا به کلاس‌ها</h1>
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
                    <h2>کلاس‌ها</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>نام کلاس</th>
                            <th>مربی</th>
                            <th>ظرفیت</th>
                            <th>تعداد فعلی</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>بدنسازی مقدماتی</td>
                            <td>علی رضایی</td>
                            <td>۱۵ نفر</td>
                            <td>۱۲ نفر</td>
                            <td>
                                <button class="btn-sm btn-primary" onclick="openAssignModal(1)">مدیریت اعضا</button>
                            </td>
                        </tr>
                        <tr>
                            <td>یوگا</td>
                            <td>مریم احمدی</td>
                            <td>۱۲ نفر</td>
                            <td>۸ نفر</td>
                            <td>
                                <button class="btn-sm btn-primary" onclick="openAssignModal(2)">مدیریت اعضا</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <!-- Modal تخصیص عضو -->
    <div class="modal" id="assignModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>مدیریت اعضای کلاس</h3>
                <button class="modal-close" onclick="closeAssignModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>جستجوی عضو:</label>
                    <input type="text" id="memberSearch" class="form-control" placeholder="نام یا کد ملی را وارد کنید...">
                </div>
                <div id="searchResults" class="search-results"></div>
                
                <h4>اعضای فعلی کلاس</h4>
                <div id="currentMembers" class="members-list"></div>
            </div>
        </div>
    </div>
    
    <script src="/public/js/app.js"></script>
    <script>
        function openAssignModal(classId) {
            document.getElementById('assignModal').style.display = 'block';
        }
        
        function closeAssignModal() {
            document.getElementById('assignModal').style.display = 'none';
        }
        
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });
    </script>
</body>
</html>
