<?php
/**
 * نقطه ورود اصلی برنامه
 * سیستم مدیریت باشگاه ورزشی - ورژن فارسی
 * تاریخ: ۱۴۰۳
 */

// تنظیمات اولیه
define('APP_ROOT', __DIR__);
define('APP_URL', '/');

// بارگذاری فایل‌های کمکی
require_once APP_ROOT . '/helpers/PersianDate.php';
require_once APP_ROOT . '/helpers/Auth.php';
require_once APP_ROOT . '/helpers/Functions.php';
require_once APP_ROOT . '/models/Database.php';

// تنظیمات خطا (در محیط تولید غیرفعال شود)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// شروع روتینگ ساده
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// حذف اسلش انتهایی
$request = rtrim($request, '/') ?: '/';

// لیست روت‌ها
$routes = [
    // صفحات عمومی
    ['GET', '/', 'home'],
    ['GET', '/index.php', 'home'],
    
    // احراز هویت
    ['GET', '/auth/login', 'AuthController@login'],
    ['POST', '/auth/login', 'AuthController@authenticate'],
    ['POST', '/auth/logout', 'AuthController@logout'],
    ['GET', '/auth/register', 'AuthController@register'],
    ['POST', '/auth/register', 'AuthController@store'],
    
    // پنل ادمین
    ['GET', '/admin/dashboard', 'AdminController@dashboard'],
    ['GET', '/admin/members', 'MembersController@index'],
    ['GET', '/admin/members/pending', 'MembersController@pending'],
    ['POST', '/admin/members/approve', 'MembersController@approve'],
    ['POST', '/admin/members/reject', 'MembersController@reject'],
    ['GET', '/admin/members/detail', 'MembersController@detail'],
    ['POST', '/admin/members/store', 'MembersController@store'],
    ['POST', '/admin/members/update', 'MembersController@update'],
    ['POST', '/admin/members/delete', 'MembersController@delete'],
    ['GET', '/admin/classes', 'ClassesController@index'],
    ['GET', '/admin/classes/create', 'ClassesController@create'],
    ['POST', '/admin/classes/store', 'ClassesController@store'],
    ['POST', '/admin/classes/update', 'ClassesController@update'],
    ['POST', '/admin/classes/delete', 'ClassesController@delete'],
    ['GET', '/admin/coaches', 'CoachesController@index'],
    ['GET', '/admin/coaches/create', 'CoachesController@create'],
    ['POST', '/admin/coaches/store', 'CoachesController@store'],
    ['POST', '/admin/coaches/update', 'CoachesController@update'],
    ['POST', '/admin/coaches/delete', 'CoachesController@delete'],
    ['GET', '/admin/payments', 'PaymentsController@index'],
    ['POST', '/admin/payments/store', 'PaymentsController@store'],
    ['GET', '/admin/settings', 'SettingsController@index'],
    ['POST', '/admin/settings/update', 'SettingsController@update'],
    ['GET', '/admin/assignments', 'AssignmentsController@index'],
    ['POST', '/admin/assignments/assign', 'AssignmentsController@assign'],
    ['POST', '/admin/assignments/remove', 'AssignmentsController@remove'],
    ['GET', '/admin/assignments/class-members', 'AssignmentsController@classMembers'],
    ['GET', '/admin/assignments/members', 'AssignmentsController@getMembers'],
    
    // پنل مربی
    ['GET', '/coach/dashboard', 'CoachController@dashboard'],
    ['GET', '/coach/classes', 'CoachController@classes'],
    ['GET', '/coach/class-students', 'CoachController@classStudents'],
    ['GET', '/coach/attendance', 'CoachController@attendance'],
    ['GET', '/coach/attendance-form', 'CoachController@attendanceForm'],
    ['POST', '/coach/attendance/save', 'CoachController@saveAttendance'],
    ['GET', '/coach/profile', 'CoachController@profile'],
    ['POST', '/coach/profile/update', 'CoachController@profileUpdate'],
    ['POST', '/coach/password/change', 'CoachController@changePassword'],
    ['GET', '/coach/notifications', 'CoachController@notifications'],
    
    // پنل اعضا (پرتال)
    ['GET', '/portal/dashboard', 'PortalController@dashboard'],
    ['GET', '/portal/profile', 'PortalController@profile'],
    ['POST', '/portal/profile/update', 'PortalController@profileUpdate'],
    ['POST', '/portal/password/change', 'PortalController@changePassword'],
    ['GET', '/portal/attendance', 'PortalController@attendance'],
    ['GET', '/portal/membership', 'PortalController@membership'],
    ['GET', '/portal/insurance', 'PortalController@insurance'],
    ['POST', '/portal/insurance/upload', 'PortalController@uploadInsurance'],
    ['GET', '/portal/payments', 'PortalController@payments'],
    ['GET', '/portal/classes', 'PortalController@classes'],
];

/**
 * پیدا کردن روت مناسب و اجرای کنترلر
 */
function dispatch($routes, $method, $path) {
    foreach ($routes as $route) {
        list($routeMethod, $routePath, $action) = $route;
        
        if ($routeMethod === $method && $routePath === $path) {
            list($controller, $method) = explode('@', $action);
            
            $controllerFile = APP_ROOT . "/controllers/{$controller}.php";
            
            if (!file_exists($controllerFile)) {
                http_response_code(500);
                die("کنترلر {$controller} یافت نشد.");
            }
            
            require_once $controllerFile;
            
            if (!class_exists($controller)) {
                http_response_code(500);
                die("کلاس {$controller} یافت نشد.");
            }
            
            $instance = new $controller();
            
            if (!method_exists($instance, $method)) {
                http_response_code(500);
                die("متد {$method} در کلاس {$controller} یافت نشد.");
            }
            
            return $instance->$method();
        }
    }
    
    // روت یافت نشد - صفحه 404
    http_response_code(404);
    echo render404();
}

/**
 * نمایش صفحه 404
 */
function render404() {
    return '<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحه یافت نشد</title>
    <style>
        body { font-family: Tahoma, Arial; background: #f3f4f6; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .container { text-align: center; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #dc2626; font-size: 72px; margin: 0; }
        p { color: #666; margin: 20px 0; }
        a { display: inline-block; padding: 12px 24px; background: #1e40af; color: white; text-decoration: none; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <p>صفحه مورد نظر یافت نشد.</p>
        <a href="/index.php">بازگشت به صفحه اصلی</a>
    </div>
</body>
</html>';
}

/**
 * نمایش صفحه خانه
 */
function home() {
    if (isLoggedIn()) {
        redirectByRole();
    } else {
        redirect('/auth/login');
    }
}

// اجرای برنامه
dispatch($routes, $method, $request);
