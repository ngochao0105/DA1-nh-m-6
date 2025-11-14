<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cập nhật Tour Du Lịch</title>
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
        <i class="bi bi-plus-circle"></i> Cập nhật Tour Du Lịch
      </h3>

      <form action="" method="POST">

        <div class="row g-3">

          <input type="hidden" name="id" value="<?= $tour['id'] ?>">

                    <!-- Tên tour -->
                    <div class="col-md-6">
                    <label class="form-label">Tên tour *</label>
                    <input type="text" name="tour_name" class="form-control"
                            value="<?= htmlspecialchars($tour['tour_name']) ?>" required>
                    </div>

                    <!-- Điểm đến -->
                    <div class="col-md-6">
                    <label class="form-label">Điểm đến *</label>
                    <input type="text" name="destination" class="form-control"
                            value="<?= htmlspecialchars($tour['destination']) ?>" required>
                    </div>

                    <!-- Ngày bắt đầu -->
                    <div class="col-md-6">
                    <label class="form-label">Ngày bắt đầu *</label>
                    <input type="date" name="start_date" class="form-control"
                            value="<?= $tour['start_date'] ?>" required>
                    </div>

                    <!-- Ngày kết thúc -->
                    <div class="col-md-6">
                    <label class="form-label">Ngày kết thúc *</label>
                    <input type="date" name="end_date" class="form-control"
                            value="<?= $tour['end_date'] ?>" required>
                    </div>

                    <!-- Giá -->
                    <div class="col-md-6">
                    <label class="form-label">Giá tour *</label>
                    <input type="number" name="price" class="form-control"
                            value="<?= $tour['price'] ?>" required>
                    </div>

                    <!-- Danh mục -->
                    <div class="col-md-6">
                    <label class="form-label">Danh mục tour</label>
                    <select name="id_danh_muc" class="form-select">

                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= ($tour['id_danh_muc'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                        <?php endforeach; ?>

                    </select>
                    </div>

                    <!-- Mô tả -->
                    <div class="col-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($tour['description']) ?></textarea>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-md-6">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="1" <?= $tour['status'] == 1 ? 'selected' : '' ?>>Đang mở</option>
                        <option value="0" <?= $tour['status'] == 0 ? 'selected' : '' ?>>Đã đóng</option>
                        <option value="2" <?= $tour['status'] == 2 ? 'selected' : '' ?>>Sắp mở</option>
                    </select>
                    </div>



                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Cập nhật tour
                                </button>
                            </div>

        </div>
      </form>

    </div>
  </div>

</body>
</html>
