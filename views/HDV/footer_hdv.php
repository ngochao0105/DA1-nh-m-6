<?php
// File: views/HDV/footer_hdv.php
?>

</div> <footer class="footer bg-dark text-white text-center py-3 mt-4">
  <div class="container">
    © 2025 - Trang Hướng Dẫn Viên
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
  /* CSS này giữ cho footer nằm cố định ở dưới
     và không bị sidebar đè lên */
  .footer {
    position: fixed;
    bottom: 0;
    left: 240px; /* Bằng chiều rộng sidebar (width: 240px) */
    width: calc(100% - 240px); /* Bằng 100% chiều rộng trừ đi sidebar */
    z-index: 10;
  }
</style>

</body>
</html>