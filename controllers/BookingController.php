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

    public function BookingList() 
    {
    $bookings = $this->modelBooking->getAllBooking();

        require_once "./views/Admin/QuanlyBooking/BookingList.php";
    }

    public function CreateBooking()
    {
        $tours = $this->modelTour->getAllTour();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Lấy dữ liệu từ form
            $id_tour      = $_POST['id_tour'];
            $name         = $_POST['customer_name'];
            $phone        = $_POST['phone'];
            $email        = $_POST['email'];
            $count        = $_POST['people_count'];
            $date         = $_POST['booking_date'];
            $request      = $_POST['special_request'];


            // Validate
            if ($id_tour === '' || $name === '' || $phone === '' || $date === '') {
                $error = "Vui lòng điền đủ các trường bắt buộc.";
                require_once "./views/Admin/QuanlyBooking/AddBooking.php";
                return;
            }

            // Lấy giá tour
            $tour = $this->modelTour->getTourById($id_tour) 
                    ?: $this->modelTour->getOneTour($id_tour);

            $pricePerPerson = isset($tour['price']) ? floatval($tour['price']) : 0;
            $totalPrice = $pricePerPerson * $count;

            // Ghi database
            $this->modelBooking->createBooking(
                $id_tour,
                $name,
                $phone,
                $email,
                $count,
                $date,
                $request,
                $totalPrice
            );

            header("Location: index.php?act=booking-list");
            exit;
        }

        require_once "./views/Admin/QuanlyBooking/AddBooking.php";
    }
    public function updateStatus()
    {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = $_POST['id'];
        $status = $_POST['status'];

        $allowed = ['pending', 'deposit', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) {
            die("Trạng thái không hợp lệ!");
        }

        $this->modelBooking->updateBookingStatus($id, $status);

        header("Location: index.php?act=booking-list");
        exit;
    }
    }
    public function BookingLogs()
    {
        $id = $_GET['id'];

        $logs = $this->modelBooking->getBookingLogs($id);

        require_once './views/Admin/QuanlyBooking/BookingLogs.php';
    }

}
