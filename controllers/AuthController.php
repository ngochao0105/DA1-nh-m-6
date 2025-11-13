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

            // Database đang lưu mật khẩu thường nên ta kiểm tra dạng text
            if (!$user || $user['password'] !== $password) {
                $error = "Sai tên đăng nhập hoặc mật khẩu!";
                require_file_view('login', compact('error'));
                return;
            }

            // Lưu Session
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            // Chuyển về trang chủ sau khi login thành công
            header("Location: ?act=/");
            exit;
        }

        // Nếu GET thì show form login
        require_file_view('login');
    }


    // ============================================================
    //  ĐĂNG KÝ
    // ============================================================
    public function register() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? 'user';

            $errors = [];

            if ($username === '' || $password === '' || $confirm_password === '') {
                $errors[] = "Vui lòng nhập đầy đủ thông tin.";
            }

            if ($password !== $confirm_password) {
                $errors[] = "Mật khẩu xác nhận không khớp.";
            }

            if (strlen($username) < 3) {
                $errors[] = "Tên đăng nhập phải có ít nhất 3 ký tự.";
            }

            if (!in_array($role, ['user','admin'])) {
                $errors[] = "Role không hợp lệ.";
            }

            if ($errors) {
                require_file_view('register', compact('errors', 'username'));
                return;
            }

            // Model
            $model = new UserModel();

            // Kiểm tra trùng username
            if ($model->getByUsername($username)) {
                $errors[] = "Tên đăng nhập đã tồn tại!";
                require_file_view('register', compact('errors', 'username'));
                return;
            }

            // Tạo user mới
            $result = $model->create($username, $password, $role);

            if ($result) {
                header("Location: ?act=login");
                exit;
            }

            $errors[] = "Đăng ký thất bại do lỗi hệ thống.";
            require_file_view('register', compact('errors', 'username'));
            return;
        }

        require_file_view('register');
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
