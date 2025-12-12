<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="margin-bottom: 0.5rem;">
                <i class="bi bi-calendar3"></i> Lịch trình Tour
            </h1>
            <p style="color: var(--text-secondary); margin: 0; font-size: 1rem;">
                <strong><?= ($tour['tour_name']) ?></strong> - <?= ($tour['destination']) ?>
            </p>
        </div>
        <a href="?act=tour-list" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Action Bar -->
<div class="action-bar">
    <div class="action-bar-left">
        <div class="action-bar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Tìm kiếm lịch trình...">
        </div>
        <div class="action-bar-filter">
            <select>
                <option>Tất cả</option>
                <option>Sắp mở</option>
                <option>Đang mở</option>
                <option>Đã đóng</option>
            </select>
        </div>
    </div>
    <div class="action-bar-right">
        <button class="btn btn-secondary">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
        </button>
        <a href="?act=schedule-create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm lịch trình
        </a>
    </div>
</div>

<!-- Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th class="sortable">
                    ID
                    <div class="sort-icons">
                        <i class="bi bi-caret-up"></i>
                        <i class="bi bi-caret-down"></i>
                    </div>
                </th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Giá</th>
                <th>Slot tối đa</th>
                <th>Đã đặt</th>
                <th>Còn lại</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($schedules)): ?>
                <?php foreach ($schedules as $sc): ?>
                <tr>
                    <td><?= $sc['id'] ?></td>
                    <td><strong><?= date('d/m/Y', strtotime($sc['start_date'])) ?></strong></td>
                    <td><strong><?= date('d/m/Y', strtotime($sc['end_date'])) ?></strong></td>
                    <td><strong style="color: var(--primary-blue);"><?= number_format($sc['price'] ?? 0, 0, ',', '.') ?> VNĐ</strong></td>
                    <td><?= $sc['max_slots'] ?></td>
                    <td><?= $sc['booked_slots'] ?? 0 ?></td>
                    <td>
                        <strong style="color: var(--success);">
                            <?= ($sc['max_slots'] - ($sc['booked_slots'] ?? 0)) ?>
                        </strong>
                    </td>
                    <td>
                        <select class="status-select" data-schedule-id="<?= $sc['id'] ?>" style="border: none; background: transparent; padding: 0.25rem 0.5rem; border-radius: 0.375rem; cursor: pointer; font-weight: 500; min-width: 100px;">
                            <option value="sap_mo" <?= $sc['status']=='sap_mo'?'selected':'' ?> style="background: #fbbf24; color: #000;">Sắp mở</option>
                            <option value="dang_mo" <?= $sc['status']=='dang_mo'?'selected':'' ?> style="background: #10b981; color: #fff;">Đang mở</option>
                            <option value="da_dong" <?= $sc['status']=='da_dong'?'selected':'' ?> style="background: #ef4444; color: #fff;">Đã đóng</option>
                        </select>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="?act=schedule-edit&id=<?= $sc['id'] ?>" 
                               class="table-action-btn edit" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="?act=schedule-delete&id=<?= $sc['id'] ?>" 
                               class="table-action-btn delete"
                               onclick="return confirm('Bạn có chắc muốn xóa lịch trình này?')"
                               title="Xóa">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <p>Chưa có lịch trình nào cho tour này.</p>
                        <a href="?act=schedule-create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary" style="margin-top: 1rem;">
                            <i class="bi bi-plus-circle"></i> Thêm lịch trình đầu tiên
                        </a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "views/layout/footer.php"; ?>

<style>
.badge.bg-success,
.badge.bg-danger {
    color: #fff !important;
}
.badge.bg-warning {
    color: #000 !important;
}

.status-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.5rem center;
    padding-right: 1.5rem !important;
}

.status-select:focus {
    outline: 2px solid var(--primary-blue);
    outline-offset: 2px;
}

.status-select option[value="sap_mo"] {
    background: #fbbf24;
    color: #000;
}

.status-select option[value="dang_mo"] {
    background: #10b981;
    color: #fff;
}

.status-select option[value="da_dong"] {
    background: #ef4444;
    color: #fff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.status-select');
    
    statusSelects.forEach(select => {
        // Update visual style based on selected value
        updateSelectStyle(select);
        
        select.addEventListener('change', function() {
            const scheduleId = this.dataset.scheduleId;
            const newStatus = this.value;
            const originalValue = this.dataset.originalValue || this.querySelector('option[selected]')?.value;
            
            // Store original value if not set
            if (!this.dataset.originalValue) {
                this.dataset.originalValue = originalValue;
            }
            
            // Show loading state
            const originalBg = this.style.background;
            this.style.opacity = '0.6';
            this.disabled = true;
            
            // Send AJAX request
            const formData = new FormData();
            formData.append('id', scheduleId);
            formData.append('status', newStatus);
            
            fetch('index.php?act=schedule-update-status', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                this.disabled = false;
                this.style.opacity = '1';
                
                if (data.success) {
                    // Update visual style
                    updateSelectStyle(this);
                    this.dataset.originalValue = newStatus;
                    
                    // Show success message (optional)
                    showNotification('Cập nhật trạng thái thành công', 'success');
                } else {
                    // Revert to original value
                    this.value = this.dataset.originalValue;
                    updateSelectStyle(this);
                    showNotification(data.message || 'Có lỗi xảy ra', 'error');
                }
            })
            .catch(error => {
                this.disabled = false;
                this.style.opacity = '1';
                this.value = this.dataset.originalValue;
                updateSelectStyle(this);
                showNotification('Có lỗi xảy ra khi cập nhật', 'error');
                console.error('Error:', error);
            });
        });
    });
    
    function updateSelectStyle(select) {
        const value = select.value;
        if (value === 'sap_mo') {
            select.style.background = '#fbbf24';
            select.style.color = '#000';
        } else if (value === 'dang_mo') {
            select.style.background = '#10b981';
            select.style.color = '#fff';
        } else if (value === 'da_dong') {
            select.style.background = '#ef4444';
            select.style.color = '#fff';
        }
    }
    
    function showNotification(message, type) {
        // Create a simple notification
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
});
</script>