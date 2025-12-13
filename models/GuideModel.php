<?php

class GuideModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Đếm số lượng HDV
    public function countGuide()
    {
        try {
            $sql = "SELECT COUNT(*) AS total FROM nhansu";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }

    // Lấy tất cả HDV
    // Lấy tất cả HDV + tìm kiếm
public function getAllGuides($keyword = "")
{
    try {
        $sql = "SELECT ns.*, 
                       tk.username, tk.role 
                FROM nhansu ns
                LEFT JOIN taikhoan tk ON ns.id_taikhoan = tk.id
                WHERE 1";

        if (!empty($keyword)) {
            $sql .= " AND (
                        ns.full_name LIKE :kw 
                        OR ns.phone LIKE :kw
                        OR ns.email LIKE :kw
                        OR ns.guide_type LIKE :kw
                    )";
        }

        $sql .= " ORDER BY ns.id DESC";

        $stmt = $this->conn->prepare($sql);

        if (!empty($keyword)) {
            $stmt->bindValue(":kw", "%$keyword%", PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Lỗi SQL: " . $e->getMessage());
    }
}



    // Xóa HDV và tài khoản liên quan
    public function deleteGuide($id)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Lấy id_taikhoan
            $guide = $this->getGuideById($id);
            $accountId = $guide['id_taikhoan'] ?? null;

            // 2. Xóa nhân sự
            $sql = "DELETE FROM nhansu WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);

            // 3. Xóa tài khoản nếu có
            if ($accountId) {
                $sqlAccount = "DELETE FROM taikhoan WHERE id = ?";
                $stmtAccount = $this->conn->prepare($sqlAccount);
                $stmtAccount->execute([$accountId]);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Lỗi SQL: " . $e->getMessage());
        }
    }

    // Thêm HDV và tạo tài khoản (tài khoản được tạo tự động, không nhập từ form)
    public function insertGuide($full_name, $birth_date, $phone, $email, $guide_type, $license_type, $username)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Tạo tài khoản trong bảng taikhoan với mật khẩu mặc định
            $default_password = '123456';
            $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
            $sqlAccount = "INSERT INTO taikhoan (username, password, role) VALUES (?, ?, 'hdv')";
            $stmtAccount = $this->conn->prepare($sqlAccount);
            $stmtAccount->execute([$username, $hashed_password]);
            $accountId = $this->conn->lastInsertId();

            // 2. Thêm nhân sự và liên kết với tài khoản
            $sql = "INSERT INTO nhansu (full_name, birth_date, phone, email, guide_type, license_type, id_taikhoan) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$full_name, $birth_date, $phone, $email, $guide_type, $license_type, $accountId]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Lỗi SQL: " . $e->getMessage());
        }
    }

    public function resetGuideIds()
    {
        $this->conn->query("SET @count = 0;");
        // $this->conn->query("UPDATE nhansu SET id = @count := @count + 1;");
        $this->conn->query("ALTER TABLE nhansu AUTO_INCREMENT = 1;");
    }

    // Lấy 1 HDV theo id
    public function getGuideById($id)
    {
        try {
            $sql = "SELECT ns.*, 
                           tk.username, tk.role 
                    FROM nhansu ns
                    LEFT JOIN taikhoan tk ON ns.id_taikhoan = tk.id
                    WHERE ns.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }

    // Update HDV và cập nhật tài khoản
    public function updateGuide($id, $full_name, $birth_date, $phone, $email, $guide_type, $license_type, $username)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Lấy id_taikhoan hiện tại
            $guide = $this->getGuideById($id);
            $accountId = $guide['id_taikhoan'] ?? null;

            // 2. Cập nhật thông tin nhân sự
            $sql = "UPDATE nhansu 
                    SET full_name = ?, birth_date = ?, phone = ?, email = ?, guide_type = ?, license_type = ? 
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$full_name, $birth_date ?: null, $phone, $email, $guide_type, $license_type, $id]);

            // 3. Cập nhật hoặc tạo tài khoản
            if ($accountId) {
                // Cập nhật tài khoản hiện có
                $sqlAccount = "UPDATE taikhoan SET username = ? WHERE id = ?";
                $stmtAccount = $this->conn->prepare($sqlAccount);
                $stmtAccount->execute([$username, $accountId]);
            } else {
                // Tạo tài khoản mới nếu chưa có
                $default_password = '123456';
                $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
                $sqlAccount = "INSERT INTO taikhoan (username, password, role) VALUES (?, ?, 'hdv')";
                $stmtAccount = $this->conn->prepare($sqlAccount);
                $stmtAccount->execute([$username, $hashed_password]);
                $newAccountId = $this->conn->lastInsertId();
                
                // Liên kết với nhân sự
                $sqlLink = "UPDATE nhansu SET id_taikhoan = ? WHERE id = ?";
                $stmtLink = $this->conn->prepare($sqlLink);
                $stmtLink->execute([$newAccountId, $id]);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Lỗi SQL: " . $e->getMessage());
        }
    }

    // HDV xem danh sách tour được phân công (dựa trên booking)
    public function getTourAssignedForGuide($id_hdv)
    {
        try {
            $sql = "SELECT 
                        pc.id AS assignment_id,
                        pc.ngay_di,
                        b.id AS booking_id,
                        b.ngay_di AS booking_start_date,
                        b.trang_thai AS booking_status,
                        b.trang_thai_thanh_toan AS payment_status,
                        t.id AS tour_id,
                        t.tour_name,
                        t.destination
                    FROM phan_cong_hdv pc
                    INNER JOIN booking b ON pc.id_booking = b.id
                    INNER JOIN tour t ON b.id_tour = t.id
                    WHERE pc.id_hdv = ?
                    ORDER BY pc.ngay_di DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_hdv]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }




}
