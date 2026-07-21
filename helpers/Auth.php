<?php
/**
 * توابع کمکی احراز هویت
 * سیستم مدیریت باشگاه ورزشی
 */

session_start();

/**
 * بررسی ورود کاربر
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * دریافت اطلاعات کاربر فعلی
 * @return array|null
 */
function auth() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return $_SESSION['user'] ?? null;
}

/**
 * بررسی نقش کاربر
 * @param string|array $roles نام نقش یا آرایه‌ای از نقش‌ها
 * @return bool
 */
function hasRole($roles) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $userRoles = $_SESSION['user_roles'] ?? [];
    
    if (is_string($roles)) {
        return in_array($roles, $userRoles);
    }
    
    if (is_array($roles)) {
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }
        return false;
    }
    
    return false;
}

/**
 * بررسی اینکه کاربر دارای تمام نقش‌های مشخص شده باشد
 * @param array $roles آرایه‌ای از نقش‌ها
 * @return bool
 */
function hasAllRoles($roles) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $userRoles = $_SESSION['user_roles'] ?? [];
    
    foreach ($roles as $role) {
        if (!in_array($role, $userRoles)) {
            return false;
        }
    }
    
    return true;
}

/**
 * ورود کاربر به سیستم
 * @param array $user اطلاعات کاربر
 * @param array $roles نقش‌های کاربر
 */
function login($user, $roles = []) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user'] = $user;
    $_SESSION['user_roles'] = $roles;
    $_SESSION['login_time'] = time();
}

/**
 * خروج کاربر از سیستم
 */
function logout() {
    session_unset();
    session_destroy();
}

/**
 * هدایت کاربر به صفحه ورود اگر وارد نشده باشد
 */
function requireAuth() {
    if (!isLoggedIn()) {
        redirect('/auth/login');
    }
}

/**
 * هدایت کاربر بر اساس نقش
 */
function redirectByRole() {
    if (!isLoggedIn()) {
        redirect('/auth/login');
    }
    
    if (hasRole(['admin', 'manager', 'receptionist', 'accountant'])) {
        redirect('/admin/dashboard');
    } elseif (hasRole('coach')) {
        redirect('/coach/dashboard');
    } elseif (hasRole('member')) {
        redirect('/portal/dashboard');
    } else {
        redirect('/auth/login');
    }
}

/**
 * بررسی دسترسی به صفحه
 * @param array $allowedRoles نقش‌های مجاز
 */
function requireRole($allowedRoles) {
    requireAuth();
    
    if (!hasRole($allowedRoles)) {
        http_response_code(403);
        die('<div style="text-align: center; padding: 50px; font-family: Tahoma, Arial;">
            <h1 style="color: #dc2626;">دسترسی غیرمجاز</h1>
            <p style="color: #666; margin-top: 20px;">شما اجازه دسترسی به این صفحه را ندارید.</p>
            <a href="/index.php" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #1e40af; color: white; text-decoration: none; border-radius: 8px;">بازگشت به صفحه اصلی</a>
        </div>');
    }
}

/**
 * ذخیره پیام در Session
 * @param string $type نوع پیام (success, error, warning, info)
 * @param string $message متن پیام
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * دریافت و حذف پیام از Session
 * @return array|null
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * تولید توکن امنیتی برای فرم‌ها
 * @return string
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * بررسی توکن CSRF
 * @param string $token
 * @return bool
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * پاکسازی ورودی‌ها
 * @param string $data
 * @return string
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * اعتبارسنجی کد ملی
 * @param string $nationalCode
 * @return bool
 */
function validateNationalCode($nationalCode) {
    if (!preg_match('/^\d{10}$/', $nationalCode)) {
        return false;
    }
    
    $code = str_split($nationalCode);
    $sum = 0;
    
    for ($i = 0; $i < 9; $i++) {
        $sum += $code[$i] * (10 - $i);
    }
    
    $remainder = $sum % 11;
    $controlDigit = ($remainder < 2) ? $remainder : 11 - $remainder;
    
    return $controlDigit == $code[9];
}
