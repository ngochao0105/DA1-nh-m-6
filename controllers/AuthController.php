<?php

class AuthController {

    // ============================================================
    //  ĐĂNG NHẬP
    // ============================================================
public function login() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $error = null;

            if ($username === '' || $password === '') {
                $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
                require_file_view('login', compact('error'));
                return;
            }

            $userModel = new UserModel();
            $user = $userModel->getByUsername($username);

            // Cập nhật: Sử dụng password_verify để so sánh mật khẩu đã mã hóa
          if (!$user || $user['password'] !== $password) {
                $error = "Sai tên đăng nhập hoặc mật khẩu!";
                require_file_view('login', compact('error'));
                return;
            }

            // Lưu Session
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role']; // Đây là chìa khóa

            // ================================================
            //  PHÂN QUYỀN CHUYỂN HƯỚNG DỰA TRÊN ROLE
            // ================================================
            
            switch ($user['role']) {
                case 'admin':
                    // Nếu là admin, chuyển đến trang admin dashboard
                    // (Giữ nguyên trang dashboard của bạn)
                    header("Location: ?act=/"); 
                    exit;

                case 'hdv':
                    // Nếu là Hướng dẫn viên, chuyển đến trang của HDV
                    header("Location: ?act=hdv_dashboard"); 
                    exit;

                default:
                    // Các vai trò khác (ví dụ: 'user') về trang chủ
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


