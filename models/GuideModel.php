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
        // Lấy password_display từ nhansu (mật khẩu gốc), không lấy password từ taikhoan (đã hash)
        $sql = "SELECT ns.*, 
                       COALESCE(ns.password_display, ns.password) as password_display,
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

    // Thêm HDV và tạo tài khoản
    public function insertGuide($full_name, $birth_date, $phone, $email, $guide_type, $competency_level, $username, $password)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Hash mật khẩu để lưu vào taikhoan (để đăng nhập)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 2. Tạo tài khoản trong bảng taikhoan với mật khẩu đã hash
            $sqlAccount = "INSERT INTO taikhoan (username, password, role) VALUES (?, ?, 'hdv')";
            $stmtAccount = $this->conn->prepare($sqlAccount);
            $stmtAccount->execute([$username, $hashed_password]);
            $accountId = $this->conn->lastInsertId();

            // 3. Thêm nhân sự và liên kết với tài khoản
            // Lưu mật khẩu gốc vào nhansu để hiển thị (nếu có cột password_display)
            // Nếu không có cột password_display, sẽ lưu vào password column
            $sql = "INSERT INTO nhansu (full_name, birth_date, phone, email, guide_type, competence_level, id_taikhoan, password_display) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$full_name, $birth_date, $phone, $email, $guide_type, $competency_level, $accountId, $password]);
            } catch (PDOException $e) {
                // Fallback: nếu không có cột password_display, lưu vào password
                $sql = "INSERT INTO nhansu (full_name, birth_date, phone, email, guide_type, competence_level, id_taikhoan, password) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$full_name, $birth_date, $phone, $email, $guide_type, $competency_level, $accountId, $password]);
            }

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
            // Lấy password_display từ nhansu (mật khẩu gốc), không lấy password từ taikhoan (đã hash)
            $sql = "SELECT ns.*, 
                           COALESCE(ns.password_display, ns.password) as password_display,
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
    public function updateGuide($id, $full_name, $birth_date, $phone, $email, $guide_type, $competency_level, $username, $password = null)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Lấy id_taikhoan hiện tại
            $guide = $this->getGuideById($id);
            $accountId = $guide['id_taikhoan'] ?? null;

            // 2. Cập nhật thông tin nhân sự
            // Nếu có mật khẩu mới, cập nhật password_display trong nhansu
            if ($password) {
                $sql = "UPDATE nhansu 
                        SET full_name = ?, birth_date = ?, phone = ?, email = ?, guide_type = ?, competence_level = ?, password_display = ? 
                        WHERE id = ?";
                try {
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([$full_name, $birth_date ?: null, $phone, $email, $guide_type, $competency_level, $password, $id]);
                } catch (PDOException $e) {
                    // Fallback: nếu không có cột password_display, dùng password
                    $sql = "UPDATE nhansu 
                            SET full_name = ?, birth_date = ?, phone = ?, email = ?, guide_type = ?, competence_level = ?, password = ? 
                            WHERE id = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([$full_name, $birth_date ?: null, $phone, $email, $guide_type, $competency_level, $password, $id]);
                }
            } else {
                // Không đổi mật khẩu, chỉ cập nhật thông tin khác
                $sql = "UPDATE nhansu 
                        SET full_name = ?, birth_date = ?, phone = ?, email = ?, guide_type = ?, competence_level = ? 
                        WHERE id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$full_name, $birth_date ?: null, $phone, $email, $guide_type, $competency_level, $id]);
            }

            // 3. Cập nhật hoặc tạo tài khoản
            if ($accountId) {
                // Cập nhật tài khoản hiện có
                if ($password) {
                    // Hash mật khẩu trước khi lưu vào taikhoan
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sqlAccount = "UPDATE taikhoan SET username = ?, password = ? WHERE id = ?";
                    $stmtAccount = $this->conn->prepare($sqlAccount);
                    $stmtAccount->execute([$username, $hashed_password, $accountId]);
                } else {
                    $sqlAccount = "UPDATE taikhoan SET username = ? WHERE id = ?";
                    $stmtAccount = $this->conn->prepare($sqlAccount);
                    $stmtAccount->execute([$username, $accountId]);
                }
            } else {
                // Tạo tài khoản mới nếu chưa có
                $default_password = $password ?: '123456';
                // Hash mật khẩu trước khi lưu
                $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
                $sqlAccount = "INSERT INTO taikhoan (username, password, role) VALUES (?, ?, 'hdv')";
                $stmtAccount = $this->conn->prepare($sqlAccount);
                $stmtAccount->execute([$username, $hashed_password]);
                $newAccountId = $this->conn->lastInsertId();
                
                // Liên kết với nhân sự và lưu mật khẩu gốc vào nhansu
                if ($password) {
                    $sqlLink = "UPDATE nhansu SET id_taikhoan = ?, password_display = ? WHERE id = ?";
                    try {
                        $stmtLink = $this->conn->prepare($sqlLink);
                        $stmtLink->execute([$newAccountId, $password, $id]);
                    } catch (PDOException $e) {
                        $sqlLink = "UPDATE nhansu SET id_taikhoan = ?, password = ? WHERE id = ?";
                        $stmtLink = $this->conn->prepare($sqlLink);
                        $stmtLink->execute([$newAccountId, $password, $id]);
                    }
                } else {
                    $sqlLink = "UPDATE nhansu SET id_taikhoan = ?, password_display = ? WHERE id = ?";
                    try {
                        $stmtLink = $this->conn->prepare($sqlLink);
                        $stmtLink->execute([$newAccountId, $default_password, $id]);
                    } catch (PDOException $e) {
                        $sqlLink = "UPDATE nhansu SET id_taikhoan = ?, password = ? WHERE id = ?";
                        $stmtLink = $this->conn->prepare($sqlLink);
                        $stmtLink->execute([$newAccountId, $default_password, $id]);
                    }
                }
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
