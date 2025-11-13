<?php require_once './views/layout/header.php'; ?>

<style>
/* === FORM LOGIN ĐẸP – GIỮ THEO TONE ADMIN PANEL === */

.login-wrapper {
    max-width: 420px;
    margin: 100px auto;
    padding: 30px 25px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

.login-wrapper h2 {
    text-align: center;
    margin-bottom: 20px;
    font-weight: 600;
}

.login-wrapper label {
    font-weight: 500;
}

.login-wrapper button {
    width: 100%;
    font-weight: 600;
    padding: 10px;
}

.login-wrapper .link {
    text-align: center;
    margin-top: 12px;
}
</style>

<div class="login-wrapper">

    <h2>Đăng nhập</h2>

    <!-- Hiển thị lỗi -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="?act=login" method="POST">

        <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Đăng nhập</button>

        <div class="link">
            <p>Chưa có tài khoản? <a href="?act=register">Đăng ký ngay</a></p>
        </div>

    </form>

</div>

<?php require_once './views/layout/footer.php'; ?>
