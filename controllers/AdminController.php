<?php
/**
 * کنترلر پنل مدیریت
 * سیستم مدیریت باشگاه ورزشی
 */

class AdminController {
    
    /**
     * داشبورد مدیریت
     */
    public function dashboard() {
        requireRole(['admin', 'manager']);
        
        try {
            $db = db();
            
            // آمار کلی
            $stats = [];
            
            // تعداد کل اعضا
            $stmt = $db->query("SELECT COUNT(*) FROM members");
            $stats['total_members'] = $stmt->fetchColumn();
            
            // اعضای فعال
            $stmt = $db->query("SELECT COUNT(*) FROM members WHERE membership_status = 'active'");
            $stats['active_members'] = $stmt->fetchColumn();
            
            // اعضای در انتظار تایید
            $stmt = $db->query("SELECT COUNT(*) FROM members WHERE is_approved = FALSE");
            $stats['pending_members'] = $stmt->fetchColumn();
            
            // تعداد کلاس‌ها
            $stmt = $db->query("SELECT COUNT(*) FROM classes WHERE is_active = TRUE");
            $stats['total_classes'] = $stmt->fetchColumn();
            
            // تعداد مربیان
            $stmt = $db->query("SELECT COUNT(*) FROM coaches WHERE is_active = TRUE");
            $stats['total_coaches'] = $stmt->fetchColumn();
            
            // درآمد این ماه
            $stmt = $db->query("
                SELECT COALESCE(SUM(amount), 0) 
                FROM payments 
                WHERE status = 'completed' 
                AND payment_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
            ");
            $stats['monthly_income'] = $stmt->fetchColumn();
            
            // پرداخت‌های اخیر
            $stmt = $db->query("
                SELECT p.*, m.first_name, m.last_name 
                FROM payments p
                JOIN members m ON p.member_id = m.id
                WHERE p.status = 'completed'
                ORDER BY p.payment_date DESC
                LIMIT 5
            ");
            $recentPayments = $stmt->fetchAll();
            
            // کلاس‌های فعال
            $stmt = $db->query("
                SELECT c.*, CONCAT(co.first_name, ' ', co.last_name) as coach_name
                FROM classes c
                LEFT JOIN coaches co ON c.coach_id = co.id
                WHERE c.is_active = TRUE
                LIMIT 5
            ");
            $activeClasses = $stmt->fetchAll();
            
            $pageTitle = 'داشبورد مدیریت';
            ob_start();
            include APP_ROOT . '/views/admin/dashboard.php';
            $content = ob_get_clean();
            
            include APP_ROOT . '/views/layouts/main.php';
            
        } catch (Exception $e) {
            logMessage("خطا در داشبورد: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
            redirect('/admin/dashboard');
        }
    }
}
