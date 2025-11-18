<?php

class CustomerController {
    private $model;
    private $bookingModel;

    public function __construct() {
        $this->model = new CustomerModel();
        $this->bookingModel = new BookingModel();
    }

    public function list() {
        $customers = $this->model->getAllCustomers();
        require_once './views/Quanlykh/customerList.php';
    }

    public function add() {
        $bookings = $this->bookingModel->getAllBooking(); // Lấy dữ liệu booking

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_booking = intval($_POST['id_booking'] ?? 0);
            $customer_name = trim($_POST['customer_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $checkin = trim($_POST['checkin'] ?? '');
            $special_request = trim($_POST['special_request'] ?? '');

            if ($customer_name !== '' && $id_booking > 0) {
                $this->model->insertCustomer($id_booking, $customer_name, $phone, $checkin, $special_request);
                header("Location: ?act=customer-list");
                exit();
            }
            $error = "Tên khách hàng và Booking là bắt buộc.";
        }
        require_once './views/Quanlykh/AddCustomer.php';
    }

    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) { 
            header("Location: ?act=customer-list"); 
            exit(); 
        }
        
        $customer = $this->model->getCustomerById($id);
        $bookings = $this->bookingModel->getAllBooking(); // Lấy dữ liệu booking

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_booking = intval($_POST['id_booking'] ?? 0);
            $customer_name = trim($_POST['customer_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $checkin = trim($_POST['checkin'] ?? '');
            $special_request = trim($_POST['special_request'] ?? '');

            if ($customer_name !== '' && $id_booking > 0) {
                $this->model->updateCustomer($id, $id_booking, $customer_name, $phone, $checkin, $special_request);
                header("Location: ?act=customer-list");
                exit();
            }
            $error = "Tên khách hàng và Booking là bắt buộc.";
        }
        require_once './views/Quanlykh/EditCustomer.php';
    }

    public function delete() {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->model->deleteCustomer($id);
        }
        header("Location: ?act=customer-list");
        exit();
    }
}