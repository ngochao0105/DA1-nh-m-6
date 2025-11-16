<?php
// File: models/HdvModel.php

class HdvModel {

    /**
     * Lấy thông tin hồ sơ nhân sự (HDV) dựa trên ID tài khoản đăng nhập
     */
    public function getHdvInfoByAccountId($taikhoan_id) {
        try {
            $conn = get_db_connection();
            // Lấy hồ sơ nhân sự (bảng nhansu) từ id tài khoản (bảng taikhoan)
            $sql = "SELECT * FROM nhansu WHERE id_taikhoan = :taikhoan_id LIMIT 1";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':taikhoan_id', $taikhoan_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return null;
        } finally {
            $conn = null;
        }
    }

    /**
     * Lấy các tour đã được phân công cho một HDV (dựa trên ID của HDV)
     */
    public function getAssignedTours($hdv_id) {
        try {
            $conn = get_db_connection();
            // Nối 2 bảng tour và phancong để lấy tour
            $sql = "SELECT t.*, pc.role AS vai_tro_trong_tour
                    FROM tour t
                    JOIN phancong pc ON t.id = pc.id_tour
                    WHERE pc.id_hdv = :hdv_id
                    ORDER BY t.start_date DESC";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':hdv_id', $hdv_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return []; // Trả về mảng rỗng nếu lỗi
        } finally {
            $conn = null;
        }
    }

     /**
     * Lấy lịch làm việc cá nhân của HDV
     */
    public function getWorkSchedule($hdv_id) {
        try {
            $conn = get_db_connection();
            $sql = "SELECT * FROM lichlamviec WHERE id_hdv = :hdv_id ORDER BY date ASC";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':hdv_id', $hdv_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        } finally {
            $conn = null;
        }
    }
}