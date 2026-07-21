<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به سیستم | <?php echo $clubName; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tahoma', 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, <?php echo $primaryColor; ?> 0%, <?php echo adjustColor($primaryColor, 40); ?> 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, <?php echo $primaryColor; ?> 0%, <?php echo adjustColor($primaryColor, 20); ?> 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 24px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .login-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: <?php echo $primaryColor; ?>;
            box-shadow: 0 0 0 3px <?php echo adjustColorOpacity($primaryColor, 0.2); ?>;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, <?php echo $primaryColor; ?> 0%, <?php echo adjustColor($primaryColor, 20); ?> 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px <?php echo adjustColorOpacity($primaryColor, 0.4); ?>;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        .login-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            margin-top: 20px;
        }
        
        .login-footer a {
            color: <?php echo $primaryColor; ?>;
            text-decoration: none;
            font-size: 14px;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon input {
            padding-right: 44px;
        }
        
        .input-icon::before {
            content: '';
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            opacity: 0.5;
        }
        
        .input-icon.user::before {
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'/%3E%3C/svg%3E") no-repeat center;
        }
        
        .input-icon.lock::before {
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'/%3E%3C/svg%3E") no-repeat center;
        }
        
        @media (max-width: 480px) {
            .login-container {
                border-radius: 12px;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .login-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><?php echo $clubName; ?></h1>
            <p>سیستم مدیریت باشگاه ورزشی</p>
        </div>
        
        <div class="login-body">
            <?php $flash = getFlashMessage(); if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?>">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/auth/login">
                <div class="form-group input-icon user">
                    <label for="username">نام کاربری یا کد ملی</label>
                    <input type="text" id="username" name="username" required placeholder="مثال: 1234567890" autocomplete="username">
                </div>
                
                <div class="form-group input-icon lock">
                    <label for="password">رمز عبور</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" autocomplete="current-password">
                </div>
                
                <button type="submit" class="btn-login">
                    ورود به حساب کاربری
                </button>
            </form>
            
            <div class="login-footer">
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">
                    هنوز ثبت‌نام نکرده‌اید؟
                    <a href="/auth/register">ثبت‌نام عضو جدید</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
/**
 * تابع کمکی برای تیره کردن رنگ
 */
function adjustColor($hex, $percent) {
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = max(0, min(255, $r * (100 - $percent) / 100));
    $g = max(0, min(255, $g * (100 - $percent) / 100));
    $b = max(0, min(255, $b * (100 - $percent) / 100));
    
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

/**
 * تابع کمکی برای شفاف کردن رنگ
 */
function adjustColorOpacity($hex, $opacity) {
    return "rgba(" . hexdec(substr($hex, 1, 2)) . ", " . hexdec(substr($hex, 3, 2)) . ", " . hexdec(substr($hex, 5, 2)) . ", $opacity)";
}
?>
