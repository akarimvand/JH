<?php
/**
 * کنترلر پنل مربی
 */

class CoachController {
    
    /**
     * نمایش داشبورد مربی
     */
    public function dashboard() {
        require_once APP_ROOT . '/views/coach/dashboard.php';
    }
    
    /**
     * نمایش کلاس‌های مربی
     */
    public function classes() {
        require_once APP_ROOT . '/views/coach/classes.php';
    }
    
    /**
     * نمایش دانشجویان یک کلاس
     */
    public function classStudents() {
        require_once APP_ROOT . '/views/coach/class-students.php';
    }
    
    /**
     * نمایش صفحه حضور و غیاب
     */
    public function attendance() {
        require_once APP_ROOT . '/views/coach/attendance.php';
    }
    
    /**
     * فرم ثبت حضور و غیاب
     */
    public function attendanceForm() {
        require_once APP_ROOT . '/views/coach/attendance-form.php';
    }
    
    /**
     * ذخیره حضور و غیاب
     */
    public function saveAttendance() {
        // پیاده‌سازی ذخیره حضور و غیاب
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'حضور و غیاب با موفقیت ثبت شد']);
    }
    
    /**
     * نمایش پروفایل مربی
     */
    public function profile() {
        require_once APP_ROOT . '/views/coach/profile.php';
    }
    
    /**
     * به‌روزرسانی پروفایل
     */
    public function profileUpdate() {
        // پیاده‌سازی به‌روزرسانی پروفایل
        redirect('/coach/profile?updated=1');
    }
    
    /**
     * تغییر رمز عبور
     */
    public function changePassword() {
        // پیاده‌سازی تغییر رمز عبور
        redirect('/coach/profile?password_changed=1');
    }
    
    /**
     * نمایش اعلانات
     */
    public function notifications() {
        require_once APP_ROOT . '/views/coach/notifications.php';
    }
}
