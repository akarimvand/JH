<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'پنل مدیریت'; ?> | <?php echo getSetting('club_name', 'باشگاه ورزشی'); ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        :root {
            --app-primary: <?php echo getSetting('primary_color', '#1e40af'); ?>;
            --app-primary-dark: <?php echo adjustColor(getSetting('primary_color', '#1e40af'), 20); ?>;
            --app-primary-light: <?php echo adjustColor(getSetting('primary_color', '#1e40af'), -20); ?>;
        }
        .sidebar {
            --sidebar-bg: <?php echo adjustColorOpacity(getSetting('primary_color', '#1e40af'), 0.95); ?>;
        }
    </style>
</head>
<body>
    <!-- سایدبار -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <?php 
            $logoPath = getSetting('logo_path');
            if ($logoPath && file_exists($logoPath)): 
            ?>
                <img src="/<?php echo $logoPath; ?>" alt="<?php echo getSetting('club_name', 'باشگاه ورزشی'); ?>" class="sidebar-brand-logo">
            <?php else: ?>
                <div class="sidebar-brand-icon">W</div>
            <?php endif; ?>
            <div class="sidebar-brand-text">
                <h1><?php echo getSetting('club_name', 'باشگاه ورزشی'); ?></h1>
                <p>سیستم مدیریت باشگاه</p>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <!-- داشبورد -->
            <a href="/admin/dashboard" class="nav-item <?php echo currentPageIs('/admin/dashboard') ? 'active' : ''; ?>">
                📊
                <span>داشبورد</span>
            </a>
            
            <!-- مدیریت اعضا -->
            <div class="sidebar-accordion <?php echo isActiveSection(['members']) ? 'open' : ''; ?>">
                <button class="sidebar-accordion-header">
                    <div class="header-content">
                        👥
                        <span>مدیریت اعضا</span>
                    </div>
                    <svg class="chevron" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M4 6l4 4 4-4H4z"/>
                    </svg>
                </button>
                <div class="sidebar-accordion-body">
                    <a href="/admin/members" class="nav-item <?php echo currentPageIs('/admin/members') ? 'active' : ''; ?>">اعضای فعال</a>
                    <a href="/admin/members/pending" class="nav-item <?php echo currentPageIs('/admin/members/pending') ? 'active' : ''; ?>">در انتظار تایید</a>
                </div>
            </div>
            
            <!-- برنامه‌ریزی -->
            <div class="sidebar-accordion <?php echo isActiveSection(['classes', 'coaches', 'assignments']) ? 'open' : ''; ?>">
                <button class="sidebar-accordion-header">
                    <div class="header-content">
                        📅
                        <span>برنامه‌ریزی</span>
                    </div>
                    <svg class="chevron" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M4 6l4 4 4-4H4z"/>
                    </svg>
                </button>
                <div class="sidebar-accordion-body">
                    <a href="/admin/classes" class="nav-item <?php echo currentPageIs('/admin/classes') ? 'active' : ''; ?>">کلاس‌ها</a>
                    <a href="/admin/coaches" class="nav-item <?php echo currentPageIs('/admin/coaches') ? 'active' : ''; ?>">مربیان</a>
                    <a href="/admin/assignments" class="nav-item <?php echo currentPageIs('/admin/assignments') ? 'active' : ''; ?>">تخصیص اعضا</a>
                </div>
            </div>
            
            <!-- مالی -->
            <div class="sidebar-accordion <?php echo isActiveSection(['payments']) ? 'open' : ''; ?>">
                <button class="sidebar-accordion-header">
                    <div class="header-content">
                        💰
                        <span>مالی</span>
                    </div>
                    <svg class="chevron" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M4 6l4 4 4-4H4z"/>
                    </svg>
                </button>
                <div class="sidebar-accordion-body">
                    <a href="/admin/payments" class="nav-item <?php echo currentPageIs('/admin/payments') ? 'active' : ''; ?>">پرداخت‌ها</a>
                </div>
            </div>
            
            <!-- ارتباطات -->
            <div class="sidebar-accordion <?php echo isActiveSection(['notifications']) ? 'open' : ''; ?>">
                <button class="sidebar-accordion-header">
                    <div class="header-content">
                        🔔
                        <span>ارتباطات</span>
                    </div>
                    <svg class="chevron" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M4 6l4 4 4-4H4z"/>
                    </svg>
                </button>
                <div class="sidebar-accordion-body">
                    <a href="#" class="nav-item">اعلانات</a>
                </div>
            </div>
            
            <!-- سیستم -->
            <div class="sidebar-accordion <?php echo isActiveSection(['settings']) ? 'open' : ''; ?>">
                <button class="sidebar-accordion-header">
                    <div class="header-content">
                        ⚙️
                        <span>سیستم</span>
                    </div>
                    <svg class="chevron" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M4 6l4 4 4-4H4z"/>
                    </svg>
                </button>
                <div class="sidebar-accordion-body">
                    <a href="/admin/settings" class="nav-item <?php echo currentPageIs('/admin/settings') ? 'active' : ''; ?>">تنظیمات</a>
                </div>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <p>© <?php echo toShamsi(date('Y-m-d')); ?></p>
            <p><?php echo getSetting('club_name', 'باشگاه ورزشی'); ?></p>
        </div>
    </aside>
    
    <div class="sidebar-overlay"></div>
    
    <!-- هدر -->
    <header class="header">
        <div class="header-right">
            <button onclick="toggleSidebar()" style="background:none;border:none;font-size:24px;cursor:pointer;">☰</button>
            <h2 style="font-size:18px;font-weight:bold;"><?php echo $pageTitle ?? 'داشبورد'; ?></h2>
        </div>
        
        <div class="header-left">
            <div class="user-menu">
                <?php 
                $user = auth();
                $avatarPath = $user['avatar_path'] ?? null;
                if ($avatarPath && file_exists($avatarPath)): 
                ?>
                    <img src="/<?php echo $avatarPath; ?>" alt="<?php echo $user['username']; ?>" class="user-avatar">
                <?php else: ?>
                    <div class="user-avatar-placeholder">
                        <?php echo mb_substr($user['username'] ?? 'U', 0, 1); ?>
                    </div>
                <?php endif; ?>
                <span class="user-name"><?php echo $user['username'] ?? 'کاربر'; ?></span>
            </div>
            
            <form method="POST" action="/auth/logout" style="display:inline;">
                <button type="submit" class="btn-logout">خروج</button>
            </form>
        </div>
    </header>
    
    <!-- محتوای اصلی -->
    <main class="main-content">
        <?php if ($flash = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>
        
        <?php echo $content ?? ''; ?>
    </main>
    
    <script src="/public/js/app.js"></script>
</body>
</html>

<?php
/**
 * بررسی صفحه فعلی
 */
function currentPageIs($path) {
    return $_SERVER['REQUEST_URI'] === $path || strpos($_SERVER['REQUEST_URI'], $path . '/') === 0;
}

/**
 * بررسی بخش فعال
 */
function isActiveSection($paths) {
    foreach ($paths as $path) {
        if (strpos($_SERVER['REQUEST_URI'], '/admin' . $path) === 0) {
            return true;
        }
    }
    return false;
}

/**
 * تیره کردن رنگ
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
 * شفاف کردن رنگ
 */
function adjustColorOpacity($hex, $opacity) {
    return "rgba(" . hexdec(substr($hex, 1, 2)) . ", " . hexdec(substr($hex, 3, 2)) . ", " . hexdec(substr($hex, 5, 2)) . ", $opacity)";
}
?>
