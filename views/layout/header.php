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
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    /* ===== HEADER ===== */
    .navbar {
      background-color: #1a252f !important;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
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

    /* ===== SIDEBAR ===== */
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

    .content {
      margin-left: 250px;
      padding: 20px;
    }
  </style>
</head>
<body>

<!-- ===== HEADER / NAVBAR ===== -->
<nav class="navbar navbar-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="index.php">
      <i class="bi bi-compass"></i> Tour Manager
    </a>
  </div>
</nav>
