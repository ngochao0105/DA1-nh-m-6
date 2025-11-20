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
    public function getAllGuides()
    {
        try {
            $sql = "SELECT ns.*, tk.username, tk.password, tk.role 
                    FROM nhansu ns
                    LEFT JOIN taikhoan tk ON ns.id_taikhoan = tk.id
                    ORDER BY ns.id DESC";
            $stmt = $this->conn->prepare($sql);
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

    // Thêm HDV và tạo tài khoản
    public function insertGuide($full_name, $birth_date, $phone, $email, $guide_type, $average_rating, $username, $password)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Tạo tài khoản trong bảng taikhoan
            $sqlAccount = "INSERT INTO taikhoan (username, password, role) VALUES (?, ?, 'hdv')";
            $stmtAccount = $this->conn->prepare($sqlAccount);
            $stmtAccount->execute([$username, $password]);
            $accountId = $this->conn->lastInsertId();

            // 2. Thêm nhân sự và liên kết với tài khoản
            $sql = "INSERT INTO nhansu (full_name, birth_date, phone, email, guide_type, average_rating, id_taikhoan) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$full_name, $birth_date, $phone, $email, $guide_type, $average_rating, $accountId]);

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
        $this->conn->query("UPDATE nhansu SET id = @count := @count + 1;");
        $this->conn->query("ALTER TABLE nhansu AUTO_INCREMENT = 1;");
    }

    // Lấy 1 HDV theo id
    public function getGuideById($id)
    {
        try {
            $sql = "SELECT ns.*, tk.username, tk.password, tk.role 
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
    public function updateGuide($id, $full_name, $birth_date, $phone, $email, $guide_type, $average_rating, $username, $password = null)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Lấy id_taikhoan hiện tại
            $guide = $this->getGuideById($id);
            $accountId = $guide['id_taikhoan'] ?? null;

            // 2. Cập nhật thông tin nhân sự
            $sql = "UPDATE nhansu 
                    SET full_name = ?, birth_date = ?, phone = ?, email = ?, guide_type = ?, average_rating = ? 
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$full_name, $birth_date ?: null, $phone, $email, $guide_type, $average_rating, $id]);

            // 3. Cập nhật hoặc tạo tài khoản
            if ($accountId) {
                // Cập nhật tài khoản hiện có
                if ($password) {
                    $sqlAccount = "UPDATE taikhoan SET username = ?, password = ? WHERE id = ?";
                    $stmtAccount = $this->conn->prepare($sqlAccount);
                    $stmtAccount->execute([$username, $password, $accountId]);
                } else {
                    $sqlAccount = "UPDATE taikhoan SET username = ? WHERE id = ?";
                    $stmtAccount = $this->conn->prepare($sqlAccount);
                    $stmtAccount->execute([$username, $accountId]);
                }
            } else {
                // Tạo tài khoản mới nếu chưa có
                $sqlAccount = "INSERT INTO taikhoan (username, password, role) VALUES (?, ?, 'hdv')";
                $stmtAccount = $this->conn->prepare($sqlAccount);
                $stmtAccount->execute([$username, $password ?: '123456']); // Mật khẩu mặc định nếu không nhập
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

    // =====================
    //  PHÂN CÔNG HDV
    // =====================

    // Lưu phân công
    public function assignGuideToTour($id_tour, $id_hdv, $role)
    {
        try {
            $sql = "INSERT INTO phancong (id_tour, id_hdv, role) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id_tour, $id_hdv, $role]);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }

    // Lấy danh sách HDV đã phân công theo tour
    public function getAssignedGuidesByTour($id_tour)
    {
        try {
            $sql = "SELECT pc.*, ns.full_name, ns.phone, ns.email
                    FROM phancong pc
                    JOIN nhansu ns ON pc.id_hdv = ns.id
                    WHERE pc.id_tour = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_tour]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }
    public function isTourAssigned($id_tour)
{
    try {
        $sql = "SELECT COUNT(*) as total FROM phancong WHERE id_tour = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_tour]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        return $count > 0; // true nếu đã có HDV
    } catch (PDOException $e) {
        die("Lỗi SQL: " . $e->getMessage());
    }
}

    // Xóa phân công
    public function deleteAssign($assign_id)
    {
        try {
            $sql = "DELETE FROM phancong WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$assign_id]);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }

    // HDV xem danh sách tour được phân công
    public function getTourAssignedForGuide($id_hdv)
    {
        try {
            $sql = "SELECT pc.*, t.tour_name, t.start_date, t.end_date, t.destination
                    FROM phancong pc
                    JOIN tour t ON pc.id_tour = t.id
                    WHERE pc.id_hdv = ?
                    ORDER BY t.start_date ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_hdv]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }
}
