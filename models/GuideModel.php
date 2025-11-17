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
            $sql = "SELECT * FROM nhansu ORDER BY id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }

    // Xóa HDV
    public function deleteGuide($id)
    {
        try {
            $sql = "DELETE FROM nhansu WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }

    // Thêm HDV
    public function insertGuide($full_name, $birth_date, $phone, $email, $guide_type, $average_rating)
    {
        try {
            $sql = "INSERT INTO nhansu (full_name, birth_date, phone, email, guide_type, average_rating) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$full_name, $birth_date, $phone, $email, $guide_type, $average_rating]);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
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
            $sql = "SELECT * FROM nhansu WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }

    // Update HDV
    public function updateGuide($id, $full_name, $birth_date, $phone, $email, $guide_type, $average_rating)
    {
        try {
            $sql = "UPDATE nhansu 
                    SET full_name = ?, birth_date = ?, phone = ?, email = ?, guide_type = ?, average_rating = ? 
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$full_name, $birth_date ?: null, $phone, $email, $guide_type, $average_rating, $id]);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
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
