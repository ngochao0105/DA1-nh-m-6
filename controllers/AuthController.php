<?php

// controllers/AuthController.php
class AuthController {
    // GET: form | POST: xử lý
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $error = null;

            if ($username === '' || $password === '') {
                $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
            } else {
                $userModel = new UserModel();
                $user = $userModel->getByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role']     = $user['role'];

                    if ($user['role'] === 'admin') {
                        header('Location: ?act=/');
                    } else {
                        header('Location: ?act=admin');
                    }
                    exit;
                } else {
                    $error = "Sai tên đăng nhập hoặc mật khẩu!";
                }
            }
            require_file_view('login', compact('error'));
            return;
        }

        // GET
        require_file_view('login');
    }

    // GET: form | POST: xử lý
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username         = trim($_POST['username'] ?? '');
            $password         = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $role             = $_POST['role'] ?? 'hdv'; // cho phép chọn, mặc định 'hdv'
            $errors = [];

            if ($username === '' || $password === '' || $confirm_password === '')
                $errors[] = "Vui lòng nhập đầy đủ các trường.";
            if (strlen($username) < 3 || strlen($username) > 50)
                $errors[] = "Tên đăng nhập phải từ 3–50 ký tự.";
            if ($password !== $confirm_password)
                $errors[] = "Xác nhận mật khẩu không khớp.";
            if (strlen($password) < 6)
                $errors[] = "Mật khẩu tối thiểu 6 ký tự.";
            if (!in_array($role, ['admin','hdv'], true))
                $errors[] = "Role không hợp lệ.";

            if ($errors) {
                require_file_view('register', ['error' => $errors, 'username' => $username, 'role' => $role]);
                return;
            }

            $userModel = new UserModel();
            if ($userModel->create($username, $password, $role)) {
                header('Location: ?act=login'); exit;
            }
            $errors[] = "Đăng ký thất bại! Username đã tồn tại hoặc lỗi hệ thống.";
            require_file_view('register', ['error' => $errors, 'username' => $username, 'role' => $role]);
            return;
        }

        // GET
        require_file_view('register');
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ?act=login'); exit;
    }
}
