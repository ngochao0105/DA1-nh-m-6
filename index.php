<?php 
// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/TourController.php';
require_once './controllers/CategoryController.php';




// Require toàn bộ file Models
require_once './models/TourModel.php';
require_once './models/CategoryModel.php';



// Route
$act = $_GET['act'] ?? '/';




// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match    


    // Trang chủ
   match ($act) {
    // Trang chủ
    '/'=>(new TourController())->Home(),
    'tour-list' =>(new TourController())->TourList(),
    'tour-category' =>(new CategoryController())->TourCategory(),
    

};