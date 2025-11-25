<?php

class HdvModel {

    public function getHdvInfoByAccountId($taikhoan_id) {
        try {
            $conn = get_db_connection();
            $sql = "SELECT * FROM nhansu WHERE id_taikhoan = :tk LIMIT 1";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':tk', $taikhoan_id);
            $stmt->execute();

            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getAssignedTours($hdv_id)
    {
        try {
            $conn = get_db_connection();

            $sql = "
                SELECT 
                    b.id AS booking_id,
                    t.tour_name,
                    b.ngay_di
                FROM phan_cong_hdv p
                JOIN booking b ON p.id_booking = b.id
                JOIN tour t ON b.id_tour = t.id
                WHERE p.id_hdv = :hdv_id
                ORDER BY b.ngay_di DESC
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':hdv_id', $hdv_id);
            $stmt->execute();

            return $stmt->fetchAll();

        } catch (PDOException $e) {
            return [];
        }
    }

    public function getTourCustomers($hdv_id)
    {
        try {
            $conn = get_db_connection();

            $sql = "
                SELECT 
                    k.ten_khach AS full_name,
                    k.sdt AS phone,
                    k.loai_khach,
                    k.yeu_cau_dac_biet AS special_note,
                    t.tour_name,
                    b.ngay_di AS start_date,
                    b.ngay_di AS end_date,
                    b.id AS booking_id
                FROM phan_cong_hdv p
                JOIN booking b ON p.id_booking = b.id
                JOIN tour t ON b.id_tour = t.id
                JOIN khachtour k ON k.id_booking = b.id
                WHERE p.id_hdv = :hdv_id
                ORDER BY b.ngay_di DESC, k.ten_khach ASC
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':hdv_id', $hdv_id);
            $stmt->execute();

            return $stmt->fetchAll();

        } catch (PDOException $e) {
            return [];
        }
    }
}
