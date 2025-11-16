<?php
// File: views/HDV/sidebar_hdv.php

// Lấy "act" từ URL để xác định trang
$act = $_GET['act'] ?? 'hdv_dashboard'; 
?>

<div class="sidebar">
  <div class="sidebar-header" style="padding: 20px 10px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <h4 style="font-size: 20px; font-weight: bold; color: #ecf0f1; text-transform: uppercase; letter-spacing: 1px;">
            <i class="bi bi-person-badge" style="font-size: 26px; color: #f1c40f;"></i> HDV Panel
        </h4>
    </div>

    <a href="?act=hdv_dashboard" 
       class="nav-link <?php echo ($act == 'hdv_dashboard' ? 'active' : ''); ?>">
       <i class="bi bi-speedometer2"></i> Trang của tôi
    </a>

    <a href="?act=hdv_my_tours" 
       class="nav-link <?php echo ($act == 'hdv_my_tours' ? 'active' : ''); ?>">
       <i class="bi bi-map"></i> Tour được phân công
    </a>
    
    <a href="?act=hdv_schedule" 
       class="nav-link <?php echo ($act == 'hdv_schedule' ? 'active' : ''); ?>">
       <i class="bi bi-calendar-check"></i> Lịch làm việc
    </a>

    <a href="?act=hdv_profile" 
       class="nav-link <?php echo ($act == 'hdv_profile' ? 'active' : ''); ?>">
       <i class="bi bi-person-circle"></i> Hồ sơ cá nhân
    </a>

  <div class="sidebar-footer" style="margin-top: auto; padding: 15px; text-align: center; color: #bdc3c7; font-size: 13px; border-top: 1px solid rgba(255,255,255,0.1);">
    <small>© 2025 HDV Panel</small>
  </div>
</div>