<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="margin-bottom: 0.5rem;">
                <i class="bi bi-pencil-square"></i> Sửa lịch khởi hành
            </h1>
            <p style="color: var(--text-secondary); margin: 0; font-size: 1rem;">
                ID: <strong><?= $schedule['id'] ?></strong>
            </p>
        </div>
        <a href="?act=schedule-list&id=<?= $schedule['tour_id'] ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div style="background: white; border-radius: 0.75rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="margin-bottom: 1.5rem; padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" id="scheduleForm" onsubmit="return validateScheduleForm()">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">
                    Ngày bắt đầu <span style="color: #ef4444;">*</span>
                </label>
                <input type="date" name="start_date" id="start_date" class="form-control" 
                       value="<?= $schedule['start_date'] ?>" min="2000-01-01" max="2100-12-31" required>
                <small class="text-muted" style="font-size: 0.75rem; color: #6b7280;">Năm phải trong khoảng 2000-2100</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Ngày kết thúc <span style="color: #ef4444;">*</span>
                </label>
                <input type="date" name="end_date" id="end_date" class="form-control" 
                       value="<?= $schedule['end_date'] ?>" min="2000-01-01" max="2100-12-31" required>
                <small class="text-muted" style="font-size: 0.75rem; color: #6b7280;">Phải sau ngày bắt đầu</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Giá tour (VNĐ) <span style="color: #ef4444;">*</span>
                </label>
                <input type="number" name="price" class="form-control" 
                       value="<?= $schedule['price'] ?? 0 ?>" required min="0">
                <small class="text-muted" style="font-size: 0.75rem; color: #6b7280;">Giá cho lịch khởi hành này</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Slot tối đa <span style="color: #ef4444;">*</span>
                </label>
                <input type="number" name="max_slots" class="form-control" 
                       value="<?= $schedule['max_slots'] ?>" required min="1">
                <small class="text-muted" style="font-size: 0.75rem; color: #6b7280;">Số lượng người tối đa cho lịch khởi hành này</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="sap_mo" <?= $schedule['status']=='sap_mo'?'selected':'' ?>>Sắp mở</option>
                    <option value="dang_mo" <?= $schedule['status']=='dang_mo'?'selected':'' ?>>Đang mở</option>
                    <option value="da_dong" <?= $schedule['status']=='da_dong'?'selected':'' ?>>Đã đóng</option>
                </select>
            </div>

            <div class="col-12">
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem;">
                    <a href="?act=schedule-list&id=<?= $schedule['tour_id'] ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Lưu thay đổi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function validateScheduleForm() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const price = document.querySelector('input[name="price"]').value;
    const maxSlots = document.querySelector('input[name="max_slots"]').value;
    
    // Validate dates
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (end < start) {
            alert('Ngày kết thúc phải sau ngày bắt đầu!');
            return false;
        }
        
        // Validate year range
        const startYear = start.getFullYear();
        const endYear = end.getFullYear();
        
        if (startYear < 2000 || startYear > 2100) {
            alert('Năm bắt đầu phải trong khoảng 2000-2100!');
            return false;
        }
        
        if (endYear < 2000 || endYear > 2100) {
            alert('Năm kết thúc phải trong khoảng 2000-2100!');
            return false;
        }
    }
    
    // Validate price
    if (price && (isNaN(price) || parseFloat(price) < 0)) {
        alert('Giá tour phải là số dương!');
        return false;
    }
    
    // Validate max slots
    if (maxSlots && (isNaN(maxSlots) || parseInt(maxSlots) < 1)) {
        alert('Slot tối đa phải là số nguyên dương!');
        return false;
    }
    
    return true;
}

// Auto-update end_date min when start_date changes
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    if (startDate) {
        endDateInput.min = startDate;
        if (endDateInput.value && endDateInput.value < startDate) {
            endDateInput.value = startDate;
        }
    }
});
</script>

<?php include "views/layout/footer.php"; ?>
