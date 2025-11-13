<!-- Sidebar -->
<style>
  .sidebar {
    height: 100vh;
    width: 240px;
    background-color: #2c3e50;
    position: fixed;
    top: 0;
    left: 0;
    color: #ecf0f1;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
  }

  .sidebar-header {
    padding: 20px 10px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }

  .sidebar-header h4 {
    font-size: 22px;
    font-weight: bold;
    color: #fff;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }

  .sidebar-header i {
    font-size: 26px;
    color: #f1c40f;
  }

  .nav-link {
    color: #bdc3c7;
    display: flex;
    align-items: center;
    padding: 12px 20px;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 6px;
    margin: 4px 10px;
  }

  .nav-link i {
    font-size: 18px;
    margin-right: 10px;
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

  .sidebar-footer {
    margin-top: auto;
    padding: 15px;
    font-size: 14px;
    color: #95a5a6;
    text-align: center;
    border-top: 1px solid rgba(255,255,255,0.1);
  }
</style>

<div class="sidebar">
  <hr>
  <hr>
  <br>
  <!-- Header -->
  <div class="sidebar-header">
    <h4><i class="bi bi-compass"></i> Tour Manager</h4>
  </div>
  

  <!-- Navigation -->
  <a href="index.php" class="nav-link active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="?act=tour-category" class="nav-link"><i class="bi bi-map"></i> Quản lý Tour</a>

 <li class="nav-item">
  <a class="nav-link" href="?act=guide-management">
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
