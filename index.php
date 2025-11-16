<?php
// 1. Require file Common (để có session_start() và checkAuth())
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ (CÓ checkAuth() và require_file_view())

// 2. Require toàn bộ file Models (Tải 1 lần ở đầu)
require_once './models/UserModel.php';
require_once './models/TourModel.php';
require_once './models/GuideModel.php';
require_once './models/HdvModel.php'; // <-- THÊM MỚI

// 3. Require toàn bộ file Controllers (Tải 1 lần ở đầu)
require_once './controllers/AuthController.php';
require_once './controllers/TourController.php';
require_once './controllers/GuideController.php';
require_once './controllers/HdvController.php'; // <-- THÊM MỚI

// 4. Route
$act = $_GET['act'] ?? '/';

// 5. Xác định các route công khai (không cần đăng nhập)
$publicRoutes = [
    'login',
    'register'
    // (Nếu bạn có trang 'tour-list' công khai, hãy thêm vào đây)
];

// 6. Kiểm tra Auth
// Nếu $act KHÔNG nằm trong $publicRoutes, thì yêu cầu đăng nhập
if (!in_array($act, $publicRoutes)) {
    checkAuth(); // Hàm này kiểm tra xem CÓ đăng nhập hay không
}

// 7. Routing (Điều hướng)
match ($act) {
    // === Auth routes (Công khai) ===
    'login' => (new AuthController())->login(),

    // === Route chung (Phải đăng nhập) ===
    'logout' => (new AuthController())->logout(),

    // === Admin Routes (Đã được checkAuth() bảo vệ) ===
    // (Lưu ý: HDV vẫn có thể vào các link này. Xem "Bước 4" bên dưới)
    '/' => (new TourController())->Home(), // Trang chủ Admin
    'tour-list' => (new TourController())->TourList(),
    'createtour' => (new TourController())->CreateTour(),
    'deletetour' => (new TourController())->DeleteTour(),
    'edit-tour' => (new TourController())->EditTour(),

    'guide-management' => (new GuideController())->GuideManagement(),
    'delete-guide' => (new GuideController())->deleteGuide(),
    'add-guide' => (new GuideController())->addGuide(),
    'edit-guide' => (new GuideController())->editGuide(),

    // === HDV Route (MỚI - Đã được checkAuth() bảo vệ) ===
    'hdv_dashboard' => (new HdvController())->dashboard(), // <-- TUYẾN ĐƯỜNG MỚI
'hdv_my_tours' => (new HdvController())->myTours(),
    // === Default ===
    default => (new TourController())->Home()
};