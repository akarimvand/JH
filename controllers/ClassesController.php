<?php
/**
 * کنترلر مدیریت کلاس‌ها
 * سیستم مدیریت باشگاه ورزشی
 */

class ClassesController {
    
    /**
     * لیست کلاس‌ها
     */
    public function index() {
        requireRole(['admin', 'manager']);
        
        try {
            $db = db();
            
            $stmt = $db->query("
                SELECT c.*, 
                       CONCAT(co.first_name, ' ', co.last_name) as coach_name
                FROM classes c
                LEFT JOIN coaches co ON c.coach_id = co.id
                ORDER BY c.is_active DESC, c.created_at DESC
            ");
            $classes = $stmt->fetchAll();
            
            $pageTitle = 'مدیریت کلاس‌ها';
            ob_start();
            include APP_ROOT . '/views/classes/index.php';
            $content = ob_get_clean();
            
            include APP_ROOT . '/views/layouts/main.php';
            
        } catch (Exception $e) {
            logMessage("خطا در لیست کلاس‌ها: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
            redirect('/admin/dashboard');
        }
    }
    
    /**
     * ذخیره کلاس جدید
     */
    public function store() {
        requireRole(['admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/classes');
        }
        
        $name = post('name');
        $coachId = post('coach_id');
        $maxCapacity = post('max_capacity');
        $scheduleDays = post('schedule_days');
        $startTime = post('start_time');
        $endTime = post('end_time');
        $room = post('room');
        
        if (empty($name)) {
            setFlashMessage('error', 'نام کلاس الزامی است.');
            redirect('/admin/classes');
        }
        
        try {
            $db = db();
            
            $stmt = $db->prepare("
                INSERT INTO classes (name, coach_id, max_capacity, schedule_days, start_time, end_time, room)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $coachId ?: null, $maxCapacity, $scheduleDays, $startTime, $endTime, $room]);
            
            setFlashMessage('success', 'کلاس با موفقیت ایجاد شد.');
            
        } catch (Exception $e) {
            logMessage("خطا در ایجاد کلاس: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
        }
        
        redirect('/admin/classes');
    }
}
