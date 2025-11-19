
<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>
<br>
<br>
<div class="container mt-4">
  <h3>Quản lý Khách hàng Tour</h3>
  <a href="?act=customer-add" class="btn btn-primary mb-3">Thêm khách hàng</a>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Tên Booking </th>
        <th>Tên khách</th>
        <th>Phone</th>
        <th>Check-in</th>
        <th>Yêu cầu đặc biệt</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($customers)): foreach ($customers as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['id']) ?></td>
        <td><?= htmlspecialchars($c['id_booking']) ?></td>
        <td><?= htmlspecialchars($c['customer_name']) ?></td>
        <td><?= htmlspecialchars($c['phone']) ?></td>
        <td><?= htmlspecialchars($c['checkin']) ?></td>
        <td><?= nl2br(htmlspecialchars($c['special_request'])) ?></td>
        <td>
          <a href="?act=customer-edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
          <a href="?act=customer-delete&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include "views/layout/footer.php"; ?>