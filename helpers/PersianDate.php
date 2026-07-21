<?php
/**
 * توابع کمکی تاریخ شمسی
 * سیستم مدیریت باشگاه ورزشی
 */

/**
 * تبدیل تاریخ میلادی به شمسی با استفاده از الگوریتم دقیق
 * @param string $date تاریخ میلادی با فرمت Y-m-d
 * @return string تاریخ شمسی با فرمت Y/n/j
 */
function toShamsi($date) {
    if (empty($date)) return '';
    
    try {
        $datetime = new DateTime($date);
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
        
        return gregorian_to_jalali($year, $month, $day);
    } catch (Exception $e) {
        return '';
    }
}

/**
 * تبدیل تاریخ شمسی به میلادی
 * @param string $shamsiDate تاریخ شمسی با فرمت Y/m/d
 * @return string تاریخ میلادی با فرمت Y-m-d
 */
function toMiladi($shamsiDate) {
    if (empty($shamsiDate)) return '';
    
    $parts = preg_split('[/\\-]', $shamsiDate);
    if (count($parts) !== 3) return '';
    
    list($year, $month, $day) = $parts;
    
    list($gy, $gm, $gd) = jalali_to_gregorian($year, $month, $day);
    
    return sprintf('%04d-%02d-%02d', $gy, $gm, $gd);
}

/**
 * تبدیل تاریخ میلادی به شمسی (الگورییم اصلی)
 */
function gregorian_to_jalali($gy, $gm, $gd) {
    $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    
    $jy = ($gy <= 1600) ? 0 : 979;
    $gy -= ($gy <= 1600) ? 621 : 1600;
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100)) + ((int)(($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm - 1];
    $jy += 33 * ((int)($days / 12053));
    $days %= 12053;
    $jy += 4 * ((int)($days / 1461));
    $days %= 1461;
    
    if ($days > 365) {
        $jy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }
    
    $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
    $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
    
    return sprintf('%04d/%02d/%02d', $jy, $jm, $jd);
}

/**
 * تبدیل تاریخ شمسی به میلادی (الگوریتم اصلی)
 */
function jalali_to_gregorian($jy, $jm, $jd) {
    $j_d_m = [0, 31, 62, 93, 124, 155, 186, 217, 248, 279, 310, 341];
    
    $gy = ($jy <= 979) ? 621 : 1600;
    $jy -= ($jy <= 979) ? 0 : 979;
    $days = (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)((($jy % 33) + 3) / 4)) + 78 + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
    $gy += 400 * ((int)($days / 146097));
    $days %= 146097;
    
    if ($days > 36524) {
        $gy += 100 * ((int)(--$days / 36524));
        $days %= 36524;
        if ($days >= 365) $days++;
    }
    
    $gy += 4 * ((int)(($days) / 1461));
    $days %= 1461;
    
    if ($days > 365) {
        $gy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }
    
    $gd = $days + 1;
    $sal_a = [0, 31, (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    
    for ($gm = 0; $gm < 13 && $gd > $sal_a[$gm]; $gm++) {
        $gd -= $sal_a[$gm];
    }
    
    return [$gy, $gm, $gd];
}

/**
 * نمایش تاریخ با فرمت فارسی
 * @param string $date تاریخ میلادی
 * @param bool $showTime نمایش ساعت
 * @return string
 */
function formatDate($date, $showTime = false) {
    if (empty($date)) return '';
    
    $shamsi = toShamsi($date);
    
    if ($showTime) {
        $time = date('H:i', strtotime($date));
        return "$shamsi ساعت $time";
    }
    
    return $shamsi;
}

/**
 * نمایش اختلاف تاریخ به صورت فارسی
 * @param string $date تاریخ میلادی
 * @return string
 */
function timeAgo($date) {
    if (empty($date)) return '';
    
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;
    
    $minutes = floor($diff / 60);
    $hours = floor($diff / 3600);
    $days = floor($diff / 86400);
    $weeks = floor($diff / 604800);
    $months = floor($diff / 2592000);
    $years = floor($diff / 31104000);
    
    if ($years > 0) {
        return "$years سال پیش";
    } elseif ($months > 0) {
        return "$months ماه پیش";
    } elseif ($weeks > 0) {
        return "$weeks هفته پیش";
    } elseif ($days > 0) {
        return "$days روز پیش";
    } elseif ($hours > 0) {
        return "$hours ساعت پیش";
    } elseif ($minutes > 0) {
        return "$minutes دقیقه پیش";
    } else {
        return "همین الان";
    }
}

/**
 * بررسی انقضا تاریخ
 * @param string $endDate تاریخ پایان به صورت میلادی
 * @return array ['is_expired' => bool, 'days_remaining' => int]
 */
function checkExpiry($endDate) {
    if (empty($endDate)) {
        return ['is_expired' => true, 'days_remaining' => 0];
    }
    
    $end = new DateTime($endDate);
    $now = new DateTime();
    $diff = $now->diff($end);
    
    return [
        'is_expired' => $end < $now,
        'days_remaining' => $diff->days
    ];
}

/**
 * دریافت نام فارسی روز هفته
 * @param string $date تاریخ میلادی
 * @return string
 */
function getDayName($date) {
    $dayNames = [
        'Saturday' => 'شنبه',
        'Sunday' => 'یکشنبه',
        'Monday' => 'دوشنبه',
        'Tuesday' => 'سه‌شنبه',
        'Wednesday' => 'چهارشنبه',
        'Thursday' => 'پنجشنبه',
        'Friday' => 'جمعه'
    ];
    
    $dayName = date('l', strtotime($date));
    return $dayNames[$dayName] ?? $dayName;
}

/**
 * دریافت نام فارسی ماه شمسی
 * @param int $monthNumber شماره ماه شمسی (1-12)
 * @return string
 */
function getPersianMonth($monthNumber) {
    $months = [
        1 => 'فروردین',
        2 => 'اردیبهشت',
        3 => 'خرداد',
        4 => 'تیر',
        5 => 'مرداد',
        6 => 'شهریور',
        7 => 'مهر',
        8 => 'آبان',
        9 => 'آذر',
        10 => 'دی',
        11 => 'بهمن',
        12 => 'اسفند'
    ];
    
    return $months[(int)$monthNumber] ?? '';
}

/**
 * فرمت کردن عدد به فارسی
 * @param int|float $number
 * @return string
 */
function toPersianDigits($number) {
    $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    return str_replace(range(0, 9), $persianDigits, $number);
}

/**
 * فرمت کردن مبلغ به ریال با جداکننده هزارگان
 * @param int $amount
 * @return string
 */
function formatCurrency($amount) {
    return number_format($amount) . ' ریال';
}

/**
 * فرمت کردن مبلغ به تومان
 * @param int $amountRials مبلغ به ریال
 * @return string
 */
function formatToman($amountRials) {
    $amountTomans = $amountRials / 10;
    return number_format($amountTomans) . ' تومان';
}
