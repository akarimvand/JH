<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo $pageTitle; ?></h3>
        <button onclick="openModal('addClassModal')" class="btn btn-success">➕ کلاس جدید</button>
    </div>
    
    <?php if (empty($classes)): ?>
        <div class="alert alert-info">
            هیچ کلاسی تعریف نشده است.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام کلاس</th>
                        <th>مربی</th>
                        <th>برنامه هفتگی</th>
                        <th>زمان</th>
                        <th>سالن</th>
                        <th>ظرفیت</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $index => $class): ?>
                        <tr>
                            <td><?php echo toPersianDigits($index + 1); ?></td>
                            <td><?php echo $class['name']; ?></td>
                            <td><?php echo $class['coach_name'] ?? '-'; ?></td>
                            <td><?php echo $class['schedule_days'] ?: '-'; ?></td>
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
                            <td><?php echo $class['room'] ?? '-'; ?></td>
                            <td><?php echo toPersianDigits($class['max_capacity']); ?></td>
                            <td>
                                <span class="badge <?php echo $class['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                    <?php echo $class['is_active'] ? 'فعال' : 'غیرفعال'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning">✏️</a>
                                <button onclick="confirmDelete(() => {})" class="btn btn-sm btn-danger">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- مودال افزودن کلاس -->
<div id="addClassModal" class="modal-backdrop" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h4 class="modal-title">افزودن کلاس جدید</h4>
            <button onclick="closeModal('addClassModal')" class="modal-close">×</button>
        </div>
        <form method="POST" action="/admin/classes/store">
            <div class="modal-body">
                <div class="form-group">
                    <label>نام کلاس *</label>
                    <input type="text" name="name" class="form-control" required placeholder="مثال: بدنسازی پیشرفته">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>مربی</label>
                        <select name="coach_id" class="form-control">
                            <option value="">انتخاب کنید...</option>
                            <!-- لیست مربیان باید از دیتابیس بارگذاری شود -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ظرفیت حداکثر</label>
                        <input type="number" name="max_capacity" class="form-control" value="20">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>روزهای برگزاری</label>
                        <input type="text" name="schedule_days" class="form-control" placeholder="مثال: شنبه، دوشنبه، چهارشنبه">
                    </div>
                    <div class="form-group">
                        <label>سالن</label>
                        <input type="text" name="room" class="form-control" placeholder="مثال: سالن اصلی">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>ساعت شروع</label>
                        <input type="time" name="start_time" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>ساعت پایان</label>
                        <input type="time" name="end_time" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('addClassModal')" class="btn btn-warning">انصراف</button>
                <button type="submit" class="btn btn-success">ذخیره</button>
            </div>
        </form>
    </div>
</div>
