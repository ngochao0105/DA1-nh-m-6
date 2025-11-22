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
        $sql = "SELECT b.*, t.tour_name
                FROM booking b
                LEFT JOIN tour t ON b.id_tour = t.id
                ORDER BY b.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
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
        $sql = "INSERT INTO booking (id_tour, ngay_di, loai_dat)
                VALUES (:t, :ngay, :l)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            't'    => $idTour,
            'ngay' => $ngayDi,
            'l'    => $loaiDat
        ]);

        return $this->conn->lastInsertId();
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
        // Lấy trạng thái cũ
        $sqlOld = "SELECT trang_thai FROM booking WHERE id = :id";
        $stmtOld = $this->conn->prepare($sqlOld);
        $stmtOld->execute(['id' => $id]);
        $old_status = $stmtOld->fetchColumn();

        // Cập nhật booking
        $sql = "UPDATE booking 
                SET trang_thai = :st, ngay_cap_nhat = NOW()
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'st' => $new_status,
            'id' => $id
        ]);

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
    $sql = "SELECT b.*, t.tour_name
            FROM booking b
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



}
