<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Sửa Danh mục</h1>
</div>

<div class="form-container">
    <form method="POST" action="index.php?act=update-category" class="form">
        <input type="hidden" name="id" value="<?= $category['id'] ?>">

        <div class="form-group">
            <label for="category_name">Tên Danh mục <span style="color: red;">*</span></label>
            <input type="text" id="category_name" name="category_name" class="form-control" 
                   placeholder="Nhập tên danh mục" value="<?= htmlspecialchars($category['category_name']) ?>" required>
        </div>

        <div class="form-group" style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Cập nhật Danh mục
            </button>
            <a href="index.php?act=category-list" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>

<?php include "views/layout/footer.php"; ?>
