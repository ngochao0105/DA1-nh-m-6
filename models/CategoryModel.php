<?php 
class CategoryModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Viết truy vấn danh sách sản phẩm 
    public function getAllTour()
    {}
        
    public function getAllCategories()
    {
        try {
            $sql = "SELECT * FROM danhmuctour ORDER BY id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $categories;
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }
}
