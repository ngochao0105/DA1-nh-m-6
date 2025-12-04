<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Quản lý Hướng dẫn viên</h1>
</div>

<!-- Action Bar -->
<div class="action-bar">
    <div class="action-bar-left">

        <!-- Form tìm kiếm -->
        <form method="GET" action="" class="action-bar-search" style="display: flex; align-items: center;">
            <input type="hidden" name="act" value="guide-management">

            <i class="bi bi-search"></i>
            <input type="text" 
                   name="keyword"
                   placeholder="Tìm kiếm tên hướng dẫn viên..."
                   value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
        </form>

        <div class="action-bar-filter">
            <select>
                <option>Tất cả</option>
                <option>Đang hoạt động</option>
                <option>Nghỉ việc</option>
            </select>
        </div>
    </div>

    <div class="action-bar-right">
        <a href="?act=guide-management" class="btn btn-secondary">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
        </a>
        <a href="?act=add-guide" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm HDV
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
                <th>Tên hướng dẫn viên</th>
                <th>Ngày sinh</th>
                <th>Phone</th>
                <th>Email</th>
                <!-- <th>Tên đăng nhập</th>
                <th>Mật khẩu</th> -->
                <th>Loại hướng dẫn</th>
                <th>Loại thẻ hướng dẫn</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($guides)): ?>
                <?php foreach ($guides as $guide): ?> 
                <tr>
                    <td><?= htmlspecialchars($guide['id']) ?></td>
                    <td><strong><?= htmlspecialchars($guide['full_name'] ?? $guide['name']) ?></strong></td>
                    <td>
                        <?php
                        $birth_date = $guide['birth_date'] ?? '';
                        if (!empty($birth_date) && $birth_date !== '0000-00-00' && $birth_date !== 'NULL') {
                            // Thử format từ YYYY-MM-DD sang dd/mm/yyyy
                            $date = DateTime::createFromFormat('Y-m-d', $birth_date);
                            if ($date && $date->format('Y-m-d') === $birth_date) {
                                // Nếu đúng định dạng YYYY-MM-DD, chuyển sang dd/mm/yyyy
                                echo htmlspecialchars($date->format('d/m/Y'));
                            } else {
                                // Nếu đã là định dạng khác hoặc không parse được, hiển thị nguyên bản
                                echo htmlspecialchars($birth_date);
                            }
                        } else {
                            echo '<span style="color: #9ca3af;">-</span>';
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($guide['phone'] ?? '') ?></td>
                    <td><?= htmlspecialchars($guide['email'] ?? '') ?></td>
                    
                
                    <td><?= htmlspecialchars($guide['guide_type'] ?? '') ?></td>
                    <td>
                        <?php
                        // Mapping từ enum database sang giá trị hiển thị
                        $license_type_map = [
                            'noi_dia' => 'Nội địa',
                            'quoc_te' => 'Quốc tế',
                            'khong_co' => 'Thực tập'
                        ];
                        
                        $license_type_db = $guide['license_type'] ?? '';
                        $license_type = $license_type_map[$license_type_db] ?? '';
                        
                        if (empty($license_type)) {
                            $license_type = '-';
                            $badge_color = '#6b7280'; // Gray
                        } else {
                            // Apply badge styling based on license type
                            if ($license_type == 'Nội địa') {
                                $badge_color = '#3b82f6'; // Blue
                            } elseif ($license_type == 'Quốc tế') {
                                $badge_color = '#10b981'; // Green
                            } elseif ($license_type == 'Thực tập') {
                                $badge_color = '#f59e0b'; // Orange
                            } else {
                                $badge_color = '#6b7280'; // Default Gray
                            }
                        }
                        ?>
                        <span style="display: inline-block; padding: 0.25rem 0.75rem; background: <?= $badge_color ?>20; color: <?= $badge_color ?>; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500;">
                            <?= htmlspecialchars($license_type) ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="?act=edit-guide&id=<?= $guide['id'] ?>" 
                               class="table-action-btn edit" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="?act=delete-guide&id=<?= $guide['id'] ?>" 
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
                    <td colspan="10" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Chưa có dữ liệu hướng dẫn viên.</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "views/layout/footer.php"; ?>
