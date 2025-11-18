<?php
class BookingModel 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAllBooking()
    {
        $sql = "SELECT booking.*, tour.tour_name
                FROM booking
                LEFT JOIN tour ON booking.id_tour = tour.id
                ORDER BY booking.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingById($id)
    {
        $sql = "SELECT booking.*, tour.tour_name
                FROM booking
                LEFT JOIN tour ON booking.id_tour = tour.id
                WHERE booking.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>