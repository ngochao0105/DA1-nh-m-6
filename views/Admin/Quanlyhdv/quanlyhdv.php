<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>
<br>
<br>
<br>

  <h3>Quản Lý Hướng Dẫn Viên</h3>
  <a href="?act=add-guide" class="btn btn-primary float-end mb-3"> <!-- <-- sửa -->
    <i class="bi bi-plus-circle"></i> Thêm hướng dẫn viên
  </a>



  <table class="table table-bordered align-middle mt-3">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Tên hướng dẫn viên</th>
        <th>Ngày tháng năm sinh</th>
        <th>Phone</th>
        <th>email</th>
        <th>Loại hướng dẫn </th>
        <th>Đánh giá hướng dẫn viên </th>
        <th>Thao Tác</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($guides)): ?>
        <?php foreach ($guides as $guide): ?>
        <tr>
          <td><?= htmlspecialchars($guide['id']) ?></td>
          <td><?= htmlspecialchars($guide['full_name'] ?? $guide['name']) ?></td>
          <td><?= htmlspecialchars($guide['birth_date'] ?? '') ?></td>
          <td><?= htmlspecialchars($guide['phone'] ?? '') ?></td>
          <td><?= htmlspecialchars($guide['email'] ?? '') ?></td>
          <td><?= htmlspecialchars($guide['guide_type'] ?? '') ?></td>
          <td>
  <?php
    $rating = $guide['average_rating'] ?? 0;
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating)) {
            echo '<i class="bi bi-star-fill" style="color: gold;"></i>';
        } elseif ($i - $rating < 1) {
            echo '<i class="bi bi-star-half" style="color: gold;"></i>';
        } else {
            echo '<i class="bi bi-star" style="color: gold;"></i>';
        }
    }
  ?>
</td>
          <td>
            <a href="?act=edit-guide&id=<?= $guide['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
            <a href="?act=delete-guide&id=<?= $guide['id'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" class="text-center text-muted">Chưa có dữ liệu hướng dẫn viên</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <?php include "views/layout/footer.php"; ?>