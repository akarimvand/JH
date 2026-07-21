<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo $pageTitle; ?></h3>
        <div style="display: flex; gap: 12px;">
            <form method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" class="form-control" placeholder="جستجو..." value="<?php echo get('search', ''); ?>" style="width: 250px;">
                <button type="submit" class="btn btn-primary">جستجو</button>
            </form>
            <button onclick="openModal('addMemberModal')" class="btn btn-success">➕ عضو جدید</button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>نام و نام خانوادگی</th>
                    <th>کد ملی</th>
                    <th>شماره تماس</th>
                    <th>جنسیت</th>
                    <th>تاریخ ثبت‌نام</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($members)): ?>
                    <tr>
                        <td colspan="8" class="text-center">هیچ عضوی یافت نشد</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($members as $index => $member): ?>
                        <tr>
                            <td><?php echo toPersianDigits($index + 1); ?></td>
                            <td><?php echo $member['full_name']; ?></td>
                            <td><?php echo toPersianDigits($member['national_code']); ?></td>
                            <td><?php echo toPersianDigits($member['phone'] ?? '-'); ?></td>
                            <td>
                                <span class="badge <?php echo $member['gender'] === 'male' ? 'badge-info' : 'badge-warning'; ?>">
                                    <?php echo statusToPersian($member['gender']); ?>
                                </span>
                            </td>
                            <td><?php echo formatDate($member['registration_date']); ?></td>
                            <td>
                                <span class="badge badge-success"><?php echo statusToPersian($member['membership_status']); ?></span>
                            </td>
                            <td>
                                <button onclick="showMemberDetail(<?php echo $member['id']; ?>)" class="btn btn-sm btn-primary">👁️</button>
                                <a href="#" class="btn btn-sm btn-warning">✏️</a>
                                <button onclick="confirmDelete(() => {})" class="btn btn-sm btn-danger">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- مودال افزودن عضو -->
<div id="addMemberModal" class="modal-backdrop" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h4 class="modal-title">افزودن عضو جدید</h4>
            <button onclick="closeModal('addMemberModal')" class="modal-close">×</button>
        </div>
        <form method="POST" action="/admin/members/store">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>نام *</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>نام خانوادگی *</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>کد ملی *</label>
                        <input type="text" name="national_code" class="form-control" required pattern="\d{10}">
                    </div>
                    <div class="form-group">
                        <label>شماره تماس *</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>تاریخ تولد</label>
                        <input type="text" name="birth_date" class="form-control" placeholder="1370/01/01">
                    </div>
                    <div class="form-group">
                        <label>جنسیت *</label>
                        <select name="gender" class="form-control" required>
                            <option value="male">مرد</option>
                            <option value="female">زن</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('addMemberModal')" class="btn btn-warning">انصراف</button>
                <button type="submit" class="btn btn-success">ذخیره</button>
            </div>
        </form>
    </div>
</div>

<!-- مودال جزئیات عضو -->
<div id="memberDetailModal" class="modal-backdrop" style="display: none;">
    <div class="modal" style="max-width: 800px;">
        <div class="modal-header">
            <h4 class="modal-title">جزئیات عضو</h4>
            <button onclick="closeModal('memberDetailModal')" class="modal-close">×</button>
        </div>
        <div id="memberDetailContent" class="modal-body">
            <div class="text-center">
                <div class="spinner"></div>
                <p class="mt-2">در حال بارگذاری...</p>
            </div>
        </div>
    </div>
</div>

