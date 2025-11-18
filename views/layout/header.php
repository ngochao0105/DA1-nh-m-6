<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentAct = $_GET['act'] ?? '/';
$isAdminPage = str_starts_with($currentAct, 'admin');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý Tour Du Lịch</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* ====================== GENERAL ====================== */
body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f6f9;
    padding-top: 60px; 
    overflow-x: hidden;
}

/* ====================== NAVBAR (HEADER) ====================== */
.navbar {
    background: linear-gradient(135deg, #1f2b38, #243447) !important;
    height: 60px;
    display: flex;
    align-items: center;
    padding: 0 20px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    z-index: 1050;
}

.navbar-brand {
    font-size: 20px;
    font-weight: 700;
    color: #ffffff !important;
    display: flex;
    align-items: center;
    gap: 10px;
}

.navbar-brand i {
    font-size: 26px;
    color: #00d9ff;
    text-shadow: 0 0 6px rgba(0,217,255,0.6);
}

/* ====================== USER BOX ====================== */
.user-box {
    background: rgba(255,255,255,0.08);
    padding: 8px 14px;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ffffff;
    border: 1px solid rgba(255,255,255,0.15);
    transition: 0.25s ease;
}

.user-box:hover {
    background: rgba(255,255,255,0.15);
    transform: translateY(-1px);
}

.user-box i {
    transition: 0.25s;
}

/* ====================== USER DROPDOWN ====================== */
.user-dropdown {
    position: absolute;
    top: 65px;
    right: 25px;
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    width: 210px;
    display: none;
    box-shadow: 0 6px 14px rgba(0,0,0,0.18);
    animation: fadeIn 0.15s ease-out;
}

.user-dropdown a {
    display: flex;
    padding: 12px 18px;
    align-items: center;
    gap: 10px;
    color: #333;
    font-weight: 500;
    border-bottom: 1px solid #f0f0f0;
    transition: 0.2s;
    text-decoration: none;
}

.user-dropdown a:hover {
    background: #f7f7f7;
}

.logout-btn {
    background: #ffeaea;
    color: #c0392b !important;
}

.logout-btn:hover {
    background: #ffd1d1 !important;
}

/* Fade animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ====================== SIDEBAR ====================== */
<?php if ($isAdminPage): ?>
.sidebar {
    position: fixed;
    top: 60px; 
    left: 0;
    width: 240px;
    height: calc(100vh - 60px);
    background-color: #2c3e50;
    padding-top: 20px;
    overflow-y: auto;
    z-index: 1000;
}

.nav-link {
    color: #bdc3c7;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    border-radius: 6px;
    margin: 4px 10px;
    text-decoration: none;
    transition: 0.25s;
}

.nav-link:hover {
    background-color: #34495e;
    color: #fff;
    transform: translateX(4px);
}

.nav-link.active {
    background-color: #1abc9c;
    color: #fff;
}

.content {
    margin-left: 250px;
    padding: 25px;
}
<?php endif; ?>
</style>

</head>
<body>

<!-- ====================== NAVBAR ====================== -->
<nav class="navbar navbar-dark fixed-top">
  <div class="container-fluid">

    <a class="navbar-brand" href="index.php">
      <i class="bi bi-compass"></i> Admin Panel
    </a>

    <?php if(isset($_SESSION['username'])): ?>
    <div class="user-box" onclick="toggleUserDropdown()">

        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username']); ?>&background=34495e&color=fff&size=50"
             width="32" height="32" alt="avatar">

        <span><?php echo $_SESSION['username']; ?></span>
        <i class="bi bi-chevron-down"></i>
    </div>

    <div class="user-dropdown" id="userDropdown">
        <a href="?act=admin_profile">
            <i class="bi bi-person"></i> Hồ sơ quản trị
        </a>

        <a href="?act=logout" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </div>
    <?php endif; ?>

  </div>
</nav>


<!-- ====================== SIDEBAR ====================== -->
<?php if ($isAdminPage): ?>
    <?php include "views/layout/sidebar.php"; ?>
<?php endif; ?>


<!-- ====================== CONTENT WRAPPER ====================== -->
<div class="<?= $isAdminPage ? 'content' : '' ?>">

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
