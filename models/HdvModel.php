<?php
class HdvModel
{
    private $pdo;

    public function __construct()
    {
        // Kết nối database
        $host = 'localhost';
        $db   = 'hao2';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    // Lấy danh sách tour HDV được phân công
    public function getAssignedTours($hdv_id)
    {
        $sql = "
            SELECT 
                pc.id AS phan_cong_id,
                pc.ngay_di,
                
                b.id AS booking_id,
                b.ngay_di,
                b.trang_thai AS booking_status,

                t.id AS tour_id,
                t.tour_name,
                t.destination,
                t.status AS tour_status

            FROM phan_cong_hdv pc
            LEFT JOIN booking b ON pc.id_booking = b.id
            LEFT JOIN tour t ON b.id_tour = t.id

            WHERE pc.id_hdv = ?
            ORDER BY pc.ngay_di DESC
        ";

        return $this->pdo_query($sql, [$hdv_id]);
    }

    // Lấy thông tin HDV theo tài khoản
    public function getHdvInfoByAccountId($account_id)
    {
        $sql = "SELECT * FROM nhansu WHERE id_taikhoan = ?";
        return $this->pdo_query_one($sql, [$account_id]);
    }

    // Lấy lịch sử booking đã dẫn của HDV (chỉ tour đã hoàn thành)
    public function getBookingHistory($hdv_id)
    {
        $sql = "SELECT 
                    b.id AS booking_id,
                    b.ngay_di,
                    b.trang_thai AS booking_status,
                    t.id AS tour_id,
                    t.tour_name,
                    t.destination,
                    pc.id AS phan_cong_id,
                    pc.ngay_di AS ngay_phan_cong
                FROM phan_cong_hdv pc
                INNER JOIN booking b ON pc.id_booking = b.id
                INNER JOIN tour t ON b.id_tour = t.id
                WHERE pc.id_hdv = ? AND b.trang_thai = 'hoan_tat'
                ORDER BY b.ngay_di DESC, b.id DESC";
        
        return $this->pdo_query($sql, [$hdv_id]);
    }

    // Đếm tổng số booking đã hoàn thành
    public function countTotalBookings($hdv_id)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM phan_cong_hdv pc
                INNER JOIN booking b ON pc.id_booking = b.id
                WHERE pc.id_hdv = ? AND b.trang_thai = 'hoan_tat'";
        
        $result = $this->pdo_query_one($sql, [$hdv_id]);
        return $result['total'] ?? 0;
    }

    // Lấy đánh giá trung bình của HDV từ database
    public function getHdvRating($hdv_id)
    {
        try {
            // Lấy đánh giá từ cột average_rating trong bảng nhansu
            $sql = "SELECT average_rating 
                    FROM nhansu 
                    WHERE id = ?";
            $result = $this->pdo_query_one($sql, [$hdv_id]);
            
            if ($result && isset($result['average_rating']) && $result['average_rating'] !== null) {
                $rating = floatval($result['average_rating']);
                // Trả về rating nếu > 0, nếu không trả về 0
                return $rating > 0 ? $rating : 0;
            }

            // Trả về 0 nếu chưa có đánh giá
            return 0;
        } catch (Exception $e) {
            // Nếu có lỗi, trả về 0
            return 0;
        }
    }

    // Lấy lịch trình tour của HDV (từ booking được phân công)
    public function getHdvSchedules($hdv_id)
    {
        $sql = "SELECT DISTINCT
                    b.id AS booking_id,
                    COALESCE(ts.start_date, b.ngay_di) AS start_date,
                    COALESCE(ts.end_date, DATE_ADD(b.ngay_di, INTERVAL 1 DAY)) AS end_date,
                    t.id AS tour_id,
                    t.tour_name,
                    t.destination,
                    t.duration,
                    t.description,
                    t.status AS tour_status,
                    b.trang_thai AS booking_status,
                    (SELECT COUNT(*) FROM khachtour k WHERE k.id_booking = b.id) AS so_khach
                FROM phan_cong_hdv pc
                INNER JOIN booking b ON pc.id_booking = b.id
                LEFT JOIN tour_schedule ts ON b.schedule_id = ts.id
                INNER JOIN tour t ON b.id_tour = t.id
                WHERE pc.id_hdv = ?
                ORDER BY COALESCE(ts.start_date, b.ngay_di) DESC";

        return $this->pdo_query($sql, [$hdv_id]);
    }

    // Lấy chi tiết khách hàng trong một booking
    public function getBookingCustomers($booking_id, $hdv_id)
    {
        // Kiểm tra quyền: HDV chỉ có thể xem khách hàng của booking mình được phân công
        $checkSql = "SELECT COUNT(*) as cnt FROM phan_cong_hdv WHERE id_booking = ? AND id_hdv = ?";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute([$booking_id, $hdv_id]);
        $result = $checkStmt->fetch();
        
        if ($result['cnt'] == 0) {
            return []; // Không có quyền xem
        }

        $sql = "SELECT 
                    k.*,
                    b.ngay_di,
                    t.tour_name,
                    t.destination
                FROM khachtour k
                INNER JOIN booking b ON k.id_booking = b.id
                INNER JOIN tour t ON b.id_tour = t.id
                WHERE k.id_booking = ?
                ORDER BY k.id ASC";

        return $this->pdo_query($sql, [$booking_id]);
    }

