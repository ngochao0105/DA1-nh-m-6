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
    background-color: #f8f9fa;
    padding-top: 70px;    /* 🚀 FIX NAVBAR CHE TRANG */
    overflow-x: hidden;
}

/* ====================== NAVBAR ====================== */
.navbar {
    background-color: #1a252f !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    height: 60px;
}

.navbar-brand {
    font-size: 22px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 10px;
}

.navbar-brand i {
    font-size: 28px;
    color: #f1c40f;
}

/* ====================== USER BOX ====================== */
.user-box {
    background: #2c3e50;
    padding: 8px 14px;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ffffff;
    transition: 0.2s;
}

.user-box:hover {
    background: #3d566e;
}

.user-dropdown {
    position: absolute;
    top: 70px;
    right: 20px;
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #dcdcdc;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    width: 210px;
    display: none;
    animation: fadeIn 0.15s ease-out;
}

.user-dropdown a {
    display: flex;
    padding: 12px 16px;
    gap: 10px;
    align-items: center;
    color: #333;
    text-decoration: none;
    font-weight: 500;
}

.logout-btn {
    background: #ffe6e6;
    color: #c0392b !important;
}

.logout-btn:hover {
    background: #ffd6d6 !important;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-4px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ====================== SIDEBAR ====================== */
<?php if ($isAdminPage): ?>
.sidebar {
    position: fixed;
    top: 60px; /* 🚀 ĐÂY LÀ FIX QUAN TRỌNG */
    left: 0;
    width: 240px;
    height: calc(100vh - 60px); /* Không bị đè */
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

/* Nội dung đẩy qua phải */
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

    <a class="navbar-brand text-white" href="index.php">
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


<!-- ====================== OPEN CONTENT WRAPPER ====================== -->
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
