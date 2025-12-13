<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<style>
/* CSS cho form tìm kiếm */
.action-bar-search {
    position: relative;
}

.action-bar-search i.bi-search {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 1;
}

.action-bar-search input[type="text"] {
    padding-left: 35px !important;
    border-radius: 4px 0 0 4px !important;
    border-right: none !important;
}

.action-bar-search .btn {
    border-radius: 0 4px 4px 0 !important;
    border-left: none !important;
}
</style>

<div class="page-header">
    <h1>Quản lý Booking</h1>
    <?php if (!empty($_GET['keyword'])): ?>
        <p class="text-muted">Kết quả tìm kiếm cho: "<strong><?= htmlspecialchars($_GET['keyword']) ?></strong>" 
           (<?= count($bookings) ?> kết quả)</p>
    <?php elseif (!empty($_GET['time_status']) && $_GET['time_status'] !== 'all'): ?>
        <p class="text-muted">Lọc theo trạng thái: 
           <strong>
           <?php
           $statusText = [
               'dang_dien_ra' => 'Đang diễn ra',
               'sap_dien_ra' => 'Sắp diễn ra', 
               'da_ket_thuc' => 'Đã kết thúc'
           ];
           echo $statusText[$_GET['time_status']] ?? $_GET['time_status'];
           ?>
           </strong>
           (<?= count($bookings) ?> kết quả)</p>
    <?php endif; ?>
</div>

<?php
    $currentTimeFilter = $_GET['time_status'] ?? 'all';
?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        <strong>Thành công!</strong> Booking đã được tạo và hiển thị trong danh sách.
    </div>
<?php endif; ?>

<div class="action-bar">
    <div class="action-bar-left">
        <form method="get" style="display: flex; gap: 12px; align-items: center;">
            <input type="hidden" name="act" value="booking-list">
            <div class="action-bar-search" style="display: flex; align-items: center;">
                <i class="bi bi-search"></i>
                <input type="text" name="keyword" placeholder="Tìm kiếm tên tour, khách hàng, ID..." 
                       value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                <button type="submit" class="btn btn-sm btn-primary" title="Tìm kiếm">
                    <i class="bi bi-search"></i> Tìm
                </button>
                <?php if (!empty($_GET['keyword'])): ?>
                    <a href="?act=booking-list<?= !empty($_GET['time_status']) ? '&time_status=' . htmlspecialchars($_GET['time_status']) : '' ?>" 
                       class="btn btn-sm btn-outline-secondary" title="Xóa tìm kiếm">
                        <i class="bi bi-x"></i>
                    </a>
                <?php endif; ?>
            </div>

            <div>
                <select name="time_status" class="form-select" onchange="this.form.submit()" style="min-width: 200px;">
                    <option value="all" <?php echo $currentTimeFilter === 'all' ? 'selected' : ''; ?>>
                        Tất cả thời gian
                    </option>
                    <option value="dang_dien_ra" <?php echo $currentTimeFilter === 'dang_dien_ra' ? 'selected' : ''; ?>>
                        Đang diễn ra
                    </option>
                    <option value="sap_dien_ra" <?php echo $currentTimeFilter === 'sap_dien_ra' ? 'selected' : ''; ?>>
                        Sắp diễn ra
                    </option>
                    <option value="da_ket_thuc" <?php echo $currentTimeFilter === 'da_ket_thuc' ? 'selected' : ''; ?>>
                        Đã kết thúc
                    </option>
                </select>
            </div>
        </form>
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
                        <?php if (!empty($_GET['keyword'])): ?>
                            <p>Không tìm thấy booking nào phù hợp với từ khóa "<strong><?= htmlspecialchars($_GET['keyword']) ?></strong>".</p>
                            <p><a href="?act=booking-list<?= !empty($_GET['time_status']) ? '&time_status=' . htmlspecialchars($_GET['time_status']) : '' ?>">Xem tất cả booking</a></p>
                        <?php else: ?>
                            <p>Chưa có booking nào. <a href="index.php?act=add-booking">Tạo booking mới</a></p>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php else: ?>
            <?php foreach ($bookings as $item): ?>

                <?php 
                // Sử dụng dữ liệu từ model đã tính sẵn
            $countCustomer  = $item['so_khach'] ?? 0;
            $tongGia        = $item['tong_gia'] ?? 0;
            $hdvName        = $item['hdv_name'] ?? null;
            $createdAt      = $item['created_at'] ?? null;
            $paymentStatus  = $item['trang_thai_thanh_toan'] ?? 'chua_thanh_toan';
            $bookingStatus  = $item['trang_thai'] ?? 'cho_xac_nhan';
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
                            // Hiển thị trạng thái BOOKING (diễn ra / sắp diễn ra / hoàn tất / hủy)
                            if ($bookingStatus === 'cho_xac_nhan') {
                                echo "<span class='status-badge status-warning'><i class=\"bi bi-clock-history\"></i> Chờ xác nhận</span>";
                            } elseif ($bookingStatus === 'da_xac_nhan') {
                                echo "<span class='status-badge status-info'><i class=\"bi bi-check-circle\"></i> Đã xác nhận</span>";
                            } elseif ($bookingStatus === 'dang_dien_ra') {
                                echo "<span class='status-badge status-info'><i class=\"bi bi-play-circle-fill\"></i> Đang diễn ra</span>";
                            } elseif ($bookingStatus === 'hoan_tat') {
                                echo "<span class='status-badge status-success'><i class=\"bi bi-check-circle-fill\"></i> Hoàn tất</span>";
                            } else {
                                echo "<span class='status-badge status-danger'><i class=\"bi bi-x-circle-fill\"></i> Đã hủy</span>";
                            }
                        ?>
                        </div>
                    </td>

                    <td>
                        <div class="status-cell">
                        <?php 
                            // Hiển thị trạng thái THANH TOÁN
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
