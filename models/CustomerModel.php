<?php

class CustomerModel {
    private $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAllCustomers() {
        $sql = "SELECT * FROM khachtour ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerById($id) {
        $sql = "SELECT * FROM khachtour WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Hàm validate ngày tháng
    private function validateDate($date) {
        if (empty($date)) return null;
        
        // Kiểm tra định dạng YYYY-MM-DD
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return null;
        }
        
        // Kiểm tra ngày hợp lệ
        $parts = explode('-', $date);
        if (!checkdate($parts[1], $parts[2], $parts[0])) {
            return null;
        }
        
        return $date;
    }

    public function insertCustomer($id_booking, $customer_name, $phone, $checkin, $special_request) {
        $checkin = $this->validateDate($checkin); // Validate
        
        $sql = "INSERT INTO khachtour (id_booking, customer_name, phone, checkin, special_request) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_booking, $customer_name, $phone, $checkin, $special_request]);
    }

    public function updateCustomer($id, $id_booking, $customer_name, $phone, $checkin, $special_request) {
        $checkin = $this->validateDate($checkin); // Validate
        
        $sql = "UPDATE khachtour SET id_booking = ?, customer_name = ?, phone = ?, checkin = ?, special_request = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_booking, $customer_name, $phone, $checkin, $special_request, $id]);
    }

    public function deleteCustomer($id) {
        $sql = "DELETE FROM khachtour WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>