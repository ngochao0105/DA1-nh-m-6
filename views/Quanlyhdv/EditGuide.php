
<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="container-fluid px-4 mt-4">
  <h3>Sửa Hướng dẫn viên</h3>

  <form method="POST" class="mt-3">
    <div class="mb-3">
      <label for="full_name" class="form-label">Tên hướng dẫn viên <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="full_name" name="full_name" required
             value="<?= htmlspecialchars($guide['full_name'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="birth_date" class="form-label">Ngày sinh</label>
      <input type="date" class="form-control" id="birth_date" name="birth_date"
             value="<?= htmlspecialchars($guide['birth_date'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="phone" class="form-label">Điện thoại <span class="text-danger">*</span></label>
      <input type="tel" class="form-control" id="phone" name="phone" required
             value="<?= htmlspecialchars($guide['phone'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email"
             value="<?= htmlspecialchars($guide['email'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="guide_type" class="form-label">Loại hướng dẫn</label>
      <select class="form-control" id="guide_type" name="guide_type">
        <option value="">Chọn loại hướng dẫn</option>
        <?php
          $types = ['Tiếng Anh','Tiếng Pháp','Tiếng Trung','Tiếng Nhật','Tiếng Việt'];
          foreach ($types as $t) {
              $sel = (isset($guide['guide_type']) && $guide['guide_type'] === $t) ? 'selected' : '';
              echo "<option value=\"" . htmlspecialchars($t) . "\" $sel>" . htmlspecialchars($t) . "</option>";
          }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="average_rating" class="form-label">Đánh giá (0-5)</label>
      <input type="number" class="form-control" id="average_rating" name="average_rating" 
             min="0" max="5" step="0.1" value="<?= htmlspecialchars($guide['average_rating'] ?? 0) ?>">
    </div>

    <button type="submit" class="btn btn-success">Cập nhật</button>
    <a href="?act=guide-management" class="btn btn-secondary">Hủy</a>
  </form>
</div>

<?php include "views/layout/footer.php"; ?>