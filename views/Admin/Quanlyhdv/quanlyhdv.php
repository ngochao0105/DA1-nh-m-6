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
                <th>Tên đăng nhập</th>
                <th>Mật khẩu</th>
                <th>Loại hướng dẫn</th>
                <th>Đánh giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($guides)): ?>
                <?php foreach ($guides as $guide): ?> 
                <tr>
                    <td><?= htmlspecialchars($guide['id']) ?></td>
                    <td><strong><?= htmlspecialchars($guide['full_name'] ?? $guide['name']) ?></strong></td>
                    <td><?= htmlspecialchars($guide['birth_date'] ?? '') ?></td>
                    <td><?= htmlspecialchars($guide['phone'] ?? '') ?></td>
                    <td><?= htmlspecialchars($guide['email'] ?? '') ?></td>
                    <td>
                        <?php if (!empty($guide['username'])): ?>
                            <span style="color: var(--primary-blue); font-weight: 500;">
                                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($guide['username']) ?>
                            </span>
                        <?php else: ?>
                            <span style="color: #9ca3af;">Chưa có tài khoản</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($guide['password'])): ?>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span class="password-display" data-password="<?= htmlspecialchars($guide['password']) ?>" style="font-family: monospace; color: #374151;">
                                    ••••••••
                                </span>
                                <button type="button" class="btn-toggle-password" 
                                        style="background: none; border: none; color: var(--primary-blue); cursor: pointer; padding: 0.25rem; font-size: 0.875rem;"
                                        onclick="togglePassword(this)" title="Hiện/Ẩn mật khẩu">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        <?php else: ?>
                            <span style="color: #9ca3af;">-</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($guide['guide_type'] ?? '') ?></td>
                    <td>
                        <?php
                        $rating = $guide['average_rating'] ?? 0;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= floor($rating)) {
                                echo '<i class="bi bi-star-fill" style="color: #fbbf24;"></i>';
                            } elseif ($i - $rating < 1) {
                                echo '<i class="bi bi-star-half" style="color: #fbbf24;"></i>';
                            } else {
                                echo '<i class="bi bi-star" style="color: #d1d5db;"></i>';
                            }
                        }
                        ?>
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

<script>
function togglePassword(button) {
    const row = button.closest('tr');
    const passwordDisplay = row.querySelector('.password-display');
    const icon = button.querySelector('i');
    
    if (passwordDisplay) {
        const isHidden = passwordDisplay.textContent.includes('••••');
        const realPassword = passwordDisplay.getAttribute('data-password');
        
        if (isHidden) {
            passwordDisplay.textContent = realPassword;
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordDisplay.textContent = '••••••••';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
}
</script>

<?php include "views/layout/footer.php"; ?>
