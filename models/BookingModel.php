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
    // ================================
    public function getAllBooking()
    {
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
                LEFT JOIN tour t ON b.id_tour = t.id
                ORDER BY b.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
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
    public function createBooking($idTour, $ngayDi, $loaiDat)
    {
        // Đảm bảo cột trang_thai đủ dài
        $this->ensureTrangThaiColumnSize();

        $sql = "INSERT INTO booking (id_tour, ngay_di, loai_dat, trang_thai)
                VALUES (:t, :ngay, :l, 'cho_xac_nhan')";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            't'    => $idTour,
            'ngay' => $ngayDi,
            'l'    => $loaiDat
        ]);

        return $this->conn->lastInsertId();
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

}
