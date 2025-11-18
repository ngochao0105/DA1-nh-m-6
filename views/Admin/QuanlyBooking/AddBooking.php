
<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="container mt-4">
    <h3>Thêm Booking Mới</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= ($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Chọn Tour *</label>
            <select name="id_tour" class="form-select" required>
                <option value="">-- Chọn tour --</option>
                <?php foreach ($tours as $tour): ?>
                    <option value="<?= $tour['id'] ?>">
                        <?= ($tour['tour_name']) ?> (<?= number_format($tour['price']) ?> VNĐ)
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
            <label class="form-label">Tên khách hàng *</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Số điện thoại *</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="col-md-6">
            <label class="form-label">Số người *</label>
            <input type="number" name="people_count" class="form-control" required min="1" value="1">
        </div>

        <div class="col-md-6">
            <label class="form-label">Ngày đặt *</label>
            <input type="date" name="booking_date" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select">
        <option value="">Chờ xác nhận</option>
        <option value="Ăn chay">Đã cọc</option>
        <option value="Dị ứng">Hủy</option>
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
    <div class="col-md-12" id="specialDetailBox" style="display:none;">
    <label class="form-label">Ghi rõ yêu cầu đặc biệt *</label>
    <textarea name="special_request" class="form-control" rows="4"
        placeholder="Ghi rõ yêu cầu của khách"></textarea>
    </div>
        <div class="col-md-12 text-end">
            <button class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Tạo booking
            </button>
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