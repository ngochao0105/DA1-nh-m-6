<?php include "views/layout/header.php"; ?>
    <?php include "views/layout/sidebar.php"; ?>

    <style>
/* ===========================
   BOOKING DETAIL – FULL CSS
   =========================== */

.detail-card {
    background: #ffffff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.08);
}

.section-title {
    font-weight: 700;
    font-size: 20px;
    margin-bottom: 15px;
    color: #1d3557;
}

.info-box {
    padding: 15px 18px;
    background: #f8f9fa;
    border-radius: 12px;
    margin-bottom: 12px;
    border: 1px solid #e3e6ea;
}

.info-box b {
    width: 150px;
    display: inline-block;
    color: #333;
}

.table-custom {
    background: white;
    border-radius: 12px;
    overflow: hidden;
}

/* ===========================
   BADGE CHO TRẠNG THÁI BOOKING
   =========================== */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    color: #fff;
}

.status-warning {
    background: #f59e0b !important;
}

.status-info {
    background: #3b82f6 !important;
}

.status-success {
    background: #10b981 !important;
}

.status-danger {
    background: #ef4444 !important;
}

/* ===========================
   BADGE CHO THANH TOÁN
   (Fix lỗi bị nền xám che)
   =========================== */

.badge {
    font-size: 13px;
    padding: 6px 10px !important;
    border-radius: 8px !important;
}

