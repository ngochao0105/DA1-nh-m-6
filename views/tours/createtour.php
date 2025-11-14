<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thêm Tour Du Lịch</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body {
      background-color: #f8f9fa;
      font-family: "Segoe UI", sans-serif;
      padding: 40px;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    .form-label {
      font-weight: 500;
    }
    .btn-primary {
      background-color: #1abc9c;
      border: none;
    }
    .btn-primary:hover {
      background-color: #16a085;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="card p-4 mt-4">
      <h3 class="text-center mb-4">
        <i class="bi bi-plus-circle"></i> Thêm Tour Du Lịch
      </h3>

      <form action="" method="POST">

        <div class="row g-3">

          <!-- Tên tour -->
          <div class="col-md-6">
            <label class="form-label">Tên tour <span class="text-danger">*</span></label>
            <input type="text" name="tour_name" class="form-control" placeholder="Ví dụ: Tour Đà Lạt 3N2Đ" required>
          </div>

          <!-- Điểm đến -->
          <div class="col-md-6">
            <label class="form-label">Điểm đến <span class="text-danger">*</span></label>
            <input type="text" name="destination" class="form-control" placeholder="Ví dụ: Đà Lạt, Nha Trang..." required>
          </div>

          <!-- Ngày bắt đầu -->
          <div class="col-md-6">
            <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
            <input type="date" name="start_date" class="form-control" required>
          </div>

          <!-- Ngày kết thúc -->
          <div class="col-md-6">
            <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
            <input type="date" name="end_date" class="form-control" required>
          </div>

          <!-- Giá -->
          <div class="col-md-6">
            <label class="form-label">Giá tour (VNĐ) <span class="text-danger">*</span></label>
            <input type="number" name="price" class="form-control" placeholder="Nhập giá" required>
          </div>

          <!-- Danh mục -->
          <div class="col-md-6">
            <label class="form-label">Danh mục tour</label>
            <select name="id_danh_muc" class="form-select">
              <option value="">-- Chọn danh mục --</option>
              <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>">
                    <?= htmlspecialchars($cat['category_name']) ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>

            </select>
          </div>  

          <!-- Mô tả -->
          <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả chi tiết..."></textarea>
          </div>

          <!-- Trạng thái -->
          <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
              <option value="1">Đang mở</option>
              <option value="0">Đã đóng</option>
              <option value="2">Sắp mở</option>
            </select>
          </div>


          <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle"></i> Thêm tour
            </button>
          </div>

        </div>
      </form>

    </div>
  </div>

</body>
</html>
