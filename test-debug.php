<?php
/**
 * فایل تست عیب‌یابی پروژه Wave3
 * این فایل تمام تنظیمات سرور، مسیرها و دسترسی‌ها را بررسی می‌کند
 */

// نمایش خطاها
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تست عیب‌یابی Wave3</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; background: #f5f5f5; padding: 20px; direction: rtl; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #1e40af; border-bottom: 3px solid #1e40af; padding-bottom: 10px; }
        h2 { color: #374151; margin-top: 30px; border-right: 4px solid #1e40af; padding-right: 10px; }
        .status { padding: 10px 15px; margin: 10px 0; border-radius: 6px; }
        .success { background: #d1fae5; color: #065f46; border: 1px solid #059669; }
        .warning { background: #fef3c7; color: #92400e; border: 1px solid #d97706; }
        .error { background: #fee2e2; color: #991b1b; border: 1px solid #dc2626; }
        .info { background: #dbeafe; color: #1e40af; border: 1px solid #1e40af; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; color: #374151; }
        code { background: #1f2937; color: #10b981; padding: 2px 8px; border-radius: 4px; font-family: Consolas, monospace; }
        .check-list { list-style: none; padding: 0; }
        .check-list li { padding: 8px 0; border-bottom: 1px dashed #e5e7eb; }
        .check-list li:before { content: "✓"; color: #059669; font-weight: bold; margin-left: 10px; }
        .check-list li.fail:before { content: "✗"; color: #dc2626; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 گزارش عیب‌یابی پروژه Wave3</h1>
        <p class="info">این فایل تمام تنظیمات سرور، مسیرها و دسترسی‌های لازم برای اجرای پروژه را بررسی می‌کند.</p>

        <?php
        // 1. اطلاعات سرور
        echo "<h2>1️⃣ اطلاعات سرور و PHP</h2>";
        echo "<table>";
        echo "<tr><th>تنظیمات</th><th>مقدار</th></tr>";
        echo "<tr><td>نسخه PHP</td><td>" . phpversion() . "</td></tr>";
        echo "<tr><td>مسیر نصب PHP</td><td><code>" . PHP_BINARY . "</code></td></tr>";
        echo "<tr><td>سیستم عامل</td><td>" . PHP_OS . "</td></tr>";
        echo "<tr><td>مسیر فعلی اسکریپت</td><td><code>" . __FILE__ . "</code></td></tr>";
        echo "<tr><td>دایرکتوری اصلی سند</td><td><code>" . $_SERVER['DOCUMENT_ROOT'] . "</code></td></tr>";
        echo "<tr><td>نام اسکریپت</td><td><code>" . ($_SERVER['SCRIPT_NAME'] ?? 'تعریف نشده') . "</code></td></tr>";
        echo "<tr><td>URI درخواستی</td><td><code>" . ($_SERVER['REQUEST_URI'] ?? 'تعریف نشده') . "</code></td></tr>";
        echo "<tr><td>آدرس IP سرور</td><td><code>" . ($_SERVER['SERVER_ADDR'] ?? 'تعریف نشده') . "</code></td></tr>";
        echo "</table>";

        // 2. بررسی مسیر پروژه
        echo "<h2>2️⃣ بررسی مسیر پروژه</h2>";
        $currentDir = dirname(__DIR__);
        $projectRoot = dirname(__DIR__);
        
        echo "<div class='status info'>";
        echo "<strong>مسیر ریشه پروژه:</strong> <code>" . $projectRoot . "</code><br>";
        echo "<strong>مسیر فایل تست:</strong> <code>" . __FILE__ . "</code>";
        echo "</div>";

        // بررسی وجود فایل‌های حیاتی
        $criticalFiles = [
            'index.php' => $projectRoot . '/public/index.php',
            'config/database.php' => $projectRoot . '/config/database.php',
            'helpers/Auth.php' => $projectRoot . '/helpers/Auth.php',
            'controllers/AuthController.php' => $projectRoot . '/controllers/AuthController.php',
            'views/auth/login.php' => $projectRoot . '/views/auth/login.php',
        ];

        echo "<ul class='check-list'>";
        foreach ($criticalFiles as $name => $path) {
            $exists = file_exists($path);
            $class = $exists ? '' : 'fail';
            $status = $exists ? 'موجود' : '<strong>یافت نشد!</strong>';
            echo "<li class='$class'>فایل <code>$name</code>: $status</li>";
        }
        echo "</ul>";

        // 3. بررسی دسترسی‌ها
        echo "<h2>3️⃣ بررسی دسترسی‌های فایل و پوشه</h2>";
        $dirsToCheck = [
            $projectRoot . '/public',
            $projectRoot . '/config',
            $projectRoot . '/controllers',
            $projectRoot . '/views',
            $projectRoot . '/helpers',
        ];

        echo "<ul class='check-list'>";
        foreach ($dirsToCheck as $dir) {
            $isReadable = is_readable($dir);
            $isWritable = is_writable($dir);
            $class = ($isReadable && $isWritable) ? '' : 'fail';
            $status = ($isReadable && $isWritable) ? 'قابل خواندن و نوشتن' : '<strong>مشکل دسترسی!</strong>';
            echo "<li class='$class'>پوشه <code>" . basename($dir) . "</code>: $status</li>";
        }
        echo "</ul>";

        // 4. بررسی پیکربندی دیتابیس
        echo "<h2>4️⃣ بررسی تنظیمات دیتابیس</h2>";
        $dbConfigFile = $projectRoot . '/config/database.php';
        if (file_exists($dbConfigFile)) {
            echo "<div class='status success'>فایل config/database.php یافت شد.</div>";
            
            // تلاش برای لود کردن تنظیمات
            try {
                require_once $dbConfigFile;
                if (isset($db_host) && isset($db_name) && isset($db_user)) {
                    echo "<div class='status info'>";
                    echo "<strong>تنظیمات دیتابیس:</strong><br>";
                    echo "هاست: <code>$db_host</code><br>";
                    echo "نام دیتابیس: <code>$db_name</code><br>";
                    echo "کاربر: <code>$db_user</code><br>";
                    echo "</div>";
                    
                    // تست اتصال
                    try {
                        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
                        $pdo = new PDO($dsn, $db_user, $db_pass);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        echo "<div class='status success'>✅ اتصال به دیتابیس با موفقیت برقرار شد!</div>";
                    } catch (PDOException $e) {
                        echo "<div class='status error'>❌ خطا در اتصال به دیتابیس: " . $e->getMessage() . "</div>";
                    }
                } else {
                    echo "<div class='status warning'>⚠️ متغیرهای دیتابیس در فایل config تعریف نشده‌اند.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='status error'>❌ خطا در بارگذاری فایل دیتابیس: " . $e->getMessage() . "</div>";
            }
        } else {
            echo "<div class='status error'>❌ فایل config/database.php یافت نشد!</div>";
        }

        // 5. بررسی Rewrite Rules
        echo "<h2>5️⃣ بررسی ماژول‌های آپاچی</h2>";
        $loadedModules = apache_get_modules();
        $modRewrite = in_array('mod_rewrite', $loadedModules);
        
        if ($modRewrite) {
            echo "<div class='status success'>✅ ماژول mod_rewrite فعال است.</div>";
        } else {
            echo "<div class='status warning'>⚠️ ماژول mod_rewrite فعال نیست. ممکن است نیاز به فعال‌سازی در httpd.conf داشته باشید.</div>";
        }

        // بررسی وجود فایل .htaccess
        $htaccessPath = $projectRoot . '/public/.htaccess';
        if (file_exists($htaccessPath)) {
            echo "<div class='status success'>✅ فایل public/.htaccess یافت شد.</div>";
            $htaccessContent = file_get_contents($htaccessPath);
            if (strpos($htaccessContent, 'RewriteEngine On') !== false) {
                echo "<div class='status info'>دستور RewriteEngine در .htaccess فعال است.</div>";
            }
        } else {
            echo "<div class='status error'>❌ فایل public/.htaccess یافت نشد!</div>";
        }

        // 6. لینک‌های تست
        echo "<h2>6️⃣ لینک‌های سریع برای تست</h2>";
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $baseProjectUrl = $baseUrl . $scriptDir;
        
        echo "<div class='status info'>";
        echo "<strong>آدرس پایه پروژه:</strong> <code>$baseProjectUrl</code><br><br>";
        echo "<a href='" . $baseProjectUrl . "/public/index.php' target='_blank' style='display:inline-block;padding:10px 20px;background:#1e40af;color:white;text-decoration:none;border-radius:6px;margin:5px;'>🏠 رفتن به صفحه اصلی</a> ";
        echo "<a href='" . $baseProjectUrl . "/public/auth/login' target='_blank' style='display:inline-block;padding:10px 20px;background:#059669;color:white;text-decoration:none;border-radius:6px;margin:5px;'>🔐 رفتن به صفحه ورود</a> ";
        echo "<a href='" . $baseProjectUrl . "/public/admin/dashboard' target='_blank' style='display:inline-block;padding:10px 20px;background:#dc2626;color:white;text-decoration:none;border-radius:6px;margin:5px;'>📊 داشبورد ادمین</a>";
        echo "</div>";

        // 7. نتیجه‌گیری
        echo "<h2>7️⃣ نتیجه‌گیری و راه‌حل‌ها</h2>";
        echo "<div class='status info'>";
        echo "<strong>اگر همه چیز سبز است:</strong><br>";
        echo "1. مطمئن شوید آدرس را درست وارد می‌کنید (مثلاً http://localhost/wave3/public/index.php)<br>";
        echo "2. اگر از XAMPP استفاده می‌کنید، سرویس‌های Apache و MySQL را ری‌استارت کنید.<br>";
        echo "3. کش مرورگر را پاک کنید (Ctrl+Shift+Delete).<br><br>";
        
        echo "<strong>اگر خطایی مشاهده شد:</strong><br>";
        echo "- اگر فایل‌ها یافت نشدند: مطمئن شوید تمام فایل‌ها را در پوشه صحیح کپی کرده‌اید.<br>";
        echo "- اگر مشکل دیتابیس دارید: فایل config/database.php را بررسی و دیتابیس را ایمپورت کنید.<br>";
        echo "- اگر mod_rewrite فعال نیست: در httpd.conf خط LoadModule rewrite_module modules/mod_rewrite.so را از کامنت خارج کنید.<br>";
        echo "</div>";
        ?>
    </div>
</body>
</html>
