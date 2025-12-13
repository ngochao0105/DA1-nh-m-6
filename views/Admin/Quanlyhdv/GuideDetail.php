<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="container-fluid px-4 mt-4">
  <h3>Chi tiết Hướng dẫn viên</h3>

  <!-- Thông tin cơ bản -->
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5>Thông tin cá nhân</h5>
        </div>
        <div class="card-body">
          <p><strong>ID:</strong> <?= htmlspecialchars($guide['id']) ?></p>
          <p><strong>Tên:</strong> <?= htmlspecialchars($guide['full_name'] ?? $guide['name']) ?></p>
          <p><strong>Ngày sinh:</strong>
            <?php
            $birth_date = $guide['birth_date'] ?? '';
            if (!empty($birth_date) && $birth_date !== '0000-00-00') {
              $date = DateTime::createFromFormat('Y-m-d', $birth_date);
              echo htmlspecialchars($date ? $date->format('d/m/Y') : $birth_date);
            } else {
              echo '-';
            }
            ?>
          </p>
          <p><strong>Điện thoại:</strong> <?= htmlspecialchars($guide['phone'] ?? '') ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($guide['email'] ?? '') ?></p>
          <p><strong>Loại hướng dẫn:</strong> <?= htmlspecialchars($guide['guide_type'] ?? '') ?></p>
          <p><strong>Loại thẻ:</strong>
            <?php
            $license_type_map = [
              'noi_dia' => 'Nội địa',
              'quoc_te' => 'Quốc tế',
              'khong_co' => 'Thực tập'
            ];
            $license_type = $license_type_map[$guide['license_type'] ?? ''] ?? '-';
            echo htmlspecialchars($license_type);
            ?>
          </p>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5>Thống kê</h5>
        </div>
        <div class="card-body">
          <p><strong>Số tour đang dẫn:</strong> <span class="badge bg-primary fs-5"><?= $totalTours ?></span></p>
          <p><strong>Đánh giá trung bình:</strong>
            <?php
            $rating = $guide['average_rating'] ?? 0;
            echo number_format($rating, 1) . '/5';
            ?>
          </p>
          <!-- Thêm các thống kê khác nếu có -->
        </div>
      </div>
    </div>
  </div>

  <!-- Danh sách tour đang dẫn -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5>Danh sách tour đang dẫn (<?= $totalTours ?> tour)</h5>
        </div>
        <div class="card-body">
          <?php if (!empty($assignedTours)): ?>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>ID Booking</th>
                    <th>Tên Tour</th>
                    <th>Ngày đi</th>
                    <th>Điểm đến</th>
                    <th>Trạng thái Booking</th>
                    <th>Trạng thái Thanh toán</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($assignedTours as $tour): ?>
                  <tr>
                    <td><?= htmlspecialchars($tour['booking_id']) ?></td>
                    <td><?= htmlspecialchars($tour['tour_name'] ?? 'N/A') ?></td>
                    <td>
                      <?php
                      $start_date = $tour['booking_start_date'] ?? '';
                      if (!empty($start_date)) {
                        $date = DateTime::createFromFormat('Y-m-d', $start_date);
                        echo htmlspecialchars($date ? $date->format('d/m/Y') : $start_date);
                      } else {
                        echo '-';
                      }
                      ?>
                    </td>
                    <td><?= htmlspecialchars($tour['destination'] ?? '') ?></td>
                    <td>
                      <?php
                      $status = $tour['booking_status'] ?? '';
                      $status_map = [
                        'cho_xac_nhan' => '<span class="badge bg-warning">Chờ xác nhận</span>',
                        'da_xac_nhan' => '<span class="badge bg-info">Đã xác nhận</span>',
                        'dang_dien_ra' => '<span class="badge bg-primary">Đang diễn ra</span>',
                        'hoan_tat' => '<span class="badge bg-success">Hoàn tất</span>',
                        'huy' => '<span class="badge bg-danger">Đã hủy</span>',
                      ];
                      echo $status_map[$status] ?? htmlspecialchars($status);
                      ?>
                    </td>
                    <td>
                      <?php
                      $payment = $tour['payment_status'] ?? '';
                      $payment_map = [
                        'chua_thanh_toan' => '<span class="badge bg-danger">Chưa thanh toán</span>',
                        'da_coc' => '<span class="badge bg-warning">Đã cọc</span>',
                        'da_thanh_toan_du' => '<span class="badge bg-success">Đã thanh toán đủ</span>',
                      ];
                      echo $payment_map[$payment] ?? htmlspecialchars($payment);
                      ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="text-muted">Hướng dẫn viên này chưa được phân công tour nào.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Các option khác -->
  <div class="row mt-4">
    <div class="col-12">
      <a href="?act=guide-management" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
      </a>
      <a href="?act=edit-guide&id=<?= $guide['id'] ?>" class="btn btn-warning">
        <i class="bi bi-pencil"></i> Sửa thông tin
      </a>
    </div>
  </div>
</div>

<?php include "views/layout/footer.php"; ?>