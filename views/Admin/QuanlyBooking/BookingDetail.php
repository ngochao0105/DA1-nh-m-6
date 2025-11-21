    <?php include "views/layout/header.php"; ?>
    <?php include "views/layout/sidebar.php"; ?>

    <style>
    .detail-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    }
    .section-title {
        font-weight: 600;
        font-size: 20px;
        margin-bottom: 15px;
        color: #1d3557;
    }
    .info-box {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 12px;
    }
    .info-box b {
        width: 150px;
        display: inline-block;
    }
    .table-custom {
        background: white;
        border-radius: 10px;
    }
    </style>

    <div class="container mt-4">
        <h2 class="mb-3 fw-bold">Chi tiết Booking #<?= $booking['id'] ?></h2>

        <a href="index.php?act=booking-list" class="btn btn-secondary mb-3">← Quay về danh sách</a>

        <div class="detail-card">

            <!-- Booking Info -->
            <h3 class="section-title">Thông tin Booking</h3>
            <div class="info-box">
                <b>Tour:</b> <?= ($booking['tour_name'] ) ?><br>
                <b>Ngày đi:</b> <?= ($booking['ngay_di'] ) ?><br>
                <b>Trạng thái:</b>
                <span class="badge bg-info"><?= ($booking['trang_thai'] ?? $booking['status'] ?? 'Không rõ') ?></span><br>
                <b>Loại đặt:</b> <?= ($booking['loai_dat'] ?? $booking['loai_dat'] ) ?><br>
            </div>

            <!-- HDV -->
            <h3 class="section-title">Hướng dẫn viên</h3>

        <?php if (!empty($hdv) && is_array($hdv)): ?>
            <div class="info-box">
                <b>Họ tên:</b> <?= ($hdv['full_name'] ?? $hdv['ho_ten'] ) ?><br>
                <b>Loại HDV:</b> <?= ($hdv['guide_type'] ?? $hdv['loai_hdv'] ) ?><br>
                <b>SĐT:</b> <?= ($hdv['phone'] ?? $hdv['sdt'] ) ?><br>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Chưa phân công HDV.</div>
        <?php endif; ?>

            <!-- Customers -->
            <h3 class="section-title">Danh sách khách</h3>

            <table class="table table-bordered table-custom">
                <thead class="table-dark">
                    <tr>
                        <th>Họ tên</th>
                        <th>SĐT</th>
                        <th>Loại</th>
                        <th>Yêu cầu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers) && is_array($customers)): ?>
                        <?php foreach ($customers as $c): ?>
                            <tr>
                                <td><?= ($c['ten_khach'] ?? $c['name'] ) ?></td>
                                <td><?= ($c['sdt'] ?? $c['phone'] ) ?></td>
                                <td><?= ($c['loai_khach'] ?? $c['loai'] ) ?></td>
                                <td><?= ($c['yeu_cau_dac_biet'] ?? $c['yeu_cau'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">Không có khách</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php include "views/layout/footer.php"; ?>
