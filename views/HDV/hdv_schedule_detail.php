<?php require_once 'header_hdv.php'; ?>
<?php require_once 'sidebar_hdv.php'; ?>

<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <a href="?act=hdv_tour_schedule" class="back-btn">
                <i class="bi bi-chevron-left"></i>
                Quay lại
            </a>
            <h1>
                <i class="bi bi-people-fill"></i>
                Chi tiết khách hàng
            </h1>
            <p class="subtitle">Danh sách khách hàng tham gia tour</p>
        </div>
    </div>

    <!-- Thông báo -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-<?= $_GET['msg'] === 'confirmed' ? 'success' : 'danger' ?>">
            <?php if ($_GET['msg'] === 'confirmed'): ?>
                <i class="bi bi-check-circle"></i> Đã xác nhận nhận tour thành công!
            <?php elseif ($_GET['msg'] === 'rejected'): ?>
                <i class="bi bi-x-circle"></i> Đã từ chối tour.
            <?php else: ?>
                <i class="bi bi-exclamation-circle"></i> Có lỗi xảy ra. Vui lòng thử lại.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="page-content">
        <!-- Thông tin booking -->
        <div class="booking-info-card">
            <div class="info-section">
                <h3><?= htmlspecialchars($bookingDetail['tour_name'] ?? '') ?></h3>
                <p class="location">
                    <i class="bi bi-geo-alt-fill"></i>
                    <?= htmlspecialchars($bookingDetail['destination'] ?? '') ?>
                </p>
            </div>

            <div class="info-grid">
                <div class="info-box">
                    <label>Ngày khởi hành</label>
                    <p><?= !empty($bookingDetail['ngay_di']) ? date('d/m/Y', strtotime($bookingDetail['ngay_di'])) : 'Chưa cập nhật' ?></p>
                </div>
                <div class="info-box">
                    <label>Thời lượng</label>
                    <p><?= htmlspecialchars($bookingDetail['duration'] ?? '') ?> ngày</p>
                </div>
                <div class="info-box">
                    <label>Tổng khách</label>
                    <p><?= htmlspecialchars($bookingDetail['so_khach'] ?? '') ?> khách</p>
                </div>
                <div class="info-box">
                    <label>Trạng thái</label>
                    <p>
                        <span class="status-badge status-<?= htmlspecialchars($bookingDetail['trang_thai'] ?? '') ?>">
                            <?php 
                            $statusMap = [
                                'cho_xac_nhan' => 'Chờ xác nhận',
                                'da_xac_nhan' => 'Đã xác nhận',
                                'dang_dien_ra' => 'Đang diễn ra',
                                'da_hoan_thanh' => 'Đã hoàn thành',
                                'da_huy' => 'Đã hủy'
                            ];
                            $statusKey = $bookingDetail['trang_thai'] ?? '';
                            echo $statusMap[$statusKey] ?? $statusKey;
                            ?>
                        </span>
                    </p>
                </div>
            </div>

            <!-- Nút hành động (chỉ hiển thị khi chờ xác nhận) -->
            <?php if (($bookingDetail['trang_thai'] ?? '') === 'cho_xac_nhan'): ?>
                <div class="action-buttons">
                    <form method="POST" action="?act=hdv_confirm_booking" style="display: inline;">
                        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($bookingDetail['id'] ?? '') ?>">
                        <button type="submit" name="action" value="confirm" class="btn btn-confirm">
                            <i class="bi bi-check2-circle"></i>
                            Xác nhận nhận tour
                        </button>
                    </form>
                    <form method="POST" action="?act=hdv_confirm_booking" style="display: inline;">
                        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($bookingDetail['id'] ?? '') ?>">
                        <button type="submit" name="action" value="reject" class="btn btn-reject" onclick="return confirm('Bạn chắc chắn muốn từ chối tour này?')">
                            <i class="bi bi-x-circle"></i>
                            Từ chối tour
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Danh sách khách hàng -->
        <?php $canAttendance = (($bookingDetail['trang_thai'] ?? '') === 'dang_dien_ra'); ?>
        <div class="customers-section">
            <div class="section-header">
                <div class="section-header-left">
                    <h2>
                        <i class="bi bi-list-ul"></i>
                        Danh sách khách hàng
                    </h2>
                </div>
                <div class="section-header-actions">
                    <span class="customer-count"><?= count($customers) ?> khách</span>
                    <?php if (!empty($customers)): ?>
                        <button type="button" class="btn btn-primary btn-save-attendance" id="attendanceSaveBtn" <?= $canAttendance ? '' : 'disabled' ?>>
                            <i class="bi bi-cloud-check"></i>
                            Lưu điểm danh
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!$canAttendance): ?>
                <div class="alert alert-warning attendance-alert">
                    <i class="bi bi-info-circle"></i>
                    Điểm danh chỉ khả dụng khi tour đang ở trạng thái <strong>Đang diễn ra</strong>.
                </div>
            <?php endif; ?>

            <?php if (empty($customers)): ?>
                <div class="empty-customers">
                    <i class="bi bi-inbox"></i>
                    <p>Chưa có khách hàng nào</p>
                </div>
            <?php else: ?>
                <div class="customers-table-wrapper">
                    <table class="customers-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên khách hàng</th>
                                <th>Số điện thoại</th>
                                <th>Điểm danh</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $idx => $customer): ?>
                                <?php
                                    // Chuẩn hóa dữ liệu để tránh thiếu khóa giữa các nguồn
                                    $phone = $customer['so_dien_thoai'] ?? $customer['sdt'] ?? $customer['phone'] ?? '';
                                    $note  = $customer['ghi_chu'] ?? $customer['yeu_cau_dac_biet'] ?? $customer['note'] ?? '';
                                    $attendanceValue = !empty($customer['da_checkin']) ? 'present' : 'absent';
                                ?>
                                <tr>
                                    <td class="text-center"><?= $idx + 1 ?></td>
                                    <td>
                                        <span class="customer-name">
                                            <?= htmlspecialchars($customer['ten_khach'] ?? '') ?>
                                        </span>
                                    </td>
                                    <td><?= !empty($phone) ? htmlspecialchars($phone) : 'N/A' ?></td>
                                    <td class="attendance-cell">
                                        <select 
                                            class="form-select form-select-sm attendance-select"
                                            data-customer-id="<?= (int)($customer['id'] ?? 0) ?>"
                                            <?= $canAttendance ? '' : 'disabled' ?>
                                        >
                                            <option value="present" <?= $attendanceValue === 'present' ? 'selected' : '' ?>>Có mặt</option>
                                            <option value="absent" <?= $attendanceValue === 'absent' ? 'selected' : '' ?>>Vắng mặt</option>
                                        </select>
                                    </td>
                                    <td class="note-cell">
                                        <?php if (!empty($note)): ?>
                                            <span class="note-text"><?= htmlspecialchars($note) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Mô tả tour -->
        <?php if (!empty($bookingDetail['description'])): ?>
            <div class="description-section">
                <h3>Mô tả tour</h3>
                <p><?= htmlspecialchars($bookingDetail['description'] ?? '') ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const saveBtn = document.getElementById('attendanceSaveBtn');
    if (!saveBtn || saveBtn.disabled) {
        return;
    }

    saveBtn.addEventListener('click', () => {
        const selects = Array.from(document.querySelectorAll('.attendance-select'));
        if (!selects.length) {
            alert('Không có khách hàng để lưu.');
            return;
        }

        const payload = selects
            .map(select => ({
                id: parseInt(select.dataset.customerId, 10) || 0,
                status: select.value
            }))
            .filter(item => item.id > 0);

        if (!payload.length) {
            alert('Không xác định được khách hàng.');
            return;
        }

        const originalText = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...';

        fetch('index.php?act=hdv_update_attendance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: new URLSearchParams({
                data: JSON.stringify(payload)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                saveBtn.innerHTML = '<i class="bi bi-check2-circle"></i> Đã lưu';
                setTimeout(() => {
                    saveBtn.innerHTML = originalText;
                }, 2000);
            } else {
                saveBtn.innerHTML = originalText;
                alert(data.message || 'Không thể lưu điểm danh.');
            }
        })
        .catch(() => {
            saveBtn.innerHTML = originalText;
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        })
        .finally(() => {
            saveBtn.disabled = false;
        });
    });
});
</script>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.page-header-content {
    position: relative;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
}

