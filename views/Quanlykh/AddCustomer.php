<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="container mt-4">
  <h3>Thêm Khách hàng</h3>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label>ID Tour / Booking <span class="text-danger">*</span></label>
      <select name="id_booking" class="form-control" required>
        <option value="">-- Chọn Booking --</option>
        <?php
          if (!empty($bookings)) {
              foreach ($bookings as $b) {
                  $tour_name = htmlspecialchars($b['tour_name'] ?? 'N/A');
                  $booking_id = htmlspecialchars($b['id']);
                  $customer_name = htmlspecialchars($b['customer_name'] ?? '');
                  echo '<option value="' . $booking_id . '">' 
                       . $booking_id . ' - ' . $tour_name . ' (' . $customer_name . ')'
                       . '</option>';
              }
          }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Tên khách <span class="text-danger">*</span></label>
      <input name="customer_name" class="form-control" required value="<?= htmlspecialchars($_POST['customer_name'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label>Phone</label>
      <input name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label>Checkin (mm/dd/yyyy)</label>
      <input name="checkin" class="form-control" type="date" value="<?= htmlspecialchars($_POST['checkin'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label>Yêu cầu đặc biệt</label>
      <textarea name="special_request" class="form-control"><?= htmlspecialchars($_POST['special_request'] ?? '') ?></textarea>
    </div>

    <button class="btn btn-success">Lưu</button>
    <a href="?act=customer-list" class="btn btn-secondary">Hủy</a>
  </form>
</div>

<?php include "views/layout/footer.php"; ?>