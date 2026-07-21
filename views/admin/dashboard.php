<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">👥</div>
        <div class="stat-content">
            <h3><?php echo toPersianDigits($stats['total_members']); ?></h3>
            <p>کل اعضا</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">✅</div>
        <div class="stat-content">
            <h3><?php echo toPersianDigits($stats['active_members']); ?></h3>
            <p>اعضای فعال</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">⏳</div>
        <div class="stat-content">
            <h3><?php echo toPersianDigits($stats['pending_members']); ?></h3>
            <p>در انتظار تایید</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon primary">📚</div>
        <div class="stat-content">
            <h3><?php echo toPersianDigits($stats['total_classes']); ?></h3>
            <p>کلاس‌های فعال</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info">🎯</div>
        <div class="stat-content">
            <h3><?php echo toPersianDigits($stats['total_coaches']); ?></h3>
            <p>مربیان</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">💰</div>
        <div class="stat-content">
            <h3><?php echo formatToman($stats['monthly_income']); ?></h3>
            <p>درآمد این ماه</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
    <!-- پرداخت‌های اخیر -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">پرداخت‌های اخیر</h3>
            <a href="/admin/payments" class="btn btn-primary btn-sm">مشاهده همه</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>عضو</th>
                        <th>مبلغ</th>
                        <th>نوع</th>
                        <th>تاریخ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentPayments)): ?>
                        <tr>
                            <td colspan="4" class="text-center">هیچ پرداختی ثبت نشده است</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentPayments as $payment): ?>
                            <tr>
                                <td><?php echo $payment['first_name'] . ' ' . $payment['last_name']; ?></td>
                                <td><?php echo formatToman($payment['amount']); ?></td>
                                <td>
                                    <span class="badge badge-primary">
                                        <?php 
                                        $types = [
                                            'membership' => 'اشتراک',
                                            'class' => 'کلاس',
                                            'insurance' => 'بیمه',
                                            'other' => 'سایر'
                                        ];
                                        echo $types[$payment['payment_type']] ?? $payment['payment_type'];
                                        ?>
                                    </span>
                                </td>
                                <td><?php echo formatDate($payment['payment_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- کلاس‌های فعال -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">کلاس‌های فعال</h3>
            <a href="/admin/classes" class="btn btn-primary btn-sm">مشاهده همه</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>نام کلاس</th>
                        <th>مربی</th>
                        <th>زمان</th>
                        <th>ظرفیت</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($activeClasses)): ?>
                        <tr>
                            <td colspan="4" class="text-center">هیچ کلاسی تعریف نشده است</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($activeClasses as $class): ?>
                            <tr>
                                <td><?php echo $class['name']; ?></td>
                                <td><?php echo $class['coach_name'] ?? '-'; ?></td>
                                <td>
                                    <?php 
                                    if ($class['start_time'] && $class['end_time']) {
                                        echo date('H:i', strtotime($class['start_time'])) . ' - ' . 
                                             date('H:i', strtotime($class['end_time']));
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?php echo toPersianDigits($class['max_capacity']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">دسترسی سریع</h3>
    </div>
    <div class="card-body">
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="/admin/members/pending" class="btn btn-warning">
                ✅ تایید اعضای در انتظار
            </a>
            <a href="/admin/members" class="btn btn-primary">
                👥 مدیریت اعضا
            </a>
            <a href="/admin/classes" class="btn btn-success">
                📚 مدیریت کلاس‌ها
            </a>
            <a href="/admin/coaches" class="btn btn-info">
                🎯 مدیریت مربیان
            </a>
            <a href="/admin/payments" class="btn btn-success">
                💰 مدیریت پرداخت‌ها
            </a>
            <a href="/admin/settings" class="btn btn-warning">
                ⚙️ تنظیمات
            </a>
        </div>
    </div>
</div>
