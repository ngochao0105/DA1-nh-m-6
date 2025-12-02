<?php include "views/HDV/header_hdv.php"; ?>
<?php include "views/HDV/sidebar_hdv.php"; ?>


<div class="page-header">
    <h1>Tour được phân công</h1>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Tên Tour</th>
                <th>Điểm đến</th>
                <th>Ngày đi</th>
                <th>Trạng thái Booking</th>
                <th>Số lượng tour </th>
                <th>Chi tiết tour</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($assignedTours)): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:20px;">
                        Chưa có tour nào được phân công.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($assignedTours as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['tour_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($t['destination'] ?? 'N/A') ?></td>

                    <td>
                        <?= $t['ngay_di'] ? date("d/m/Y", strtotime($t['ngay_di'])) : "N/A" ?>
                    </td>

                    <td>
                        <span class="badge bg-info">
                            <?= htmlspecialchars($t['booking_status'] ?? 'N/A') ?>
                        </span>
                    </td>

                    <td>
                        <span class="badge bg-success">
                            <?= htmlspecialchars($t['tour_status'] ?? 'N/A') ?>
                        </span>
                    </td>

                    <!-- Nút xem chi tiết -->
                    <td>
                         <a href="index.php?act=booking-detail&id=<?= $item['id'] ?>"
                           class="btn btn-primary btn-sm">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "views/HDV/footer_hdv.php"; ?>
