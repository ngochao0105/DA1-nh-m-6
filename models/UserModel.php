<?php

class UserModel {

    public function getByUsername($username) {
        try {
            $conn = get_db_connection();
            $sql = "SELECT * FROM taikhoan WHERE username = :username LIMIT 1";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return null;

        } finally {
            $conn = null;
        }
    }


}