.back-btn:hover {
    color: white;
}

.page-header-content h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.subtitle {
    margin: 8px 0 0 0;
    font-size: 14px;
    opacity: 0.9;
}

/* Alert Messages */
.alert {
    padding: 14px 20px;
    border-radius: 8px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.booking-info-card {
    background: white;
    padding: 24px;
    border-radius: 8px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.booking-info-card h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 8px;
}

.location {
    margin: 0;
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.info-box {
    background: #f8f9fa;
    padding: 16px;
    border-radius: 6px;
}

.info-box label {
    display: block;
    font-size: 11px;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.info-box p {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-cho_xac_nhan {
    background-color: #fff3cd;
    color: #856404;
}

.status-da_xac_nhan {
    background-color: #d1ecf1;
    color: #0c5460;
}

.status-dang_dien_ra {
    background-color: #d4edda;
    color: #155724;
}

.status-da_hoan_thanh {
    background-color: #d1ecf1;
    color: #0c5460;
}

.status-da_huy {
    background-color: #f8d7da;
    color: #721c24;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 12px;
    margin: 24px 0;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-confirm {
    background-color: #28a745;
    color: white;
}

.btn-confirm:hover {
    background-color: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-reject {
    background-color: #dc3545;
    color: white;
}

.btn-reject:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.customers-section {
    background: white;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f0f0f0;
    gap: 12px;
    flex-wrap: wrap;
}

.section-header-left h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.customer-count {
    background: #667eea;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.btn-save-attendance {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    border-radius: 20px;
    padding: 6px 16px;
}

.btn-save-attendance:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.attendance-alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 13px;
}

.attendance-cell select:disabled {
    background-color: #f5f5f5;
    cursor: not-allowed;
}

.empty-customers {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.empty-customers i {
    font-size: 48px;
    display: block;
    margin-bottom: 10px;
    opacity: 0.5;
}

.customers-table-wrapper {
    overflow-x: auto;
}

.customers-table {
    width: 100%;
    border-collapse: collapse;
}

.customers-table thead {
    background: #f8f9fa;
}

.customers-table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    color: #666;
    text-transform: uppercase;
    border-bottom: 2px solid #e0e0e0;
}

.customers-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
    color: #333;
}

.customers-table tbody tr:hover {
    background: #f8f9fa;
}

.text-center {
    text-align: center;
}

.customer-name {
    font-weight: 600;
    color: #333;
}

.note-cell {
    max-width: 200px;
}

.note-text {
    display: block;
    word-wrap: break-word;
    color: #555;
    font-size: 13px;
}

.text-muted {
    color: #999;
}

.description-section {
    background: white;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.description-section h3 {
    margin: 0 0 16px 0;
    font-size: 18px;
    font-weight: 700;
    color: #333;
}

.description-section p {
    margin: 0;
    color: #555;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .page-header {
        padding: 20px;
    }

    .page-header-content h1 {
        font-size: 22px;
    }

    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .customers-table {
        font-size: 12px;
    }

    .customers-table th,
    .customers-table td {
        padding: 10px 8px;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php require_once 'footer_hdv.php'; ?>
