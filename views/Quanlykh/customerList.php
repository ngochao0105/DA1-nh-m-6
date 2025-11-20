<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Quản lý Khách hàng</h1>
</div>

<!-- Action Bar -->
<div class="action-bar">
    <div class="action-bar-left">
        <div class="action-bar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Tìm kiếm tên khách hàng...">
        </div>
        <div class="action-bar-filter">
            <select>
                <option>Tất cả</option>
                <option>Đã check-in</option>
                <option>Chưa check-in</option>
            </select>
        </div>
    </div>
    <div class="action-bar-right">
        <button class="btn btn-secondary">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
        </button>
        <a href="?act=customer-add" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm khách hàng
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
                <th>ID Booking</th>
                <th>Tên khách</th>
                <th>Phone</th>
                <th>Check-in</th>
                <th>Yêu cầu đặc biệt</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['id']) ?></td>
                    <td><?= htmlspecialchars($c['id_booking']) ?></td>
                    <td><strong><?= htmlspecialchars($c['customer_name']) ?></strong></td>
                    <td><?= htmlspecialchars($c['phone']) ?></td>
                    <td><?= htmlspecialchars($c['checkin']) ?></td>
                    <td><?= nl2br(htmlspecialchars($c['special_request'])) ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="?act=customer-edit&id=<?= $c['id'] ?>" 
                               class="table-action-btn edit" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="?act=customer-delete&id=<?= $c['id'] ?>" 
                               class="table-action-btn delete"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa?')"
                               title="Xóa">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Không có dữ liệu</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "views/layout/footer.php"; ?>
