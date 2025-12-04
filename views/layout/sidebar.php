<?php $act = $_GET['act'] ?? '/'; ?>

<div class="sidebar">
    <!-- Logo Section -->
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <i class="bi bi-compass"></i>
        </div>
        <div class="sidebar-logo-text">
            <h4>Tour Manager</h4>
            <p>Admin Dashboard</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <a href="index.php" 
           class="nav-link <?php echo ($act == '/' ? 'active' : ''); ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Tổng quan</span>
        </a>

        <a href="?act=category-list" 
           class="nav-link <?php echo ($act == 'category-list' ? 'active' : ''); ?>">
            <i class="bi bi-map"></i>
            <span>Quản lý danh mục</span>
        </a>

        <a href="?act=tour-list" 
           class="nav-link <?php echo ($act == 'tour-list' ? 'active' : ''); ?>">
            <i class="bi bi-map"></i>
            <span>Quản lý Tour</span>
        </a>
         
        
        <a href="?act=booking-list" 
           class="nav-link <?php echo ($act == 'booking-list' ? 'active' : ''); ?>">
            <i class="bi bi-journal-check"></i>
            <span>Quản lý Booking</span>
        </a>

        <a href="?act=guide-management" 
           class="nav-link <?php echo ($act == 'guide-management' ? 'active' : ''); ?>">
            <i class="bi bi-person-badge"></i>
            <span>Quản lý hướng dẫn viên</span>
        </a>

        <a href="?act=customer-list" 
           class="nav-link <?php echo ($act == 'customer-list' ? 'active' : ''); ?>">
            <i class="bi bi-people"></i>
            <span>Quản lý khách hàng</span>
        </a>

        <a href="#" class="nav-link">
            <i class="bi bi-bar-chart"></i>
            <span>Thống kê</span>
        </a>

    </nav>

    <!-- User Section -->
    <div class="sidebar-user">
        <div class="sidebar-user-icon">
            <i class="bi bi-person"></i>
        </div>
        <div class="sidebar-user-info">
            <strong><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?></strong>
            <span>Quản trị viên</span>
        </div>
    </div>
</div>
