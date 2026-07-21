-- ساختار پایگاه داده سیستم مدیریت باشگاه ورزشی
-- تاریخ ایجاد: ۱۴۰۳/۰۱/۰۱
-- پشتیبانی از تاریخ شمسی و زبان فارسی

SET NAMES utf8mb4 COLLATE utf8mb4_persian_ci;

-- جدول تنظیمات باشگاه
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    club_name VARCHAR(255) NOT NULL DEFAULT 'باشگاه ورزشی',
    club_description TEXT,
    logo_path VARCHAR(255),
    primary_color VARCHAR(7) DEFAULT '#1e40af',
    secondary_color VARCHAR(7) DEFAULT '#3b82f6',
    financial_year_start DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول کاربران
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL COMMENT 'نام کاربری یا کد ملی',
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    avatar_path VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول نقش‌ها
CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    name_fa VARCHAR(50) NOT NULL,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول ارتباط کاربران و نقش‌ها
CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول مربیان
CREATE TABLE IF NOT EXISTS coaches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    national_code VARCHAR(10) UNIQUE,
    phone VARCHAR(20),
    specialty VARCHAR(100),
    experience_years INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول اعضا
CREATE TABLE IF NOT EXISTS members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    national_code VARCHAR(10) UNIQUE NOT NULL,
    father_name VARCHAR(50),
    birth_date DATE,
    gender ENUM('male', 'female') NOT NULL,
    phone VARCHAR(20),
    emergency_phone VARCHAR(20),
    address TEXT,
    postal_code VARCHAR(10),
    blood_type VARCHAR(5),
    allergies TEXT,
    medications TEXT,
    medical_history TEXT,
    height DECIMAL(5,2),
    weight DECIMAL(5,2),
    registration_date DATE,
    membership_status ENUM('active', 'expired', 'pending', 'suspended') DEFAULT 'pending',
    is_approved BOOLEAN DEFAULT FALSE COMMENT 'تاییدیه مدیر برای ثبت‌نام خودکار',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول کلاس‌ها
CREATE TABLE IF NOT EXISTS classes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    coach_id INT,
    max_capacity INT DEFAULT 20,
    schedule_days VARCHAR(50) COMMENT 'روزهای برگزاری به صورت JSON',
    start_time TIME,
    end_time TIME,
    room VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (coach_id) REFERENCES coaches(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول ثبت‌نام در کلاس‌ها
CREATE TABLE IF NOT EXISTS class_registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    class_id INT NOT NULL,
    member_id INT NOT NULL,
    registration_date DATE NOT NULL,
    expiry_date DATE,
    status ENUM('active', 'expired', 'cancelled') DEFAULT 'active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_member_class (class_id, member_id, status),
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول حضور و غیاب
CREATE TABLE IF NOT EXISTS attendance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    class_id INT NOT NULL,
    member_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') DEFAULT 'present',
    notes TEXT,
    recorded_by INT,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_attendance (class_id, member_id, attendance_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول اشتراک‌ها
CREATE TABLE IF NOT EXISTS memberships (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    price DECIMAL(10,0) NOT NULL,
    sessions_count INT,
    sessions_used INT DEFAULT 0,
    status ENUM('active', 'expired', 'cancelled') DEFAULT 'active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول پرداخت‌ها
CREATE TABLE IF NOT EXISTS payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    amount DECIMAL(10,0) NOT NULL,
    payment_type ENUM('membership', 'class', 'insurance', 'other') NOT NULL,
    payment_method ENUM('cash', 'card', 'online', 'transfer') DEFAULT 'cash',
    payment_date DATE NOT NULL,
    description TEXT,
    reference_code VARCHAR(100),
    status ENUM('completed', 'pending', 'failed') DEFAULT 'completed',
    recorded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول بیمه اعضا
CREATE TABLE IF NOT EXISTS insurances (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    insurance_company VARCHAR(100),
    policy_number VARCHAR(50),
    start_date DATE,
    end_date DATE,
    document_path VARCHAR(255),
    status ENUM('pending_approval', 'approved', 'rejected', 'expired') DEFAULT 'pending_approval',
    notes TEXT,
    reviewed_by INT,
    reviewed_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول اعلانات
CREATE TABLE IF NOT EXISTS notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'warning', 'success', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- جدول تخصیص اعضا به مربیان
CREATE TABLE IF NOT EXISTS member_coach_assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    coach_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (coach_id) REFERENCES coaches(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- داده‌های اولیه نقش‌ها
INSERT INTO roles (name, name_fa, description) VALUES
('admin', 'مدیر کل', 'دسترسی کامل به تمام بخش‌ها'),
('manager', 'مدیر داخلی', 'مدیریت اعضا و کلاس‌ها'),
('receptionist', 'پذیرش', 'ثبت‌نام و حضور غیاب'),
('accountant', 'حسابدار', 'مدیریت مالی و پرداخت‌ها'),
('coach', 'مربی', 'مدیریت کلاس‌ها و اعضا'),
('member', 'عضو', 'دسترسی به پنل کاربری');

-- داده‌های اولیه تنظیمات
INSERT INTO settings (club_name, club_description) VALUES
('باشگاه ورزشی موج', 'باشگاه تخصصی بدنسازی و تناسب اندام');

-- کاربر پیش‌فرض مدیر
INSERT INTO users (username, password, email, phone, is_active) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@gym.com', '09123456789', TRUE);

-- اختصاص نقش مدیر به کاربر پیش‌فرض
INSERT INTO user_roles (user_id, role_id) VALUES (1, 1);
