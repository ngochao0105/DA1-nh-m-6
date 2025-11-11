<?php
include "views/layout/header.php";
include "views/layout/sidebar.php";
?>

<div class="content">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Quản lý Tour Du Lịch</h3>
    <a href="?act=tour-add" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Thêm Tour mới
    </a>
  </div>
  <form class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" class="form-control" placeholder="Tìm theo tên tour...">
    </div>
    <div class="col-md-3">
      <select class="form-select">
        <option selected>Trạng thái</option>
        <option>Đang mở</option>
        <option>Đã đóng</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="button" class="btn btn-success w-100">
        <i class="bi bi-funnel"></i> Lọc
      </button>
    </div>
  </form>

  <!-- Bảng danh sách tour -->
  <table class="table table-hover align-middle">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Tên tour</th>
        <th>Địa điểm</th>
        <th>Thời gian</th>
        <th>Giá</th>
        <th>Số chỗ</th>
        <th>Trạng thái</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tours as $tour): ?>
      <tr>
        <td><?= $tour["id"] ?></td>
        <td><?= $tour["name"] ?></td>
        <td><?= $tour["location"] ?></td>
        <td><?= $tour["duration"] ?></td>
        <td><?= $tour["price"] ?></td>
        <td><?= $tour["slots"] ?></td>
        <td>
          <?php if ($tour["status"] === "Đang mở"): ?>
            <span class="badge bg-success"><?= $tour["status"] ?></span>
          <?php else: ?>
            <span class="badge bg-secondary"><?= $tour["status"] ?></span>
          <?php endif; ?>
        </td>
        <td>
          <a href="?act=tour-edit&id=<?= $tour["id"] ?>" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a>
          <a href="?act=tour-delete&id=<?= $tour["id"] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include "views/layout/footer.php"; ?>
