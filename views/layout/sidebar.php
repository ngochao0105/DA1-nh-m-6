<!-- Sidebar -->
<style>
/* === SIDEBAR MODERN STYLE === */
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
}

.sidebar-header {
    padding: 20px 10px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-header h4 {
    font-size: 20px;
    font-weight: bold;
    color: #ecf0f1;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.sidebar-header i {
    font-size: 26px;
    color: #f1c40f;
}

/* Navigation Links */
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

.nav-link i {
    font-size: 18px;
    margin-right: 12px;
    transition: 0.3s;
}

/* Hover Effect */
.nav-link:hover {
    background: rgba(255,255,255,0.15);
    color: white;
    transform: translateX(6px);
}

/* Icon Glow On Hover */
.nav-link:hover i {
    color: #1abc9c;
    transform: scale(1.1);
}

/* Active Link */
.nav-link.active {
    background: #1abc9c;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(26, 188, 156, 0.6);
}

.nav-link.active i {
    color: #fff;
}

/* Sidebar Footer */
.sidebar-footer {
    margin-top: auto;
    padding: 15px;
    text-align: center;
    color: #bdc3c7;
    font-size: 13px;
    border-top: 1px solid rgba(255,255,255,0.1);
}

/* CONTENT AREA */
.content {
    margin-left: 240px;
    padding: 25px;
    transition: all 0.3s;
}

</style>
<?php $act = $_GET['act'] ?? '/'; ?>

<div class="sidebar">
  <!-- Header -->
 <div class="sidebar-header">
        <h4><i class="bi bi-compass"></i> Tour Manager</h4>
    </div>

    <a href="index.php" 
       class="nav-link <?php echo ($act == '/' ? 'active' : ''); ?>">
       <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="?act=tour-list" 
       class="nav-link <?php echo ($act == 'tour-list' ? 'active' : ''); ?>">
       <i class="bi bi-map"></i> Quản lý Tour
    </a>

    <a href="?act=guide-management" 
       class="nav-link <?php echo ($act == 'guide-management' ? 'active' : ''); ?>">
       <i class="bi bi-person-badge"></i> Quản lý hướng dẫn viên
    </a>

</li>
  <a href="#" class="nav-link"><i class="bi bi-people"></i> Khách hàng</a>
  <a href="#" class="nav-link"><i class="bi bi-bar-chart"></i> Báo cáo</a>

  <!-- Footer -->
  <div class="sidebar-footer">
    <small>© 2025 Tour Admin</small>
  </div>
</div>

<!-- Phần nội dung -->
<div class="content" style="margin-left:240px; padding:20px;">
