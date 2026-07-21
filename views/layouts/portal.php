<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">W</div>
        <div class="sidebar-brand-text">
            <h1>سیستم مدیریت باشگاه</h1>
            <p>پرتال اعضا</p>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <a href="/portal/dashboard" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/portal/dashboard') !== false) ? 'active' : '' ?>">
            <span class="link-icon">📊</span>
            <span class="link-text">داشبورد</span>
        </a>
        
        <a href="/portal/profile" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/portal/profile') !== false) ? 'active' : '' ?>">
            <span class="link-icon">👤</span>
            <span class="link-text">پروفایل من</span>
        </a>
        
        <a href="/portal/attendance" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/portal/attendance') !== false) ? 'active' : '' ?>">
            <span class="link-icon">✅</span>
            <span class="link-text">حضور و غیاب</span>
        </a>
        
        <a href="/portal/classes" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/portal/classes') !== false) ? 'active' : '' ?>">
            <span class="link-icon">📚</span>
            <span class="link-text">کلاس‌های من</span>
        </a>
        
        <a href="/portal/membership" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/portal/membership') !== false) ? 'active' : '' ?>">
            <span class="link-icon">📅</span>
            <span class="link-text">عضویت</span>
        </a>
        
        <a href="/portal/insurance" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/portal/insurance') !== false) ? 'active' : '' ?>">
            <span class="link-icon">🏥</span>
            <span class="link-text">بیمه</span>
        </a>
        
        <a href="/portal/payments" class="sidebar-link <?= (strpos($_SERVER['REQUEST_URI'], '/portal/payments') !== false) ? 'active' : '' ?>">
            <span class="link-icon">💰</span>
            <span class="link-text">پرداخت‌ها</span>
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <p>© ۱۴۰۳ - تمام حقوق محفوظ است</p>
    </div>
</div>
