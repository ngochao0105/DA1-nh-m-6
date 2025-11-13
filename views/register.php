<?php require_once './views/layout/header.php'; ?>

<style>
/* === FORM REGISTER ĐẸP – CHUẨN ADMIN PANEL === */

.register-wrapper {
    max-width: 420px;
    margin: 100px auto;
    padding: 30px 25px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

.register-wrapper h2 {
    text-align: center;
    margin-bottom: 20px;
    font-weight: 600;
}

.register-wrapper label {
    font-weight: 500;
}

.register-wrapper button {
    width: 100%;
    font-weight: 600;
    padding: 10px;
}

.register-wrapper .link {
    text-align: center;
    margin-top: 12px;
}
</style>


<div class="register-wrapper">

    <h2>Đăng ký tài khoản</h2>

    <!-- Hiển thị lỗi -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): ?>
                <div><?php echo $e; ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="?act=register" method="POST">

        <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input type="text" 
                   name="username" 
                   class="form-control"
                   required 
                   value="<?php echo $username ?? ''; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nhập lại mật khẩu</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>


        <button type="submit" class="btn btn-primary mt-2">Đăng ký</button>

        <div class="link">
            <p>Đã có tài khoản? <a href="?act=login">Đăng nhập</a></p>
        </div>
    </form>

</div>

<?php require_once './views/layout/footer.php'; ?>
