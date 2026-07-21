<?php
/**
 * کنترلر احراز هویت
 * سیستم مدیریت باشگاه ورزشی
 */

class AuthController {
    
    /**
     * نمایش صفحه ورود
     */
    public function login() {
        if (isLoggedIn()) {
            redirectByRole();
        }
        
        $clubName = getSetting('club_name', 'باشگاه ورزشی');
        $primaryColor = getSetting('primary_color', '#1e40af');
        
        include APP_ROOT . '/views/auth/login.php';
    }
    
    /**
     * پردازش فرم ورود
     */
    public function authenticate() {
        $username = post('username');
        $password = post('password');
        
        if (empty($username) || empty($password)) {
            setFlashMessage('error', 'نام کاربری و رمز عبور الزامی است.');
            redirect('/auth/login');
        }
        
        try {
            $db = db();
            
            // جستجوی کاربر بر اساس نام کاربری یا کد ملی
            $stmt = $db->prepare("
                SELECT u.*, 
                       GROUP_CONCAT(r.name) as roles,
                       GROUP_CONCAT(r.name_fa) as roles_fa
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id
                WHERE u.username = ? OR u.username = ?
                GROUP BY u.id
            ");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if (!$user) {
                setFlashMessage('error', 'نام کاربری یا رمز عبور اشتباه است.');
                redirect('/auth/login');
            }
            
            if (!$user['is_active']) {
                setFlashMessage('error', 'حساب کاربری شما غیرفعال است. با مدیر تماس بگیرید.');
                redirect('/auth/login');
            }
            
            if (!verifyPassword($password, $user['password'])) {
                setFlashMessage('error', 'نام کاربری یا رمز عبور اشتباه است.');
                redirect('/auth/login');
            }
            
            // تبدیل نقش‌ها به آرایه
            $roles = $user['roles'] ? explode(',', $user['roles']) : [];
            
            // حذف فیلدهای حساس
            unset($user['password'], $user['roles']);
            
            // ورود کاربر
            login($user, $roles);
            
            // لاگ ورود
            logMessage("ورود کاربر: {$user['username']} با نقش‌های: " . implode(',', $roles), 'auth');
            
            // هدایت بر اساس نقش
            redirectByRole();
            
        } catch (Exception $e) {
            logMessage("خطا در ورود: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
            redirect('/auth/login');
        }
    }
    
    /**
     * خروج از سیستم
     */
    public function logout() {
        if (isLoggedIn()) {
            $user = auth();
            logMessage("خروج کاربر: {$user['username']}", 'auth');
        }
        
        logout();
        redirect('/auth/login');
    }
    
    /**
     * نمایش صفحه ثبت‌نام
     */
    public function register() {
        if (isLoggedIn()) {
            redirectByRole();
        }
        
        $clubName = getSetting('club_name', 'باشگاه ورزشی');
        $primaryColor = getSetting('primary_color', '#1e40af');
        
        include APP_ROOT . '/views/auth/register.php';
    }
    
    /**
     * پردازش ثبت‌نام
     */
    public function store() {
        // بررسی CSRF
        if (!verifyCsrfToken(post('csrf_token'))) {
            setFlashMessage('error', 'خطای امنیتی. لطفاً مجدد تلاش کنید.');
            redirect('/auth/register');
        }
        
        $firstName = post('first_name');
        $lastName = post('last_name');
        $nationalCode = post('national_code');
        $phone = post('phone');
        $email = post('email');
        $password = post('password');
        $passwordConfirm = post('password_confirm');
        $birthDate = post('birth_date');
        $gender = post('gender');
        
        // اعتبارسنجی
        $errors = [];
        
        if (empty($firstName) || empty($lastName)) {
            $errors[] = 'نام و نام خانوادگی الزامی است.';
        }
        
        if (empty($nationalCode) || !validateNationalCode($nationalCode)) {
            $errors[] = 'کد ملی معتبر وارد کنید.';
        }
        
        if (empty($phone)) {
            $errors[] = 'شماره تلفن الزامی است.';
        }
        
        if ($password !== $passwordConfirm) {
            $errors[] = 'رمز عبور و تکرار آن مطابقت ندارند.';
        }
        
        if (strlen($password) < 6) {
            $errors[] = 'رمز عبور باید حداقل ۶ کاراکتر باشد.';
        }
        
        if (!empty($errors)) {
            setFlashMessage('error', implode('<br>', $errors));
            redirect('/auth/register');
        }
        
        try {
            $db = db();
            
            // بررسی تکراری نبودن کد ملی
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$nationalCode]);
            
            if ($stmt->fetch()) {
                setFlashMessage('error', 'این کد ملی قبلاً ثبت شده است.');
                redirect('/auth/register');
            }
            
            // شروع تراکنش
            $db->beginTransaction();
            
            // ایجاد کاربر
            $stmt = $db->prepare("
                INSERT INTO users (username, password, email, phone, is_active)
                VALUES (?, ?, ?, ?, FALSE)
            ");
            $stmt->execute([
                $nationalCode,
                hashPassword($password),
                $email,
                $phone
            ]);
            
            $userId = $db->lastInsertId();
            
            // ایجاد عضو
            $stmt = $db->prepare("
                INSERT INTO members (
                    user_id, first_name, last_name, national_code, 
                    phone, birth_date, gender, registration_date, 
                    membership_status, is_approved
                ) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE(), 'pending', FALSE)
            ");
            $stmt->execute([
                $userId,
                $firstName,
                $lastName,
                $nationalCode,
                $phone,
                toMiladi($birthDate),
                $gender
            ]);
            
            // اختصاص نقش member
            $stmt = $db->prepare("SELECT id FROM roles WHERE name = 'member'");
            $stmt->execute();
            $memberRoleId = $stmt->fetchColumn();
            
            $stmt = $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            $stmt->execute([$userId, $memberRoleId]);
            
            $db->commit();
            
            setFlashMessage('success', 'ثبت‌نام شما با موفقیت انجام شد. پس از تایید مدیر می‌توانید وارد شوید.');
            logMessage("ثبت‌نام جدید: $nationalCode - $firstName $lastName", 'auth');
            
            redirect('/auth/login');
            
        } catch (Exception $e) {
            $db->rollBack();
            logMessage("خطا در ثبت‌نام: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
            redirect('/auth/register');
        }
    }
}
