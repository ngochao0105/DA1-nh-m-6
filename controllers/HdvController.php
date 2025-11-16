<?php
// File: controllers/HdvController.php

class HdvController
{

    public function dashboard()
    {

        // ============================================================
        //  BẢO VỆ: KIỂM TRA QUYỀN TRUY CẬP
        // ============================================================
        // Kiểm tra xem người dùng đã đăng nhập và có đúng vai trò 'hdv' không
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            // Nếu không đúng, đá về trang đăng nhập
            session_unset();
            session_destroy();
            header("Location: ?act=login");
            exit;
        }

        // ============================================================
        //  LẤY DỮ LIỆU
        // ============================================================
        $hdvModel = new HdvModel();

        // 1. Lấy thông tin hồ sơ (full_name, email, ...) của HDV
        //    Lưu ý: $_SESSION['user_id'] là ID từ bảng 'taikhoan'
        $hdvProfile = $hdvModel->getHdvInfoByAccountId($_SESSION['user_id']);
        $_SESSION['full_name'] = $hdvProfile['full_name'];
        $_SESSION['email']     = $hdvProfile['email'];
        $_SESSION['phone']     = $hdvProfile['phone'];
          $_SESSION['average_rating']     = $hdvProfile['average_rating'];

        // Nếu không tìm thấy hồ sơ (tài khoản có role 'hdv' nhưng chưa liên kết bảng nhansu)
        if (!$hdvProfile) {
            // Hiển thị lỗi hoặc đăng xuất
            echo "Lỗi: Không tìm thấy hồ sơ nhân sự cho tài khoản này.";
            exit;
        }

        // 2. Lấy ID của nhân sự (từ bảng 'nhansu')
        $hdv_id = $hdvProfile['id'];

        // 3. Lấy các tour đã được phân công
        $assignedTours = $hdvModel->getAssignedTours($hdv_id);

        // 4. Lấy lịch làm việc
        $schedule = $hdvModel->getWorkSchedule($hdv_id);

        // ============================================================
        //  HIỂN THỊ VIEW
        // ============================================================
        // Gửi các biến ($hdvProfile, $assignedTours, $schedule) sang view
        require_file_view('HDV/hdv_dashboard', compact(
            'hdvProfile',
            'assignedTours',
            'schedule'
        ));
    }
    public function myTours()
    {

        // 1. Bảo vệ: Kiểm tra xem có phải HDV không
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            header("Location: ?act=login");
            exit;
        }

        // 2. Lấy dữ liệu từ Model
        $hdvModel = new HdvModel();

        // Lấy ID tài khoản từ session
        $taikhoan_id = $_SESSION['user_id'];

        // Lấy hồ sơ nhân sự (để lấy hdv_id)
        $hdvProfile = $hdvModel->getHdvInfoByAccountId($taikhoan_id);

        if (!$hdvProfile) {
            // Lỗi này không nên xảy ra nếu bạn đã liên kết DB chính xác
            echo "Lỗi: Không tìm thấy hồ sơ nhân sự.";
            exit;
        }

        // Lấy ID nhân sự (ví dụ: 1, 2, 3...)
        $hdv_id = $hdvProfile['id'];

        // Lấy các tour đã gán (Dùng lại hàm từ dashboard)
        $assignedTours = $hdvModel->getAssignedTours($hdv_id);

        // 3. Hiển thị View và gửi dữ liệu sang
        require_file_view('HDV/hdv_my_tours', compact('assignedTours'));
    }
}
