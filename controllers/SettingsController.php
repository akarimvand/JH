<?php
/**
 * کنترلر تنظیمات
 * سیستم مدیریت باشگاه ورزشی
 */

class SettingsController {
    
    /**
     * نمایش صفحه تنظیمات
     */
    public function index() {
        requireRole(['admin']);
        
        try {
            $db = db();
            $stmt = $db->query("SELECT * FROM settings LIMIT 1");
            $settings = $stmt->fetch();
            
            $pageTitle = 'تنظیمات سیستم';
            ob_start();
            include APP_ROOT . '/views/admin/settings.php';
            $content = ob_get_clean();
            
            include APP_ROOT . '/views/layouts/main.php';
            
        } catch (Exception $e) {
            logMessage("خطا در تنظیمات: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
            redirect('/admin/dashboard');
        }
    }
    
    /**
     * به‌روزرسانی تنظیمات
     */
    public function update() {
        requireRole(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/settings');
        }
        
        try {
            $db = db();
            
            $clubName = post('club_name');
            $clubDescription = post('club_description');
            $primaryColor = post('primary_color');
            $secondaryColor = post('secondary_color');
            $financialYearStart = toMiladi(post('financial_year_start'));
            
            // آپلود لوگو
            $logoPath = null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = APP_ROOT . '/public/uploads/settings';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $result = uploadFile($_FILES['logo'], $uploadDir, ['image/jpeg', 'image/png', 'image/gif'], 2097152);
                
                if ($result['success']) {
                    $logoPath = str_replace(APP_ROOT . '/', '', $result['path']);
                }
            }
            
            // به‌روزرسانی تنظیمات
            $query = "UPDATE settings SET 
                      club_name = ?, 
                      club_description = ?, 
                      primary_color = ?, 
                      secondary_color = ?, 
                      financial_year_start = ?";
            $params = [$clubName, $clubDescription, $primaryColor, $secondaryColor, $financialYearStart];
            
            if ($logoPath) {
                $query .= ", logo_path = ?";
                $params[] = $logoPath;
            }
            
            $query .= " WHERE id = 1";
            
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            
            setFlashMessage('success', 'تنظیمات با موفقیت ذخیره شد.');
            logMessage("به‌روزرسانی تنظیمات باشگاه", 'admin');
            
        } catch (Exception $e) {
            logMessage("خطا در به‌روزرسانی تنظیمات: " . $e->getMessage(), 'error');
            setFlashMessage('error', 'خطایی رخ داد. لطفاً مجدد تلاش کنید.');
        }
        
        redirect('/admin/settings');
    }
}