/* Badge màu fix chuẩn Bootstrap mới */
.badge.bg-secondary { background-color: #6c757d !important; color: #fff !important; }
.badge.bg-info { background-color: #0dcaf0 !important; color: #fff !important; }
.badge.bg-success { background-color: #198754 !important; color: #fff !important; }

/* ===========================
   BUTTON
   =========================== */

.btn-primary {
    background: #2563eb;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
}

.btn-primary:hover {
    background: #1d4ed8;
}

.btn-danger {
    padding: 4px 10px;
    border-radius: 6px;
}

/* ===========================
   RESPONSIVE
   =========================== */

@media (max-width: 768px) {
    .info-box b {
        display: block;
        margin-bottom: 4px;
        width: 100%;
    }

    .detail-card {
        padding: 18px;
    }
}

    
    </style>

    <div class="container mt-4">
        <h2 class="mb-3 fw-bold">Chi tiết Booking #<?= $booking['id'] ?></h2>

        <!-- ✅ HIỂN THỊ THÔNG BÁO LỖI / THÀNH CÔNG -->
        <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #fecaca;">
                <i class="bi bi-exclamation-circle-fill"></i> <strong>Lỗi:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <strong>✓ Thành công!</strong> Khách hàng đã được thêm vào booking.
            </div>
        <?php endif; ?>

        <a href="index.php?act=booking-list" class="btn btn-secondary mb-3">← Quay về danh sách</a>

        <div class="detail-card">

            <!-- Booking Info -->
            <h3 class="section-title">Thông tin Booking</h3>
            <div class="info-box">
                <b>Tour:</b> <?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?><br>
                <b>Loại đặt:</b> <?= htmlspecialchars($booking['loai_dat'] ?? 'N/A') ?><br>
                
                <?php
                // Hiển thị thông tin lịch trình nếu có schedule
                if (!empty($schedule) && is_array($schedule)) {
                    $maxSlots = intval($schedule['max_slots'] ?? 0);
                    $bookedSlots = intval($schedule['booked_slots'] ?? 0);
                    $remainingSlots = $maxSlots - $bookedSlots;
                    $currentCustomers = is_array($customers) ? count($customers) : 0;
                    
                    // Ngày bắt đầu
                    if (!empty($schedule['start_date'])) {
                        $startDate = date('d/m/Y', strtotime($schedule['start_date']));
                        echo "<b>Ngày bắt đầu:</b> <strong>{$startDate}</strong><br>";
                    } else {
                        // Fallback: hiển thị ngày đi nếu không có start_date
                        echo "<b>Ngày đi:</b> ";
                        if (!empty($booking['ngay_di'])) {
                            $ngayDi = $booking['ngay_di'];
                            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngayDi)) {
                                echo "<strong>" . date('d/m/Y', strtotime($ngayDi)) . "</strong>";
                            } else {
                                echo "<strong>" . htmlspecialchars($ngayDi) . "</strong>";
                            }
                        } else {
                            echo 'N/A';
                        }
                        echo "<br>";
                    }
                    
                    // Ngày kết thúc
                    if (!empty($schedule['end_date'])) {
                        $endDate = date('d/m/Y', strtotime($schedule['end_date']));
                        echo "<b>Ngày kết thúc:</b> <strong>{$endDate}</strong><br>";
                    }
                    
                    // Giá
                    if (!empty($schedule['price'])) {
                        $price = number_format($schedule['price'], 0, ',', '.');
                        echo "<b>Giá:</b> <strong style='color: #3b82f6;'>{$price} VNĐ</strong><br>";
                    }
                    
                    // Slot tối đa
                    echo "<b>Slot tối đa:</b> <span style='color: #3b82f6; font-weight: 600;'>{$maxSlots}</span><br>";
                    
                    // Đã đặt
                    echo "<b>Đã đặt:</b> <span style='color: #666;'>{$bookedSlots}</span><br>";
                    
                    // Còn lại
                    echo "<b>Còn lại:</b> <span style='color: " . ($remainingSlots > 0 ? '#10b981' : '#ef4444') . "; font-weight: 600;'>{$remainingSlots}</span><br>";
                    
                    // Trạng thái
                    $status = $schedule['status'] ?? 'sap_mo';
                    $statusText = '';
                    $statusClass = '';
                    if ($status == 'dang_mo') {
                        $statusText = 'Đang mở';
                        $statusClass = 'bg-success';
                    } elseif ($status == 'da_dong') {
                        $statusText = 'Đã đóng';
                        $statusClass = 'bg-danger';
                    } else {
                        $statusText = 'Sắp mở';
                        $statusClass = 'bg-warning';
                    }
                    echo "<b>Trạng thái:</b> <span class='badge {$statusClass}'>{$statusText}</span><br>";
                } else {
                    // Nếu không có schedule, chỉ hiển thị ngày đi
                    echo "<b>Ngày đi:</b> ";
                    if (!empty($booking['ngay_di'])) {
                        $ngayDi = $booking['ngay_di'];
                        // Nếu là định dạng YYYY-MM-DD, convert sang d/m/Y
                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngayDi)) {
                            echo "<strong>" . date('d/m/Y', strtotime($ngayDi)) . "</strong>";
                        } else {
                            echo "<strong>" . htmlspecialchars($ngayDi) . "</strong>";
                        }
                    } else {
                        echo 'N/A';
                    }
                    echo "<br>";
                    echo "<div style='margin-top: 10px; padding: 10px; background: #fff3cd; border-radius: 5px; color: #856404; font-size: 0.9em;'><i class='bi bi-info-circle'></i> Chưa có thông tin lịch trình. Vui lòng liên kết booking với lịch trình tour.</div>";
                }
                ?>
            </div>

            <!-- ✅ Cập nhật trạng thái Booking với Validation -->
            <h3 class="section-title">Cập nhật trạng thái Booking</h3>
            <div class="info-box">
                <?php
                $currentStatus = $booking['trang_thai'] ?? 'cho_xac_nhan';
                $isLocked = ($currentStatus === 'dang_dien_ra' || $currentStatus === 'hoan_tat' || $currentStatus === 'da_huy');
                $lockMessage = '';
                
                if ($currentStatus === 'dang_dien_ra') {
                    $lockMessage = '🔒 Booking đang diễn ra không thể thay đổi trạng thái.';
                } elseif ($currentStatus === 'hoan_tat') {
                    $lockMessage = '🔒 Booking hoàn tất. Chỉ có thể chuyển sang Đã hủy.';
                } elseif ($currentStatus === 'da_huy') {
                    $lockMessage = '🔒 Booking đã hủy không thể hoàn tác.';
                }
                ?>

                <form action="index.php?act=update-booking-status" method="POST">
                    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label"><b>Trạng thái hiện tại:</b></label>
                        <div class="mb-2">
                            <?php 
                                if($currentStatus=='cho_xac_nhan') {
                                    echo "<span class='status-badge status-warning'><i class='bi bi-clock-history'></i> Chờ xác nhận</span>";
                                } elseif($currentStatus=='da_xac_nhan') {
                                    echo "<span class='status-badge status-info'><i class='bi bi-check-circle'></i> Đã xác nhận</span>";
                                } elseif($currentStatus=='dang_dien_ra') {
                                    echo "<span class='status-badge status-info'><i class='bi bi-play-circle-fill'></i> Đang diễn ra</span>";
                                } elseif($currentStatus=='hoan_tat') {
                                    echo "<span class='status-badge status-success'><i class='bi bi-check-circle-fill'></i> Hoàn tất</span>";
                                } else {
                                    echo "<span class='status-badge status-danger'><i class='bi bi-x-circle-fill'></i> Đã hủy</span>";
                                }
                            ?>
                        </div>

                        <?php if (!empty($lockMessage)): ?>
                            <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 10px; border: 1px solid #fecaca; font-size: 13px;">
                                <i class="bi bi-exclamation-triangle-fill"></i> <?= $lockMessage ?>
                            </div>
                        <?php endif; ?>

                        <label class="form-label"><b>Cập nhật trạng thái:</b></label>
                        <select name="status" class="form-select" style="max-width: 300px;" <?= $isLocked ? 'disabled' : '' ?>>
                            <option value="cho_xac_nhan" <?= $currentStatus=='cho_xac_nhan'?'selected':'' ?>>Chờ xác nhận</option>
                            <option value="da_xac_nhan" <?= $currentStatus=='da_xac_nhan'?'selected':'' ?>>Đã xác nhận</option>
                            <option value="dang_dien_ra" <?= $currentStatus=='dang_dien_ra'?'selected':'' ?>>Đang diễn ra</option>
                            <option value="hoan_tat" <?= $currentStatus=='hoan_tat'?'selected':'' ?>>Hoàn tất</option>
                            <option value="da_huy" <?= $currentStatus=='da_huy'?'selected':'' ?>>Đã hủy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" <?= $isLocked ? 'disabled' : '' ?>>Lưu thay đổi</button>
                </form>
            </div>

            <!-- ✅ Cập nhật trạng thái Thanh toán với validation UI -->
            <h3 class="section-title">Cập nhật trạng thái Thanh toán</h3>
            <div class="info-box">
                <?php 
                $paymentStatus = $booking['trang_thai_thanh_toan'] ?? 'chua_thanh_toan';
                $bookingStatus = $booking['trang_thai'] ?? 'cho_xac_nhan';

                // Build allowed options for UI
                if ($bookingStatus === 'dang_dien_ra') {
                    $options = [
                        'da_coc' => 'Đã cọc',
                        'da_thanh_toan_du' => 'Đã thanh toán đủ'
                    ];
                    $isPaymentLocked = false; // allow change between these two
                    $paymentLockMessage = '🔒 Booking đang diễn ra ';
                    $infoStyle = 'background:#d1ecf1;color:#0c5460;border:1px solid #bee5eb';
                } elseif ($bookingStatus === 'da_huy') {
                    $options = ['chua_thanh_toan' => 'Chưa thanh toán'];
                    $isPaymentLocked = true;
                    $paymentLockMessage = '🔒 Booking đã hủy .';
                    $infoStyle = 'background:#fee2e2;color:#991b1b;border:1px solid #fecaca';
                } else {
                    $options = [
                        'chua_thanh_toan' => 'Chưa thanh toán',
                        'da_coc' => 'Đã cọc',
                        'da_thanh_toan_du' => 'Đã thanh toán đủ'
                    ];
                    $isPaymentLocked = ($paymentStatus === 'da_thanh_toan_du'); // không đổi nếu đã đủ
                    $paymentLockMessage = $isPaymentLocked ? '🔒 Đã thanh toán đủ không thể thay đổi.' : '';
                    $infoStyle = $isPaymentLocked ? 'background:#d4edda;color:#155724;border:1px solid #c3e6cb' : '';
                }
                ?>

                <form action="index.php?act=update-payment-status" method="POST">
                    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label"><b>Trạng thái hiện tại:</b></label>
                        <div class="mb-2">
                            <?php 
                                if($paymentStatus=='chua_thanh_toan') echo "<span class='badge bg-secondary'>Chưa thanh toán</span>";
                                elseif($paymentStatus=='da_coc') echo "<span class='badge bg-info'>Đã cọc</span>";
                                elseif($paymentStatus=='da_thanh_toan_du') echo "<span class='badge bg-success'>Đã thanh toán đủ</span>";
                                else echo "<span class='badge bg-secondary'>Chưa thanh toán</span>";
                            ?>
                        </div>

                        <?php if (!empty($paymentLockMessage)): ?>
                            <div style="<?= $infoStyle ?>; padding: 10px; border-radius: 8px; margin-bottom: 10px; font-size: 13px;">
                                <i class="bi bi-info-circle-fill"></i> <?= $paymentLockMessage ?>
                            </div>
                        <?php endif; ?>

                        <label class="form-label"><b>Cập nhật trạng thái:</b></label>
                        <select name="payment_status" class="form-select" style="max-width: 300px; background-color: <?= $isPaymentLocked ? '#f5f5f5' : '#fff' ?>;" <?= $isPaymentLocked ? 'disabled' : '' ?>>
                            <?php foreach ($options as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $paymentStatus == $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" <?= $isPaymentLocked ? 'disabled' : '' ?>>Lưu thay đổi</button>
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
                <div>
                    <h3 class="section-title" style="margin: 0;">Danh sách khách</h3>
                    <?php
                    if (!empty($schedule)) {
                        $maxSlots = intval($schedule['max_slots'] ?? 0);
                        $bookedSlots = intval($schedule['booked_slots'] ?? 0);
                        $remainingSlots = $maxSlots - $bookedSlots;
                        $currentCustomers = is_array($customers) ? count($customers) : 0;
                        
                        if ($remainingSlots <= 0) {
                            echo '<div class="alert alert-warning" style="margin-top: 10px; padding: 10px; border-radius: 8px; background: #fff3cd; border: 1px solid #ffc107;">
                                    <i class="bi bi-exclamation-triangle"></i> <strong>Cảnh báo:</strong> Đã hết slot. Không thể thêm khách hàng mới.
                                  </div>';
                        } elseif ($remainingSlots <= 3) {
                            echo '<div class="alert alert-info" style="margin-top: 10px; padding: 10px; border-radius: 8px; background: #d1ecf1; border: 1px solid #0dcaf0;">
                                    <i class="bi bi-info-circle"></i> <strong>Lưu ý:</strong> Chỉ còn <strong>' . $remainingSlots . '</strong> slot trống.
                                  </div>';
                        }
                    }
                    ?>
                </div>
                <button type="button" class="btn btn-primary" onclick="openAddCustomerModal()" 
                        style="display: inline-flex; align-items: center; gap: 8px;"
                        <?php 
                        if (!empty($schedule)) {
                            $maxSlots = intval($schedule['max_slots'] ?? 0);
                            $bookedSlots = intval($schedule['booked_slots'] ?? 0);
                            $remainingSlots = $maxSlots - $bookedSlots;
                            if ($remainingSlots <= 0) {
                                echo 'disabled title="Đã hết slot"';
                            }
                        }
                        ?>>
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
                        <th style="width: 100px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers) && is_array($customers)): ?>
                        <?php foreach ($customers as $c): ?>
                            <tr id="customer-row-<?= $c['id'] ?>">
                                <td><?= htmlspecialchars($c['ten_khach'] ?? $c['name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['sdt'] ?? $c['phone'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['loai_khach'] ?? $c['loai'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['yeu_cau_dac_biet'] ?? $c['yeu_cau'] ?? '') ?></td>
                                <td style="text-align: center;">
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="deleteCustomer(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['ten_khach'] ?? $c['name'] ?? '')) ?>')"
                                            title="Xóa khách hàng">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">Không có khách</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php
        // Tính slot còn lại để chặn thêm quá giới hạn
        $maxSlots = isset($schedule['max_slots']) ? (int)$schedule['max_slots'] : null;
        $bookedSlots = isset($schedule['booked_slots']) ? (int)$schedule['booked_slots'] : 0;
        $remainingSlots = isset($maxSlots) ? max($maxSlots - $bookedSlots, 0) : null;
    ?>

    <!-- Modal Thêm khách hàng -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus-fill"></i> Thêm khách hàng vào booking
                    </h5>
                    <button type="button" class="btn-close" aria-label="Close" onclick="closeAddCustomerModal()"></button>
                </div>
                <form id="addCustomerForm" action="index.php?act=add-customer-to-booking" method="POST">
                    <input type="hidden" name="id_booking" value="<?= $booking['id'] ?>">
                    <div class="modal-body">
                        <?php if ($remainingSlots === 0): ?>
                            <div class="alert alert-warning mb-3">
                                <i class="bi bi-exclamation-triangle"></i>
                                Booking đã hết slot, không thể thêm khách mới.
                            </div>
                        <?php elseif (isset($remainingSlots)): ?>
                            <div class="alert alert-info mb-3">
                                <i class="bi bi-info-circle"></i>
                                Còn lại <strong><?= $remainingSlots ?></strong> slot trống cho lịch trình này.
                            </div>
                        <?php endif; ?>

                        <div id="addCustomerRows"></div>

                        <button type="button" class="btn btn-outline-primary w-100" onclick="addCustomerRow()">
                            <i class="bi bi-plus-lg"></i> Thêm dòng khách hàng
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeAddCustomerModal()">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu khách hàng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let addCustomerModal = null;
        const bookingId = <?= (int)$booking['id']; ?>;
        const remainingSlots = <?= $remainingSlots !== null ? $remainingSlots : 'null'; ?>;

        document.addEventListener('DOMContentLoaded', () => {
            const modalEl = document.getElementById('addCustomerModal');
            if (modalEl && window.bootstrap) {
                addCustomerModal = new bootstrap.Modal(modalEl);
            }
            resetAddCustomerForm();

            const addCustomerForm = document.getElementById('addCustomerForm');
            if (addCustomerForm) {
                addCustomerForm.addEventListener('submit', handleAddCustomerSubmit);
            }
        });

        function openAddCustomerModal() {
            if (remainingSlots === 0) {
                alert('Booking đã hết slot, không thể thêm khách mới.');
                return;
            }
            resetAddCustomerForm();
            addCustomerModal?.show();
        }

        function closeAddCustomerModal() {
            addCustomerModal?.hide();
        }

        function resetAddCustomerForm() {
            const rowsContainer = document.getElementById('addCustomerRows');
            if (!rowsContainer) return;
            rowsContainer.innerHTML = '';
            addCustomerRow();
            const form = document.getElementById('addCustomerForm');
            form?.reset();
        }

        function addCustomerRow() {
            const rowsContainer = document.getElementById('addCustomerRows');
            if (!rowsContainer) return;

            const currentRows = rowsContainer.querySelectorAll('.customer-row').length;
            if (remainingSlots !== null && currentRows >= remainingSlots) {
                alert(`Bạn chỉ có thể thêm tối đa ${remainingSlots} khách cho booking này.`);
                return;
            }

            const row = document.createElement('div');
            row.className = 'customer-row border rounded p-3 mb-3';
            row.innerHTML = `
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Họ tên *</label>
                        <input type="text" name="ten_khach[]" class="form-control" placeholder="VD: Nguyễn Văn A" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">SĐT</label>
                        <input type="text" name="sdt[]" class="form-control" placeholder="090xxxxxxx">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Loại</label>
                        <select name="loai_khach[]" class="form-select">
                            <option value="nguoi_lon">Người lớn</option>
                            <option value="tre_em">Trẻ em</option>
                            <option value="tre_nho">Trẻ nhỏ</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Yêu cầu</label>
                        <input type="text" name="yeu_cau_dac_biet[]" class="form-control" placeholder="Ăn chay, dị ứng...">
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCustomerRow(this)">
                        <i class="bi bi-trash"></i> Xóa dòng
                    </button>
                </div>
            `;
            rowsContainer.appendChild(row);
        }

        function removeCustomerRow(button) {
            const rowsContainer = document.getElementById('addCustomerRows');
            if (!rowsContainer) return;
            const rows = rowsContainer.querySelectorAll('.customer-row');
            if (rows.length <= 1) {
                alert('Cần ít nhất một khách hàng.');
                return;
            }
            button.closest('.customer-row')?.remove();
        }

        function handleAddCustomerSubmit(event) {
            if (!remainingSlots) return; // Không giới hạn, submit bình thường

            const rowsContainer = document.getElementById('addCustomerRows');
            const rows = rowsContainer?.querySelectorAll('.customer-row') ?? [];
            if (remainingSlots !== null && rows.length > remainingSlots) {
                event.preventDefault();
                alert(`Bạn chỉ có thể thêm tối đa ${remainingSlots} khách cho booking này.`);
            }
        }

        function deleteCustomer(customerId, customerName) {
            if (!confirm(`Bạn có chắc muốn xóa khách "${customerName}" khỏi booking?`)) {
                return;
            }

            fetch('index.php?act=delete-customer-from-booking', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: new URLSearchParams({
                    customer_id: customerId,
                    id_booking: bookingId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById(`customer-row-${customerId}`);
                    row?.remove();
                    alert('Đã xóa khách hàng thành công.');
                } else {
                    alert(data.message || 'Không thể xóa khách hàng.');
                }
            })
            .catch(() => {
                alert('Có lỗi xảy ra khi xóa khách hàng.');
            });
        }
    </script>

    <?php include "views/layout/footer.php"; ?>