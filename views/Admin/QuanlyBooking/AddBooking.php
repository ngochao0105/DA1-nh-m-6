<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Thêm Booking Mới</h1>
</div>

<div style="background: white; border-radius: 0.75rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Chọn Tour <span style="color: #ef4444;">*</span></label>
                <select name="id_tour" class="form-select" required>
                    <option value="">-- Chọn tour --</option>
                    <?php foreach ($tours as $tour): ?>
                        <option value="<?= $tour['id'] ?>">
                            <?= htmlspecialchars($tour['tour_name']) ?> (<?= number_format($tour['price']) ?> VNĐ)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Loại booking</label>
                <select name="booking_type" class="form-select">
                    <option value="individual">Khách lẻ (1-2 người)</option>
                    <option value="group">Đoàn (nhiều người)</option>
                    <option value="company">Công ty / Tổ chức</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tên khách hàng <span style="color: #ef4444;">*</span></label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Số điện thoại <span style="color: #ef4444;">*</span></label>
                <input type="text" name="phone" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Số người <span style="color: #ef4444;">*</span></label>
                <input type="number" name="people_count" class="form-control" required min="1" value="1">
            </div>

            <div class="col-md-6">
                <label class="form-label">Ngày đặt <span style="color: #ef4444;">*</span></label>
                <input type="date" name="booking_date" class="form-control" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="pending">Chờ xác nhận</option>
                    <option value="deposit">Đã cọc</option>
                    <option value="cancelled">Hủy</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Yêu cầu đặc biệt</label>
                <select name="special_type" id="specialSelect" class="form-select">
                    <option value="">Không có</option>
                    <option value="Ăn chay">Ăn chay</option>
                    <option value="Dị ứng">Dị ứng</option>
                    <option value="Bệnh lý">Bệnh lý</option>
                    <option value="Yêu cầu khác">Yêu cầu khác</option>
                </select>
            </div>
            
            <div class="col-12" id="specialDetailBox" style="display:none;">
                <label class="form-label">Ghi rõ yêu cầu đặc biệt <span style="color: #ef4444;">*</span></label>
                <textarea name="special_request" class="form-control" rows="4"
                    placeholder="Ghi rõ yêu cầu của khách"></textarea>
            </div>

            <div class="col-12">
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem;">
                    <a href="?act=booking-list" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Tạo booking
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('specialSelect').addEventListener('change', function () {
    let box = document.getElementById('specialDetailBox');
    if (this.value === 'Yêu cầu khác') {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
});
</script>

<?php include "views/layout/footer.php"; ?>
