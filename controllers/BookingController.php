<?php
class BookingController 
{
    public $modelBooking;
    public $modelTour;

    public function __construct()
    {
        $this->modelBooking = new BookingModel();
        $this->modelTour    = new TourModel();
    }

    // ================================
    // Danh sách booking
    // ================================
    public function BookingList() 
    {
        $bookings = $this->modelBooking->getAllBooking();
        require_once "./views/Admin/QuanlyBooking/BookingList.php";
    }

    // ================================
    // Form tạo booking
    // ================================
    public function CreateBooking()
    {
        $tours = $this->modelTour->getAllTour();
        require_once "./views/Admin/QuanlyBooking/AddBooking.php";
    }

    // ================================
    // AJAX load Schedule theo tour
    // ================================
    public function AjaxGetSchedule()
    {
        $tourId = $_GET['tour_id'] ?? null;

        if (!$tourId) {
            echo '<div style="padding: 20px; text-align: center; color: #666;">Vui lòng chọn tour</div>';
            exit;
        }

        $modelSchedule = new ScheduleModel();
        $schedules = $modelSchedule->getSchedulesByTour($tourId);

        if (empty($schedules)) {
            echo '<div style="padding: 20px; text-align: center; color: #666; background: #f8f9fa; border-radius: 10px; margin-top: 15px;">
                    <i class="bi bi-calendar-x" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                    <p>Tour này chưa có lịch trình nào.</p>
                  </div>';
            exit;
        }

        echo '<div class="schedule-list" style="margin-top: 15px;">';
        echo '<label style="display: block; margin-bottom: 10px; font-weight: 600;">Chọn lịch trình:</label>';
        
        foreach ($schedules as $schedule) {
            $startDate = date('d/m/Y', strtotime($schedule['start_date']));
            $endDate = date('d/m/Y', strtotime($schedule['end_date']));
            $price = number_format($schedule['price'] ?? 0, 0, ',', '.');
            $maxSlots = $schedule['max_slots'] ?? 0;
            $bookedSlots = $schedule['booked_slots'] ?? 0;
            $availableSlots = $maxSlots - $bookedSlots;
            
            // Xác định trạng thái
            $status = $schedule['status'] ?? 'sap_mo';
            $statusText = '';
            $statusClass = '';
            if ($status == 'dang_mo') {
                $statusText = 'Đang mở';
                $statusClass = 'status-open';
            } elseif ($status == 'da_dong') {
                $statusText = 'Đã đóng';
                $statusClass = 'status-closed';
            } else {
                $statusText = 'Sắp mở';
                $statusClass = 'status-coming';
            }
            
            // Kiểm tra có thể chọn không
            $canSelect = ($status == 'dang_mo' && $availableSlots > 0);
            $disabled = $canSelect ? '' : 'disabled';
            $radioClass = $canSelect ? '' : 'schedule-disabled';
            
            echo '<div class="schedule-item ' . $radioClass . '" style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 10px; border: 2px solid ' . ($canSelect ? '#e0e7ff' : '#fee') . '; cursor: ' . ($canSelect ? 'pointer' : 'not-allowed') . ';">';
            echo '<label style="display: flex; align-items: center; cursor: ' . ($canSelect ? 'pointer' : 'not-allowed') . '; margin: 0;">';
            echo '<input type="radio" name="schedule_id" value="' . $schedule['id'] . '" data-start-date="' . $schedule['start_date'] . '" data-price="' . ($schedule['price'] ?? 0) . '" ' . $disabled . ' style="margin-right: 15px; cursor: pointer;">';
            echo '<div style="flex: 1;">';
            echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">';
            echo '<strong style="font-size: 16px;">' . $startDate . ' - ' . $endDate . '</strong>';
            echo '<span class="schedule-status ' . $statusClass . '" style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: ' . ($status == 'dang_mo' ? 'rgba(16, 185, 129, 0.15)' : ($status == 'da_dong' ? 'rgba(239, 68, 68, 0.15)' : 'rgba(245, 158, 11, 0.15)')) . '; color: ' . ($status == 'dang_mo' ? '#059669' : ($status == 'da_dong' ? '#dc2626' : '#d97706')) . ';">' . $statusText . '</span>';
            echo '</div>';
            echo '<div style="display: flex; gap: 20px; font-size: 14px; color: #666;">';
            echo '<span><i class="bi bi-currency-dollar"></i> <strong style="color: #3b82f6;">' . $price . ' VNĐ</strong></span>';
            echo '<span><i class="bi bi-people"></i> Còn lại: <strong style="color: ' . ($availableSlots > 0 ? '#10b981' : '#ef4444') . ';">' . $availableSlots . '</strong>/' . $maxSlots . '</span>';
            echo '</div>';
            if (!$canSelect) {
                echo '<div style="margin-top: 5px; font-size: 12px; color: #ef4444;">';
                if ($status != 'dang_mo') {
                    echo '<i class="bi bi-info-circle"></i> Lịch trình này chưa mở hoặc đã đóng';
                } elseif ($availableSlots <= 0) {
                    echo '<i class="bi bi-x-circle"></i> Đã hết chỗ';
                }
                echo '</div>';
            }
            echo '</div>';
            echo '</label>';
            echo '</div>';
        }
        
        echo '</div>';
        exit;
    }

