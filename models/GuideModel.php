<?php

class GuideModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

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
    // Lấy tất cả hướng dẫn viên
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
    public function deleteGuide($id)
    {
        try {
            $sql = "DELETE FROM nhansu WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
            $this->conn->query("SET @count = 0;");
            $this->conn->query("UPDATE nhansu SET id = @count := @count + 1;");
            $this->conn->query("ALTER TABLE nhansu AUTO_INCREMENT = 1;");
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }

    //thêm hdv

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


    //sửa hdv
    public function getAllhdv()
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

    // Lấy 1 hướng dẫn viên theo id
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

    // Cập nhật hướng dẫn viên
    public function updateGuide($id, $full_name, $birth_date, $phone, $email, $guide_type, $average_rating)
    {
        try {
            $sql = "UPDATE nhansu SET full_name = ?, birth_date = ?, phone = ?, email = ?, guide_type = ?, average_rating = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$full_name, $birth_date ?: null, $phone, $email, $guide_type, $average_rating, $id]);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }

    // ...existing code...
    public function inserthdv($full_name, $birth_date, $phone, $email, $guide_type, $average_rating)
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
}
