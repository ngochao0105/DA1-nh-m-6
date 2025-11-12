<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Bắt đầu session đúng cách
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Kết nối CSDL qua PDO
function connectDB() {
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
        return $conn;
    } catch (PDOException $e) {
        echo ("Connection failed: " . $e->getMessage());
    }
}

function uploadFile($file, $folderSave){
    $file_upload = $file;
    $pathStorage = $folderSave . rand(10000, 99999) . $file_upload['name'];

    $tmp_file = $file_upload['tmp_name'];
    $pathSave = PATH_ROOT . $pathStorage; // Đường dãn tuyệt đối của file

    if (move_uploaded_file($tmp_file, $pathSave)) {
        return $pathStorage;
    }
    return null;
}

function deleteFile($file){
    $pathDelete = PATH_ROOT . $file;
    if (file_exists($pathDelete)) {
        unlink($pathDelete); // Hàm unlink dùng để xóa file
    }
}
function get_db_connection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        return new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
    } catch (PDOException $e) {
        // Nếu kết nối lỗi, hiển thị thông báo
        die("Lỗi kết nối CSDL: " . $e->getMessage());
    }
}
function require_file_view($viewName) {
    // Đường dẫn tương đối từ file index.php (file gốc chạy dự án)
    $path = './views/' . $viewName . '.php';

    if (file_exists($path)) {
        require_once $path;
    } else {
        echo "Lỗi: Không tìm thấy file view tại đường dẫn: $path";
    }
}
function checkAuth() {
    // session_start() đã được gọi ở file index.php (gốc)
    
    if (!isset($_SESSION['user_id'])) {
        // Nếu CHƯA đăng nhập, lập tức chuyển hướng
        // về trang login và dừng mọi mã khác.
        header('Location: ?act=login');
        exit;
    }
}
