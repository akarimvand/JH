/**
 * اسکریپت‌های اصلی سیستم مدیریت باشگاه ورزشی
 * تاریخ: ۱۴۰۳
 */

// منوی موبایل
function toggleSidebar() {
    const body = document.body;
    body.classList.toggle('sidebar-mobile-open');
}

// بستن سایدبار با کلیک روی overlay
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.addEventListener('click', function() {
            document.body.classList.remove('sidebar-mobile-open');
        });
    }
    
    // آکاردئون منو
    const accordions = document.querySelectorAll('.sidebar-accordion-header');
    accordions.forEach(function(accordion) {
        accordion.addEventListener('click', function() {
            const parent = this.parentElement;
            parent.classList.toggle('open');
        });
    });
    
    // فرم‌ها
    const forms = document.querySelectorAll('form[data-confirm]');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const message = form.getAttribute('data-confirm') || 'آیا از انجام این عملیات اطمینان دارید؟';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // حذف پیام‌های alert بعد از 5 ثانیه
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// تابع کمکی برای درخواست AJAX
function ajaxRequest(url, method = 'GET', data = null) {
    return new Promise(function(resolve, reject) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        if (method === 'POST' && data) {
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        }
        
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    resolve(response);
                } catch (e) {
                    resolve(xhr.responseText);
                }
            } else {
                reject(new Error('خطا در درخواست: ' + xhr.status));
            }
        };
        
        xhr.onerror = function() {
            reject(new Error('خطا در ارتباط با سرور'));
        };
        
        if (method === 'POST' && data) {
            const params = new URLSearchParams(data).toString();
            xhr.send(params);
        } else {
            xhr.send();
        }
    });
}

// نمایش پیام
function showMessage(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = message;
    
    const container = document.querySelector('.main-content') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(function() {
        alertDiv.style.transition = 'opacity 0.3s';
        alertDiv.style.opacity = '0';
        setTimeout(function() {
            alertDiv.remove();
        }, 300);
    }, 5000);
}

// فرمت کردن عدد به فارسی
function toPersianDigits(num) {
    const persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    return num.toString().replace(/\d/g, function(d) {
        return persianDigits[d];
    });
}

// فرمت کردن مبلغ
function formatCurrency(amount) {
    return amount.toLocaleString('fa-IR') + ' ریال';
}

// فرمت کردن تومان
function formatToman(amountRials) {
    return (amountRials / 10).toLocaleString('fa-IR') + ' تومان';
}

// تایید عملیات حذف
function confirmDelete(callback) {
    if (confirm('آیا از حذف این مورد اطمینان دارید؟ این عملیات قابل بازگشت نیست.')) {
        callback();
    }
}

// باز کردن مودال
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

// بستن مودال
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// بستن مودال با کلیک روی backdrop
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-backdrop')) {
        e.target.style.display = 'none';
        document.body.style.overflow = '';
    }
});

// خروج با دکمه Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal-backdrop');
        modals.forEach(function(modal) {
            modal.style.display = 'none';
        });
        document.body.style.overflow = '';
    }
});
