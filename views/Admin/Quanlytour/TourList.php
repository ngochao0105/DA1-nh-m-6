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

  <style>
/* ===== Card Search & Filter ===== */
.card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.card-body label {
    font-weight: 600;
    color: #333;
}

/* ===== Table Style ===== */
.table {
    border-radius: 12px;
    overflow: hidden;
    background: white;
}

.table thead {
    background: #343a40;
    color: #fff;
}

.table tbody tr:hover {
    background: #f5f7fa;
    transition: .25s;
}

.table td, .table th {
    vertical-align: middle !important;
    padding: 12px 14px;
}

/* ===== Badge Status ===== */
.badge {
    padding: 8px 12px;
    font-size: 12px;
    border-radius: 6px;
}

/* ===== Button ===== */
.btn {
    border-radius: 6px !important;
    font-size: 14px;
    padding: 6px 12px;
}

.btn-primary {
    background: #4b7bec;
    border: none;
}

.btn-primary:hover {
    background: #3867d6;
}

.btn-warning {
    background: #f1c40f;
    border: none;
}

.btn-warning:hover {
    background: #f39c12;
}

.btn-danger {
    background: #eb3b5a;
    border: none;
}

.btn-danger:hover {
    background: #d63031;
}

/* ===== Page title ===== */
h3 {
    font-weight: 700;
    color: #2d3436;
    letter-spacing: .5px;
    margin-bottom: 20px;
}
</style>
  
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Danh mục Tour Du Lịch</h3>
    <a href="?act=createtour" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Thêm tour mới
    </a>
</div>


 


  <!-- === PHẦN THÊM: THANH TÌM KIẾM & LỌC === -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" action="" class="row g-3">
        <input type="hidden" name="act" value="list-tours">

        <div class="col-md-4">
          <label for="searchName" class="form-label">Tên tour</label>

          <input type="text" class="form-control" id="searchName" name="search_name" placeholder="Nhập tên tour..." value="<?= ($_GET['search_name'] ?? '') ?>">

         

        </div>

        <div class="col-md-4">
          <label for="filterDestination" class="form-label">Điểm đến</label>
          <select class="form-select" id="filterDestination" name="filter_destination">
            <option value="">Tất cả</option>


            <option value="Đà Nẵng" <?= (($_GET['filter_destination'] ?? '') == 'Đà Nẵng') ? 'selected' : '' ?>>Đà Nẵng</option>
            <option value="Phú Quốc" <?= (($_GET['filter_destination'] ?? '') == 'Phú Quốc') ? 'selected' : '' ?>>Phú Quốc</option>
            <option value="Hà Nội" <?= (($_GET['filter_destination'] ?? '') == 'Hà Nội') ? 'selected' : '' ?>>Hà Nội</option>
            <option value="Sapa" <?= (($_GET['filter_destination'] ?? '') == 'Sapa') ? 'selected' : '' ?>>Sapa</option>
            <option value="Nha Trang" <?= (($_GET['filter_destination'] ?? '') == 'Nha Trang') ? 'selected' : '' ?>>Nha Trang</option>

          </select>
        </div>

        <div class="col-md-4">
          <label for="filterStatus" class="form-label">Trạng thái</label>
          <select class="form-select" id="filterStatus" name="filter_status">
            <option value="">Tất cả</option>
            <option value="1" <?= (($_GET['filter_status'] ?? '') == '1') ? 'selected' : '' ?>>Đang mở</option>
            <option value="0" <?= (($_GET['filter_status'] ?? '') == '0') ? 'selected' : '' ?>>Đã đóng</option>
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
        <td><?= ($cat['tour_name'] ?? '') ?></td>
        <td><?= ($cat['description'] ?? '') ?></td>
        <td><?= ($cat['start_date'] ?? '') ?></td>
        <td><?= ($cat['end_date'] ?? '') ?></td>
        <td><?= ($cat['destination'] ?? '') ?></td>
     <td><?= number_format($cat['price'], 0, ',', '.') ?> VNĐ</td>
        <td><?= ($cat['category_name'] ?? '') ?></td>
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
          <span class="badge <?= ($statusClass) ?>"><?= ($statusText) ?></span> 
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