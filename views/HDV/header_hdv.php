<?php
// File: views/layout/header_hdv.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xác định xem có phải trang HDV hay không
$currentAct = $_GET['act'] ?? '';
$hdvRoutes = ['hdv_dashboard', 'hdv_my_tours', 'hdv_schedule', 'hdv_profile']; // Các route của HDV
$isHdvPage = in_array($currentAct, $hdvRoutes);
?>

<!DOCTYPE html>
<html lang="vi">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HDV Panel - Quản lý Tour</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* ----------------------- GENERAL ----------------------- */
body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f8f9fa;
}

/* ----------------------- NAVBAR ----------------------- */
.navbar {
    background-color: #1a252f !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 1031;
}
.navbar-brand {
    font-size: 26px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 10px;
}
.navbar-brand i {
    font-size: 30px;
    color: #f1c40f;
}
.user-box {
    background: #2c3e50;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #ffffff;
    transition: 0.2s;
}
.user-box:hover { background: #3d566e; }
.user-dropdown {
    position: absolute;
    top: 58px;
    right: 15px;
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #dcdcdc;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    width: 180px;
    display: none;
    animation: fadeIn 0.15s ease-out;
}
.user-dropdown a { display: block; padding: 10px 15px; color: #333; text-decoration: none; font-weight: 500; }
.user-dropdown a:hover { background: #f2f2f2; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ----------------------- HDV SIDEBAR ----------------------- */
<?php if ($isHdvPage): ?>
.sidebar {
    height: 100vh;
    width: 240px;
    background: linear-gradient(180deg, #1f2d3d, #2c3e50);
    position: fixed;
    top: 0;
    left: 0;
    color: #ecf0f1;
    display: flex;
    flex-direction: column;
    padding-top: 10px;
    box-shadow: 2px 0 10px rgba(0,0,0,0.25);
    z-index: 1030;
}
.nav-link {
    color: #bdc3c7;
    display: flex;
    align-items: center;
    padding: 12px 18px;
    margin: 6px 12px;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.25s ease-in-out;
    background: rgba(255,255,255,0.03);
}
.nav-link:hover {
    background: rgba(255,255,255,0.15);
    color: white;
    transform: translateX(6px);
}
.nav-link.active {
    background: #1abc9c;
    color: #fff;
    font-weight: 600;
}
.nav-link i {
    font-size: 18px;
    margin-right: 12px;
}

.content {
    margin-left: 250px; 
    padding: 20px;
}
<?php endif; ?>
</style>

</head>
<body>

<nav class="navbar navbar-dark fixed-top">
  <div class="container-fluid">

    <a class="navbar-brand text-white" href="?act=hdv_dashboard">
      <i class="bi bi-person-badge"></i> HDV Panel
    </a>

    <?php if(isset($_SESSION['username'])): ?>
    <div class="user-box" onclick="toggleUserDropdown()">
        <i class="bi bi-person-circle" style="font-size:20px;"></i>
        <span><?php echo $_SESSION['username']; ?></span>
    </div>

    <div class="user-dropdown" id="userDropdown">
        <a href="?act=logout">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </div>
    <?php endif; ?>

  </div>
</nav>


<?php
if ($isHdvPage) {
    // SỬA LẠI ĐƯỜNG DẪN NÀY:
    require_once './views/HDV/sidebar_hdv.php';
}
?>

<?php
$contentClass = $isHdvPage ? 'content' : '';
echo "<div class='{$contentClass}'>";
?>

<script>
function toggleUserDropdown() {
    let dropdown = document.getElementById("userDropdown");
    dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
}
document.addEventListener("click", function(e) {
    const userBox = document.querySelector(".user-box");
    const dropdown = document.getElementById("userDropdown");
    if (userBox && dropdown && !userBox.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = "none";
    }
});
</script>