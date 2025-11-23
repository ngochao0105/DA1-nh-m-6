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

            // Sử dụng password_verify để so sánh mật khẩu đã hash
            // Kiểm tra nếu mật khẩu đã được hash (bắt đầu bằng $2y$), nếu chưa thì so sánh trực tiếp (backward compatible)
            if (!$user) {
                $error = "Sai tên đăng nhập hoặc mật khẩu!";
                require_file_view('login', compact('error'));
                return;
            }
            
            // Kiểm tra mật khẩu: nếu đã hash thì dùng password_verify, nếu chưa thì so sánh trực tiếp
            $password_valid = false;
            if (strpos($user['password'], '$2y$') === 0) {
                // Mật khẩu đã được hash bằng password_hash()
                $password_valid = password_verify($password, $user['password']);
            } else {
                // Mật khẩu chưa được hash (backward compatible cho dữ liệu cũ)
                $password_valid = ($user['password'] === $password);
            }
            
            if (!$password_valid) {
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


