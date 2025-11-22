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
    // AJAX load HDV theo ngày đi
    // ================================
    public function AjaxGetHdv()
    {
        $ngay = $_GET['ngay_di'] ?? null;

        if (!$ngay) {
            echo "<option value=''>Chưa chọn ngày</option>";
            exit;
        }

        $data = $this->modelBooking->getAvailableHdvByDate($ngay);

        if (empty($data)) {
            echo "<option value=''>Không có HDV rảnh</option>";
            exit;
        }

        foreach ($data as $row) {
            $name = htmlspecialchars($row['full_name']);
            $type = htmlspecialchars($row['guide_type'] ?? '');
            echo "<option value='{$row['id']}'>{$name} ({$type})</option>";
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
        $ngay_di = $_POST['ngay_di'] ?? null;
        $id_hdv  = $_POST['id_hdv'] ?? null;

        $loai_dat = $_POST['loai_dat'] ?? 'ca_nhan';

        // Validate
        $errors = [];
        if (!$id_tour) $errors[] = "Chưa chọn tour";
        if (!$ngay_di) $errors[] = "Chưa chọn ngày đi";
        if (!$id_hdv) $errors[] = "Chưa chọn HDV";
        if (empty($_POST['ten_khach'])) $errors[] = "Chưa có khách";

        if (!empty($errors)) {
            $tours = $this->modelTour->getAllTour();
            $error = implode(", ", $errors);
            require "./views/Admin/QuanlyBooking/AddBooking.php";
            return;
        }

        // 1. Tạo booking
        $id_booking = $this->modelBooking->createBooking($id_tour, $ngay_di, $loai_dat);

        // 2. Lưu khách
        foreach ($_POST['ten_khach'] as $i => $name) {
            $sdt = $_POST['sdt'][$i] ?? '';
            $loai = $_POST['loai_khach'][$i] ?? 'nguoi_lon';
            $req = $_POST['yeu_cau_dac_biet'][$i] ?? '';
            $this->modelBooking->addCustomer($id_booking, $name, $sdt, $loai, $req);
        }

        // 3. Gán HDV
        $this->modelBooking->assignHdvToDate($id_hdv, $id_booking, $ngay_di);

        header("Location: index.php?act=booking-list");
        exit;
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

        $allowed = ['cho_xac_nhan','da_coc','hoan_tat','huy'];

        if (!in_array($status, $allowed)) {
            die("Trạng thái không hợp lệ!");
        }

        $this->modelBooking->updateBookingStatus($id, $status);

        header("Location: index.php?act=booking-list");
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

}
