<?php
class HdvController
{
     public function dashboard()
    {
       if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
        header("Location: ?act=login");
        exit;
       }
        // Hoặc load view dashboard:
       require_once 'views/HDV/hdv_dashboard.php';
       }
    public function myTours()
    {
        // 1. Kiểm tra quyền truy cập
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            header("Location: ?act=login");
            exit;
        }

        $hdvModel = new HdvModel();

        // 2. Lấy thông tin HDV từ tài khoản
        $account_id = $_SESSION['user_id'];
        $hdvProfile = $hdvModel->getHdvInfoByAccountId($account_id);

        if (!$hdvProfile) {
            echo "Không tìm thấy thông tin HDV.";
            exit;
        }

        // 3. Lấy ID HDV
        $hdv_id = $hdvProfile['id'];

        // 4. Lấy các tour được phân công
        $assignedTours = $hdvModel->getAssignedTours($hdv_id);

        // 5. Gửi sang view
        require_file_view("HDV/hdv_my_tours", compact("assignedTours"));
    }

    public function viewSchedule()
    {
        // 1. Kiểm tra quyền truy cập
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            header("Location: ?act=login");
            exit;
        }

        $hdvModel = new HdvModel();

        // 2. Lấy thông tin HDV từ tài khoản
        $account_id = $_SESSION['user_id'];
        $hdvProfile = $hdvModel->getHdvInfoByAccountId($account_id);

        if (!$hdvProfile) {
            echo "Không tìm thấy thông tin HDV.";
            exit;
        }

        // 3. Lấy ID HDV
        $hdv_id = $hdvProfile['id'];

        // 4. Lấy lịch trình tour
        $schedules = $hdvModel->getHdvSchedules($hdv_id);

        // 5. Gửi sang view
        require_file_view("HDV/hdv_tour_schedule", compact("schedules", "hdvProfile"));
    }

    public function viewScheduleDetail()
    {
        // 1. Kiểm tra quyền truy cập
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            header("Location: ?act=login");
            exit;
        }

        $booking_id = $_GET['booking_id'] ?? null;
        if (!$booking_id) {
            header("Location: ?act=hdv_tour_schedule");
            exit;
        }

        $hdvModel = new HdvModel();

        // 2. Lấy thông tin HDV từ tài khoản
        $account_id = $_SESSION['user_id'];
        $hdvProfile = $hdvModel->getHdvInfoByAccountId($account_id);

        if (!$hdvProfile) {
            echo "Không tìm thấy thông tin HDV.";
            exit;
        }

        // 3. Lấy ID HDV
        $hdv_id = $hdvProfile['id'];

        // 4. Lấy thông tin booking
        $bookingDetail = $hdvModel->getBookingDetail($booking_id, $hdv_id);
        if (!$bookingDetail) {
            header("Location: ?act=hdv_tour_schedule");
            exit;
        }

        // 5. Lấy danh sách khách hàng
        $customers = $hdvModel->getBookingCustomers($booking_id, $hdv_id);

        // 6. Gửi sang view
        require_file_view("HDV/hdv_schedule_detail", compact("bookingDetail", "customers", "hdvProfile"));
    }

    public function confirmBookingAction()
    {
        // 1. Kiểm tra quyền truy cập
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            header("Location: ?act=login");
            exit;
        }

        $booking_id = $_POST['booking_id'] ?? null;
        if (!$booking_id) {
            header("Location: ?act=hdv_tour_schedule");
            exit;
        }

        $hdvModel = new HdvModel();

        // 2. Lấy thông tin HDV từ tài khoản
        $account_id = $_SESSION['user_id'];
        $hdvProfile = $hdvModel->getHdvInfoByAccountId($account_id);

        if (!$hdvProfile) {
            echo "Không tìm thấy thông tin HDV.";
            exit;
        }

        // 3. Lấy ID HDV
        $hdv_id = $hdvProfile['id'];

        // 4. Xác nhận nhận tour
        $action = $_POST['action'] ?? null;
        if ($action === 'confirm') {
            $result = $hdvModel->confirmBooking($booking_id, $hdv_id);
            if ($result) {
                header("Location: ?act=hdv_schedule_detail&booking_id=$booking_id&msg=confirmed");
            } else {
                header("Location: ?act=hdv_schedule_detail&booking_id=$booking_id&msg=error");
            }
        } elseif ($action === 'reject') {
            $result = $hdvModel->rejectBooking($booking_id, $hdv_id);
            if ($result) {
                header("Location: ?act=hdv_tour_schedule&msg=rejected");
            } else {
                header("Location: ?act=hdv_schedule_detail&booking_id=$booking_id&msg=error");
            }
        }
        exit;
    }

    public function updateCustomerAttendance()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập.']);
            exit;
        }

        $account_id = $_SESSION['user_id'];
        $hdvModel = new HdvModel();
        $hdvProfile = $hdvModel->getHdvInfoByAccountId($account_id);

        if (!$hdvProfile) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin HDV.']);
            exit;
        }

        $rawPayload = $_POST['data'] ?? null;

        if ($rawPayload !== null) {
            $items = json_decode($rawPayload, true);
            if (!is_array($items) || empty($items)) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
                exit;
            }

            $updated = 0;
            foreach ($items as $item) {
                $customerId = isset($item['id']) ? (int)$item['id'] : 0;
                $status = $item['status'] ?? '';
                if ($customerId <= 0 || !in_array($status, ['present', 'absent'], true)) {
                    continue;
                }
                if ($hdvModel->setCustomerAttendance($customerId, (int)$hdvProfile['id'], $status === 'present' ? 1 : 0)) {
                    $updated++;
                }
            }

            echo json_encode([
                'success' => true,
                'updated' => $updated
            ]);
            exit;
        }

        // Fallback: single update
        $customerId = isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : 0;
        $status = $_POST['status'] ?? '';

        if ($customerId <= 0 || !in_array($status, ['present', 'absent'], true)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
            exit;
        }

        $isPresent = $status === 'present' ? 1 : 0;
        $updated = $hdvModel->setCustomerAttendance($customerId, (int)$hdvProfile['id'], $isPresent);

        if ($updated) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật điểm danh.']);
        }
        exit;
    }



    // // HDV check-in
    // public function hdvCheckinForm() {
    //     if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
    //         header("Location: ?act=login");
    //         exit;
    //     }

    //     $hdv_id = $_SESSION['user_id'];
    //     $tourModel = new TourModel();
    //     $assignedTours = $tourModel->getToursByHdv($hdv_id); // cần tạo hàm này
    //     require_once 'views/Checkin/hdv_checkin_form.php';
    // }

    // public function hdvCheckinSubmit() {
    //     $hdv_id = $_SESSION['user_id'];
    //     $tour_id = $_POST['tour_id'];
    //     $location = $_POST['location'] ?? '';

    //     $success = $this->model->hdvCheckin($hdv_id, $tour_id, $location);
    //     if ($success) {
    //         header("Location: ?act=hdv-checkin&msg=success");
    //     } else {
    //         echo "Điểm danh thất bại!";
    //     }
    // }

   
    // public function customerCheckinForm() {
    //     if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    //         header("Location: ?act=login");
    //         exit;
    //     }

    //     $khach_id = $_SESSION['user_id'];
    //     $tourModel = new TourModel();
    //     $bookedTours = $tourModel->getToursByCustomer($khach_id); // cần tạo hàm này
    //     require_once 'views/Checkin/customer_checkin_form.php';
    // }

    // public function customerCheckinSubmit() {
    //     $khach_id = $_SESSION['user_id'];
    //     $tour_id = $_POST['tour_id'];
    //     $location = $_POST['location'] ?? '';

    //     $success = $this->model->customerCheckin($khach_id, $tour_id, $location);
    //     if ($success) {
    //         header("Location: ?act=customer-checkin&msg=success");
    //     } else {
    //         echo "Điểm danh thất bại!";
    //     }
    // }

    // // Xem danh sách điểm danh theo tour
    // public function checkinList($tour_id) {
    //     $list = $this->model->getCheckinByTour($tour_id);
    //     require_once 'views/Checkin/checkin_list.php';
    // }
}
