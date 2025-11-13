<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xác định xem có phải trang admin hay không
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

/* ----------------------- USER BOX ----------------------- */
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

.user-box:hover {
    background: #3d566e;
}

/* Dropdown */
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

.user-dropdown a {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
}

.user-dropdown a:hover {
    background: #f2f2f2;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}


/* ----------------------- ADMIN SIDEBAR ----------------------- */
<?php if ($isAdminPage): ?>
.sidebar {
    height: 100vh;
    width: 240px;
    background-color: #2c3e50;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 70px;
    color: #ecf0f1;
    transition: all 0.3s ease;
    z-index: 1030;
}

.nav-link {
    color: #bdc3c7;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    border-radius: 6px;
    margin: 4px 10px;
    text-decoration: none;
    transition: 0.3s;
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
    padding: 20px;
}
<?php endif; ?>
</style>

</head>
<body>

<!-- ======================== NAVBAR ======================== -->
<nav class="navbar navbar-dark fixed-top">
  <div class="container-fluid">

    <!-- Logo -->
    <a class="navbar-brand text-white" href="index.php">
      <i class="bi bi-compass"></i> Admin Panel
    </a>

    <!-- User Box -->
    <?php if(isset($_SESSION['username'])): ?>
    <div class="user-box" onclick="toggleUserDropdown()">
        <i class="bi bi-person-circle" style="font-size:20px;"></i>
        <span><?php echo $_SESSION['username']; ?></span>
    </div>

    <!-- Dropdown -->
    <div class="user-dropdown" id="userDropdown">
        <a href="?act=logout">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </div>
    <?php endif; ?>

  </div>
</nav>


<!-- ======================== SIDEBAR ADMIN ======================== -->
<?php
if ($isAdminPage) {
    require_once './views/layout/sidebar.php';
}
?>

<!-- ======================== OPEN CONTENT WRAPPER ======================== -->
<?php
$contentClass = $isAdminPage ? 'content' : '';
echo "<div class='{$contentClass}'>";
?>

<!-- ======================== JAVASCRIPT ======================== -->
<script>
// Toggle dropdown user
function toggleUserDropdown() {
    let dropdown = document.getElementById("userDropdown");
    dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
}

// Click ra ngoài sẽ đóng dropdown
document.addEventListener("click", function(e) {
    const userBox = document.querySelector(".user-box");
    const dropdown = document.getElementById("userDropdown");

    if (!userBox.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = "none";
    }
});
</script>
