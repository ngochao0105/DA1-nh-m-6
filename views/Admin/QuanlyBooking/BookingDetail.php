    <?php include "views/layout/header.php"; ?>
    <?php include "views/layout/sidebar.php"; ?>

    <style>
    .detail-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    }
    .section-title {
        font-weight: 600;
        font-size: 20px;
        margin-bottom: 15px;
        color: #1d3557;
    }
    .info-box {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 12px;
    }
    .info-box b {
        width: 150px;
        display: inline-block;
    }
    .table-custom {
        background: white;
        border-radius: 10px;
    }
    </style>

    <div class="container mt-4">
        <h2 class="mb-3 fw-bold">Chi tiết Booking #<?= $booking['id'] ?></h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <strong>Thành công!</strong> Khách hàng đã được thêm vào booking.
            </div>
        <?php endif; ?>

        <a href="index.php?act=booking-list" class="btn btn-secondary mb-3">← Quay về danh sách</a>

        <div class="detail-card">

            <!-- Booking Info -->
            <h3 class="section-title">Thông tin Booking</h3>
            <div class="info-box">
                <b>Tour:</b> <?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?><br>
                <b>Ngày đi:</b> 
                <?php 
                if (!empty($booking['ngay_di'])) {
                    $ngayDi = $booking['ngay_di'];
                    // Nếu là định dạng YYYY-MM-DD, convert sang d/m/Y
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngayDi)) {
                        echo date('d/m/Y', strtotime($ngayDi));
                    } else {
                        echo htmlspecialchars($ngayDi);
                    }
                } else {
                    echo 'N/A';
                }
                ?><br>
                <b>Loại đặt:</b> <?= htmlspecialchars($booking['loai_dat'] ?? 'N/A') ?><br>
            </div>

            <!-- Cập nhật trạng thái Booking -->
            <h3 class="section-title">Cập nhật trạng thái Booking</h3>
            <div class="info-box">
                <form action="index.php?act=update-booking-status" method="POST">
                    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label"><b>Trạng thái hiện tại:</b></label>
                        <div class="mb-2">
                            <?php 
                                $status = $booking['trang_thai'] ?? 'cho_xac_nhan';
                                if($status=='cho_xac_nhan') {
                                    echo "<span class='status-badge status-warning'><i class='bi bi-clock-history'></i> Chờ xác nhận</span>";
                                } elseif($status=='da_xac_nhan') {
                                    echo "<span class='status-badge status-info'><i class='bi bi-check-circle'></i> Đã xác nhận</span>";
                                } elseif($status=='dang_dien_ra') {
                                    echo "<span class='status-badge status-info'><i class='bi bi-play-circle-fill'></i> Đang diễn ra</span>";
                                } elseif($status=='hoan_tat') {
                                    echo "<span class='status-badge status-success'><i class='bi bi-check-circle-fill'></i> Hoàn tất</span>";
                                } else {
                                    echo "<span class='status-badge status-danger'><i class='bi bi-x-circle-fill'></i> Đã hủy</span>";
                                }
                            ?>
                        </div>
                        <label class="form-label"><b>Cập nhật trạng thái:</b></label>
                        <select name="status" class="form-select" style="max-width: 300px;">
                            <option value="cho_xac_nhan" <?= $status=='cho_xac_nhan'?'selected':'' ?>>Chờ xác nhận</option>
                            <option value="da_xac_nhan" <?= $status=='da_xac_nhan'?'selected':'' ?>>Đã xác nhận</option>
                            <option value="dang_dien_ra" <?= $status=='dang_dien_ra'?'selected':'' ?>>Đang diễn ra</option>
                            <option value="hoan_tat" <?= $status=='hoan_tat'?'selected':'' ?>>Hoàn tất</option>
                            <option value="da_huy" <?= $status=='da_huy'?'selected':'' ?>>Đã hủy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>

            <!-- Cập nhật trạng thái Thanh toán -->
            <h3 class="section-title">Cập nhật trạng thái Thanh toán</h3>
            <div class="info-box">
                <form action="index.php?act=update-payment-status" method="POST">
                    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label"><b>Trạng thái hiện tại:</b></label>
                        <div class="mb-2">
                            <?php 
                                $paymentStatus = $booking['trang_thai_thanh_toan'] ?? 'chua_thanh_toan';
                                if($paymentStatus=='chua_thanh_toan') echo "<span class='badge bg-secondary'>Chưa thanh toán</span>";
                                elseif($paymentStatus=='da_coc') echo "<span class='badge bg-info'>Đã cọc</span>";
                                elseif($paymentStatus=='da_thanh_toan_du') echo "<span class='badge bg-success'>Đã thanh toán đủ</span>";
                                else echo "<span class='badge bg-secondary'>Chưa thanh toán</span>";
                            ?>
                        </div>
                        <label class="form-label"><b>Cập nhật trạng thái:</b></label>
                        <select name="payment_status" class="form-select" style="max-width: 300px;">
                            <option value="chua_thanh_toan" <?= $paymentStatus=='chua_thanh_toan'?'selected':'' ?>>Chưa thanh toán</option>
                            <option value="da_coc" <?= $paymentStatus=='da_coc'?'selected':'' ?>>Đã cọc</option>
                            <option value="da_thanh_toan_du" <?= $paymentStatus=='da_thanh_toan_du'?'selected':'' ?>>Đã thanh toán đủ</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>

            <!-- HDV -->
            <h3 class="section-title">Hướng dẫn viên</h3>

        <?php if (!empty($hdv) && is_array($hdv)): ?>
            <div class="info-box">
                <b>Họ tên:</b> <?= ($hdv['full_name'] ?? $hdv['ho_ten'] ) ?><br>
                <b>Loại HDV:</b> <?= ($hdv['guide_type'] ?? $hdv['loai_hdv'] ) ?><br>
                <b>SĐT:</b> <?= ($hdv['phone'] ?? $hdv['sdt'] ) ?><br>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Chưa phân công HDV.</div>
        <?php endif; ?>

            <!-- Customers -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 class="section-title" style="margin: 0;">Danh sách khách</h3>
                <button type="button" class="btn btn-primary" onclick="openAddCustomerModal()" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-plus-circle"></i> Thêm khách hàng
                </button>
            </div>

            <table class="table table-bordered table-custom">
                <thead class="table-dark">
                    <tr>
                        <th>Họ tên</th>
                        <th>SĐT</th>
                        <th>Loại</th>
                        <th>Yêu cầu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers) && is_array($customers)): ?>
                        <?php foreach ($customers as $c): ?>
                            <tr>
                                <td><?= ($c['ten_khach'] ?? $c['name'] ) ?></td>
                                <td><?= ($c['sdt'] ?? $c['phone'] ) ?></td>
                                <td><?= ($c['loai_khach'] ?? $c['loai'] ) ?></td>
                                <td><?= ($c['yeu_cau_dac_biet'] ?? $c['yeu_cau'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">Không có khách</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

    <!-- Modal Thêm khách hàng -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Thêm khách hàng</h5>
                    <button type="button" class="btn-close" onclick="closeAddCustomerModal()" aria-label="Close"></button>
                </div>
                <form action="index.php?act=add-customer-to-booking" method="POST">
                    <input type="hidden" name="id_booking" value="<?= $booking['id'] ?>">
                    <div class="modal-body">
                        <?php if (isset($error) && !empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label"><b>Tên khách hàng <span class="text-danger">*</span></b></label>
                            <input type="text" name="ten_khach" class="form-control" required placeholder="Nhập tên khách hàng">
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><b>Số điện thoại</b></label>
                            <input type="text" name="sdt" class="form-control" placeholder="Nhập số điện thoại">
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><b>Loại khách</b></label>
                            <select name="loai_khach" class="form-select">
                                <option value="nguoi_lon">Người lớn</option>
                                <option value="tre_em">Trẻ em</option>
                                <option value="em_be">Em bé</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><b>Yêu cầu đặc biệt</b></label>
                            <textarea name="yeu_cau_dac_biet" class="form-control" rows="3" placeholder="Nhập yêu cầu đặc biệt (nếu có)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeAddCustomerModal()">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm khách hàng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openAddCustomerModal() {
        const modal = document.getElementById('addCustomerModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Sử dụng Bootstrap 5 modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        } else {
            // Fallback: hiển thị modal bằng CSS
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modalBackdrop';
            document.body.appendChild(backdrop);
        }
    }

    function closeAddCustomerModal() {
        const modal = document.getElementById('addCustomerModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        } else {
            // Fallback: ẩn modal bằng CSS
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
            const backdrop = document.getElementById('modalBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    }

    // Đóng modal khi click vào backdrop
    document.getElementById('addCustomerModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddCustomerModal();
        }
    });
    </script>

    <style>
    /* Fallback styles cho modal nếu Bootstrap chưa load */
    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }
    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-dialog {
        position: relative;
        width: auto;
        max-width: 500px;
        margin: 1.75rem auto;
    }
    .modal-content {
        position: relative;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.2);
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        outline: 0;
    }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    .modal-body {
        position: relative;
        flex: 1 1 auto;
        padding: 1rem;
    }
    .modal-footer {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        padding: 0.75rem;
        border-top: 1px solid #dee2e6;
    }
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: #000;
    }
    .modal-backdrop.show {
        opacity: 0.5;
    }
    </style>

    <?php include "views/layout/footer.php"; ?>
