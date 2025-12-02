<?php
class CategoryModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả danh mục
    public function getAllCategories()
    {
        $sql = "SELECT id, category_name FROM danhmuctour ORDER BY category_name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy danh mục theo ID
    public function getCategoryById($id)
    {
        $sql = "SELECT id, category_name FROM danhmuctour WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Thêm danh mục
    public function addCategory($category_name)
    {
        // Kiểm tra danh mục đã tồn tại chưa
        $sql = "SELECT id FROM danhmuctour WHERE category_name = :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $category_name, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return false; // Danh mục đã tồn tại
        }

        $sql = "INSERT INTO danhmuctour (category_name) VALUES (:name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $category_name, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật danh mục
    public function updateCategory($id, $category_name)
    {
        // Kiểm tra tên danh mục đã tồn tại chưa (không phải danh mục hiện tại)
        $sql = "SELECT id FROM danhmuctour WHERE category_name = :name AND id != :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $category_name, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return false; // Danh mục đã tồn tại
        }

        $sql = "UPDATE danhmuctour SET category_name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $category_name, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Xóa danh mục
    public function deleteCategory($id)
    {
        $sql = "DELETE FROM danhmuctour WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
?>
