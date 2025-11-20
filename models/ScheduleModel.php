<?php

class ScheduleModel
{
    private $conn;
    protected $table = "tour_schedule";

    public function __construct()
    {
        $this->conn = connectDB(); // dùng hàm global của bạn
    }

    public function getSchedulesByTour($tourId)
    {
        $sql = "SELECT * FROM $this->table WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tourId]);
        return $stmt->fetchAll();
    }

    public function getScheduleById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createSchedule($tourId, $start, $end, $price, $max, $status)
    {
        try {
            // Validate and format dates
            $start_formatted = $this->validateAndFormatDate($start);
            $end_formatted = $this->validateAndFormatDate($end);
            
            if (!$start_formatted || !$end_formatted) {
                throw new Exception("Định dạng ngày không hợp lệ");
            }

            // Validate and sanitize status
            $status = $this->validateStatus($status);

            // Validate price
            $price = floatval($price);
            if ($price < 0) {
                throw new Exception("Giá tour phải là số dương");
            }

            $sql = "INSERT INTO $this->table (tour_id, start_date, end_date, price, max_slots, status) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$tourId, $start_formatted, $end_formatted, $price, $max, $status]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Lỗi database: " . $e->getMessage());
        }
    }

    public function updateSchedule($id, $start, $end, $price, $max, $status)
    {
        try {
            // Validate and format dates
            $start_formatted = $this->validateAndFormatDate($start);
            $end_formatted = $this->validateAndFormatDate($end);
            
            if (!$start_formatted || !$end_formatted) {
                throw new Exception("Định dạng ngày không hợp lệ");
            }

            // Validate and sanitize status
            $status = $this->validateStatus($status);

            // Validate price
            $price = floatval($price);
            if ($price < 0) {
                throw new Exception("Giá tour phải là số dương");
            }

            $sql = "UPDATE $this->table 
                    SET start_date=?, end_date=?, price=?, max_slots=?, status=?, updated_at=NOW()
                    WHERE id=?";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$start_formatted, $end_formatted, $price, $max, $status, $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Lỗi database: " . $e->getMessage());
        }
    }

    /**
     * Validate and sanitize status value
     * Only allows: sap_mo, dang_mo, da_dong
     */
    private function validateStatus($status)
    {
        $allowedStatuses = ['sap_mo', 'dang_mo', 'da_dong'];
        
        // Clean and normalize the status value
        if (is_null($status) || $status === '') {
            return 'sap_mo';
        }
        
        $status = trim(strtolower($status));
        
        // Remove any whitespace or special characters
        $status = preg_replace('/[^a-z_]/', '', $status);
        
        if (!in_array($status, $allowedStatuses)) {
            // Default to 'sap_mo' if invalid
            return 'sap_mo';
        }
        
        return $status;
    }

    private function validateAndFormatDate($date) {
        if (empty($date)) {
            return false;
        }
        
        // Check if date is in valid format (YYYY-MM-DD)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }
        
        // Parse date
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return false;
        }
        
        // Check year range (2000-2100)
        $year = date('Y', $timestamp);
        if ($year < 2000 || $year > 2100) {
            return false;
        }
        
        // Return formatted date
        return date('Y-m-d', $timestamp);
    }

    public function deleteSchedule($id)
    {
        try {
            $sql = "DELETE FROM $this->table WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Lỗi khi xóa lịch trình: " . $e->getMessage());
        }
    }

    /**
     * Update booked slots when a booking is created or deleted
     */
    public function updateBookedSlots($scheduleId, $increment = true)
    {
        try {
            $operator = $increment ? '+' : '-';
            $sql = "UPDATE $this->table 
                    SET booked_slots = booked_slots $operator 1,
                        updated_at = NOW()
                    WHERE id = ? AND booked_slots >= 0";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$scheduleId]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Lỗi khi cập nhật số slot đã đặt: " . $e->getMessage());
        }
    }

    /**
     * Get available slots (max_slots - booked_slots)
     */
    public function getAvailableSlots($scheduleId)
    {
        try {
            $sql = "SELECT (max_slots - booked_slots) as available_slots 
                    FROM $this->table 
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$scheduleId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['available_slots'] : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Check if schedule has available slots
     */
    public function hasAvailableSlots($scheduleId, $requiredSlots = 1)
    {
        $available = $this->getAvailableSlots($scheduleId);
        return $available >= $requiredSlots;
    }
}
