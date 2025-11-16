<?php

class AuthController {

    // ============================================================
    //  ĐĂNG NHẬP
    // ============================================================
public function login() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? ''; // Lấy mật khẩu thường
            $error = null;

            if ($username === '' || $password === '') {
                $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
                require_file_view('login', compact('error'));
                return;
            }

            $userModel = new UserModel();
            $user = $userModel->getByUsername($username);

            // ================================================
            //  SỬ DỤNG SO SÁNH MẬT KHẨU THƯỜNG (KHÔNG AN TOÀN)
            // ================================================
            if (!$user || $user['password'] !== $password) {
                $error = "Sai tên đăng nhập hoặc mật khẩu!";
                require_file_view('login', compact('error'));
                return;
            }
            // ================================================

            // Lưu Session
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role']; // Đây là chìa khóa

            // Phân quyền chuyển hướng
            switch ($user['role']) {
                case 'admin':
                    header("Location: ?act=/"); 
                    exit;

                case 'hdv':
                    header("Location: ?act=hdv_dashboard"); 
                    exit;

                default: // 'user'
                    header("Location: ?act=home"); // Trang chủ public
                    exit;
            }
        }

        // Nếu GET thì show form login
        require_file_view('login');
    }

    // ============================================================
    //  ĐĂNG XUẤT
    // ============================================================
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: ?act=login");
        exit;
    }
}


