<?php
class HdvModel
{
    private $pdo;

    public function __construct()
    {
        // Kết nối database
        $host = 'localhost';
        $db   = 'da_1';
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
