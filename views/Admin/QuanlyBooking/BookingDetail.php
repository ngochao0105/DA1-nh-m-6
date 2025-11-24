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

    <!-- Modal Thêm khách hàng -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Thêm khách hàng</h5>
                    <button type="button" class="btn-close" onclick="closeAddCustomerModal()" aria-label="Close"></button>
                </div>
                <form id="addCustomerForm" action="index.php?act=add-customer-to-booking" method="POST">
                    <input type="hidden" name="id_booking" value="<?= $booking['id'] ?>">
                    <input type="hidden" id="scheduleStartDate" value="<?= !empty($schedule['start_date']) ? $schedule['start_date'] : '' ?>">
                    <input type="hidden" id="scheduleEndDate" value="<?= !empty($schedule['end_date']) ? $schedule['end_date'] : '' ?>">
                    <div class="modal-body">
                        <?php if (isset($error) && !empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <?php
                        // Hiển thị thông tin slot trong modal
                        if (!empty($schedule)) {
                            $maxSlots = intval($schedule['max_slots'] ?? 0);
                            $bookedSlots = intval($schedule['booked_slots'] ?? 0);
                            $remainingSlots = $maxSlots - $bookedSlots;
                            ?>
                            <div class="alert alert-info" style="margin-bottom: 15px; padding: 12px; border-radius: 8px; background: #d1ecf1; border: 1px solid #0dcaf0;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <strong><i class="bi bi-info-circle"></i> Thông tin Slot:</strong><br>
                                        <small>Slot tối đa: <strong><?= $maxSlots ?></strong> | 
                                               Slot còn lại: <strong style="color: <?= $remainingSlots > 0 ? '#10b981' : '#ef4444' ?>;"><?= $remainingSlots ?></strong></small>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <b><i class="bi bi-people"></i> Chọn khách hàng từ danh sách (tích vào checkbox để chọn nhiều):</b>
                            </label>
                            <div id="existingCustomersListModal" style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; max-height: 200px; overflow-y: auto;">
                                <div style="text-align: center; padding: 20px; color: #666;">
                                    <i class="bi bi-hourglass-split"></i> Đang tải danh sách khách hàng...
                                </div>
                            </div>
                            <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
                                <button type="button" class="btn btn-sm btn-primary" onclick="addSelectedCustomersModal()" style="flex: 1;">
                                    <i class="bi bi-plus-circle"></i> Thêm khách đã chọn
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="selectAllCustomersModal()">
                                    <i class="bi bi-check-all"></i> Chọn tất cả
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAllCustomersModal()">
                                    <i class="bi bi-x-square"></i> Bỏ chọn
                                </button>
                            </div>
                            <div id="selectedCountModal" style="margin-top: 8px; font-size: 13px; color: #3b82f6; font-weight: 500;">
                                Đã chọn: <span id="selectedCountNumberModal">0</span> khách
                            </div>
                        </div>

                        <div style="margin: 15px 0; text-align: center; color: #666;">
                            <strong>HOẶC</strong>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><b>Nhập thông tin khách mới:</b></label>
                            <div id="newCustomerListModal"></div>
                            <button type="button" class="btn btn-sm btn-success" onclick="addNewCustomerModal()">
                                <i class="bi bi-plus-circle"></i> Thêm khách mới
                            </button>
                        </div>

                        <div id="customerConflictWarningModal" style="display: none; margin-top: 15px; padding: 15px; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b; font-size: 14px;">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 18px; margin-right: 8px;"></i>
                            <strong>Cảnh báo trùng lịch:</strong>
                            <div id="customerConflictMessageModal" style="margin-top: 8px;"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeAddCustomerModal()">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="submitCustomerBtn">Thêm khách hàng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Load existing customers when modal opens
    function loadExistingCustomersModal() {
        fetch('index.php?act=ajax-get-customers')
            .then(res => res.json())
            .then(customers => {
                const container = document.getElementById('existingCustomersListModal');
                if (customers && customers.length > 0) {
                    let html = '';
                    customers.forEach((customer, index) => {
                        const name = customer.name || customer.ten_khach || '';
                        const phone = customer.phone || customer.sdt || '';
                        const value = `${name}|${phone}`;
                        html += `
                            <div class="customer-checkbox-item-modal" style="display: flex; align-items: center; padding: 8px; margin-bottom: 6px; background: white; border-radius: 6px; border: 1px solid #e0e0e0; cursor: pointer; transition: all 0.2s;" 
                                 onmouseover="this.style.background='#f0f7ff'; this.style.borderColor='#3b82f6';" 
                                 onmouseout="this.style.background='white'; this.style.borderColor='#e0e0e0';"
                                 onclick="toggleCustomerCheckboxModal(this)">
                                <input type="checkbox" class="customer-checkbox-modal" value="${value}" data-name="${name}" data-phone="${phone}" 
                                       style="width: 16px; height: 16px; margin-right: 10px; cursor: pointer;" 
                                       onchange="updateSelectedCountModal()">
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #1d3557; font-size: 14px;">${name}</div>
                                    ${phone ? `<div style="font-size: 12px; color: #666; margin-top: 2px;"><i class="bi bi-telephone"></i> ${phone}</div>` : ''}
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div style="text-align: center; padding: 20px; color: #999;">
                            <i class="bi bi-inbox" style="font-size: 1.5rem; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                            <p style="font-size: 13px;">Chưa có khách hàng nào trong hệ thống</p>
                        </div>
                    `;
                }
                updateSelectedCountModal();
            })
            .catch(err => {
                console.error('Lỗi khi tải danh sách khách hàng:', err);
                document.getElementById('existingCustomersListModal').innerHTML = `
                    <div style="text-align: center; padding: 15px; color: #ef4444;">
                        <i class="bi bi-exclamation-triangle"></i> Lỗi khi tải danh sách khách hàng
                    </div>
                `;
            });
    }

    function toggleCustomerCheckboxModal(element) {
        const checkbox = element.querySelector('.customer-checkbox-modal');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            updateSelectedCountModal();
        }
    }

    function selectAllCustomersModal() {
        const checkboxes = document.querySelectorAll('.customer-checkbox-modal');
        checkboxes.forEach(cb => cb.checked = true);
        updateSelectedCountModal();
    }

    function deselectAllCustomersModal() {
        const checkboxes = document.querySelectorAll('.customer-checkbox-modal');
        checkboxes.forEach(cb => cb.checked = false);
        updateSelectedCountModal();
    }

    function updateSelectedCountModal() {
        const checkboxes = document.querySelectorAll('.customer-checkbox-modal:checked');
        const count = checkboxes.length;
        document.getElementById('selectedCountNumberModal').textContent = count;
    }

    function addSelectedCustomersModal() {
        const checkboxes = document.querySelectorAll('.customer-checkbox-modal:checked');
        
        if (checkboxes.length === 0) {
            alert('Vui lòng chọn ít nhất một khách hàng bằng cách tích vào checkbox');
            return;
        }
        
        let addedCount = 0;
        checkboxes.forEach(checkbox => {
            if (!checkbox.value) return;
            const name = checkbox.getAttribute('data-name') || checkbox.value.split('|')[0];
            const phone = checkbox.getAttribute('data-phone') || checkbox.value.split('|')[1] || '';
            
            if (!name) return;
            
            // Kiểm tra xem khách này đã được thêm chưa
            const existingInputs = document.querySelectorAll('#newCustomerListModal input[name="ten_khach[]"]');
            let alreadyAdded = false;
            existingInputs.forEach(input => {
                if (input.value.trim() === name.trim()) {
                    alreadyAdded = true;
                }
            });
            
            if (!alreadyAdded) {
                addCustomerRowModal(name, phone);
                addedCount++;
            }
        });
        
        if (addedCount > 0) {
            // Bỏ chọn sau khi thêm
            checkboxes.forEach(cb => cb.checked = false);
            updateSelectedCountModal();
        } else {
            alert('Tất cả khách hàng đã được thêm vào danh sách');
        }
    }

    function addNewCustomerModal() {
        addCustomerRowModal('', '');
    }

    function addCustomerRowModal(name = '', phone = '') {
        const html = `
            <div class="customer-box-modal" data-customer-row style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 10px; position: relative;">
                <button type="button" onclick="removeCustomerRowModal(this)" style="position: absolute; top: 5px; right: 5px; background: #ef4444; color: white; border: none; border-radius: 5px; padding: 3px 8px; cursor: pointer; font-size: 12px;">Xóa</button>
                <div class="mb-2">
                    <input type="text" class="form-control" name="ten_khach[]" placeholder="Tên khách *" value="${name}" required onblur="checkCustomerConflictModal(this)">
                </div>
                <div class="mb-2">
                    <input type="text" class="form-control" name="sdt[]" placeholder="Số điện thoại" value="${phone}" onblur="checkCustomerConflictModal(this)">
                </div>
                <div class="mb-2">
                    <select class="form-select" name="loai_khach[]" required>
                        <option value="nguoi_lon">Người lớn</option>
                        <option value="tre_em">Trẻ em</option>
                        <option value="em_be">Em bé</option>
                    </select>
                </div>
                <div class="mb-2">
                    <textarea class="form-control" name="yeu_cau_dac_biet[]" placeholder="Yêu cầu đặc biệt" rows="2"></textarea>
                </div>
                <div class="customer-conflict-message-modal" style="display: none; color: #ef4444; font-size: 12px; margin-top: 5px;"></div>
            </div>
        `;
        document.getElementById('newCustomerListModal').insertAdjacentHTML('beforeend', html);
    }

    function removeCustomerRowModal(btn) {
        btn.closest('.customer-box-modal').remove();
        updateConflictWarningModal();
    }

    function checkCustomerConflictModal(input) {
        const row = input.closest('.customer-box-modal');
        const nameInput = row.querySelector('input[name="ten_khach[]"]');
        const phoneInput = row.querySelector('input[name="sdt[]"]');
        const conflictMsg = row.querySelector('.customer-conflict-message-modal');
        
        const name = nameInput.value.trim();
        const phone = phoneInput.value.trim();
        const startDate = document.getElementById('scheduleStartDate').value;
        const endDate = document.getElementById('scheduleEndDate').value || startDate;
        const bookingId = document.querySelector('input[name="id_booking"]').value;
        
        if (!name || !startDate) {
            conflictMsg.style.display = 'none';
            return;
        }
        
        fetch(`index.php?act=ajax-check-customer-conflict&customer_name=${encodeURIComponent(name)}&customer_phone=${encodeURIComponent(phone)}&start_date=${startDate}&end_date=${endDate}&exclude_booking_id=${bookingId}`)
            .then(res => res.json())
            .then(data => {
                if (data.conflict) {
                    conflictMsg.textContent = data.message;
                    conflictMsg.style.display = 'block';
                    nameInput.style.borderColor = '#ef4444';
                } else {
                    conflictMsg.style.display = 'none';
                    nameInput.style.borderColor = '';
                }
                updateConflictWarningModal();
            })
            .catch(err => {
                console.error('Lỗi khi kiểm tra trùng lịch:', err);
            });
    }

    function updateConflictWarningModal() {
        const conflictMessages = document.querySelectorAll('.customer-conflict-message-modal[style*="block"]');
        const warningDiv = document.getElementById('customerConflictWarningModal');
        const messageDiv = document.getElementById('customerConflictMessageModal');
        
        if (conflictMessages.length > 0) {
            let messages = [];
            conflictMessages.forEach(msg => {
                messages.push(msg.textContent);
            });
            messageDiv.innerHTML = messages.join('<br>');
            warningDiv.style.display = 'block';
        } else {
            warningDiv.style.display = 'none';
        }
    }

    function openAddCustomerModal() {
        loadExistingCustomersModal();
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
        // Clear form when closing
        document.getElementById('newCustomerListModal').innerHTML = '';
        // Uncheck all checkboxes
        const checkboxes = document.querySelectorAll('.customer-checkbox-modal');
        checkboxes.forEach(cb => cb.checked = false);
        updateSelectedCountModal();
        document.getElementById('customerConflictWarningModal').style.display = 'none';
        
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

    // Handle form submission - convert multiple customers to multiple form submissions
    document.getElementById('addCustomerForm')?.addEventListener('submit', function(e) {
        const customerRows = document.querySelectorAll('#newCustomerListModal .customer-box-modal');
        if (customerRows.length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất một khách hàng');
            return false;
        }
        
        // Check for conflicts before submitting
        const hasConflicts = document.querySelectorAll('.customer-conflict-message-modal[style*="block"]').length > 0;
        if (hasConflicts) {
            e.preventDefault();
            alert('Không thể thêm khách hàng do có trùng lịch. Vui lòng kiểm tra lại.');
            return false;
        }
        
        // Kiểm tra lại tất cả khách hàng trước khi submit
        const customerInputs = document.querySelectorAll('#newCustomerListModal input[name="ten_khach[]"]');
        let hasConflict = false;
        let conflictNames = [];
        
        // Lấy thông tin schedule
        const startDate = document.getElementById('scheduleStartDate').value;
        const endDate = document.getElementById('scheduleEndDate').value || startDate;
        const bookingId = document.querySelector('input[name="id_booking"]').value;
        
        if (!startDate) {
            e.preventDefault();
            alert('Không thể kiểm tra trùng lịch. Vui lòng thử lại.');
            return false;
        }
        
        // Kiểm tra từng khách hàng
        const checkPromises = [];
        customerInputs.forEach(input => {
            const name = input.value.trim();
            if (!name) return;
            
            const phoneInput = input.closest('.customer-box-modal').querySelector('input[name="sdt[]"]');
            const phone = phoneInput ? phoneInput.value.trim() : '';
            
            const promise = fetch(`index.php?act=ajax-check-customer-conflict&customer_name=${encodeURIComponent(name)}&customer_phone=${encodeURIComponent(phone)}&start_date=${startDate}&end_date=${endDate}&exclude_booking_id=${bookingId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.conflict) {
                        hasConflict = true;
                        conflictNames.push(name);
                        // Highlight input
                        input.style.borderColor = '#ef4444';
                        const conflictMsg = input.closest('.customer-box-modal').querySelector('.customer-conflict-message-modal');
                        if (conflictMsg) {
                            conflictMsg.textContent = data.message;
                            conflictMsg.style.display = 'block';
                        }
                    }
                })
                .catch(err => {
                    console.error('Lỗi khi kiểm tra conflict:', err);
                });
            
            checkPromises.push(promise);
        });
        
        // Chờ tất cả kiểm tra hoàn thành trước khi submit
        if (checkPromises.length > 0) {
            e.preventDefault();
            
            Promise.all(checkPromises).then(() => {
                if (hasConflict) {
                    alert('Không thể thêm khách hàng. Các khách hàng sau đã có booking trùng lịch:\n' + conflictNames.join('\n') + '\n\nVui lòng chọn ngày khác.');
                    updateConflictWarningModal();
                } else {
                    // Nếu không có conflict, submit form
                    document.getElementById('addCustomerForm').submit();
                }
            });
            
            return false;
        }
    });

    // Đóng modal khi click vào backdrop
    document.getElementById('addCustomerModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddCustomerModal();
        }
    });

    // Xóa khách hàng
    function deleteCustomer(customerId, customerName) {
        if (!confirm(`Bạn có chắc chắn muốn xóa khách hàng "${customerName}" khỏi booking này không?`)) {
            return;
        }

        // Hiển thị loading
        const row = document.getElementById('customer-row-' + customerId);
        if (row) {
            row.style.opacity = '0.5';
            row.style.pointerEvents = 'none';
        }

        fetch('index.php?act=delete-customer-from-booking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `customer_id=${customerId}&id_booking=<?= $booking['id'] ?>`
        })
        .then(response => {
            // Kiểm tra xem response có phải JSON không
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // Nếu không phải JSON, đọc text để debug
                return response.text().then(text => {
                    console.error('Response không phải JSON:', text);
                    throw new Error('Server trả về response không hợp lệ');
                });
            }
        })
        .then(data => {
            if (data && data.success) {
                // Xóa dòng khỏi bảng
                if (row) {
                    row.remove();
                }
                
                // Kiểm tra xem còn khách nào không
                const remainingRows = document.querySelectorAll('table tbody tr[id^="customer-row-"]');
                if (remainingRows.length === 0) {
                    const tbody = document.querySelector('table tbody');
                    if (tbody) {
                        tbody.innerHTML = '<tr><td colspan="5">Không có khách</td></tr>';
                    }
                }
                
                // Hiển thị thông báo thành công
                showNotification('Xóa khách hàng thành công!', 'success');
                
                // Reload trang sau 1 giây để cập nhật slot
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                const errorMsg = (data && data.message) ? data.message : 'Không xác định được lỗi';
                alert('Lỗi khi xóa khách hàng: ' + errorMsg);
                if (row) {
                    row.style.opacity = '1';
                    row.style.pointerEvents = 'auto';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi khi xóa khách hàng: ' + (error.message || 'Vui lòng thử lại.'));
            if (row) {
                row.style.opacity = '1';
                row.style.pointerEvents = 'auto';
            }
        });
    }

    // Hiển thị thông báo
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    </script>

    <style>
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    </style>

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
