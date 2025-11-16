<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="container-fluid px-4 mt-4">

    <h3>Quản lý Booking</h3>

    <table class="table table-bordered table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tour</th>
                <th>Khách hàng</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Số người</th>
                <th>Ngày đặt</th>
                <th>Ghi chú</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= $b['id'] ?></td>
                        <td><?= htmlspecialchars($b['tour_name'] ?? "Không có") ?></td>
                        <td><?= htmlspecialchars($b['customer_name']) ?></td>
                        <td><?= htmlspecialchars($b['email']) ?></td>
                        <td><?= htmlspecialchars($b['phone']) ?></td>
                        <td><?= $b['people_count'] ?></td>
                        <td><?= $b['booking_date'] ?></td>
                        <td><?= htmlspecialchars($b['notes']) ?></td>

                        <td>
                            <?php 
                                if ($b['status'] == 0) {
                                    echo "<span class='badge bg-warning text-dark'>Chờ xác nhận</span>";
                                } elseif ($b['status'] == 1) {
                                    echo "<span class='badge bg-primary'>Đã cọc</span>";
                                } elseif ($b['status'] == 2) {
                                    echo "<span class='badge bg-success'>Hoàn tất</span>";
                                } else {
                                    echo "<span class='badge bg-danger'>Hủy</span>";
                                }
                            ?>
                        </td>

                        <td>
                            <a class="btn btn-sm btn-warning" href="?act=edit-booking&id=<?= $b['id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a class="btn btn-sm btn-danger" 
                               href="?act=delete-booking&id=<?= $b['id'] ?>"
                               onclick="return confirm('Xóa booking này?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>

                <tr>
                    <td colspan="10" class="text-center text-muted">Chưa có booking nào.</td>
                </tr>

            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php include "views/layout/footer.php"; ?>
