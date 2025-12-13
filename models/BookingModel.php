<?php
class BookingModel 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // ================================
    // Lấy danh sách booking
    // Có thể lọc theo nhóm trạng thái:
    //  - all:      tất cả
    //  - dang_dien_ra:  booking đang diễn ra
    //  - sap_dien_ra:   booking sắp diễn ra (chờ/đã xác nhận)
    //  - da_ket_thuc:   booking đã kết thúc (hoàn tất/đã hủy)
    // ================================
    public function getAllBooking($timeStatus = 'all')
    {
        $timeStatus = $timeStatus ?: 'all';

        // Kiểm tra xem cột trang_thai_thanh_toan có tồn tại không
        try {
            $checkCols2 = $this->conn->query("SHOW COLUMNS FROM booking LIKE 'trang_thai_thanh_toan'")->fetch();
            $hasPaymentStatus = !empty($checkCols2);
        } catch (Exception $e) {
            $hasPaymentStatus = false;
        }

        $sql = "SELECT 
                    b.*, 
                    t.tour_name,
                    (SELECT k.ten_khach FROM khachtour k WHERE k.id_booking = b.id ORDER BY k.id LIMIT 1) AS customer_name,
                    (SELECT COUNT(*) FROM khachtour k WHERE k.id_booking = b.id) AS so_khach,
                    (SELECT ts.price FROM tour_schedule ts WHERE ts.tour_id = b.id_tour AND ts.start_date = b.ngay_di LIMIT 1) AS price_per_person,
                    (SELECT n.full_name FROM phan_cong_hdv p 
                     JOIN nhansu n ON p.id_hdv = n.id 
                     WHERE p.id_booking = b.id LIMIT 1) AS hdv_name,
                    b.ngay_tao AS created_at";
        
        if ($hasPaymentStatus) {
            $sql .= ", COALESCE(b.trang_thai_thanh_toan, 'chua_thanh_toan') AS trang_thai_thanh_toan";
        } else {
            $sql .= ", 'chua_thanh_toan' AS trang_thai_thanh_toan";
        }
        
        $sql .= " FROM booking b
                LEFT JOIN tour t ON b.id_tour = t.id";

        // Lọc theo nhóm trạng thái booking
        $params = [];
        if ($timeStatus === 'dang_dien_ra') {
            $sql .= " WHERE b.trang_thai = 'dang_dien_ra'";
        } elseif ($timeStatus === 'sap_dien_ra') {
            $sql .= " WHERE b.trang_thai IN ('cho_xac_nhan','da_xac_nhan')";
        } elseif ($timeStatus === 'da_ket_thuc') {
            $sql .= " WHERE b.trang_thai IN ('hoan_tat','da_huy')";
        }

        $sql .= " ORDER BY b.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        
        // Tính tổng giá cho mỗi booking
        foreach ($results as &$row) {
            $pricePerPerson = floatval($row['price_per_person'] ?? 0);
            $soKhach = intval($row['so_khach'] ?? 0);
            $row['tong_gia'] = $pricePerPerson * $soKhach;
        }
        
        return $results;
    }

    // ================================
    // Tìm kiếm booking
    // ================================
    public function searchBooking($keyword, $timeStatus = 'all')
    {
        $timeStatus = $timeStatus ?: 'all';

        // Kiểm tra xem cột trang_thai_thanh_toan có tồn tại không
        try {
            $checkCols2 = $this->conn->query("SHOW COLUMNS FROM booking LIKE 'trang_thai_thanh_toan'")->fetch();
            $hasPaymentStatus = !empty($checkCols2);
        } catch (Exception $e) {
            $hasPaymentStatus = false;
        }

        $sql = "SELECT 
                    b.*, 
                    t.tour_name,
                    (SELECT k.ten_khach FROM khachtour k WHERE k.id_booking = b.id ORDER BY k.id LIMIT 1) AS customer_name,
                    (SELECT COUNT(*) FROM khachtour k WHERE k.id_booking = b.id) AS so_khach,
                    (SELECT ts.price FROM tour_schedule ts WHERE ts.tour_id = b.id_tour AND ts.start_date = b.ngay_di LIMIT 1) AS price_per_person,
                    (SELECT n.full_name FROM phan_cong_hdv p 
                     JOIN nhansu n ON p.id_hdv = n.id 
                     WHERE p.id_booking = b.id LIMIT 1) AS hdv_name,
                    b.ngay_tao AS created_at";
        
        if ($hasPaymentStatus) {
            $sql .= ", COALESCE(b.trang_thai_thanh_toan, 'chua_thanh_toan') AS trang_thai_thanh_toan";
        } else {
            $sql .= ", 'chua_thanh_toan' AS trang_thai_thanh_toan";
        }
        
        $sql .= " FROM booking b
                LEFT JOIN tour t ON b.id_tour = t.id";

        // Điều kiện tìm kiếm
        $conditions = [];
        $params = [];
        
        if (!empty($keyword)) {
            $conditions[] = "(t.tour_name LIKE :keyword OR 
                            (SELECT k.ten_khach FROM khachtour k WHERE k.id_booking = b.id ORDER BY k.id LIMIT 1) LIKE :keyword OR
                            b.id LIKE :keyword_id)";
            $params[':keyword'] = '%' . $keyword . '%';
            $params[':keyword_id'] = '%' . $keyword . '%';
        }

        // Lọc theo nhóm trạng thái booking
        if ($timeStatus === 'dang_dien_ra') {
            $conditions[] = "b.trang_thai = 'dang_dien_ra'";
        } elseif ($timeStatus === 'sap_dien_ra') {
            $conditions[] = "b.trang_thai IN ('cho_xac_nhan','da_xac_nhan')";
        } elseif ($timeStatus === 'da_ket_thuc') {
            $conditions[] = "b.trang_thai IN ('hoan_tat','da_huy')";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY b.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        
        // Tính tổng giá cho mỗi booking
        foreach ($results as &$row) {
            $pricePerPerson = floatval($row['price_per_person'] ?? 0);
            $soKhach = intval($row['so_khach'] ?? 0);
            $row['tong_gia'] = $pricePerPerson * $soKhach;
        }
        
        return $results;
    }

    // ================================
    // Lấy HDV rảnh theo ngày
    // ================================
    public function getAvailableHdvByDate($ngayDi)
    {
        $sql = "SELECT n.*
                FROM nhansu n
                WHERE n.id NOT IN (
                    SELECT id_hdv FROM phan_cong_hdv 
                    WHERE ngay_di = :ngay
                )";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['ngay' => $ngayDi]);
        return $stmt->fetchAll();
    }

    // ================================
    // Tạo booking
    // ================================
    public function createBooking($idTour, $ngayDi, $loaiDat, $scheduleId = null)
    {
        // Đảm bảo cột trang_thai đủ dài
        $this->ensureTrangThaiColumnSize();
        
        // Đảm bảo cột schedule_id tồn tại
        $this->ensureScheduleIdColumn();

        $sql = "INSERT INTO booking (id_tour, ngay_di, loai_dat, trang_thai";
        if ($scheduleId) {
            $sql .= ", schedule_id";
        }
        $sql .= ") VALUES (:t, :ngay, :l, 'cho_xac_nhan'";
        if ($scheduleId) {
            $sql .= ", :schedule_id";
        }
        $sql .= ")";

        $params = [
            't'    => $idTour,
            'ngay' => $ngayDi,
            'l'    => $loaiDat
        ];
        
        if ($scheduleId) {
            $params['schedule_id'] = $scheduleId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $this->conn->lastInsertId();
    }
    
    // ================================
    // Đảm bảo cột schedule_id tồn tại
    // ================================
    private function ensureScheduleIdColumn()
    {
        try {
            // Kiểm tra xem cột schedule_id có tồn tại không
            $checkCol = $this->conn->query("SHOW COLUMNS FROM booking LIKE 'schedule_id'")->fetch();
            if (empty($checkCol)) {
                // Tạo cột schedule_id nếu chưa có
                $this->conn->exec("ALTER TABLE booking ADD COLUMN schedule_id INT(11) NULL DEFAULT NULL AFTER id_tour");
                // Thêm foreign key nếu cần (tùy chọn)
                // $this->conn->exec("ALTER TABLE booking ADD CONSTRAINT fk_booking_schedule FOREIGN KEY (schedule_id) REFERENCES tour_schedule(id) ON DELETE SET NULL");
            }
        } catch (PDOException $e) {
            // Nếu lỗi, có thể cột đã tồn tại hoặc có vấn đề khác, bỏ qua
        }
    }

    // ================================
    // Đảm bảo cột trang_thai đủ dài
    // ================================
    private function ensureTrangThaiColumnSize()
    {
        // Đơn giản hóa: luôn thử mở rộng cột lên VARCHAR(50)
        // MySQL sẽ bỏ qua nếu cột đã đủ dài hoặc không tồn tại
        try {
            // Thử sửa cột (nếu tồn tại)
            $this->conn->exec("ALTER TABLE booking MODIFY COLUMN trang_thai VARCHAR(50) DEFAULT 'cho_xac_nhan'");
        } catch (PDOException $e1) {
            // Nếu lỗi (có thể cột không tồn tại), thử tạo mới
            try {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN trang_thai VARCHAR(50) DEFAULT 'cho_xac_nhan'");
            } catch (PDOException $e2) {
                // Nếu vẫn lỗi, có thể cột đã tồn tại và đủ dài, hoặc có vấn đề khác
                // Bỏ qua và tiếp tục
            }
        }
    }

    // ================================
    // Thêm khách
    // ================================
    public function addCustomer($idBooking, $name, $phone, $type, $request)
    {
        $sql = "INSERT INTO khachtour 
                (id_booking, ten_khach, sdt, loai_khach, yeu_cau_dac_biet)
                VALUES (:b, :name, :phone, :type, :req)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'b'    => $idBooking,
            'name' => $name,
            'phone'=> $phone,
            'type' => $type,
            'req'  => $request
        ]);
    }

    // ================================
    // Xóa khách hàng khỏi booking
    // ================================
    public function deleteCustomerFromBooking($customerId, $bookingId)
    {
        try {
            // Kiểm tra xem khách hàng có thuộc booking này không
            $sqlCheck = "SELECT id FROM khachtour WHERE id = :customer_id AND id_booking = :booking_id";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->execute([
                'customer_id' => $customerId,
                'booking_id' => $bookingId
            ]);
            
            if (!$stmtCheck->fetch()) {
                return false; // Khách hàng không thuộc booking này
            }
            
            // Xóa khách hàng
            $sql = "DELETE FROM khachtour WHERE id = :customer_id AND id_booking = :booking_id";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                'customer_id' => $customerId,
                'booking_id' => $bookingId
            ]);
            
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================================
    // Kiểm tra HDV có rảnh trong ngày không
    // ================================
    public function isHdvAvailable($idHdv, $ngayDi)
    {
        $sql = "SELECT COUNT(*) FROM phan_cong_hdv 
                WHERE id_hdv = :hdv AND ngay_di = :ngay";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'hdv' => $idHdv,
            'ngay' => $ngayDi
        ]);

        $count = $stmt->fetchColumn();
        return $count == 0; // Trả về true nếu rảnh (count = 0)
    }

    // ================================
    // Lấy thông tin HDV đã bận (nếu có)
    // ================================
    public function getHdvConflictInfo($idHdv, $ngayDi)
    {
        $sql = "SELECT p.*, b.id as booking_id, t.tour_name
                FROM phan_cong_hdv p
                JOIN booking b ON p.id_booking = b.id
                LEFT JOIN tour t ON b.id_tour = t.id
                WHERE p.id_hdv = :hdv AND p.ngay_di = :ngay
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'hdv' => $idHdv,
            'ngay' => $ngayDi
        ]);

        return $stmt->fetch();
    }

    // ================================
    // Gán HDV vào ngày đi
    // ================================
    public function assignHdvToDate($idHdv, $idBooking, $ngayDi)
    {
        $sql = "INSERT INTO phan_cong_hdv (id_hdv, id_booking, ngay_di)
                VALUES (:hdv, :booking, :ngay)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'hdv'     => $idHdv,
            'booking' => $idBooking,
            'ngay'    => $ngayDi
        ]);
    }

    // ================================
    // Update trạng thái booking + log
    // ================================
    public function updateBookingStatus($id, $new_status)
    {
        // Đảm bảo cột trang_thai đủ dài
        $this->ensureTrangThaiColumnSize();

        // Lấy trạng thái cũ
        $sqlOld = "SELECT trang_thai FROM booking WHERE id = :id";
        $stmtOld = $this->conn->prepare($sqlOld);
        $stmtOld->execute(['id' => $id]);
        $old_status = $stmtOld->fetchColumn();

        // Cập nhật booking - thử với error handling
        $sql = "UPDATE booking 
                SET trang_thai = :st, ngay_cap_nhat = NOW()
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        try {
            $stmt->execute([
                'st' => $new_status,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            // Nếu lỗi do cột quá ngắn, thử sửa lại và update lại
            if (strpos($e->getMessage(), 'truncated') !== false || strpos($e->getMessage(), 'trang_thai') !== false) {
                // Force sửa cột
                try {
                    $this->conn->exec("ALTER TABLE booking MODIFY trang_thai VARCHAR(50)");
                } catch (PDOException $e2) {
                    // Bỏ qua nếu không sửa được
                }
                // Thử lại
                $stmt->execute([
                    'st' => $new_status,
                    'id' => $id
                ]);
            } else {
                // Nếu lỗi khác, throw lại
                throw $e;
            }
        }

        // Nếu thay đổi trạng thái -> lưu log
        if ($old_status !== $new_status) {
            if (session_status() === PHP_SESSION_NONE) {
                @session_start();
            }

            $changedBy = $_SESSION['user']['username'] ?? 
                         $_SESSION['username'] ?? 
                         "admin";

            $sqlLog = "INSERT INTO booking_logs 
                       (booking_id, old_status, new_status, changed_by, changed_at)
                       VALUES (:id, :old, :new, :by, NOW())";

            $stmtLog = $this->conn->prepare($sqlLog);
            $stmtLog->execute([
                'id'  => $id,
                'old' => $old_status,
                'new' => $new_status,
                'by'  => $changedBy
            ]);
        }
    }

    // ================================
    // Lấy lịch sử thay đổi
    // ================================
    public function getBookingLogs($id)
    {
        $sql = "SELECT booking_id, old_status, new_status, changed_by,
                DATE_FORMAT(changed_at, '%d/%m/%Y %H:%i:%s') AS changed_at
                FROM booking_logs
                WHERE booking_id = :id
                ORDER BY changed_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }
    public function findBookingById($id)
    {
        // Kiểm tra xem cột trang_thai_thanh_toan có tồn tại không
        try {
            $checkCol = $this->conn->query("SHOW COLUMNS FROM booking LIKE 'trang_thai_thanh_toan'")->fetch();
            $hasPaymentStatus = !empty($checkCol);
        } catch (Exception $e) {
            $hasPaymentStatus = false;
        }

        $sql = "SELECT b.*, t.tour_name";
        
        if ($hasPaymentStatus) {
            $sql .= ", COALESCE(b.trang_thai_thanh_toan, 'chua_thanh_toan') AS trang_thai_thanh_toan";
        } else {
            $sql .= ", 'chua_thanh_toan' AS trang_thai_thanh_toan";
        }
        
        $sql .= " FROM booking b
                JOIN tour t ON b.id_tour = t.id
                WHERE b.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function getCustomersByBooking($idBooking)
    {
        // Trả về danh sách khách (khachtour) cho booking
        $sql = "SELECT * FROM khachtour WHERE id_booking = :id ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $idBooking]);
        return $stmt->fetchAll();
    }

    public function getHdvByBooking($idBooking)
    {
        // Trả về HDV được phân công cho booking (nếu có)
        $sql = "SELECT n.* 
                FROM phan_cong_hdv p
                JOIN nhansu n ON p.id_hdv = n.id
                WHERE p.id_booking = :id
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $idBooking]);
        return $stmt->fetch();
    }

    // ================================
    // Lấy thông tin schedule từ booking
    // ================================
    public function getScheduleByBooking($idBooking)
    {
        // Lấy booking để có schedule_id
        $booking = $this->findBookingById($idBooking);
        if (!$booking) {
            return null;
        }

        // Ưu tiên lấy schedule từ schedule_id (liên kết trực tiếp)
        if (!empty($booking['schedule_id'])) {
            $sql = "SELECT * FROM tour_schedule WHERE id = :schedule_id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['schedule_id' => $booking['schedule_id']]);
            $schedule = $stmt->fetch();
            if ($schedule) {
                return $schedule;
            }
        }

        // Fallback: Tìm schedule dựa vào tour_id và start_date = ngay_di (cho các booking cũ)
        if (isset($booking['id_tour']) && isset($booking['ngay_di'])) {
            $sql = "SELECT * FROM tour_schedule 
                    WHERE tour_id = :tour_id AND start_date = :ngay_di
                    LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'tour_id' => $booking['id_tour'],
                'ngay_di' => $booking['ngay_di']
            ]);
            return $stmt->fetch();
        }

        return null;
    }

    // ================================
    // Xóa booking
    // ================================
    public function deleteBooking($id)
    {
        try {
            $this->conn->beginTransaction();
            
            // Xóa khách tour
            $sql1 = "DELETE FROM khachtour WHERE id_booking = :id";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute(['id' => $id]);
            
            // Xóa phân công HDV
            $sql2 = "DELETE FROM phan_cong_hdv WHERE id_booking = :id";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute(['id' => $id]);
            
            // Xóa logs
            $sql3 = "DELETE FROM booking_logs WHERE booking_id = :id";
            $stmt3 = $this->conn->prepare($sql3);
            $stmt3->execute(['id' => $id]);
            
            // Xóa booking
            $sql4 = "DELETE FROM booking WHERE id = :id";
            $stmt4 = $this->conn->prepare($sql4);
            $stmt4->execute(['id' => $id]);
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // ================================
    // Cập nhật trạng thái thanh toán
    // ================================
    public function updatePaymentStatus($id, $payment_status)
    {
        // Kiểm tra xem cột có tồn tại không
        try {
            $checkCol = $this->conn->query("SHOW COLUMNS FROM booking LIKE 'trang_thai_thanh_toan'")->fetch();
            if (empty($checkCol)) {
                // Tạo cột nếu chưa có
                $this->conn->exec("ALTER TABLE booking ADD COLUMN trang_thai_thanh_toan VARCHAR(50) DEFAULT 'chua_thanh_toan'");
            }
        } catch (Exception $e) {
            // Nếu không thể tạo cột, bỏ qua
        }

        $sql = "UPDATE booking 
                SET trang_thai_thanh_toan = :status
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'status' => $payment_status,
            'id' => $id
        ]);
    }
    public function countBookingByStatus($status) {
        $sql = "SELECT COUNT(*) AS total FROM booking WHERE trang_thai = :status";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':status' => $status]);
        return $stmt->fetch()['total'] ?? 0;
    }

    public function sumRevenueByStatus($status) {
        $sql = "SELECT SUM(tong_tien) AS revenue FROM booking WHERE trang_thai = :status";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':status' => $status]);
        return $stmt->fetch()['revenue'] ?? 0;
    }

    // ================================
    // Cập nhật schedule_id cho các booking cũ (migration)
    // ================================
    public function updateOldBookingsScheduleId()
    {
        try {
            // Đảm bảo cột schedule_id tồn tại
            $this->ensureScheduleIdColumn();
            
            // Cập nhật schedule_id cho các booking chưa có schedule_id
            // Tìm schedule dựa vào tour_id và start_date = ngay_di
            $sql = "UPDATE booking b
                    INNER JOIN tour_schedule ts ON ts.tour_id = b.id_tour AND ts.start_date = b.ngay_di
                    SET b.schedule_id = ts.id
                    WHERE b.schedule_id IS NULL OR b.schedule_id = 0";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            // Nếu lỗi, có thể do cột chưa tồn tại hoặc có vấn đề khác
            return 0;
        }
    }
    
    // ================================
    // Cập nhật schedule_id cho một booking cụ thể
    // ================================
    public function updateBookingScheduleId($bookingId)
    {
        try {
            // Đảm bảo cột schedule_id tồn tại
            $this->ensureScheduleIdColumn();
            
            // Lấy booking
            $booking = $this->findBookingById($bookingId);
            if (!$booking) {
                return false;
            }
            
            // Nếu đã có schedule_id, không cần cập nhật
            if (!empty($booking['schedule_id'])) {
                return true;
            }
            
            // Tìm schedule dựa vào tour_id và start_date = ngay_di
            $sql = "UPDATE booking b
                    INNER JOIN tour_schedule ts ON ts.tour_id = b.id_tour AND ts.start_date = b.ngay_di
                    SET b.schedule_id = ts.id
                    WHERE b.id = :booking_id AND (b.schedule_id IS NULL OR b.schedule_id = 0)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['booking_id' => $bookingId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================================
    // Lấy danh sách khách hàng đã có trong hệ thống (unique by name+phone)
    // ================================
    public function getAllUniqueCustomers()
    {
        $sql = "SELECT DISTINCT 
                    ten_khach as name,
                    sdt as phone,
                    loai_khach as type
                FROM khachtour 
                WHERE ten_khach IS NOT NULL AND ten_khach != ''
                ORDER BY ten_khach ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ================================
    // Kiểm tra khách hàng có trùng lịch không (overlapping dates)
    // ================================
    public function checkCustomerScheduleConflict($customerName, $customerPhone, $newStartDate, $newEndDate, $excludeBookingId = null)
    {
        // Tìm tất cả booking của khách hàng này (theo tên hoặc số điện thoại)
        // Khách hàng được xác định bằng tên, hoặc nếu có số điện thoại thì cũng kiểm tra theo số điện thoại
        $sql = "SELECT DISTINCT b.id as booking_id, b.ngay_di, ts.start_date, ts.end_date, t.tour_name
                FROM khachtour k
                JOIN booking b ON k.id_booking = b.id
                LEFT JOIN tour_schedule ts ON b.schedule_id = ts.id
                LEFT JOIN tour t ON b.id_tour = t.id
                WHERE k.ten_khach = :name";
        
        $params = ['name' => $customerName];
        
        // Nếu có số điện thoại, cũng kiểm tra các booking có cùng số điện thoại (ngay cả khi tên khác)
        if (!empty($customerPhone)) {
            $sql = "SELECT DISTINCT b.id as booking_id, b.ngay_di, ts.start_date, ts.end_date, t.tour_name
                    FROM khachtour k
                    JOIN booking b ON k.id_booking = b.id
                    LEFT JOIN tour_schedule ts ON b.schedule_id = ts.id
                    LEFT JOIN tour t ON b.id_tour = t.id
                    WHERE (k.ten_khach = :name OR k.sdt = :phone)";
            $params['phone'] = $customerPhone;
        }
        
        // Loại trừ booking hiện tại nếu đang cập nhật
        if ($excludeBookingId) {
            $sql .= " AND b.id != :exclude_id";
            $params['exclude_id'] = $excludeBookingId;
        }
        
        // Chỉ lấy các booking chưa hủy
        $sql .= " AND b.trang_thai != 'da_huy'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $existingBookings = $stmt->fetchAll();
        
        if (empty($existingBookings)) {
            return null; // Không có booking nào, không trùng
        }
        
        // Kiểm tra trùng lịch
        foreach ($existingBookings as $booking) {
            $existingStart = null;
            $existingEnd = null;
            
            // Ưu tiên lấy từ schedule
            if (!empty($booking['start_date']) && !empty($booking['end_date'])) {
                $existingStart = $booking['start_date'];
                $existingEnd = $booking['end_date'];
            } elseif (!empty($booking['ngay_di'])) {
                // Fallback: nếu không có schedule, dùng ngay_di làm start_date
                // Giả sử tour kéo dài 1 ngày nếu không có end_date
                $existingStart = $booking['ngay_di'];
                $existingEnd = $booking['ngay_di'];
            }
            
            if ($existingStart && $existingEnd && $newStartDate && $newEndDate) {
                // Kiểm tra overlap: (start1 <= end2) AND (end1 >= start2)
                if ($existingStart <= $newEndDate && $existingEnd >= $newStartDate) {
                    return [
                        'conflict' => true,
                        'booking_id' => $booking['booking_id'],
                        'tour_name' => $booking['tour_name'] ?? 'N/A',
                        'existing_start' => $existingStart,
                        'existing_end' => $existingEnd,
                        'new_start' => $newStartDate,
                        'new_end' => $newEndDate
                    ];
                }
            }
        }
        
        return null; // Không trùng
    }

    // ================================
    // Báo cáo doanh thu theo tháng
    // ================================
    public function getRevenueByMonth($year = null)
    {
        if (!$year) {
            $year = date('Y'); // Năm hiện tại mặc định
        }

        $sql = "
            SELECT 
                MONTH(ngay_di) AS thang,    
                YEAR(ngay_di) AS nam,
                COUNT(DISTINCT id) AS so_booking,
                COUNT(DISTINCT (SELECT id FROM khachtour k WHERE k.id_booking = b.id)) AS so_khach,
                COALESCE(SUM(tong_tien), 0) AS doanh_thu,
                COALESCE(AVG(tong_tien), 0) AS doanh_thu_trung_binh
            FROM booking b
            WHERE YEAR(ngay_di) = :year
                AND b.trang_thai NOT IN ('da_huy')
            GROUP BY MONTH(ngay_di), YEAR(ngay_di)
            ORDER BY MONTH(ngay_di) ASC
        ";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['year' => $year]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // ================================
    // Lấy doanh thu chi tiết theo tháng (danh sách booking)
    // ================================
    public function getRevenueDetailByMonth($year, $month)
    {
        $sql = "
            SELECT 
                b.id,
                b.id_tour,
                t.tour_name,
                b.ngay_di,
                b.trang_thai,
                b.trang_thai_thanh_toan,
                COUNT(k.id) AS so_khach,
                COALESCE(b.tong_tien, 0) AS tong_tien
            FROM booking b
            LEFT JOIN tour t ON b.id_tour = t.id
            LEFT JOIN khachtour k ON b.id = k.id_booking
            WHERE YEAR(b.ngay_di) = :year
                AND MONTH(b.ngay_di) = :month
                AND b.trang_thai NOT IN ('da_huy')
            GROUP BY b.id
            ORDER BY b.ngay_di DESC
        ";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['year' => $year, 'month' => $month]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // ================================
    // Lấy tổng doanh thu toàn năm
    // ================================
    public function getTotalRevenueByYear($year)
    {
        $sql = "
            SELECT COALESCE(SUM(tong_tien), 0) AS tong_doanh_thu
            FROM booking b
            WHERE YEAR(b.ngay_di) = :year
                AND b.trang_thai NOT IN ('da_huy')
        ";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['year' => $year]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['tong_doanh_thu'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
    
    // ================================
    // Cập nhật tong_tien cho tất cả booking (migration)
    // ================================
    public function updateAllBookingTotals()
    {
        try {
            // Bước 1: Đảm bảo schedule_id được gán
            $sql1 = "UPDATE booking b
                     INNER JOIN tour_schedule ts 
                        ON ts.tour_id = b.id_tour AND ts.start_date = b.ngay_di
                     SET b.schedule_id = ts.id
                     WHERE b.schedule_id IS NULL OR b.schedule_id = 0";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute();
            $updated1 = $stmt1->rowCount();

            // Bước 2: Cập nhật tong_tien = price_per_person * so_khach
            $sql2 = "UPDATE booking b
                     INNER JOIN tour_schedule ts ON b.schedule_id = ts.id
                     SET b.tong_tien = ts.price * (
                         SELECT COUNT(*) FROM khachtour k WHERE k.id_booking = b.id
                     )
                     WHERE b.schedule_id IS NOT NULL
                       AND b.trang_thai != 'da_huy'";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute();
            $updated2 = $stmt2->rowCount();

            return [
                'success' => true,
                'schedule_updated' => $updated1,
                'revenue_updated' => $updated2,
                'message' => "Đã cập nhật {$updated2} booking với tổng tiền"
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}