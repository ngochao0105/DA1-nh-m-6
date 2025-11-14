<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="container-fluid px-4 mt-4">
  <h3>Thêm Hướng dẫn viên Mới</h3>
  
  <form method="POST" class="mt-3">
    <div class="mb-3">
      <label for="full_name" class="form-label">Tên hướng dẫn viên <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="full_name" name="full_name" required>
    </div>

    <div class="mb-3">
      <label for="birth_date" class="form-label">Ngày sinh</label>
      <input type="date" class="form-control" id="birth_date" name="birth_date">
    </div>

    <div class="mb-3">
      <label for="phone" class="form-label">Điện thoại <span class="text-danger">*</span></label>
      <input type="tel" class="form-control" id="phone" name="phone" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email">
    </div>

    <div class="mb-3">
      <label for="guide_type" class="form-label">Loại hướng dẫn</label>
      <select class="form-control" id="guide_type" name="guide_type">
        <option value="">Chọn loại hướng dẫn</option>
        <option value="Tiếng Anh">Tiếng Anh</option>
        <option value="Tiếng Trung">Tiếng Trung</option>
        <option value="Tiếng Việt">Tiếng Việt</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="average_rating" class="form-label">Đánh giá (0-5)</label>
      <input type="number" class="form-control" id="average_rating" name="average_rating" 
             min="0" max="5" step="0.1" value="0">
    </div>

    <button type="submit" class="btn btn-success">Lưu</button>
    <a href="?act=guide-management" class="btn btn-secondary">Hủy</a>
  </form>
</div>

<?php include "views/layout/footer.php"; ?>