<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>
<div class="container-fluid px-4 mt-4">
  <hr>
  <hr>
  <hr>
  
  <h3>Danh mục Tour Du Lịch</h3>
  <a href="?act=add-category" class="btn btn-primary float-end mb-3">
    <i class="bi bi-plus-circle"></i> Thêm danh mục
  </a>

  <table class="table table-bordered align-middle mt-3">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Tên danh mục</th>
        <th>Mô tả</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $cat): ?>
          <tr>
            <td><?= $cat['id'] ?></td>
            <td><?= htmlspecialchars($cat['category_name']) ?></td>
            <td><?= htmlspecialchars($cat['description']) ?></td>
            <td>
              <a href="?act=edit-category&id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning text-white">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="?act=delete-category&id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="text-center text-muted">Chưa có danh mục nào.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include "views/layout/footer.php"; ?>