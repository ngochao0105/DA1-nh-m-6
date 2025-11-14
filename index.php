<?php
// 1. Require file Common (để có session_start() và checkAuth())
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ (CÓ checkAuth())

// Require toàn bộ file Controllers
require_once './controllers/TourController.php';
require_once './controllers/GuideController.php';



// Require toàn bộ file Models
require_once './models/TourModel.php';
require_once './models/GuideModel.php';
// Route
$act = $_GET['act'] ?? '/';

// 3. (!!!) BẢO VỆ ROUTE (!!!)
// Đây là "người gác cổng" chính
// Liệt kê TẤT CẢ các route KHÔNG cần đăng nhập

$publicRoutes = [
    'login', 
    'register'
    // (Nếu bạn có trang 'tour-list' công khai, hãy thêm vào đây)
    // 'tour-list',
    // 'tour-category',
];

// Nếu $act KHÔNG nằm trong danh sách public


// 4. Require Controllers & Models
// Code ở đây chỉ chạy NẾU ĐÃ VƯỢT QUA checkAuth()
require_once './controllers/TourController.php';
require_once './controllers/AuthController.php';

require_once './models/TourModel.php';
require_once './models/UserModel.php';

// 5. Routing
// Để bảo bảo tính chất chỉ gọi 1 hàm Controller...
match ($act) {
    // Trang chủ
    '/' => (new TourController())->Home(),
    'tour-list' => (new TourController())->TourList(),
    'createtour' =>(new TourController())->CreateTour(),
    'deletetour' =>(new TourController())->DeleteTour(),
    'edittour' =>(new TourController())->EditTour(),

    'guide-management' => (new GuideController())->GuideManagement(),
    'delete-guide' => (new GuideController())->deleteGuide(), 
    'add-guide' => (new GuideController())->addGuide(),
     'edit-guide' => (new GuideController())->editGuide(),
    
    // Auth routes
    'login' => (new AuthController())->login(),
    'register' => (new AuthController())->register(),
    'logout' => (new AuthController())->logout(),
    
    // Default
    default => (new TourController())->Home()
};