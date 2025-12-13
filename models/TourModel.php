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

        return $stmt->fetchAll();

        } catch (PDOException $e) {
        die("Lỗi SQL: " . $e->getMessage());
        }
    }
   public function getStatus($status)   
    {
    $sql = "SELECT tour.*, danhmuctour.category_name 
            FROM tour
            LEFT JOIN danhmuctour 
            ON tour.id_danh_muc = danhmuctour.id
            WHERE tour.status = :status
            ORDER BY tour.id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':status' => $status]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchTour($keyword)
    {
        try {
            $sql = "SELECT tour.*, danhmuctour.category_name 
                    FROM tour
                    LEFT JOIN danhmuctour 
                    ON tour.id_danh_muc = danhmuctour.id
                    WHERE tour.tour_name LIKE :keyword 
                       OR tour.destination LIKE :keyword 
                       OR tour.description LIKE :keyword
                    ORDER BY tour.id DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':keyword' => '%' . $keyword . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        }
    }

    public function createTour(
        $tour_name,
        $description,
        $destination,
        $departure_point,
        $vehicle,
        $id_danh_muc,
        $status
    ) {
        try {
            $sql = "INSERT INTO tour(tour_name, description, destination, departure_point, vehicle, id_danh_muc, status)
                    VALUES(:tour_name, :description, :destination, :departure_point, :vehicle, :id_danh_muc, :status)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":tour_name" => $tour_name,
                ":description" => $description,
                ":destination" => $destination,
                ":departure_point" => $departure_point ?: null,
                ":vehicle" => $vehicle ?: null,
                ":id_danh_muc" => $id_danh_muc,
                ":status" => $status
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("Lỗi database: " . $e->getMessage());
        }
    }
    public function updateTour(
            $id,
        $tour_name,
        $description,
        $destination,
        $departure_point,
        $vehicle,
        $id_danh_muc,
        $status
    )
    {
        try {
            $sql = "UPDATE tour 
                SET tour_name = :tour_name,
                    description = :description,
                    destination = :destination,
                    departure_point = :departure_point,
                    vehicle = :vehicle,
                    id_danh_muc = :id_danh_muc,
                    status = :status
                WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":id" => $id,
                ":tour_name" => $tour_name,
                ":description" => $description,
                ":destination" => $destination,
                ":departure_point" => $departure_point ?: null,
                ":vehicle" => $vehicle ?: null,
                ":id_danh_muc" => $id_danh_muc,
                ":status" => $status
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("Lỗi database: " . $e->getMessage());
        }
    }
    public function getOneTour($id) 
    {
        $sql = "SELECT tour.*, danhmuctour.category_name 
                FROM tour
                LEFT JOIN danhmuctour 
                ON tour.id_danh_muc = danhmuctour.id
                WHERE tour.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
         return $stmt->fetch(); 
    }
   public function getCategories() {
    $sql = "SELECT id,category_name FROM danhmuctour ORDER BY category_name ASC";
    return $this->conn->query($sql)->fetchAll();

    }
    public function deleteTour($id)
    {
        $sql = "DELETE FROM tour WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    public function getTourById($id)
{
    try {
        $sql = "SELECT tour.*, danhmuctour.category_name 
                FROM tour
                LEFT JOIN danhmuctour 
                ON tour.id_danh_muc = danhmuctour.id
                WHERE tour.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        die("Lỗi SQL: " . $e->getMessage());
    }
}
}
