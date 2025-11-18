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
        ORDER BY booking.id DESC
         ";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();


        return $stmt->fetchAll();
    }
    public function createBooking($id_tour, $name, $phone, $email, $count, $date, $request, $price)
    {
    $sql = "INSERT INTO booking 
            (id_tour, customer_name, phone, email, people_count, booking_date, special_request, total_price)
            VALUES 
            (:id_tour, :customer_name, :phone, :email, :people_count, :booking_date, :special_request, :total_price)";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        ':id_tour'        => $id_tour,
        ':customer_name'  => $name,
        ':phone'          => $phone,
        ':email'          => $email,
        ':people_count'   => $count,
        ':booking_date'   => $date,
        ':special_request'=> $request,
        ':total_price'    => $price
    ]);
    }
    public function updateBookingStatus($id, $new_status)
{
    // Lấy trạng thái cũ
    $sqlOld = "SELECT status FROM booking WHERE id = :id";
    $stmtOld = $this->conn->prepare($sqlOld);
    $stmtOld->execute([':id' => $id]);
    $old_status = $stmtOld->fetchColumn();
    
    $sql = "UPDATE booking 
            SET status = :status, updated_at = NOW()
            WHERE id = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
        ':status' => $new_status,
        ':id' => $id
    ]);
    if ($old_status != $new_status) {
        $sqlLog = "INSERT INTO booking_logs (booking_id, old_status, new_status, changed_by)
                   VALUES (:booking_id, :old_status, :new_status, 'admin')";
        $stmtLog = $this->conn->prepare($sqlLog);
        $stmtLog->execute([
            ':booking_id' => $id,
            ':old_status' => $old_status,
            ':new_status' => $new_status
        ]);
    }
    }

    public function getBookingLogs($booking_id)
    {
        $sql = "SELECT * FROM booking_logs WHERE booking_id = :booking_id ORDER BY changed_at DESC";
         $stmt = $this->conn->prepare($sql);
        $stmt->execute([':booking_id' =>$booking_id]);
        return $stmt->fetchAll();
    }
    

}