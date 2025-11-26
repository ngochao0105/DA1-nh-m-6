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
