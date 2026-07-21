<?php
/**
 * کنترلر پنل اعضا (پرتال)
 */

class PortalController {
    
    /**
     * نمایش داشبورد عضو
     */
    public function dashboard() {
        require_once APP_ROOT . '/views/portal/dashboard.php';
    }
    
    /**
     * نمایش پروفایل عضو
     */
    public function profile() {
        require_once APP_ROOT . '/views/portal/profile.php';
    }
    
    /**
     * به‌روزرسانی پروفایل
     */
    public function profileUpdate() {
        // پیاده‌سازی به‌روزرسانی پروفایل
        redirect('/portal/profile?updated=1');
    }
    
    /**
     * تغییر رمز عبور
     */
    public function changePassword() {
        // پیاده‌سازی تغییر رمز عبور
        redirect('/portal/profile?password_changed=1');
    }
    
    /**
     * نمایش تاریخچه حضور و غیاب
     */
    public function attendance() {
        require_once APP_ROOT . '/views/portal/attendance.php';
    }
    
    /**
     * نمایش وضعیت عضویت
     */
    public function membership() {
        require_once APP_ROOT . '/views/portal/membership.php';
    }
    
    /**
     * نمایش وضعیت بیمه
     */
    public function insurance() {
        require_once APP_ROOT . '/views/portal/insurance.php';
    }
    
    /**
     * آپلود مدارک بیمه
     */
    public function uploadInsurance() {
        // پیاده‌سازی آپلود مدارک بیمه
        redirect('/portal/insurance?uploaded=1');
    }
    
    /**
     * نمایش پرداخت‌ها
     */
    public function payments() {
        require_once APP_ROOT . '/views/portal/payments.php';
    }
    
    /**
     * نمایش کلاس‌های عضو
     */
    public function classes() {
        require_once APP_ROOT . '/views/portal/classes.php';
    }
    
    /**
     * دریافت اطلاعات عضو (برای مودال جزئیات)
     */
    public function getMember() {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'member' => []]);
    }
}
