<?php $act = $_GET['act'] ?? 'hdv_dashboard'; ?>

<div class="sidebar">
    <!-- Logo Section -->
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <i class="bi bi-person-badge"></i>
        </div>
        <div class="sidebar-logo-text">
            <h4>Tour Manager</h4>
            <p>HDV Dashboard</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <a href="?act=hdv_dashboard" 
           class="nav-link <?php echo ($act == 'hdv_dashboard' ? 'active' : ''); ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Trang của tôi</span>
        </a>

        <a href="?act=hdv_my_tours" 
           class="nav-link <?php echo ($act == 'hdv_my_tours' ? 'active' : ''); ?>">
            <i class="bi bi-map"></i>
            <span>Tour được phân công</span>
        </a>
        
 <a href="?act=hdv_tour_schedule" 
           class="nav-link <?php echo ($act == 'hdv_tour_schedule' ? 'active' : ''); ?>">
            <i class="bi bi-calendar"></i>
            <span>Lịch trình tour</span>
        </a>
    </nav>

    <!-- User Section -->
    <div class="sidebar-user">
        <div class="sidebar-user-icon">
            <i class="bi bi-person"></i>
        </div>
        <div class="sidebar-user-info">
            <strong><?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : (isset($_SESSION['username']) ? $_SESSION['username'] : 'HDV'); ?></strong>
            <span>Hướng dẫn viên</span>
        </div>
    </div>
</div>
