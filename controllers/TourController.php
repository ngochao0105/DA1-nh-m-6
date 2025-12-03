<?php

class TourController
{
    public $modelTour;
    public $modelGuide;
    public $modelSchedule;


    public function __construct()
    {
        $this->modelTour = new TourModel();
        $this->modelGuide = new GuideModel();
        $this->modelBooking = new BookingModel();
        $this->modelSchedule = new ScheduleModel();
    }

    public function Home()
    {
        // Auto-close expired schedules (ngày kết thúc < hôm nay)
        $this->modelSchedule->closeExpiredSchedules();

        $totalTour = $this->modelTour->countTours();
        $totalHDV = $this->modelGuide->countGuide();

        $bookingDangChay = $this->modelBooking->countBookingByStatus('dang_dien_ra');
        $revenueDangChay = $this->modelBooking->sumRevenueByStatus('dang_dien_ra');

        // Chờ xác nhận
        $bookingCho = $this->modelBooking->countBookingByStatus('cho_xac_nhan');
        $revenueCho = $this->modelBooking->sumRevenueByStatus('cho_xac_nhan');

        // Hoàn tất
        $bookingHoanTat = $this->modelBooking->countBookingByStatus('hoan_tat');
        $revenueHoanTat = $this->modelBooking->sumRevenueByStatus('hoan_tat');

            
        require_once './views/Admin/trangchu.php';
    }

    public function TourList()
    {
        $filterStatus = $_GET['filter_status'] ?? '';

        if ($filterStatus !== '') {
            $categories = $this->modelTour->getStatus($filterStatus);
        } else {
            $categories = $this->modelTour->getAllTour();
        }

        require_once "./views/Admin/Quanlytour/TourList.php";
    }

