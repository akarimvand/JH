<?php
/**
 * توابع کمکی عمومی
 * سیستم مدیریت باشگاه ورزشی
 */

/**
 * هدایت به آدرس مشخص
 * @param string $url
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * بازگشت پاسخ JSON
 * @param mixed $data
 * @param int $statusCode
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * دریافت مقدار از POST با پاکسازی
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function post($key, $default = null) {
    if (!isset($_POST[$key])) {
        return $default;
    }
    
    return sanitize($_POST[$key]);
}

/**
 * دریافت مقدار از GET با پاکسازی
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function get($key, $default = null) {
    if (!isset($_GET[$key])) {
        return $default;
    }
    
    return sanitize($_GET[$key]);
}

/**
 * بررسی درخواست AJAX
 * @return bool
 */
function isAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * آپلود فایل
 * @param array $file اطلاعات فایل از $_FILES
 * @param string $uploadDir مسیر پوشه آپلود
 * @param array $allowedTypes انواع مجاز فایل
 * @param int $maxSize حداکثر حجم به بایت
 * @return array ['success' => bool, 'path' => string|null, 'error' => string|null]
 */
function uploadFile($file, $uploadDir, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'], $maxSize = 2097152) {
    $result = [
        'success' => false,
        'path' => null,
        'error' => null
    ];
    
    // بررسی خطای آپلود
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $result['error'] = 'خطا در آپلود فایل';
        return $result;
    }
    
    // بررسی حجم فایل
    if ($file['size'] > $maxSize) {
        $result['error'] = 'حجم فایل بیش از حد مجاز است';
        return $result;
    }
    
    // بررسی نوع فایل
    if (!in_array($file['type'], $allowedTypes)) {
        $result['error'] = 'نوع فایل مجاز نیست';
        return $result;
    }
    
    // ایجاد پوشه در صورت عدم وجود
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // تولید نام منحصر به فرد برای فایل
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . '/' . $filename;
    
    // انتقال فایل
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $result['success'] = true;
        $result['path'] = $filepath;
    } else {
        $result['error'] = 'خطا در ذخیره فایل';
    }
    
    return $result;
}

/**
 * حذف فایل
 * @param string $filepath
 * @return bool
 */
function deleteFile($filepath) {
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * دریافت تنظیمات باشگاه
 * @return array
 */
function getClubSettings() {
    static $settings = null;
    
    if ($settings === null) {
        try {
            $db = db();
            $stmt = $db->query("SELECT * FROM settings LIMIT 1");
            $settings = $stmt->fetch() ?: [];
        } catch (Exception $e) {
            $settings = [
                'club_name' => 'باشگاه ورزشی',
                'primary_color' => '#1e40af',
                'secondary_color' => '#3b82f6'
            ];
        }
    }
    
    return $settings;
}

/**
 * دریافت مقدار یک تنظیم خاص
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function getSetting($key, $default = null) {
    $settings = getClubSettings();
    return $settings[$key] ?? $default;
}

/**
 * ثبت لاگ
 * @param string $message
 * @param string $level
 */
function logMessage($message, $level = 'info') {
    $logFile = __DIR__ . '/../logs/app.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $shamsiDate = toShamsi($timestamp);
    $logEntry = "[$shamsi - $level] $message\n";
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

/**
 * ارسال ایمیل (ساده - قابل توسعه با PHPMailer)
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return bool
 */
function sendEmail($to, $subject, $message) {
    $headers = [
        'From: no-reply@gym.com',
        'Reply-To: no-reply@gym.com',
        'X-Mailer: PHP/' . phpversion(),
        'Content-Type: text/html; charset=UTF-8'
    ];
    
    return mail($to, $subject, $message, implode("\r\n", $headers));
}

/**
 * ارسال پیامک (ساده - قابل توسعه با پنل‌های SMS)
 * @param string $phone
 * @param string $message
 * @return bool
 */
function sendSms($phone, $message) {
    // اینجا باید کد اتصال به پنل SMS قرار گیرد
    // فعلاً فقط لاگ می‌شود
    logMessage("SMS to $phone: $message", 'sms');
    return true;
}

/**
 * محاسبه سن از تاریخ تولد
 * @param string $birthDate
 * @return int
 */
function calculateAge($birthDate) {
    if (empty($birthDate)) {
        return 0;
    }
    
    $birth = new DateTime($birthDate);
    $today = new DateTime();
    $diff = $today->diff($birth);
    
    return $diff->y;
}

/**
 * بررسی اینکه تاریخ بین دو تاریخ دیگر باشد
 * @param string $date تاریخ مورد بررسی
 * @param string $startDate تاریخ شروع
 * @param string $endDate تاریخ پایان
 * @return bool
 */
function isDateBetween($date, $startDate, $endDate) {
    $timestamp = strtotime($date);
    return $timestamp >= strtotime($startDate) && $timestamp <= strtotime($endDate);
}

/**
 * تبدیل وضعیت به متن فارسی
 * @param string $status
 * @param string $type نوع وضعیت (membership, insurance, payment, etc.)
 * @return string
 */
function statusToPersian($status, $type = 'general') {
    $translations = [
        'active' => 'فعال',
        'expired' => 'منقضی شده',
        'pending' => 'در انتظار',
        'suspended' => 'معلق',
        'cancelled' => 'لغو شده',
        'approved' => 'تایید شده',
        'rejected' => 'رد شده',
        'pending_approval' => 'در انتظار تایید',
        'present' => 'حاضر',
        'absent' => 'غایب',
        'late' => 'با تاخیر',
        'excused' => 'موجه',
        'completed' => 'انجام شده',
        'failed' => 'ناموفق',
        'male' => 'مرد',
        'female' => 'زن'
    ];
    
    return $translations[$status] ?? $status;
}

/**
 * تولید رمز عبور تصادفی
 * @param int $length
 * @return string
 */
function generateRandomPassword($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $password = '';
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    
    return $password;
}

/**
 * هش کردن رمز عبور
 * @param string $password
 * @return string
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * بررسی صحت رمز عبور
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * محدود کردن متن
 * @param string $text
 * @param int $maxLength
 * @return string
 */
function truncate($text, $maxLength = 50) {
    if (mb_strlen($text) <= $maxLength) {
        return $text;
    }
    
    return mb_substr($text, 0, $maxLength) . '...';
}

/**
 * دریافت IP کاربر
 * @return string
 */
function getUserIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}

/**
 * بررسی ریسپانسیو بودن (موبایل)
 * @return bool
 */
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"] ?? '');
}
