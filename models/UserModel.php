<?php
// File: UserModel.php

// Giả định rằng hàm get_db_connection() đã được định nghĩa
// (Hàm này dùng các hằng số DB_... từ env.php để tạo kết nối PDO)

class UserModel {

    /**
     * Lấy thông tin user bằng username
     */
    public function getByUsername($username) {
        try {
            $conn = get_db_connection(); // Hàm kết nối database
            $sql = "SELECT * FROM taikhoan WHERE username = :username LIMIT 1";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            
            $stmt->execute();
            // Lấy kết quả dưới dạng mảng kết hợp
            return $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            // Thường thì chỉ cần log lỗi thay vì echo ra màn hình production
            error_log("UserModel Error: " . $e->getMessage()); 
            return null;
        } finally {
            $conn = null; // Đóng kết nối
        }
    }
    
    /**
     * Tạo user mới (dùng cho đăng ký)
     */
    public function create($username, $password) {
        // Băm mật khẩu (Hashing) trước khi lưu vào database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $conn = get_db_connection();
            // Giả định bảng users có các cột: id, username, password, role
            $sql = "INSERT INTO taikhoan (username, password, role) VALUES (:username, :password, 'user')";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Lỗi khi trùng username (UNIQUE constraint) sẽ bị bắt ở đây
            // error_log("UserModel Create Error: " . $e->getMessage());
            return false;
        } finally {
            $conn = null;
        }
    }
}