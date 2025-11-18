<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="container-fluid px-4 mt-4">
    <h3>Quản lý Booking</h3>
    <div class="d-flex justify-content-end mb-3">
    <a href="index.php?act=add-booking" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Tạo Booking Mới
    </a>
</div>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tour</th>
                <th>Khách hàng</th>
                <th>Liên hệ</th>
                <th>Email   </th>
                <th>Số người</th>
                <th>Ghi chú đặc biệt</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Cập nhật</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($bookings as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= $item['tour_name'] ?></td>
                <td><?= $item['customer_name'] ?></td>
                <td><?= $item['phone'] ?></td>
                <td><?= $item['email'] ?></td>
                <td><?= $item['people_count'] ?></td>
                <td><?= $item['special_request'] ?></td>   
                <td><?= $item['booking_date'] ?></td>
                <td><?= number_format($item['total_price']) ?> VNĐ</td>

                
                <td>
                    <?php 
                        if($item['status']=='pending') echo "<span class='badge bg-warning'>Chờ xác nhận</span>";
                        elseif($item['status']=='deposit') echo "<span class='badge bg-info'>Đã cọc</span>";
                        elseif($item['status']=='completed') echo "<span class='badge bg-success'>Hoàn tất</span>";
                        else echo "<span class='badge bg-danger'>Hủy</span>";
                    ?>
                </td>

                <td>
            <form method="POST" action="index.php?act=update-booking-status">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <select name="status" class="form-select form-select-sm">
                    <option value="pending"   <?= $item['status']=='pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                    <option value="deposit"   <?= $item['status']=='deposit' ? 'selected' : '' ?>>Đã cọc</option>
                    <option value="completed" <?= $item['status']=='completed' ? 'selected' : '' ?>>Hoàn tất</option>
                    <option value="cancelled" <?= $item['status']=='cancelled' ? 'selected' : '' ?>>Hủy</option>
                    </select>
                <button class="btn btn-sm btn-primary mt-1">Lưu</button>
            </form>

                </td>
                <td>
                    <a href="index.php?act=booking-logs&id=<?= $item['id'] ?>" 
                        class="btn btn-sm btn-info mt-1">
                        Lịch sử
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "views/layout/footer.php"; ?>
