<?php 
// Có class chứa các function thực thi tương tác với cơ sở dữ liệu 
class TourModel 
{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Viết truy vấn danh sách sản phẩm 
    
    public function getAllCategories()
    {
        try {
            $sql = "SELECT * FROM tour ORDER BY id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $categories;
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }
    public function createTour(
    $tour_name,
    $description,
    $start_date,
    $end_date,
    $destination,
    $price,
    $id_danh_muc,
    $status
) {
    $sql = "INSERT INTO tour(tour_name,description,start_date,end_date,destination,price,id_danh_muc,status)
    VALUES(:tour_name, :description, :start_date,:end_date,:destination,:price,:id_danh_muc,:status);";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
    ":tour_name" => $tour_name,
    ":description" => $description,
    ":start_date" => $start_date,
    ":end_date" => $end_date,
    ":destination" => $destination,
    ":price" => $price,
    ":id_danh_muc" => $id_danh_muc,
    ":status" => $status
]);
}

   public function getCateogries() {
    $sql = "SELECT * FROM danhmuctour ORDER BY id DESC";
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
