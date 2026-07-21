<?php
/**
 * کنترلر پرداخت‌ها
 */

class PaymentsController {
    
    /**
     * نمایش لیست پرداخت‌ها
     */
    public function index() {
        require_once APP_ROOT . '/views/payments/index.php';
    }
    
    /**
     * ثبت پرداخت جدید
     */
    public function store() {
        // پیاده‌سازی ثبت پرداخت
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'پرداخت با موفقیت ثبت شد']);
    }
}
