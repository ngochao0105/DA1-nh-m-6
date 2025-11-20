<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Quản lý Booking</h1>
</div>

<!-- Action Bar -->
<div class="action-bar">
    <div class="action-bar-left">
        <div class="action-bar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Tìm kiếm mã hoặc tên...">
        </div>
        <div class="action-bar-filter">
            <select>
                <option>Tất cả</option>
                <option>Chờ xác nhận</option>
                <option>Đã cọc</option>
                <option>Hoàn tất</option>
                <option>Hủy</option>
            </select>
        </div>
    </div>
    <div class="action-bar-right">
        <button class="btn btn-secondary">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
        </button>
        <a href="index.php?act=add-booking" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Booking
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
                <th>Tour</th>
                <th>Khách hàng</th>
                <th>Liên hệ</th>
                <th>Email</th>
                <th>Số người</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Cập nhật</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><strong><?= htmlspecialchars($item['tour_name']) ?></strong></td>
                    <td><?= htmlspecialchars($item['customer_name']) ?></td>
                    <td><?= htmlspecialchars($item['phone']) ?></td>
                    <td><?= htmlspecialchars($item['email']) ?></td>
                    <td><?= $item['people_count'] ?></td>
                    <td><?= $item['booking_date'] ?></td>
                    <td><strong><?= number_format($item['total_price']) ?> VNĐ</strong></td>
                    
                    <td>
                        <?php 
                            if($item['status']=='pending') echo "<span class='badge bg-warning'>Chờ xác nhận</span>";
                            elseif($item['status']=='deposit') echo "<span class='badge bg-info'>Đã cọc</span>";
                            elseif($item['status']=='completed') echo "<span class='badge bg-success'>Hoàn tất</span>";
                            else echo "<span class='badge bg-danger'>Hủy</span>";
                        ?>
                    </td>

                    <td>
                        <form method="POST" action="index.php?act=update-booking-status" style="display: flex; gap: 4px;">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <select name="status" class="form-select form-select-sm" style="min-width: 120px;">
                                <option value="pending" <?= $item['status']=='pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                <option value="deposit" <?= $item['status']=='deposit' ? 'selected' : '' ?>>Đã cọc</option>
                                <option value="completed" <?= $item['status']=='completed' ? 'selected' : '' ?>>Hoàn tất</option>
                                <option value="cancelled" <?= $item['status']=='cancelled' ? 'selected' : '' ?>>Hủy</option>
                            </select>
                            <button class="btn btn-sm btn-primary">Lưu</button>
                        </form>
                    </td>
                    
                    <td>
                        <div class="table-actions">
                            <a href="index.php?act=booking-logs&id=<?= $item['id'] ?>" 
                               class="table-action-btn edit" title="Lịch sử">
                                <i class="bi bi-clock-history"></i>
                            </a>
                            <a href="?act=edit-booking&id=<?= $item['id'] ?>" 
                               class="table-action-btn edit" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="?act=delete-booking&id=<?= $item['id'] ?>" 
                               class="table-action-btn delete" 
                               onclick="return confirm('Bạn có chắc muốn xóa?')"
                               title="Xóa">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Chưa có booking nào.</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "views/layout/footer.php"; ?>
