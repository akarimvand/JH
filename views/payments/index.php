<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرداخت‌ها - سیستم مدیریت باشگاه</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include APP_ROOT . '/views/layouts/main.php'; ?>
    
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <h1>مدیریت پرداخت‌ها</h1>
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
                    <h2>لیست پرداخت‌ها</h2>
                    <button class="btn btn-primary" onclick="openPaymentModal()">ثبت پرداخت جدید</button>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>نام عضو</th>
                            <th>مبلغ (تومان)</th>
                            <th>نوع پرداخت</th>
                            <th>تاریخ پرداخت</th>
                            <th>وضعیت</th>
                            <th>شماره پیگیری</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>علی محمدی</td>
                            <td>۵۰۰,۰۰۰</td>
                            <td>شهریه کلاس</td>
                            <td>۱۴۰۳/۰۹/۲۰</td>
                            <td><span class="badge badge-success">موفق</span></td>
                            <td>۱۲۳۴۵۶۷۸۹۰</td>
                        </tr>
                        <tr>
                            <td>رضا کریمی</td>
                            <td>۳۰۰,۰۰۰</td>
                            <td>تمدید اشتراک</td>
                            <td>۱۴۰۳/۰۹/۱۸</td>
                            <td><span class="badge badge-success">موفق</span></td>
                            <td>۰۹۸۷۶۵۴۳۲۱</td>
                        </tr>
                        <tr>
                            <td>حسین احمدی</td>
                            <td>۵۰۰,۰۰۰</td>
                            <td>شهریه کلاس</td>
                            <td>۱۴۰۳/۰۹/۱۵</td>
                            <td><span class="badge badge-warning">در انتظار</span></td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <!-- Modal ثبت پرداخت -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>ثبت پرداخت جدید</h3>
                <button class="modal-close" onclick="closePaymentModal()">×</button>
            </div>
            <div class="modal-body">
                <form class="form">
                    <div class="form-group">
                        <label>انتخاب عضو:</label>
                        <select class="form-control">
                            <option>علی محمدی</option>
                            <option>رضا کریمی</option>
                            <option>حسین احمدی</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>مبلغ (تومان):</label>
                        <input type="text" class="form-control" placeholder="مثال: ۵۰۰۰۰۰">
                    </div>
                    <div class="form-group">
                        <label>نوع پرداخت:</label>
                        <select class="form-control">
                            <option>شهریه کلاس</option>
                            <option>تمدید اشتراک</option>
                            <option>هزینه ثبت‌نام</option>
                            <option>سایر</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>توضیحات:</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">ثبت پرداخت</button>
                        <button type="button" class="btn btn-secondary" onclick="closePaymentModal()">انصراف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="/public/js/app.js"></script>
    <script>
        function openPaymentModal() {
            document.getElementById('paymentModal').style.display = 'block';
        }
        
        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }
        
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });
    </script>
</body>
</html>
