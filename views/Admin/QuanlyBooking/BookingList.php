<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Quản lý Booking</h1>
</div>

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
                <th>Ngày đi</th>
                <th>Trạng thái</th>
                <th>Cập nhật</th>
                <th>Chi tiết</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($bookings as $item): ?>

                <?php 
                // Lấy số khách theo booking
                $sql = "SELECT COUNT(*) FROM khachtour WHERE id_booking = ".$item['id'];
                $countCustomer = $this->modelBooking->conn->query($sql)->fetchColumn();
                ?>

                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><strong><?= $item['tour_name'] ?></strong></td>

                    <td><?= $countCustomer ?> khách</td>

                    <td><?= $item['ngay_di'] ?></td>

                    <td>
                        <?php 
                            $status = $item['trang_thai'];
                            if($status=='cho_xac_nhan') echo "<span class='badge bg-warning'>Chờ xác nhận</span>";
                            elseif($status=='da_coc') echo "<span class='badge bg-info'>Đã cọc</span>";
                            elseif($status=='hoan_tat') echo "<span class='badge bg-success'>Hoàn tất</span>";
                            else echo "<span class='badge bg-danger'>Hủy</span>";
                        ?>
                    </td>

                    <td>
                        <form action="index.php?act=update-booking-status" method="POST">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <select name="status" class="form-select form-select-sm">
                                <option value="cho_xac_nhan" <?= $status=='cho_xac_nhan'?'selected':'' ?>>Chờ xác nhận</option>
                                <option value="da_coc" <?= $status=='da_coc'?'selected':'' ?>>Đã cọc</option>
                                <option value="hoan_tat" <?= $status=='hoan_tat'?'selected':'' ?>>Hoàn tất</option>
                                <option value="huy" <?= $status=='huy'?'selected':'' ?>>Hủy</option>
                            </select>
                            <button class="btn btn-sm btn-primary">Lưu</button>
                        </form>
                    </td>

                    <td>
                        <a href="index.php?act=booking-logs&id=<?= $item['id'] ?>" 
                           class="btn btn-sm btn-secondary">
                           <i class="bi bi-clock-history"></i>
                        </a>
                    </td>
                    <td>
                    <a href="index.php?act=booking-detail&id=<?= $item['id'] ?>"
                        class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i> Chi tiết
                    </a>
                    </td>

                </tr>

            <?php endforeach; ?>
        </tbody>

    </table>
</div>

<?php include "views/layout/footer.php"; ?>
