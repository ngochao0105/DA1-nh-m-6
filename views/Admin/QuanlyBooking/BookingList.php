<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Quản lý Booking</h1>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        <strong>Thành công!</strong> Booking đã được tạo và hiển thị trong danh sách.
    </div>
<?php endif; ?>

<div class="action-bar">
    <div class="action-bar-left">
        <div class="action-bar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Tìm kiếm...">
        </div>
    </div>
    <div class="action-bar-right">
        <a href="index.php?act=add-booking" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Booking
        </a>
    </div>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tour</th>
                <th>Số khách</th>
                <th>Tổng giá</th>
                <th>Ngày đặt</th>
                <th>Ngày đi</th>
                <th>Trạng thái Booking</th>
                <th>Trạng thái Thanh toán</th>
                <th>Hướng dẫn viên</th>
                <th>Chi tiết</th>
                <th>Xóa</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="11" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                        <i class="bi bi-inbox" style="font-size: 3rem; display: block; margin-bottom: 15px; opacity: 0.5;"></i>
                        <p>Chưa có booking nào. <a href="index.php?act=add-booking">Tạo booking mới</a></p>
                    </td>
                </tr>
            <?php else: ?>
            <?php foreach ($bookings as $item): ?>

                <?php 
                // Sử dụng dữ liệu từ model đã tính sẵn
                $countCustomer = $item['so_khach'] ?? 0;
                $tongGia = $item['tong_gia'] ?? 0;
                $hdvName = $item['hdv_name'] ?? null;
                $createdAt = $item['created_at'] ?? null;
                $paymentStatus = $item['trang_thai_thanh_toan'] ?? 'chua_thanh_toan';
                ?>

                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><strong><?= htmlspecialchars($item['tour_name'] ?? 'N/A') ?></strong></td>

                    <td><?= $countCustomer ?> khách</td>

                    <td>
                        <strong style="color: var(--primary-blue);">
                            <?= number_format($tongGia, 0, ',', '.') ?> VNĐ
                        </strong>
                    </td>

                    <td>
                        <?php 
                        if ($createdAt) {
                            echo date('d/m/Y', strtotime($createdAt));
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>

                    <td>
                        <?php 
                        if ($item['ngay_di']) {
                            // Kiểm tra định dạng ngày
                            $ngayDi = $item['ngay_di'];
                            // Nếu là định dạng YYYY-MM-DD, convert sang d/m/Y
                            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngayDi)) {
                                echo date('d/m/Y', strtotime($ngayDi));
                            } else {
                                // Nếu đã là định dạng khác, hiển thị nguyên
                                echo htmlspecialchars($ngayDi);
                            }
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>

                    <td>
                        <div class="status-cell">
                        <?php 
                            $status = $item['trang_thai'] ?? 'cho_xac_nhan';
                            if($status=='cho_xac_nhan') {
                                echo "<span class='status-badge status-warning'><i class='bi bi-clock-history'></i> Chờ xác nhận</span>";
                            } elseif($status=='da_xac_nhan') {
                                echo "<span class='status-badge status-info'><i class='bi bi-check-circle'></i> Đã xác nhận</span>";
                            } elseif($status=='dang_dien_ra') {
                                echo "<span class='status-badge status-info'><i class='bi bi-play-circle-fill'></i> Đang diễn ra</span>";
                            } elseif($status=='hoan_tat') {
                                echo "<span class='status-badge status-success'><i class='bi bi-check-circle-fill'></i> Hoàn tất</span>";
                            } else {
                                echo "<span class='status-badge status-danger'><i class='bi bi-x-circle-fill'></i> Đã hủy</span>";
                            }
                        ?>
                        </div>
                    </td>

                    <td>
                        <div class="status-cell">
                        <?php 
                            if($paymentStatus=='chua_thanh_toan') {
                                echo "<span class='status-badge status-secondary'><i class='bi bi-x-circle'></i> Chưa thanh toán</span>";
                            } elseif($paymentStatus=='da_coc') {
                                echo "<span class='status-badge status-info'><i class='bi bi-wallet2'></i> Đã cọc</span>";
                            } elseif($paymentStatus=='da_thanh_toan_du') {
                                echo "<span class='status-badge status-success'><i class='bi bi-check-circle-fill'></i> Đã thanh toán đủ</span>";
                            } else {
                                echo "<span class='status-badge status-secondary'><i class='bi bi-x-circle'></i> Chưa thanh toán</span>";
                            }
                        ?>
                        </div>
                    </td>

                    <td>
                        <div class="guide-cell">
                        <?php if ($hdvName): ?>
                            <div class="guide-info">
                                <i class="bi bi-person-badge"></i>
                                <span class="guide-name"><?= htmlspecialchars($hdvName) ?></span>
                            </div>
                        <?php else: ?>
                            <div class="guide-info guide-empty">
                                <i class="bi bi-person-x"></i>
                                <span class="guide-name">Chưa gán</span>
                            </div>
                        <?php endif; ?>
                        </div>
                    </td>

                    <td>
                        <a href="index.php?act=booking-detail&id=<?= $item['id'] ?>"
                            class="btn btn-sm btn-info">
                            Chi tiết
                        </a>
                    </td>

                    <td>
                        <a href="index.php?act=delete-booking&id=<?= $item['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa booking này?');">
                           Xóa
                        </a>
                    </td>

                </tr>

            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

    </table>
</div>

<?php include "views/layout/footer.php"; ?>
