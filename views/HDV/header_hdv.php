<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentAct = $_GET['act'] ?? '/';
$isHdvPage = str_starts_with($currentAct, 'hdv');

// Get current date in Vietnamese
$days = ['Chủ nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
$months = ['', 'tháng 1', 'tháng 2', 'tháng 3', 'tháng 4', 'tháng 5', 'tháng 6', 
           'tháng 7', 'tháng 8', 'tháng 9', 'tháng 10', 'tháng 11', 'tháng 12'];
$dayName = $days[date('w')];
$day = date('d');
$month = $months[date('n')];
$year = date('Y');
$currentDate = "$dayName, $day $month, $year";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HDV Panel - Hệ thống quản lý tour du lịch</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Global Styles -->
    <link href="views/layout/assets/style.css" rel="stylesheet">
</head>
<body>

<!-- ====================== HEADER ====================== -->
<header class="header">
    <div class="header-left">
        <button class="header-menu-toggle" onclick="toggleSidebar()" id="menuToggle">
            <i class="bi bi-list"></i>
        </button>
        <div class="header-greeting">
            <h3>Chào mừng trở lại!</h3>
            <p><?= $currentDate ?></p>
        </div>
    </div>
    
    <div class="header-right">
        <div class="header-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Tìm kiếm...">
        </div>
        
        <div class="header-notification">
            <button class="header-icon-btn" title="Thông báo">
                <i class="bi bi-bell"></i>
                <span class="header-notification-badge">3</span>
            </button>
        </div>
        
        <?php if(isset($_SESSION['username'])): ?>
        <div class="header-user" onclick="toggleUserDropdown()">
            <div class="header-user-avatar">
                <?= strtoupper(substr($_SESSION['full_name'] ?? $_SESSION['username'], 0, 1)) ?>
            </div>
            <div class="header-user-info">
                <strong><?php echo $_SESSION['full_name'] ?? $_SESSION['username']; ?></strong>
                <span>Hướng dẫn viên</span>
            </div>
        </div>
        
        <div class="user-dropdown" id="userDropdown" style="display: none;">
            <a href="?act=hdv_profile">
                <i class="bi bi-person"></i> Hồ sơ cá nhân
            </a>
            <a href="?act=logout" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
            </a>
        </div>
        <?php endif; ?>
    </div>
</header>

<!-- ====================== MAIN CONTENT ====================== -->
<div class="main-content">

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const menuToggle = document.getElementById('menuToggle');
    
    if (sidebar) {
        sidebar.classList.toggle('open');
        const icon = menuToggle.querySelector('i');
        if (sidebar.classList.contains('open')) {
            icon.classList.remove('bi-list');
            icon.classList.add('bi-x-lg');
        } else {
            icon.classList.remove('bi-x-lg');
            icon.classList.add('bi-list');
        }
    }
}

function toggleUserDropdown() {
    let dropdown = document.getElementById("userDropdown");
    dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
}

document.addEventListener("click", function(e) {
    const userBox = document.querySelector(".header-user");
    const dropdown = document.getElementById("userDropdown");
    const sidebar = document.querySelector('.sidebar');
    const menuToggle = document.getElementById('menuToggle');

    if (userBox && dropdown && !userBox.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = "none";
    }

    if (window.innerWidth <= 768 && sidebar && menuToggle) {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target) && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            const icon = menuToggle.querySelector('i');
            icon.classList.remove('bi-x-lg');
            icon.classList.add('bi-list');
        }
    }
});

window.addEventListener('resize', function() {
    const sidebar = document.querySelector('.sidebar');
    const menuToggle = document.getElementById('menuToggle');
    
    if (window.innerWidth > 768 && sidebar) {
        sidebar.classList.remove('open');
        if (menuToggle) {
            const icon = menuToggle.querySelector('i');
            icon.classList.remove('bi-x-lg');
            icon.classList.add('bi-list');
        }
    }
});
</script>