<script>
function showMemberDetail(memberId) {
    openModal('memberDetailModal');
    const content = document.getElementById('memberDetailContent');
    
    fetch('/admin/members/detail?id=' + memberId)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                content.innerHTML = '<div class="alert alert-error">' + (data.error || 'خطا در بارگذاری') + '</div>';
                return;
            }
            
            const m = data.member;
            const insurance = data.insurance;
            const payments = data.payments || [];
            const classes = data.classRegistrations || [];
            
            let html = `
                <div style="background: linear-gradient(135deg, var(--app-primary), var(--app-primary-dark)); color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold;">
                            ${m.full_name.charAt(0)}
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 20px;">${m.full_name}</h3>
                            <p style="margin: 4px 0 0 0; opacity: 0.9; font-size: 14px;">کد ملی: ${toPersianDigits(m.national_code)}</p>
                        </div>
                        <div style="margin-right: auto;">
                            <span class="badge badge-success">${m.membership_status}</span>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 2px solid var(--app-gray-200); padding-bottom: 12px;">
                    <button onclick="switchTab('info')" class="btn btn-sm btn-primary tab-btn active" data-tab="info">📋 اطلاعات</button>
                    <button onclick="switchTab('medical')" class="btn btn-sm btn-warning tab-btn" data-tab="medical">🏥 پزشکی</button>
                    <button onclick="switchTab('insurance')" class="btn btn-sm btn-success tab-btn" data-tab="insurance">🛡️ بیمه</button>
                    <button onclick="switchTab('payments')" class="btn btn-sm btn-info tab-btn" data-tab="payments">💰 تراکنش‌ها</button>
                    <button onclick="switchTab('classes')" class="btn btn-sm btn-primary tab-btn" data-tab="classes">📚 کلاس‌ها</button>
                </div>
                
                <div id="tab-info" class="tab-content">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div><strong>جنسیت:</strong> ${m.gender === 'male' ? 'مرد' : 'زن'}</div>
                        <div><strong>تاریخ تولد:</strong> ${m.birth_date}</div>
                        <div><strong>شماره تماس:</strong> ${toPersianDigits(m.phone)}</div>
                        <div><strong>تماس اضطراری:</strong> ${toPersianDigits(m.emergency_phone)}</div>
                        <div><strong>گروه خونی:</strong> <span style="color: var(--app-danger); font-weight: bold;">${m.blood_type}</span></div>
                        <div><strong>قد:</strong> ${m.height}</div>
                        <div><strong>وزن:</strong> ${m.weight}</div>
                        <div><strong>تاریخ ثبت‌نام:</strong> ${m.registration_date}</div>
                    </div>
                </div>
                
                <div id="tab-medical" class="tab-content" style="display: none;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div><strong>سوابق پزشکی:</strong> ${m.medical_history}</div>
                        <div><strong>حساسیت‌ها:</strong> ${m.allergies}</div>
                        <div><strong>داروها:</strong> ${m.medications}</div>
                        <div><strong>یادداشت:</strong> ${m.notes}</div>
                    </div>
                </div>
                
                <div id="tab-insurance" class="tab-content" style="display: none;">
                    ${insurance ? `
                        <div style="background: #f0fdf4; padding: 16px; border-radius: 8px; border: 1px solid #bbf7d0;">
                            <div><strong>شرکت بیمه:</strong> ${insurance.company}</div>
                            <div><strong>شماره بیمه‌نامه:</strong> ${toPersianDigits(insurance.policy_number)}</div>
                            <div><strong>تاریخ شروع:</strong> ${insurance.start_date}</div>
                            <div><strong>تاریخ پایان:</strong> ${insurance.end_date}</div>
                            <div><strong>وضعیت:</strong> <span class="badge badge-success">${insurance.status}</span></div>
                        </div>
                    ` : '<div class="alert alert-warning">بیمه‌ای ثبت نشده است</div>'}
                </div>
                
                <div id="tab-payments" class="tab-content" style="display: none;">
                    ${payments.length > 0 ? `
                        <div class="table-responsive">
                            <table class="table">
                                <thead><tr><th>مبلغ</th><th>نوع</th><th>تاریخ</th><th>وضعیت</th></tr></thead>
                                <tbody>
                                    ${payments.map(p => `
                                        <tr>
                                            <td>${p.amount}</td>
                                            <td>${p.type}</td>
                                            <td>${p.date}</td>
                                            <td><span class="badge badge-success">${p.status}</span></td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        <div style="margin-top: 16px; padding: 12px; background: var(--app-gray-50); border-radius: 8px;">
                            <strong>جمع کل پرداختی:</strong> ${data.balance.total_paid}
                        </div>
                    ` : '<div class="alert alert-warning">هیچ پرداختی ثبت نشده است</div>'}
                </div>
                
                <div id="tab-classes" class="tab-content" style="display: none;">
                    ${classes.length > 0 ? `
                        <div style="display: grid; gap: 12px;">
                            ${classes.map(c => `
                                <div style="padding: 16px; background: var(--app-gray-50); border-radius: 8px; border-right: 4px solid var(--app-primary);">
                                    <div style="font-weight: bold; margin-bottom: 8px;">${c.class_name}</div>
                                    <div style="font-size: 13px; color: var(--app-gray-600);">
                                        <div>مربی: ${c.coach_name}</div>
                                        <div>برنامه: ${c.schedule}</div>
                                        <div>زمان: ${c.time}</div>
                                        <div>تاریخ ثبت‌نام: ${c.registration_date}</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    ` : '<div class="alert alert-warning">در هیچ کلاسی ثبت‌نام نکرده است</div>'}
                </div>
            `;
            
            content.innerHTML = html;
        })
        .catch(err => {
            content.innerHTML = '<div class="alert alert-error">خطا در بارگذاری اطلاعات</div>';
        });
}

function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    document.getElementById('tab-' + tabName).style.display = 'block';
    document.querySelector('[data-tab="' + tabName + '"]').classList.add('active');
}
</script>

<style>
.tab-btn.active {
    background: var(--app-primary) !important;
    color: white !important;
}
.tab-content {
    animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
