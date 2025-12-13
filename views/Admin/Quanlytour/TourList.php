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

.action-bar-search .btn-outline-secondary {
    border-radius: 4px !important;
    border-left: 1px solid #dee2e6 !important;
    margin-left: 5px !important;
}
</style>

<div class="page-header">
    <h1>Quản lý Tour</h1>
    <?php if (!empty($_GET['keyword'])): ?>
        <p class="text-muted">Kết quả tìm kiếm cho: "<strong><?= htmlspecialchars($_GET['keyword']) ?></strong>" 
           (<?= count($categories) ?> kết quả)</p>
    <?php elseif (!empty($_GET['filter_status'])): ?>
        <p class="text-muted">Lọc theo trạng thái: 
           <strong>
           <?php
           $statusText = [
               'active' => 'Đang mở',
               'inactive' => 'Đã đóng', 
               'upcoming' => 'Sắp mở'
           ];
           echo $statusText[$_GET['filter_status']] ?? $_GET['filter_status'];
           ?>
           </strong>
           (<?= count($categories) ?> kết quả)</p>
    <?php endif; ?>
</div>

<!-- Action Bar -->
<div class="action-bar">
    <div class="action-bar-left">
        <!-- Form tìm kiếm -->
        <form method="GET" action="" class="action-bar-search" style="display: flex; align-items: center;">
            <input type="hidden" name="act" value="tour-list">
            <i class="bi bi-search"></i>
            <input type="text" 
                   name="keyword"
                   placeholder="Tìm kiếm tên tour, điểm đến..."
                   value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
            <button type="submit" class="btn btn-sm btn-primary ms-2" title="Tìm kiếm">
                <i class="bi bi-search"></i> Tìm
            </button>
            <?php if (!empty($_GET['keyword'])): ?>
                <a href="?act=tour-list" class="btn btn-sm btn-outline-secondary ms-2" title="Xóa tìm kiếm">
                    <i class="bi bi-x"></i>
                </a>
            <?php endif; ?>
        </form>
        <div class="action-bar-filter">
            <form method="GET" action="" style="display: flex; align-items: center;">
                <input type="hidden" name="act" value="tour-list">
                <input type="hidden" name="keyword" value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                <select name="filter_status" onchange="this.form.submit()">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" <?= (isset($_GET['filter_status']) && $_GET['filter_status'] == 'active') ? 'selected' : '' ?>>Đang mở</option>
                    <option value="inactive" <?= (isset($_GET['filter_status']) && $_GET['filter_status'] == 'inactive') ? 'selected' : '' ?>>Đã đóng</option>
                    <option value="upcoming" <?= (isset($_GET['filter_status']) && $_GET['filter_status'] == 'upcoming') ? 'selected' : '' ?>>Sắp mở</option>
                </select>
            </form>
        </div>
    </div>
    <div class="action-bar-right">
        <a href="?act=tour-list" class="btn btn-secondary">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
        </a>
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
                            echo "<span class='badge bg-info' style='color: black;'>Đang mở</span>";
                        } elseif ($status == 0 || $status == 'closed') {
                            echo "<span class='badge bg-danger' style='color: black;'>Đã đóng</span>";
                        } elseif ($status == 2 || $status == 'upcoming') {
                            echo "<span class='badge bg-warning' style='color: black;'>Sắp mở</span>";
                        } else {
                            echo "<span class='badge bg-info' style='color: black;'>Không xác định</span>";
                        }
                        ?>
                    </td>

                    <td>
                        <div class="table-actions">
                            <a href="?act=tour-detail&id=<?= $cat['id'] ?>"
                               class="table-action-btn info" title="Chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="?act=schedule-list&id=<?= $cat['id'] ?>"
                               class="table-action-btn edit" title="Lịch trình">
                                <i class="bi bi-calendar3"></i>
                            </a>
                            <a href="?act=edit-tour&id=<?= $cat['id'] ?>"
                               class="table-action-btn edit" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
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
                        <td colspan="7" class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <?php if (!empty($_GET['keyword'])): ?>
                                <p>Không tìm thấy tour nào phù hợp với từ khóa "<strong><?= htmlspecialchars($_GET['keyword']) ?></strong>".</p>
                                <p><a href="?act=tour-list">Xem tất cả tour</a></p>
                            <?php else: ?>
                                <p>Chưa có tour nào.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "views/layout/footer.php"; ?>
