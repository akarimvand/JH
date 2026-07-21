<?php
/**
 * کنترلر مدیریت اعضا
 * سیستم مدیریت باشگاه ورزشی
 */

class MembersController {
    
    /**
     * لیست اعضای فعال
     */
    public function index() {
        requireRole(['admin', 'manager', 'receptionist']);
        
        try {
            $db = db();
            
            // جستجو و فیلتر
            $search = get('search', '');
            $status = get('status', 'active');
            
            $query = "
                SELECT m.*, u.username, u.phone, u.email,
                       CONCAT(m.first_name, ' ', m.last_name) as full_name
                FROM members m
                LEFT JOIN users u ON m.user_id = u.id
                WHERE m.membership_status = ?
            ";
            
            $params = [$status];
            
            if (!empty($search)) {
                $query .= " AND (m.first_name LIKE ? OR m.last_name LIKE ? OR m.national_code LIKE ?)";
                $searchParam = "%$search%";
                $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
            }
            
            $query .= " ORDER BY m.created_at DESC";
            
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $members = $stmt->fetchAll();
            
            $pageTitle = 'اعضای ' . ($status === 'active' ? 'فعال' : 'منقضی شده');
            ob_start();
            include APP_ROOT . '/views/members/index.php';
            $content = ob_get_clean();
            
            include APP_ROOT . '/views/layouts/main.php';
            
        } catch (Exception $e) {
            logMessage("خطا در لیست اعضا: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
            redirect('/admin/members');
        }
    }
    
    /**
     * اعضای در انتظار تایید
     */
    public function pending() {
        requireRole(['admin', 'manager']);
        
        try {
            $db = db();
            
            $stmt = $db->query("
                SELECT m.*, u.username, u.phone, u.email,
                       CONCAT(m.first_name, ' ', m.last_name) as full_name
                FROM members m
                LEFT JOIN users u ON m.user_id = u.id
                WHERE m.is_approved = FALSE
                ORDER BY m.created_at DESC
            ");
            $members = $stmt->fetchAll();
            
            $pageTitle = 'اعضای در انتظار تایید';
            ob_start();
            include APP_ROOT . '/views/members/pending.php';
            $content = ob_get_clean();
            
            include APP_ROOT . '/views/layouts/main.php';
            
        } catch (Exception $e) {
            logMessage("خطا در لیست اعضای در انتظار: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
            redirect('/admin/members/pending');
        }
    }
    
    /**
     * تایید عضویت
     */
    public function approve() {
        requireRole(['admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/members/pending');
        }
        
        $memberId = post('member_id');
        
        if (!$memberId) {
            setFlashMessage('error', 'شناسه عضو نامعتبر است.');
            redirect('/admin/members/pending');
        }
        
        try {
            $db = db();
            
            // تایید عضو
            $stmt = $db->prepare("
                UPDATE members 
                SET is_approved = TRUE, membership_status = 'active'
                WHERE id = ?
            ");
            $stmt->execute([$memberId]);
            
            // فعال کردن کاربر
            $stmt = $db->prepare("
                UPDATE users u
                JOIN members m ON u.id = m.user_id
                SET u.is_active = TRUE
                WHERE m.id = ?
            ");
            $stmt->execute([$memberId]);
            
            setFlashMessage('success', 'عضویت با موفقیت تایید شد.');
            logMessage("تایید عضویت شماره $memberId", 'admin');
            
        } catch (Exception $e) {
            logMessage("خطا در تایید عضویت: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
        }
        
        redirect('/admin/members/pending');
    }
    
    /**
     * رد عضویت
     */
    public function reject() {
        requireRole(['admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/members/pending');
        }
        
        $memberId = post('member_id');
        $reason = post('reason', 'بدون دلیل مشخص');
        
        if (!$memberId) {
            setFlashMessage('error', 'شناسه عضو نامعتبر است.');
            redirect('/admin/members/pending');
        }
        
        try {
            $db = db();
            
            // حذف عضو
            $stmt = $db->prepare("DELETE FROM members WHERE id = ?");
            $stmt->execute([$memberId]);
            
            setFlashMessage('success', 'درخواست عضویت رد شد.');
            logMessage("رد عضویت شماره $memberId - دلیل: $reason", 'admin');
            
        } catch (Exception $e) {
            logMessage("خطا در رد عضویت: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
        }
        
        redirect('/admin/members/pending');
    }
    
    /**
     * نمایش جزئیات عضو (JSON)
     */
    public function detail() {
        requireRole(['admin', 'manager', 'receptionist', 'coach']);
        
        $memberId = get('id');
        
        if (!$memberId) {
            jsonResponse(['success' => false, 'error' => 'شناسه عضو نامعتبر است'], 400);
        }
        
        try {
            $db = db();
            
            // اطلاعات عضو
            $stmt = $db->prepare("
                SELECT m.*, u.username, u.email, u.phone,
                       CONCAT(m.first_name, ' ', m.last_name) as full_name
                FROM members m
                LEFT JOIN users u ON m.user_id = u.id
                WHERE m.id = ?
            ");
            $stmt->execute([$memberId]);
            $member = $stmt->fetch();
            
            if (!$member) {
                jsonResponse(['success' => false, 'error' => 'عضو یافت نشد'], 404);
            }
            
            // بیمه
            $stmt = $db->prepare("SELECT * FROM insurances WHERE member_id = ? ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([$memberId]);
            $insurance = $stmt->fetch();
            
            // پرداخت‌ها
            $stmt = $db->prepare("
                SELECT * FROM payments 
                WHERE member_id = ? 
                ORDER BY payment_date DESC 
                LIMIT 10
            ");
            $stmt->execute([$memberId]);
            $payments = $stmt->fetchAll();
            
            // موجودی حساب
            $stmt = $db->prepare("
                SELECT 
                    COALESCE(SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END), 0) as total_paid,
                    COALESCE(SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END), 0) as total_pending
                FROM payments
                WHERE member_id = ?
            ");
            $stmt->execute([$memberId]);
            $balance = $stmt->fetch();
            
            // ثبت‌نام کلاس‌ها
            $stmt = $db->prepare("
                SELECT cr.*, c.name as class_name, c.schedule_days, c.start_time, c.end_time,
                       CONCAT(co.first_name, ' ', co.last_name) as coach_name
                FROM class_registrations cr
                JOIN classes c ON cr.class_id = c.id
                LEFT JOIN coaches co ON c.coach_id = co.id
                WHERE cr.member_id = ? AND cr.status = 'active'
            ");
            $stmt->execute([$memberId]);
            $classRegistrations = $stmt->fetchAll();
            
            // اشتراک فعال
            $stmt = $db->prepare("
                SELECT * FROM memberships 
                WHERE member_id = ? AND status = 'active' AND end_date >= CURDATE()
                ORDER BY end_date DESC 
                LIMIT 1
            ");
            $stmt->execute([$memberId]);
            $activeMembership = $stmt->fetch();
            
            jsonResponse([
                'success' => true,
                'member' => [
                    'id' => $member['id'],
                    'full_name' => $member['full_name'],
                    'national_code' => $member['national_code'],
                    'gender' => $member['gender'],
                    'birth_date' => formatDate($member['birth_date']),
                    'phone' => $member['phone'],
                    'emergency_phone' => $member['emergency_phone'] ?? '-',
                    'blood_type' => $member['blood_type'] ?? '-',
                    'membership_status' => statusToPersian($member['membership_status']),
                    'registration_date' => formatDate($member['registration_date']),
                    'height' => $member['height'] ? $member['height'] . ' cm' : '-',
                    'weight' => $member['weight'] ? $member['weight'] . ' kg' : '-',
                    'medical_history' => $member['medical_history'] ?? 'ندارد',
                    'allergies' => $member['allergies'] ?? 'ندارد',
                    'medications' => $member['medications'] ?? 'ندارد',
                    'notes' => 'ندارد'
                ],
                'insurance' => $insurance ? [
                    'company' => $insurance['insurance_company'],
                    'policy_number' => $insurance['policy_number'],
                    'start_date' => formatDate($insurance['start_date']),
                    'end_date' => formatDate($insurance['end_date']),
                    'status' => statusToPersian($insurance['status'])
                ] : null,
                'payments' => array_map(function($p) {
                    return [
                        'amount' => formatToman($p['amount']),
                        'type' => statusToPersian($p['payment_type'], 'payment'),
                        'date' => formatDate($p['payment_date']),
                        'status' => statusToPersian($p['status'])
                    ];
                }, $payments),
                'balance' => [
                    'total_paid' => formatToman($balance['total_paid']),
                    'total_pending' => formatToman($balance['total_pending'])
                ],
                'classRegistrations' => array_map(function($cr) {
                    return [
                        'class_name' => $cr['class_name'],
                        'coach_name' => $cr['coach_name'] ?? '-',
                        'schedule' => $cr['schedule_days'],
                        'time' => ($cr['start_time'] && $cr['end_time']) 
                            ? date('H:i', strtotime($cr['start_time'])) . '-' . date('H:i', strtotime($cr['end_time']))
                            : '-',
                        'registration_date' => formatDate($cr['registration_date'])
                    ];
                }, $classRegistrations),
                'activeMembership' => $activeMembership ? [
                    'type' => $activeMembership['type'],
                    'start_date' => formatDate($activeMembership['start_date']),
                    'end_date' => formatDate($activeMembership['end_date']),
                    'sessions' => toPersianDigits($activeMembership['sessions_used']) . '/' . toPersianDigits($activeMembership['sessions_count'])
                ] : null
            ]);
            
        } catch (Exception $e) {
            logMessage("خطا در دریافت جزئیات عضو: " . $e->getMessage(), 'error');
            jsonResponse(['success' => false, 'error' => 'خطایی رخ داد'], 500);
        }
    }
    
    /**
     * ذخیره عضو جدید
     */
    public function store() {
        requireRole(['admin', 'manager', 'receptionist']);
        
        // اعتبارسنجی و ذخیره
        $firstName = post('first_name');
        $lastName = post('last_name');
        $nationalCode = post('national_code');
        
        if (empty($firstName) || empty($lastName) || empty($nationalCode)) {
            setFlashMessage('error', 'لطفاً تمام فیلدهای الزامی را پر کنید.');
            redirect('/admin/members');
        }
        
        try {
            $db = db();
            $db->beginTransaction();
            
            // ایجاد کاربر
            $stmt = $db->prepare("
                INSERT INTO users (username, password, phone, is_active, is_approved)
                VALUES (?, ?, ?, TRUE, TRUE)
            ");
            $password = hashPassword(substr($nationalCode, 0, 6));
            $stmt->execute([$nationalCode, $password, post('phone')]);
            $userId = $db->lastInsertId();
            
            // ایجاد عضو
            $stmt = $db->prepare("
                INSERT INTO members (
                    user_id, first_name, last_name, national_code, phone,
                    birth_date, gender, membership_status, is_approved, registration_date
                ) VALUES (?, ?, ?, ?, ?, ?, ?, 'active', TRUE, CURDATE())
            ");
            $stmt->execute([
                $userId, $firstName, $lastName, $nationalCode,
                post('phone'), toMiladi(post('birth_date')), post('gender')
            ]);
            
            $db->commit();
            setFlashMessage('success', 'عضو با موفقیت اضافه شد.');
            
        } catch (Exception $e) {
            $db->rollBack();
            logMessage("خطا در افزودن عضو: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
        }
        
        redirect('/admin/members');
    }
}