    // Lấy thông tin booking chi tiết
    public function getBookingDetail($booking_id, $hdv_id)
    {
        // Kiểm tra quyền
        $checkSql = "SELECT COUNT(*) as cnt FROM phan_cong_hdv WHERE id_booking = ? AND id_hdv = ?";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute([$booking_id, $hdv_id]);
        $result = $checkStmt->fetch();
        
        if ($result['cnt'] == 0) {
            return null; // Không có quyền xem
        }

        $sql = "SELECT 
                    b.*,
                    t.tour_name,
                    t.destination,
                    t.duration,
                    t.description,
                    t.status AS tour_status,
                    (SELECT COUNT(*) FROM khachtour k WHERE k.id_booking = b.id) AS so_khach
                FROM booking b
                INNER JOIN tour t ON b.id_tour = t.id
                WHERE b.id = ?";

        return $this->pdo_query_one($sql, [$booking_id]);
    }

    // Cập nhật trạng thái HDV đã nhận tour
    public function confirmBooking($booking_id, $hdv_id)
    {
        // Kiểm tra quyền
        $checkSql = "SELECT COUNT(*) as cnt FROM phan_cong_hdv WHERE id_booking = ? AND id_hdv = ?";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute([$booking_id, $hdv_id]);
        $result = $checkStmt->fetch();
        
        if ($result['cnt'] == 0) {
            return false; // Không có quyền
        }

        // Cập nhật trạng thái thành "da_xac_nhan" nếu chưa
        $sql = "UPDATE booking SET trang_thai = 'da_xac_nhan' WHERE id = ? AND trang_thai = 'cho_xac_nhan'";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$booking_id]);
    }

    // Từ chối nhận tour
    public function rejectBooking($booking_id, $hdv_id, $ly_do = null)
    {
        // Kiểm tra quyền
        $checkSql = "SELECT COUNT(*) as cnt FROM phan_cong_hdv WHERE id_booking = ? AND id_hdv = ?";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute([$booking_id, $hdv_id]);
        $result = $checkStmt->fetch();
        
        if ($result['cnt'] == 0) {
            return false; // Không có quyền
        }

        // Cập nhật trạng thái thành "da_huy"
        $sql = "UPDATE booking SET trang_thai = 'da_huy' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$booking_id]);
    }

    // Cập nhật điểm danh khách hàng
    public function setCustomerAttendance($customer_id, $hdv_id, $is_present)
    {
        try {
            // Kiểm tra quyền: HDV chỉ có thể cập nhật điểm danh cho khách hàng trong booking mình được phân công
            $checkSql = "SELECT k.id_booking 
                        FROM khachtour k
                        INNER JOIN phan_cong_hdv pc ON k.id_booking = pc.id_booking
                        WHERE k.id = ? AND pc.id_hdv = ?";
            $checkStmt = $this->pdo->prepare($checkSql);
            $checkStmt->execute([$customer_id, $hdv_id]);
            $result = $checkStmt->fetch();
            
            if (!$result) {
                return false; // Không có quyền
            }

            // Kiểm tra xem cột da_checkin có tồn tại không
            $checkColSql = "SHOW COLUMNS FROM khachtour LIKE 'da_checkin'";
            $colResult = $this->pdo->query($checkColSql)->fetch();
            
            if (!$colResult) {
                // Nếu cột chưa tồn tại, thêm cột
                $alterSql = "ALTER TABLE khachtour ADD COLUMN da_checkin TINYINT(1) DEFAULT 0";
                $this->pdo->exec($alterSql);
            }

            // Cập nhật điểm danh
            $sql = "UPDATE khachtour SET da_checkin = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$is_present, $customer_id]);
        } catch (PDOException $e) {
            error_log("Error in setCustomerAttendance: " . $e->getMessage());
            return false;
        }
    }

    // PHƯƠNG THỨC HỖ TRỢ PDO
    private function pdo_query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function pdo_query_one($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(); // trả về 1 row
    }

    //  // HDV check-in
    // public function hdvCheckin($hdv_id, $tour_id, $location) {
    //     $sql = "INSERT INTO diemdanh (id_hdv, id_tour, check_time, location) VALUES (?, ?, NOW(), ?)";
    //     $stmt = $this->pdo->prepare($sql);
    //     return $stmt->execute([$hdv_id, $tour_id, $location]);
    // }

    // Khách hàng check-in
    // public function customerCheckin($khach_id, $tour_id, $location) {
    //     $sql = "INSERT INTO diemdanh (id_khach, id_tour, check_time, location) VALUES (?, ?, NOW(), ?)";
    //     $stmt = $this->pdo->prepare($sql);
    //     return $stmt->execute([$khach_id, $tour_id, $location]);
    

    // Lấy danh sách điểm danh theo tour
    // public function getCheckinByTour($tour_id) {
    //     $sql = "SELECT dd.*, tk.username AS hdv_name, hk.id_khach AS khach_id
    //             FROM diemdanh dd
    //             LEFT JOIN taikhoan tk ON dd.id_hdv = tk.id
    //             LEFT JOIN hosokhach hk ON dd.id_khach = hk.id_khach
    //             WHERE dd.id_tour = ?
    //             ORDER BY dd.check_time DESC";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute([$tour_id]);
    //     return $stmt->fetchAll();
    // }
}
