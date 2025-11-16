<?php
// File: views/HDV/hdv_dashboard.php
// (PHIÊN BẢN DỮ LIỆU CỨNG - KHÔNG CẦN BIẾN)

// Giả sử bạn dùng chung layout header/footer
require_once './views/HDV/header_hdv.php'; 
// require_once './views/layout/sidebar_hdv.php'; // (Nếu có sidebar riêng)
?>

<div class="container-fluid px-4">
    <br><br>
    
    <h2 class="mt-4 fw-bold">
        <i class="bi bi-person-badge"></i> Dashboard Hướng Dẫn Viên
    </h2>
    
    <p class="text-muted">
        Chào mừng trở lại, <strong>Trần Ngọc Hào</strong>!
    </p>
    <p>Email: ngochao@gmail.com | Phone: 0912345678</p>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary text-white rounded-circle p-3 me-3">
                        <i class="bi bi-map"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Tổng Tour được gán</p>
                        <h4 class="fw-bold">2</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success text-white rounded-circle p-3 me-3">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Đánh giá trung bình</p>
                        <h4 class="fw-bold">4.5 / 5 <i class="bi bi-star-fill text-warning"></i></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-info text-white rounded-circle p-3 me-3">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Lịch trình sắp tới</p>
                        <h4 class="fw-bold">1</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">

        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-list-task"></i> Tour được phân công
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tên Tour</th>
                                <th>Ngày đi</th>
                                <th>Điểm đến</th>
                                <th>Vai trò</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tour Đà Lạt 3 ngày 2 đêm</td>
                                <td>01/12/2025</td>
                                <td>Đà Lạt</td>
                                <td><span class="badge bg-primary">HDV Chính</span></td>
                            </tr>
                            <tr>
                                <td>Tour Sa Pa săn mây 2 ngày 1 đêm</td>
                                <td>15/12/2025</td>
                                <td>Sa Pa</td>
                                <td><span class="badge bg-info">HDV Phụ</span></td>
                            </tr>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-calendar-event"></i> Lịch làm việc
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon bg-light text-dark rounded-circle p-2 me-3">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">Họp team HDV chuẩn bị tour Tết</p>
                            <small class="text-muted">Ngày: 20/11/2025</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon bg-light text-dark rounded-circle p-2 me-3">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">Khảo sát tuyến điểm mới tại Ninh Bình</p>
                            <small class="text-muted">Ngày: 25/11/2025</small>
                        </div>
                    </div>
                    
                    </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-3 text-muted">
        © 2025 - Trang quản lý Hướng Dẫn Viên
    </footer>
</div>

<?php require_once './views/HDV/footer_hdv.php'; ?>