    // ================================
    // AJAX load TẤT CẢ HDV theo ngày đi
    // ================================
    public function AjaxGetHdv()
    {
        $ngay = $_GET['ngay_di'] ?? null;

        if (!$ngay) {
            echo "<option value=''>Chưa chọn ngày</option>";
            exit;
        }

        // Lấy TẤT CẢ HDV
        $sql = "SELECT n.* FROM nhansu n ORDER BY n.full_name ASC";
        $stmt = $this->modelBooking->conn->prepare($sql);
        $stmt->execute();
        $allHdv = $stmt->fetchAll();

        // Lấy danh sách HDV đã bận trong ngày này
        $sqlBusy = "SELECT id_hdv FROM phan_cong_hdv WHERE ngay_di = :ngay";
        $stmtBusy = $this->modelBooking->conn->prepare($sqlBusy);
        $stmtBusy->execute(['ngay' => $ngay]);
        $busyHdvIds = array_column($stmtBusy->fetchAll(), 'id_hdv');

        if (empty($allHdv)) {
            echo "<option value=''>Không có HDV nào</option>";
            exit;
        }

        foreach ($allHdv as $row) {
            $name = htmlspecialchars($row['full_name']);
            $type = htmlspecialchars($row['guide_type'] ?? '');
            $isBusy = in_array($row['id'], $busyHdvIds);
            $busyText = $isBusy ? ' ⚠️ (Đã có lịch)' : '';
            
            // Không disable, cho phép chọn để xem cảnh báo
            echo "<option value='{$row['id']}' data-busy='" . ($isBusy ? 'true' : 'false') . "'>{$name} ({$type}){$busyText}</option>";
        }
        exit;
    }

    // ================================
    // AJAX kiểm tra HDV có bận không
    // ================================
    public function AjaxCheckHdvBusy()
    {
        $hdvId = $_GET['hdv_id'] ?? null;
        $ngay = $_GET['ngay_di'] ?? null;

        if (!$hdvId || !$ngay) {
            echo json_encode(['busy' => false, 'message' => '']);
            exit;
        }

        $isBusy = !$this->modelBooking->isHdvAvailable($hdvId, $ngay);
        
        if ($isBusy) {
            $conflict = $this->modelBooking->getHdvConflictInfo($hdvId, $ngay);
            
            // Lấy tên HDV
            $sqlHdv = "SELECT full_name FROM nhansu WHERE id = :id";
            $stmtHdv = $this->modelBooking->conn->prepare($sqlHdv);
            $stmtHdv->execute(['id' => $hdvId]);
            $hdvInfo = $stmtHdv->fetch();
            $hdvName = $hdvInfo ? $hdvInfo['full_name'] : 'HDV này';
            
            $tourName = $conflict['tour_name'] ?? 'tour khác';
            $bookingId = $conflict['booking_id'] ?? '';
            $ngayFormat = date('d/m/Y', strtotime($ngay));
            
            $message = "Hướng dẫn viên '{$hdvName}' đã được phân công cho {$tourName} (Booking #{$bookingId}) vào ngày {$ngayFormat}.";
            
            echo json_encode([
                'busy' => true,
                'message' => $message,
                'hdv_name' => $hdvName,
                'tour_name' => $tourName,
                'booking_id' => $bookingId
            ]);
        } else {
            echo json_encode(['busy' => false, 'message' => '']);
        }
        exit;
    }

