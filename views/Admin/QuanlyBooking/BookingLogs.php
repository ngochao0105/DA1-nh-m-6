<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<?php
function statusLabel($status) {
    switch ($status) {
        case 'cho_xac_nhan':
            return "<span class='badge bg-warning text-dark'>Chờ xác nhận</span>";
        case 'da_coc':
            return "<span class='badge bg-info text-dark'>Đã cọc</span>";
        case 'hoan_tat':
            return "<span class='badge bg-success'>Hoàn tất</span>";
        case 'huy':
            return "<span class='badge bg-danger'>Hủy</span>";
        default:
            return "<span class='badge bg-secondary'>Không rõ</span>";
    }
}
?>

<div class="container mt-4">

    <h3>Lịch sử thay đổi trạng thái Booking #<?= $_GET['id'] ?></h3>

    <a href="index.php?act=booking-list" class="btn btn-secondary mb-3">
        ← Quay lại danh sách booking
    </a>

    <?php if (empty($logs)): ?>
        <div class="alert alert-info">Chưa có thay đổi trạng thái nào.</div>
    <?php else: ?>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Thời gian</th>
                <th>Trạng thái cũ</th>
                <th>Trạng thái mới</th>
                <th>Thay đổi bởi</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= $log['changed_at'] ?></td>
                <td><?= statusLabel($log['old_status']) ?></td>
                <td><?= statusLabel($log['new_status']) ?></td>
                <td><?= htmlspecialchars($log['changed_by']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>

</div>

<?php include "views/layout/footer.php"; ?>
            