    public function CreateTour()
    {
        $categories = $this->modelTour->getCategories();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour_name = trim($_POST['tour_name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $destination = trim($_POST['destination'] ?? '');
            $id_danh_muc = $_POST['id_danh_muc'] ?? '';
            $status = $_POST['status'] ?? '1';

            // Validation
            if (empty($tour_name)) {
                $error = "Vui lòng nhập tên tour";
            } elseif (empty($destination)) {
                $error = "Vui lòng nhập điểm đến";
            } else {
                // All validations passed, try to create tour
                try {
                    $this->modelTour->createTour(
                        $tour_name,
                        $description,
                        $destination,
                        $id_danh_muc ?: null,
                        $status
                    );

                    header("Location: index.php?act=tour-list");
                    exit;
                } catch (Exception $e) {
                    $error = "Lỗi khi thêm tour: " . $e->getMessage();
                }
            }
        }

        require_once "./views/Admin/Quanlytour/createtour.php";
    }

    public function EditTour()
    {
        if (!isset($_GET['id'])) {
            header("Location: index.php?act=tour-list");
            exit;
        }

        $id = $_GET['id'];
        $tour = $this->modelTour->getOneTour($id);
        $categories = $this->modelTour->getCategories();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour_name = trim($_POST['tour_name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $destination = trim($_POST['destination'] ?? '');
            $id_danh_muc = $_POST['id_danh_muc'] ?? '';
            $status = $_POST['status'] ?? '1';

            // Validation
            if (empty($tour_name)) {
                $error = "Vui lòng nhập tên tour";
            } elseif (empty($destination)) {
                $error = "Vui lòng nhập điểm đến";
            } else {
                // All validations passed
                try {
                    $this->modelTour->updateTour(
                        $id,
                        $tour_name,
                        $description,
                        $destination,
                        $id_danh_muc ?: null,
                        $status
                    );

                    header("Location: index.php?act=tour-list");
                    exit;
                } catch (Exception $e) {
                    $error = "Lỗi khi cập nhật tour: " . $e->getMessage();
                }
            }
        }

        require_once "./views/Admin/Quanlytour/edittour.php";
    }

    public function DeleteTour()
    {
        if (!isset($_GET['id'])) {
            header("Location: index.php?act=tour-list");
            exit;
        }

        $id = $_GET['id'];
        $this->modelTour->deleteTour($id);

        header("Location: index.php?act=tour-list");
        exit;
    }

   public function scheduleList()
{
    $tourId = $_GET['id'] ?? null;
    if (!$tourId) die("Thiếu ID tour");

    // Auto-close expired schedules for this tour before listing
    $this->modelSchedule->closeExpiredSchedulesByTour($tourId);

    $tour = $this->modelTour->getTourById($tourId);
    if (!$tour) die("Tour không tồn tại");

    $schedules = $this->modelSchedule->getSchedulesByTour($tourId);

    require_file_view('Admin/Quanlytour/schedule-list', [
        'tour' => $tour,
        'schedules' => $schedules
    ]);
}

   public function scheduleCreate()
{
    $tourId = $_GET['tour_id'] ?? null;
    if (!$tourId) {
        header("Location: index.php?act=tour-list");
        exit;
    }

    $tour = $this->modelTour->getTourById($tourId);
    if (!$tour) {
        header("Location: index.php?act=tour-list");
        exit;
    }

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $start = trim($_POST['start_date'] ?? '');
        $end   = trim($_POST['end_date'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $max   = trim($_POST['max_slots'] ?? '');
        $status = trim($_POST['status'] ?? 'sap_mo');
        
        // Validate status
        $allowedStatuses = ['sap_mo', 'dang_mo', 'da_dong'];
        if (!in_array($status, $allowedStatuses)) {
            $status = 'sap_mo'; // Default value
        }

        // Validation
        if (empty($start)) {
            $error = "Vui lòng chọn ngày bắt đầu";
        } elseif (empty($end)) {
            $error = "Vui lòng chọn ngày kết thúc";
        } elseif (empty($price) || !is_numeric($price) || $price < 0) {
            $error = "Vui lòng nhập giá tour hợp lệ";
        } elseif (empty($max) || !is_numeric($max) || $max < 1) {
            $error = "Vui lòng nhập số slot tối đa hợp lệ";
        } else {
            $start_timestamp = strtotime($start);
            $end_timestamp = strtotime($end);
            
            if ($start_timestamp === false) {
                $error = "Ngày bắt đầu không hợp lệ";
            } elseif ($end_timestamp === false) {
                $error = "Ngày kết thúc không hợp lệ";
            } elseif ($end_timestamp < $start_timestamp) {
                $error = "Ngày kết thúc phải sau ngày bắt đầu";
            } else {
                try {
                    $this->modelSchedule->createSchedule($tourId, $start, $end, $price, $max, $status);
                    header("Location: index.php?act=schedule-list&id=$tourId");
                    exit;
                } catch (Exception $e) {
                    $error = "Lỗi khi tạo lịch trình: " . $e->getMessage();
                }
            }
        }
    }

    require_file_view('Admin/Quanlytour/schedule-create', [
        'tour_id' => $tourId,
        'tour' => $tour,
        'error' => $error
    ]);
}
public function scheduleEdit()
{
    $id = $_GET['id'] ?? null;
    if (!$id) die("Thiếu ID lịch");

    $schedule = $this->modelSchedule->getScheduleById($id);
    if (!$schedule) die("Lịch không tồn tại");

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $start = trim($_POST['start_date'] ?? '');
        $end   = trim($_POST['end_date'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $max   = trim($_POST['max_slots'] ?? '');
        $status = trim($_POST['status'] ?? 'sap_mo');
        
        // Validate status
        $allowedStatuses = ['sap_mo', 'dang_mo', 'da_dong'];
        if (!in_array($status, $allowedStatuses)) {
            $status = 'sap_mo'; // Default value
        }

        // Validation
        if (empty($start)) {
            $error = "Vui lòng chọn ngày bắt đầu";
        } elseif (empty($end)) {
            $error = "Vui lòng chọn ngày kết thúc";
        } elseif (empty($price) || !is_numeric($price) || $price < 0) {
            $error = "Vui lòng nhập giá tour hợp lệ";
        } elseif (empty($max) || !is_numeric($max) || $max < 1) {
            $error = "Vui lòng nhập số slot tối đa hợp lệ";
        } else {
            $start_timestamp = strtotime($start);
            $end_timestamp = strtotime($end);
            
            if ($start_timestamp === false) {
                $error = "Ngày bắt đầu không hợp lệ";
            } elseif ($end_timestamp === false) {
                $error = "Ngày kết thúc không hợp lệ";
            } elseif ($end_timestamp < $start_timestamp) {
                $error = "Ngày kết thúc phải sau ngày bắt đầu";
            } else {
                try {
                    $this->modelSchedule->updateSchedule($id, $start, $end, $price, $max, $status);
                    header("Location: index.php?act=schedule-list&id={$schedule['tour_id']}");
                    exit;
                } catch (Exception $e) {
                    $error = "Lỗi khi cập nhật lịch trình: " . $e->getMessage();
                }
            }
        }
    }

    require_file_view('Admin/Quanlytour/schedule-edit', [
        'schedule' => $schedule,
        'error' => $error
    ]);
}
public function scheduleDelete()
{
    $id = $_GET['id'] ?? null;
    if (!$id) die("Thiếu ID");

    $schedule = $this->modelSchedule->getScheduleById($id);

    $this->modelSchedule->deleteSchedule($id);

    header("Location: index.php?act=schedule-list&id={$schedule['tour_id']}");
    exit;
}

public function tourDetail()
{
    $id = $_GET['id'] ?? null;

    if (!$id) {
        header("Location: index.php?act=tour-list");
        exit;
    }

    $tour = $this->modelTour->getTourById($id);

    if (!$tour) {
        header("Location: index.php?act=tour-list");
        exit;
    }

    require_file_view("Admin/Quanlytour/TourDetail", compact("tour"));
}

}

