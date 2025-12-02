<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Quản lý Danh mục</h1>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        <strong>Thành công!</strong> Danh mục đã được tạo.
    </div>
<?php endif; ?>

<?php if (isset($_GET['msg'])): ?>
    <?php 
    $messages = [
        'success' => ['text' => 'Danh mục đã được tạo thành công!', 'type' => 'success'],
        'updated' => ['text' => 'Danh mục đã được cập nhật thành công!', 'type' => 'success'],
        'deleted' => ['text' => 'Danh mục đã được xóa thành công!', 'type' => 'success'],
        'exists' => ['text' => 'Danh mục này đã tồn tại!', 'type' => 'danger'],
        'error' => ['text' => 'Có lỗi xảy ra. Vui lòng thử lại!', 'type' => 'danger'],
    ];
    
    $msg = $_GET['msg'];
    if (isset($messages[$msg])): 
    ?>
        <div style="background: <?= $messages[$msg]['type'] === 'success' ? '#d4edda' : '#f8d7da' ?>; color: <?= $messages[$msg]['type'] === 'success' ? '#155724' : '#721c24' ?>; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid <?= $messages[$msg]['type'] === 'success' ? '#c3e6cb' : '#f5c6cb' ?>;">
            <strong><?= $messages[$msg]['type'] === 'success' ? 'Thành công!' : 'Lỗi!' ?></strong> <?= $messages[$msg]['text'] ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="action-bar">
    <div class="action-bar-right">
        <a href="index.php?act=add-category" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Danh mục
        </a>
    </div>
</div>

<div class="table-container">
    <table class="table" id="categoryTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Danh mục</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="3" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                        <i class="bi bi-inbox" style="font-size: 3rem; display: block; margin-bottom: 15px; opacity: 0.5;"></i>
                        <p>Chưa có danh mục nào. <a href="index.php?act=add-category">Tạo danh mục mới</a></p>
                    </td>
                </tr>
            <?php else: ?>
            <?php foreach ($categories as $item): ?>

                <tr class="category-row">
                    <td><?= $item['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($item['category_name']) ?></strong>
                    </td>

                    <td>
                        <div style="display: flex; gap: 10px;">
                            <a href="index.php?act=edit-category&id=<?= $item['id'] ?>"
                                class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>

                            <a href="index.php?act=delete-category&id=<?= $item['id'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                               <i class="bi bi-trash"></i> Xóa
                            </a>
                        </div>
                    </td>

                </tr>

            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

    </table>
</div>

<script>
// Tìm kiếm
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.category-row');
    
    rows.forEach(row => {
        const categoryName = row.querySelector('strong').textContent.toLowerCase();
        if (categoryName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php include "views/layout/footer.php"; ?>
