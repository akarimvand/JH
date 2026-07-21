<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">W</div>
        <div class="sidebar-brand-text">
            <h1>سیستم مدیریت باشگاه</h1>
            <p>پنل مربی</p>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <a href="/coach/dashboard" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/coach/dashboard') !== false) ? 'active' : '' ?>">
            <span class="link-icon">📊</span>
            <span class="link-text">داشبورد</span>
        </a>
        
        <a href="/coach/classes" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/coach/classes') !== false || strpos($_SERVER['REQUEST_URI'], '/coach/class-students') !== false) ? 'active' : '' ?>">
            <span class="link-icon">📚</span>
            <span class="link-text">کلاس‌های من</span>
        </a>
        
        <a href="/coach/attendance" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/coach/attendance') !== false || strpos($_SERVER['REQUEST_URI'], '/coach/attendance-form') !== false) ? 'active' : '' ?>">
            <span class="link-icon">✅</span>
            <span class="link-text">حضور و غیاب</span>
        </a>
        
        <a href="/coach/profile" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/coach/profile') !== false) ? 'active' : '' ?>">
            <span class="link-icon">👤</span>
            <span class="link-text">پروفایل</span>
        </a>
        
        <a href="/coach/notifications" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/coach/notifications') !== false) ? 'active' : '' ?>">
            <span class="link-icon">🔔</span>
            <span class="link-text">اعلانات</span>
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <p>© ۱۴۰۳ - تمام حقوق محفوظ است</p>
    </div>
</div>
