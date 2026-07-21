<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo $pageTitle; ?></h3>
    </div>
    
    <?php if (empty($members)): ?>
        <div class="alert alert-info">
            هیچ عضوی در انتظار تایید نیست.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام و نام خانوادگی</th>
                        <th>کد ملی</th>
                        <th>شماره تماس</th>
                        <th>تاریخ ثبت‌نام</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $index => $member): ?>
                        <tr>
                            <td><?php echo toPersianDigits($index + 1); ?></td>
                            <td><?php echo $member['full_name']; ?></td>
                            <td><?php echo toPersianDigits($member['national_code']); ?></td>
                            <td><?php echo toPersianDigits($member['phone'] ?? '-'); ?></td>
                            <td><?php echo formatDate($member['created_at']); ?></td>
                            <td>
                                <form method="POST" action="/admin/members/approve" style="display: inline;">
                                    <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success">✅ تایید</button>
                                </form>
                                
                                <button onclick="showRejectModal(<?php echo $member['id']; ?>)" class="btn btn-sm btn-danger">❌ رد</button>
                                
                                <button onclick="showMemberDetail(<?php echo $member['id']; ?>)" class="btn btn-sm btn-primary">👁️ مشاهده</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- مودال رد عضویت -->
<div id="rejectModal" class="modal-backdrop" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h4 class="modal-title">رد درخواست عضویت</h4>
            <button onclick="closeModal('rejectModal')" class="modal-close">×</button>
        </div>
        <form method="POST" action="/admin/members/reject">
            <input type="hidden" name="member_id" id="rejectMemberId">
            <div class="modal-body">
                <div class="form-group">
                    <label>دلیل رد:</label>
                    <textarea name="reason" class="form-control" rows="4" placeholder="دلیل رد درخواست را وارد کنید..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('rejectModal')" class="btn btn-warning">انصراف</button>
                <button type="submit" class="btn btn-danger">رد درخواست</button>
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
function showRejectModal(memberId) {
    document.getElementById('rejectMemberId').value = memberId;
    openModal('rejectModal');
}

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
                            <span class="badge badge-warning">در انتظار تایید</span>
                        </div>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    <div><strong>نام:</strong> ${m.full_name}</div>
                    <div><strong>کد ملی:</strong> ${toPersianDigits(m.national_code)}</div>
                    <div><strong>جنسیت:</strong> ${m.gender === 'male' ? 'مرد' : 'زن'}</div>
                    <div><strong>تاریخ تولد:</strong> ${m.birth_date || '-'}</div>
                    <div><strong>شماره تماس:</strong> ${toPersianDigits(m.phone)}</div>
                    <div><strong>ایمیل:</strong> ${m.email || '-'}</div>
                    <div><strong>تماس اضطراری:</strong> ${toPersianDigits(m.emergency_phone)}</div>
                    <div><strong>تاریخ ثبت‌نام:</strong> ${m.registration_date}</div>
                </div>
                
                <div class="alert alert-warning mt-3">
                    این عضو هنوز توسط مدیر تایید نشده است.
                </div>
            `;
            
            content.innerHTML = html;
        })
        .catch(err => {
            content.innerHTML = '<div class="alert alert-error">خطا در بارگذاری اطلاعات</div>';
        });
}
</script>
