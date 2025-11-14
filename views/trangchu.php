<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="container-fluid px-4">
<br>
<br>
    <!-- Tiêu đề -->
    <h2 class="mt-4 fw-bold">
        <i class="bi bi-speedometer2"></i> Dashboard Admin
    </h2>
    <p class="text-muted">Chào mừng trở lại, Quản Trị Viên!</p>

    <!-- 4 ô thống kê -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary text-white rounded-circle p-3 me-3">
                        <i class="bi bi-map"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Tổng số Tour</p>
                       <p class="card-text fs-4"><?= $totalTour ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success text-white rounded-circle p-3 me-3">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Tổng Booking</p>
                        <h4 class="fw-bold">24</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-info text-white rounded-circle p-3 me-3">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Hướng dẫn viên</p>
                     <p class="card-text fs-4"><?= $totalHDV ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning text-white rounded-circle p-3 me-3">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Doanh thu tháng</p>
                        <h4 class="fw-bold">4000$</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 cột chính -->
    <div class="row g-4 mb-5">

        <!-- Hoạt động gần đây -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-clock-history"></i> Hoạt động gần đây
                </div>
                <div class="card-body">

                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <div>
                            <p class="mb-0">Booking mới #1234</p>
                            <small class="text-muted">5 phút trước</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-plus-circle text-primary me-2"></i>
                        <div>
                            <p class="mb-0">Tour mới được tạo</p>
                            <small class="text-muted">1 giờ trước</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-plus text-info me-2"></i>
                        <div>
                            <p class="mb-0">HDV mới được thêm</p>
                            <small class="text-muted">2 giờ trước</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Thông báo -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-bell"></i> Thông báo
                </div>
                <div class="card-body">

                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Tour cần HDV:</strong> 3 tour chưa có HDV phụ trách
                    </div>

                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Booking chờ xác nhận:</strong> 12 booking đang chờ xử lý
                    </div>

                    <div class="alert alert-success d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Hệ thống:</strong> Tất cả dịch vụ hoạt động bình thường
                    </div>

                </div>
            </div>
        </div>

    </div>

    <footer class="text-center py-3 text-muted">
        © 2025 - Quản lý Tour Du Lịch
    </footer>

</div>

<?php include "views/layout/footer.php"; ?>
