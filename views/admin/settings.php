<div class="card">
    <div class="card-header">
        <h3 class="card-title">تنظیمات باشگاه</h3>
    </div>
    
    <form method="POST" action="/admin/settings/update" enctype="multipart/form-data">
        <div class="card-body">
            <!-- اطلاعات پایه -->
            <h4 style="margin-bottom: 16px; color: var(--app-primary);">اطلاعات پایه باشگاه</h4>
            
            <div class="form-row">
                <div class="form-group">
                    <label>نام باشگاه *</label>
                    <input type="text" name="club_name" class="form-control" value="<?php echo $settings['club_name'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>توضیحات</label>
                    <textarea name="club_description" class="form-control" rows="3"><?php echo $settings['club_description'] ?? ''; ?></textarea>
                </div>
            </div>
            
            <!-- لوگو و رنگ‌ها -->
            <h4 style="margin: 24px 0 16px 0; color: var(--app-primary);">لوگو و ظاهر</h4>
            
            <div class="form-row">
                <div class="form-group">
                    <label>لوگو باشگاه</label>
                    <?php if (!empty($settings['logo_path']) && file_exists($settings['logo_path'])): ?>
                        <div style="margin-bottom: 12px;">
                            <img src="/<?php echo $settings['logo_path']; ?>" alt="لوگو" style="width: 100px; height: 100px; object-fit: contain; border: 1px solid var(--app-gray-200); border-radius: 8px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    <small style="color: var(--app-gray-500);">فرمت‌های مجاز: JPG, PNG, GIF - حداکثر حجم: 2MB</small>
                </div>
                <div class="form-group">
                    <label>رنگ اصلی</label>
                    <input type="color" name="primary_color" class="form-control" value="<?php echo $settings['primary_color'] ?? '#1e40af'; ?>" style="height: 50px;">
                </div>
                <div class="form-group">
                    <label>رنگ ثانویه</label>
                    <input type="color" name="secondary_color" class="form-control" value="<?php echo $settings['secondary_color'] ?? '#3b82f6'; ?>" style="height: 50px;">
                </div>
            </div>
            
            <!-- تنظیمات مالی -->
            <h4 style="margin: 24px 0 16px 0; color: var(--app-primary);">تنظیمات مالی</h4>
            
            <div class="form-row">
                <div class="form-group">
                    <label>شروع سال مالی</label>
                    <input type="text" name="financial_year_start" class="form-control" value="<?php echo !empty($settings['financial_year_start']) ? formatDate($settings['financial_year_start']) : '1403/01/01'; ?>" placeholder="1403/01/01">
                    <small style="color: var(--app-gray-500);">فرمت: 1403/01/01</small>
                </div>
            </div>
        </div>
        
        <div class="modal-footer" style="border-top: none; padding-top: 0;">
            <button type="submit" class="btn btn-success">💾 ذخیره تنظیمات</button>
            <a href="/admin/dashboard" class="btn btn-warning">انصراف</a>
        </div>
    </form>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">راهنما</h3>
    </div>
    <div class="card-body">
        <ul style="line-height: 2;">
            <li><strong>نام باشگاه:</strong> این نام در تمام صفحات سیستم نمایش داده می‌شود.</li>
            <li><strong>لوگو:</strong> لوگوی باشگاه در سایدبار و هدر نمایش داده می‌شود.</li>
            <li><strong>رنگ اصلی:</strong> رنگ منوها، دکمه‌ها و المان‌های اصلی را تعیین می‌کند.</li>
            <li><strong>سال مالی:</strong> برای گزارش‌گیری‌های مالی استفاده می‌شود.</li>
        </ul>
    </div>
</div>
