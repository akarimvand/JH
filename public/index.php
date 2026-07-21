<?php
/**
 * Wave Gym Management System
 * Entry Point - قابل اجرا در هر پوشه‌ای
 */

// شناسایی خودکار مسیر ریشه پروژه
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// بارگذاری فایل‌های حیاتی
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/helpers/Functions.php';
require_once ROOT_PATH . '/helpers/Auth.php';
require_once ROOT_PATH . '/helpers/PersianDate.php';

// شروع سشن اگر هنوز شروع نشده
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// تنظیم تایم‌زون پیش‌فرض
date_default_timezone_set('Asia/Tehran');

// دریافت آدرس درخواستی نسبت به ریشه عمومی
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH);

// محاسبه مسیر نسبی (Sub-path) اگر پروژه در زیرپوشه باشد
$subPath = str_replace($scriptName, '', $requestUri);
$subPath = trim($subPath, '/');

// مسیریابی ساده (Routing)
$route = empty($subPath) ? 'auth/login' : $subPath;
$routeParts = explode('/', $route);
$controllerName = ucfirst($routeParts[0]) . 'Controller';
$action = isset($routeParts[1]) ? $routeParts[1] : 'index';

// نگاشت کنترلرها
$controllers = [
    'AuthController' => 'AuthController',
    'AdminController' => 'AdminController',
    'MembersController' => 'MembersController',
    'ClassesController' => 'ClassesController',
    'CoachesController' => 'CoachesController',
    'CoachController' => 'CoachController',
    'PortalController' => 'PortalController',
    'SettingsController' => 'SettingsController',
    'PaymentsController' => 'PaymentsController',
    'AssignmentsController' => 'AssignmentsController',
];

// بررسی وجود کنترلر
if (array_key_exists($controllerName, $controllers)) {
    $controllerFile = ROOT_PATH . '/controllers/' . $controllers[$controllerName] . '.php';
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        
        $controller = new $controllerName();
        
        if (method_exists($controller, $action)) {
            // اجرای اکشن مورد نظر
            $controller->$action();
        } else {
            // اکشن پیدا نشد
            http_response_code(404);
            echo "خطا: متد $action در کنترلر $controllerName یافت نشد.";
        }
    } else {
        http_response_code(500);
        echo "خطا: فایل کنترلر $controllerFile یافت نشد.";
    }
} else {
    // مسیر نامعتبر - هدایت به صفحه ورود یا خطا
    if (empty($subPath)) {
        header('Location: ' . $scriptName . '/auth/login');
        exit;
    }
    http_response_code(404);
    echo "خطا: صفحه مورد نظر یافت نشد. ($route)";
}
