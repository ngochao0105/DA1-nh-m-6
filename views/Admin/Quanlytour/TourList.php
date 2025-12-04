<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Quản lý Tour</h1>
</div>

<!-- Action Bar -->
<div class="action-bar">
    <div class="action-bar-left">
        <div class="action-bar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Tìm kiếm tên tour...">
        </div>
        <div class="action-bar-filter">
            <select>
                <option>Tất cả</option>
                <option>Đang mở</option>
                <option>Đã đóng</option>
                <option>Sắp mở</option>
            </select>
        </div>
    </div>
    <div class="action-bar-right">
        <button class="btn btn-secondary">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
        </button>
        <a href="?act=createtour" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Tour
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
                <th>Tên Tour</th>
                <th>Mô tả</th>
                <th>Điểm đến</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><strong><?= htmlspecialchars($cat['tour_name']) ?></strong></td>
                    <td><?= htmlspecialchars(substr($cat['description'] ?? '', 0, 50)) ?><?= strlen($cat['description'] ?? '') > 50 ? '...' : '' ?></td>
                    <td><?= htmlspecialchars($cat['destination']) ?></td>
                    <td><?= htmlspecialchars($cat['category_name']) ?></td>

                    <td>
                        <?php
                        $status = $cat['status'];
                        if ($status == 1 || $status == 'open') {
                            echo "<span class='badge bg-success'>Đang mở</span>";
                        } elseif ($status == 0 || $status == 'closed') {
                            echo "<span class='badge bg-danger'>Đã đóng</span>";
                        } elseif ($status == 2 || $status == 'upcoming') {
                            echo "<span class='badge bg-warning'>Sắp mở</span>";
                        } else {
                            echo "<span class='badge bg-info'>Không xác định</span>";
                        }
                        ?>
                    </td>

                    <td>
                        <div class="table-actions">
                            <a href="?act=tour-detail&id=<?= $cat['id'] ?>"
                               class="table-action-btn info" title="Chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if ($status == 1 || $status == 2 || $status == 'open' || $status == 'upcoming'): ?>
                            <a href="?act=schedule-list&id=<?= $cat['id'] ?>"
                               class="table-action-btn edit" title="Lịch trình">
                                <i class="bi bi-calendar3"></i>
                            </a>
                            <?php endif; ?>
                            <a href="?act=deletetour&id=<?= $cat['id'] ?>"
                               class="table-action-btn delete"
                               onclick="return confirm('Bạn có chắc muốn xóa không?')"
                               title="Xóa">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Chưa có tour nào.</p>
                        </td>
                    </tr>
                <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "views/layout/footer.php"; ?>
