<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<!-- CSS FIX VỠ FORM -->
<style>
  td {
    vertical-align: middle !important;
  }

  .actions {
    white-space: nowrap;
    width: 170px !important;
  }

  .actions .btn {
    padding: 4px 6px !important;
    font-size: 12px !important;
    margin-right: 3px;
  }
</style>

<div class="container-fluid px-4 mt-4">
  <hr>
  <hr>
  <hr>

  <h3>Danh mục Tour Du Lịch</h3>
  <a href="?act=createtour" class="btn btn-primary float-end mb-3">
    <i class="bi bi-plus-circle"></i> Thêm tour mới
  </a>

  <!-- === PHẦN THÊM: THANH TÌM KIẾM & LỌC === -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" action="" class="row g-3">
        <input type="hidden" name="act" value="tour-list">

        <div class="col-md-4">
          <label for="searchName" class="form-label">Tên tour</label>
          <input type="text" class="form-control" id="searchName" name="search_name"
            placeholder="Nhập tên tour..."
            value="<?= ($_GET['search_name'] ?? '') ?>">
        </div>

        <div class="col-md-4">
          <label for="filterDestination" class="form-label">Điểm đến</label>
          <select class="form-select" id="filterDestination" name="filter_destination">
          </select>
        </div>

        <div class="col-md-4">
          <label for="filterStatus" class="form-label">Trạng thái</label>
          <select class="form-select" id="filterStatus" name="filter_status">
            <option value="">Tất cả</option>
            <option value="1" <?= (($_GET['filter_status'] ?? '') == '1') ? 'selected' : '' ?>>Đang mở</option>
            <option value="0" <?= (($_GET['filter_status'] ?? '') == '0') ? 'selected' : '' ?>>Đã đóng</option>
            <option value="2" <?= (($_GET['filter_status'] ?? '') == '2') ? 'selected' : '' ?>>Sắp mở</option>
          </select>
        </div>

        <div class="col-md-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
      </form>
    </div>
  </div>
  <!-- === END LỌC === -->

  <table class="table table-bordered align-middle mt-3">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Tên Tour</th>
        <th>Mô tả</th>
        <th>Ngày bắt đầu</th>
        <th>Ngày kết thúc</th>
        <th>Điểm đến</th>
        <th>Giá</th>
        <th>Danh mục tour</th>
        <th>Trạng thái</th>
        <th>Thao tác</th>
      </tr>
    </thead>

    <tbody>
      <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $cat): ?>
          <tr>
            <td><?= $cat['id'] ?></td>
            <td><?= ($cat['tour_name']) ?></td>
            <td><?= ($cat['description']) ?></td>
            <td><?= date('d-m-Y', strtotime($cat['start_date'])) ?></td>
            <td><?= date('d-m-Y', strtotime($cat['end_date'])) ?></td>
            <td><?= ($cat['destination']) ?></td>
            <td><?= number_format($cat['price'], 0, ',', '.') ?> VNĐ</td>
            <td><?= ($cat['category_name']) ?></td>

            <!-- TRẠNG THÁI -->
            <td>
              <?php
              $status = $cat['status'];
              $statusClass = 'bg-secondary';
              $statusText = 'Không xác định';

              if ($status == 1 || $status == 'open') {
                $statusClass = 'bg-success';
                $statusText = 'Đang mở';
              } elseif ($status == 0 || $status == 'closed') {
                $statusClass = 'bg-danger';
                $statusText = 'Đã đóng';
              } elseif ($status == 2 || $status == 'upcoming') {
                $statusClass = 'bg-warning text-dark';
                $statusText = 'Sắp mở';
              }
              ?>
              <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
            </td>

            <!-- THAO TÁC -->
            <td class="actions">
              <!-- Sửa -->
              <a href="?act=edit-tour&id=<?= $cat['id'] ?>"
                class="btn btn-warning btn-sm text-white" title="Sửa">
                <i class="bi bi-pencil"></i>
              </a>

              <!-- Xóa -->
              <a href="?act=deletetour&id=<?= $cat['id'] ?>"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Bạn có chắc muốn xóa không?')"
                title="Xóa">
                <i class="bi bi-trash"></i>
              </a>

              
              <a href="?act=assign-guide&id=<?= $cat['id'] ?>"
                class="btn btn-info btn-sm text-white" title="Tạo booking">
                <i class="bi bi-people"></i>
              </a>

              <!-- Chi phí Tour -->
              <a href="?act=tour-expense&id=<?= $cat['id'] ?>"
                class="btn btn-secondary btn-sm text-white" title="Chi phí">
                <i class="bi bi-cash-stack"></i>
              </a>
            </td>

          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="10" class="text-center text-muted">Chưa có tour nào.</td>
        </tr>
      <?php endif; ?>
    </tbody>

  </table>
</div>

<?php include "views/layout/footer.php"; ?>