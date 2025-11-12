<?php require_once './views/layout/header.php'; // Gọi header ?>

<div class="container" style="padding: 20px;">
    <h2>Đăng ký</h2>

    <!-- Hiển thị lỗi nếu có -->
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- action trỏ về ?act=register -->
    <form action="?act=register" method="POST">
        <div>
            <label>Tên đăng nhập:</label>
            <input type="text" name="username" required>
        </div>
        <div style="margin-top: 10px;">
            <label>Mật khẩu:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" style="margin-top: 15px;">Đăng ký</button>
    </form>
    
    <p>Đã có tài khoản? <a href="?act=login">Đăng nhập</a></p>
</div>

<?php require_once './views/layout/footer.php'; // Gọi footer ?>