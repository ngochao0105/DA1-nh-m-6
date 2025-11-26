<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="margin-bottom: 0.5rem;">
                <i class="bi bi-calendar3"></i> Lịch trình Tour
            </h1>
            <p style="color: var(--text-secondary); margin: 0; font-size: 1rem;">
                <strong><?= ($tour['tour_name']) ?></strong> - <?= ($tour['destination']) ?>
            </p>
        </div>
        <a href="?act=tour-list" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Action Bar -->
<div class="action-bar">
    <div class="action-bar-left">
        <div class="action-bar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Tìm kiếm lịch trình...">
        </div>
        <div class="action-bar-filter">
            <select>
                <option>Tất cả</option>
                <option>Sắp mở</option>
                <option>Đang mở</option>
                <option>Đã đóng</option>
            </select>
        </div>
    </div>
    <div class="action-bar-right">
        <button class="btn btn-secondary">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
        </button>
        <a href="?act=schedule-create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm lịch trình
        </a>
    </div>
</div>

<!-- Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th class="sortable">
                    ID
                    <div class="sort-icons">
                        <i class="bi bi-caret-up"></i>
                        <i class="bi bi-caret-down"></i>
                    </div>
                </th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Giá</th>
                <th>Slot tối đa</th>
                <th>Đã đặt</th>
                <th>Còn lại</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($schedules)): ?>
                <?php foreach ($schedules as $sc): ?>
                <tr>
                    <td><?= $sc['id'] ?></td>
                    <td><strong><?= date('d/m/Y', strtotime($sc['start_date'])) ?></strong></td>
                    <td><strong><?= date('d/m/Y', strtotime($sc['end_date'])) ?></strong></td>
                    <td><strong style="color: var(--primary-blue);"><?= number_format($sc['price'] ?? 0, 0, ',', '.') ?> VNĐ</strong></td>
                    <td><?= $sc['max_slots'] ?></td>
                    <td><?= $sc['booked_slots'] ?? 0 ?></td>
                    <td>
                        <strong style="color: var(--success);">
                            <?= ($sc['max_slots'] - ($sc['booked_slots'] ?? 0)) ?>
                        </strong>
                    </td>
                    <td>
                        <?php
                        $st = $sc['status'];
                        if ($st == 'dang_mo') {
                            echo "<span class='badge bg-success'>Đang mở</span>";
                        } elseif ($st == 'da_dong') {
                            echo "<span class='badge bg-danger'>Đã đóng</span>";
                        } else {
                            echo "<span class='badge bg-warning'>Sắp mở</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="?act=schedule-edit&id=<?= $sc['id'] ?>" 
                               class="table-action-btn edit" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="?act=schedule-delete&id=<?= $sc['id'] ?>" 
                               class="table-action-btn delete"
                               onclick="return confirm('Bạn có chắc muốn xóa lịch trình này?')"
                               title="Xóa">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <p>Chưa có lịch trình nào cho tour này.</p>
                        <a href="?act=schedule-create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary" style="margin-top: 1rem;">
                            <i class="bi bi-plus-circle"></i> Thêm lịch trình đầu tiên
                        </a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "views/layout/footer.php"; ?>

<style>
.badge.bg-success,
.badge.bg-danger {
    color: #fff !important;
}
.badge.bg-warning {
    color: #000 !important;
}
</style>