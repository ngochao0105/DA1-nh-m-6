<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="container mt-4">
  <h3>Sửa Khách hàng</h3>

  <form method="POST">
    <div class="mb-3">
      <label>ID Tour / Booking <span class="text-danger">*</span></label>
      <select name="id_booking" class="form-control" required>
        <option value="">-- Chọn Booking --</option>
        <?php
          if (!empty($bookings)) {
              foreach ($bookings as $b) {
                  $selected = (isset($customer['id_booking']) && $customer['id_booking'] == $b['id']) ? 'selected' : '';
                  $tour_name = htmlspecialchars($b['tour_name'] ?? 'N/A');
                  $booking_id = htmlspecialchars($b['id']);
                  $customer_name = htmlspecialchars($b['customer_name'] ?? '');
                  echo '<option value="' . $booking_id . '" ' . $selected . '>' 
                       . $booking_id . ' - ' . $tour_name . ' (' . $customer_name . ')'
                       . '</option>';
              }
          }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Tên khách <span class="text-danger">*</span></label>
      <input name="customer_name" class="form-control" required value="<?= htmlspecialchars($customer['customer_name'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label>Phone</label>
      <input name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label>Checkin (mm/dd/yyyy)</label>
      <input name="checkin" class="form-control" type="date" value="<?= htmlspecialchars($customer['checkin'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label>Yêu cầu đặc biệt</label>
      <textarea name="special_request" class="form-control"><?= htmlspecialchars($customer['special_request'] ?? '') ?></textarea>
    </div>

    <button class="btn btn-success">Cập nhật</button>
    <a href="?act=customer-list" class="btn btn-secondary">Hủy</a>
  </form>
</div>

<?php include "views/layout/footer.php"; ?>