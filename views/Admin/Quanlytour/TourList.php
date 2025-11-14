<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

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
        <input type="hidden" name="act" value="list-tours"> <!-- Giả định hành động là list-tours -->
        <div class="col-md-4">
          <label for="searchName" class="form-label">Tên tour</label>
          <input type="text" class="form-control" id="searchName" name="search_name" placeholder="Nhập tên tour..." value="<?= htmlspecialchars($_GET['search_name'] ?? '') ?>">
        </div>
        <div class="col-md-4">
          <label for="filterDestination" class="form-label">Điểm đến</label>
          <select class="form-select" id="filterDestination" name="filter_destination">
            <option value="">Tất cả</option>
            <!-- Dưới đây là ví dụ, bạn cần lấy danh sách điểm đến từ DB hoặc config -->
            <option value="Đà Nẵng" <?= (isset($_GET['filter_destination']) && $_GET['filter_destination'] == 'Đà Nẵng') ? 'selected' : '' ?>>Đà Nẵng</option>
            <option value="Phú Quốc" <?= (isset($_GET['filter_destination']) && $_GET['filter_destination'] == 'Phú Quốc') ? 'selected' : '' ?>>Phú Quốc</option>
            <option value="Hà Nội" <?= (isset($_GET['filter_destination']) && $_GET['filter_destination'] == 'Hà Nội') ? 'selected' : '' ?>>Hà Nội</option>
            <option value="Sapa" <?= (isset($_GET['filter_destination']) && $_GET['filter_destination'] == 'Sapa') ? 'selected' : '' ?>>Sapa</option>
            <option value="Nha Trang" <?= (isset($_GET['filter_destination']) && $_GET['filter_destination'] == 'Nha Trang') ? 'selected' : '' ?>>Nha Trang</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="filterStatus" class="form-label">Trạng thái</label>
          <select class="form-select" id="filterStatus" name="filter_status">
            <option value="">Tất cả</option>
            <option value="1" <?= (isset($_GET['filter_status']) && $_GET['filter_status'] == '1') ? 'selected' : '' ?>>Đang mở</option>
            <option value="0" <?= (isset($_GET['filter_status']) && $_GET['filter_status'] == '0') ? 'selected' : '' ?>>Đã đóng</option>
          </select>
        </div>
        <div class="col-md-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
      </form>
    </div>
  </div>
  <!-- === KẾT THÚC PHẦN THÊM === -->

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
        <td><?= htmlspecialchars($cat['tour_name'] ?? '') ?></td>
        <td><?= htmlspecialchars($cat['description'] ?? '') ?></td>
        <td><?= htmlspecialchars($cat['start_date'] ?? '') ?></td>
        <td><?= htmlspecialchars($cat['end_date'] ?? '') ?></td>
        <td><?= htmlspecialchars($cat['destination'] ?? '') ?></td>
     <td><?= number_format($cat['price'], 0, ',', '.') ?> VNĐ</td>
        <td><?= htmlspecialchars($cat['category_name'] ?? '') ?></td>
        <td>
        <?php 
              $status = $cat['status'] ?? 1;
              $statusClass = 'bg-secondary';  
              $statusText = 'Không xác định';  
              if ($status == 1 || $status === 'open') {
                  $statusClass = 'bg-success';
                  $statusText = 'Đang mở';
              } elseif ($status == 0 || $status === 'closed') {
                  $statusClass = 'bg-danger';
                  $statusText = 'Đã đóng';
              } elseif ($status == 2 || $status === 'upcoming') {
                  $statusClass = 'bg-warning text-dark';
                  $statusText = 'Sắp mở';
              }
          ?>
          <span class="badge <?= htmlspecialchars($statusClass) ?>"><?= htmlspecialchars($statusText) ?></span> 
        <td>
          <a href="?act=edit-tour&id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning text-white">
            <i class="bi bi-pencil"></i>
          </a>
          <a href="?act=deletetour&id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger"
          onclick ="return confirm('Bạn có chắc muốn xóa không')";
          >
            
            <i class="bi bi-trash"></i>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="9" class="text-center text-muted">Chưa có danh mục nào.</td>
    </tr>
  <?php endif; ?>
</tbody>
  </table>
</div>

<?php include "views/layout/footer.php"; ?>
