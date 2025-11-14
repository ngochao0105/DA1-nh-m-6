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
    public function countTours()
    {
        $sql = "SELECT COUNT(*) AS total FROM tour";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function countGuide()
    {
        $sql = "SELECT COUNT(*) AS total FROM huongdanvien";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getAllTour()
    {
        try {
        $sql = "SELECT tour.*, danhmuctour.category_name 
                FROM tour
                LEFT JOIN danhmuctour 
                ON tour.id_danh_muc = danhmuctour.id
                ORDER BY tour.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

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
   public function getCategories() {
    $sql = "SELECT id,category_name FROM danhmuctour ORDER BY category_name ASC";
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    }
    public function deleteTour($id)
    {
        $sql = "DELETE FROM tour WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