    // ================================
    // Lưu Booking
    // ================================
    public function SaveBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id_tour = $_POST['id_tour'] ?? null;
        $schedule_id = $_POST['schedule_id'] ?? null;
        $ngay_di = $_POST['ngay_di'] ?? null;
        $id_hdv  = $_POST['id_hdv'] ?? null;

        $loai_dat = $_POST['loai_dat'] ?? 'ca_nhan';

        // Validate
        $errors = [];
        if (!$id_tour) $errors[] = "Chưa chọn tour";
        if (!$schedule_id) $errors[] = "Chưa chọn lịch trình";
        if (!$ngay_di) $errors[] = "Chưa chọn ngày đi";
        if (!$id_hdv) $errors[] = "Chưa chọn HDV";
        if (empty($_POST['ten_khach']) || !is_array($_POST['ten_khach'])) {
            $errors[] = "Chưa có khách hàng";
        }

        if (!empty($errors)) {
            $tours = $this->modelTour->getAllTour();
            $error = implode(", ", $errors);
            require "./views/Admin/QuanlyBooking/AddBooking.php";
            return;
        }

        try {
            // Kiểm tra schedule có còn slot không
            $modelSchedule = new ScheduleModel();
            $schedule = $modelSchedule->getScheduleById($schedule_id);
            
            if (!$schedule) {
                throw new Exception("Lịch trình không tồn tại");
            }
            
            if ($schedule['status'] != 'dang_mo') {
                throw new Exception("Lịch trình này không còn mở để đặt");
            }
            
            $availableSlots = $schedule['max_slots'] - ($schedule['booked_slots'] ?? 0);
            $soKhach = count(array_filter($_POST['ten_khach'], function($name) {
                return !empty(trim($name));
            }));
            
            if ($availableSlots < $soKhach) {
                throw new Exception("Lịch trình chỉ còn {$availableSlots} chỗ, nhưng bạn đang đặt {$soKhach} khách");
            }

            // 1. Tạo booking
            $id_booking = $this->modelBooking->createBooking($id_tour, $ngay_di, $loai_dat);
            
            if (!$id_booking) {
                throw new Exception("Không thể tạo booking");
            }

            // 2. Kiểm tra HDV có rảnh không
            if (!$this->modelBooking->isHdvAvailable($id_hdv, $ngay_di)) {
                // Lấy thông tin conflict để hiển thị lỗi chi tiết
                $conflict = $this->modelBooking->getHdvConflictInfo($id_hdv, $ngay_di);
                $hdvName = '';
                
                // Lấy tên HDV
                $sqlHdv = "SELECT full_name FROM nhansu WHERE id = :id";
                $stmtHdv = $this->modelBooking->conn->prepare($sqlHdv);
                $stmtHdv->execute(['id' => $id_hdv]);
                $hdvInfo = $stmtHdv->fetch();
                if ($hdvInfo) {
                    $hdvName = $hdvInfo['full_name'];
                }
                
                $tourName = $conflict['tour_name'] ?? 'tour khác';
                $bookingId = $conflict['booking_id'] ?? '';
                
                throw new Exception("Hướng dẫn viên '{$hdvName}' đã được phân công cho {$tourName} (Booking #{$bookingId}) vào ngày " . date('d/m/Y', strtotime($ngay_di)) . ". Vui lòng chọn HDV khác.");
            }

            // 3. Lưu khách
            foreach ($_POST['ten_khach'] as $i => $name) {
                if (empty(trim($name))) continue; // Bỏ qua nếu tên rỗng
                
                $sdt = $_POST['sdt'][$i] ?? '';
                $loai = $_POST['loai_khach'][$i] ?? 'nguoi_lon';
                $req = $_POST['yeu_cau_dac_biet'][$i] ?? '';
                
                $this->modelBooking->addCustomer($id_booking, trim($name), $sdt, $loai, $req);
            }

            // 4. Gán HDV
            $this->modelBooking->assignHdvToDate($id_hdv, $id_booking, $ngay_di);

            // 5. Cập nhật số slot đã đặt trong schedule
            for ($i = 0; $i < $soKhach; $i++) {
                $modelSchedule->updateBookedSlots($schedule_id, true);
            }

            // Redirect về danh sách với thông báo thành công
            header("Location: index.php?act=booking-list&success=1");
            exit;
        } catch (Exception $e) {
            $tours = $this->modelTour->getAllTour();
            $error = "Lỗi khi lưu booking: " . $e->getMessage();
            require "./views/Admin/QuanlyBooking/AddBooking.php";
            return;
        }
    }

    // ================================
    // Lịch sử thay đổi trạng thái
    // ================================
    public function BookingLogs()
    {
        $id = $_GET['id'] ?? 0;
        if (!$id) die("Không tìm thấy ID booking");

        $logs = $this->modelBooking->getBookingLogs($id);

        require "./views/Admin/QuanlyBooking/BookingLogs.php";
    }

    // ================================
    // Cập nhật trạng thái + log
    // ================================
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id     = $_POST['id'];
        $status = $_POST['status'];

        $allowed = ['cho_xac_nhan','da_xac_nhan','dang_dien_ra','hoan_tat','da_huy'];

        if (!in_array($status, $allowed)) {
            die("Trạng thái không hợp lệ!");
        }

        $this->modelBooking->updateBookingStatus($id, $status);

        // Redirect về trang chi tiết thay vì danh sách
        header("Location: index.php?act=booking-detail&id=" . $id);
        exit;
    }
    public function BookingDetail()
    {
    $id = $_GET['id'] ?? 0;

    if (!$id) {
        die("Không tìm thấy ID booking");
    }

    // Lấy booking
    $booking = $this->modelBooking->findBookingById($id);

    // Lấy khách của booking
    $customers = $this->modelBooking->getCustomersByBooking($id);

    // Lấy HDV
    $hdv = $this->modelBooking->getHdvByBooking($id);

    require "./views/Admin/QuanlyBooking/BookingDetail.php";
    }

    // ================================
    // Xóa booking
    // ================================
    public function deleteBooking()
    {
        if (!isset($_GET['id'])) {
            header("Location: index.php?act=booking-list");
            exit;
        }

        $id = $_GET['id'];
        $result = $this->modelBooking->deleteBooking($id);

        if ($result) {
            header("Location: index.php?act=booking-list");
        } else {
            die("Lỗi khi xóa booking!");
        }
        exit;
    }

    // ================================
    // Cập nhật trạng thái thanh toán
    // ================================
    public function updatePaymentStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id = $_POST['id'] ?? 0;
        $payment_status = $_POST['payment_status'] ?? 'chua_thanh_toan';

        $allowed = ['chua_thanh_toan', 'da_coc', 'da_thanh_toan_du'];
        if (!in_array($payment_status, $allowed)) {
            die("Trạng thái thanh toán không hợp lệ!");
        }

        $this->modelBooking->updatePaymentStatus($id, $payment_status);

        // Redirect về trang chi tiết thay vì danh sách
        header("Location: index.php?act=booking-detail&id=" . $id);
        exit;
    }

    // ================================
    // Thêm khách hàng vào booking
    // ================================
    public function addCustomerToBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=booking-list");
            exit;
        }

        $id_booking = $_POST['id_booking'] ?? 0;
        $ten_khach = trim($_POST['ten_khach'] ?? '');
        $sdt = trim($_POST['sdt'] ?? '');
        $loai_khach = $_POST['loai_khach'] ?? 'nguoi_lon';
        $yeu_cau_dac_biet = trim($_POST['yeu_cau_dac_biet'] ?? '');

        // Validate
        if (!$id_booking || empty($ten_khach)) {
            // Lấy lại dữ liệu để hiển thị form
            $booking = $this->modelBooking->findBookingById($id_booking);
            $customers = $this->modelBooking->getCustomersByBooking($id_booking);
            $hdv = $this->modelBooking->getHdvByBooking($id_booking);
            $error = "Tên khách hàng là bắt buộc";
            require "./views/Admin/QuanlyBooking/BookingDetail.php";
            return;
        }

        try {
            // Thêm khách hàng
            $this->modelBooking->addCustomer($id_booking, $ten_khach, $sdt, $loai_khach, $yeu_cau_dac_biet);

            // Redirect về trang chi tiết với thông báo thành công
            header("Location: index.php?act=booking-detail&id=" . $id_booking . "&success=1");
            exit;
        } catch (Exception $e) {
            // Lấy lại dữ liệu để hiển thị form với lỗi
            $booking = $this->modelBooking->findBookingById($id_booking);
            $customers = $this->modelBooking->getCustomersByBooking($id_booking);
            $hdv = $this->modelBooking->getHdvByBooking($id_booking);
            $error = "Lỗi khi thêm khách hàng: " . $e->getMessage();
            require "./views/Admin/QuanlyBooking/BookingDetail.php";
            return;
        }
    }

}
