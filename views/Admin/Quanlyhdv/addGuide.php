<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Thêm Hướng dẫn viên</h1>
</div>

<div style="background: white; border-radius: 0.75rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="margin-bottom: 1.5rem; padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">
                    Tên hướng dẫn viên <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" class="form-control" name="full_name" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Ngày sinh</label>
                <input type="date" class="form-control" name="birth_date">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Điện thoại <span style="color: #ef4444;">*</span>
                </label>
                <input type="tel" class="form-control" name="phone" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Tên đăng nhập <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" class="form-control" name="username" required>
                <small class="text-muted" style="font-size: 0.75rem; color: #6b7280;">Dùng để đăng nhập hệ thống</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Mật khẩu <span style="color: #ef4444;">*</span>
                </label>
                <input type="password" class="form-control" name="password" required minlength="6">
                <small class="text-muted" style="font-size: 0.75rem; color: #6b7280;">Tối thiểu 6 ký tự</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Loại hướng dẫn</label>
                <select class="form-select" name="guide_type">
                    <option value="">Chọn loại hướng dẫn</option>
                    <option value="Tiếng Anh">Tiếng Anh</option>
                    <option value="Tiếng Trung">Tiếng Trung</option>
                    <option value="Tiếng Việt">Tiếng Việt</option>
                    <option value="Tiếng Pháp">Tiếng Pháp</option>
                    <option value="Tiếng Nhật">Tiếng Nhật</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Đánh giá năng lực</label>
                <select class="form-select" name="competency_level">
                    <option value="">Chọn năng lực</option>
                    <option value="Nhân viên mới">Nhân viên mới</option>
                    <option value="Nhân viên">Nhân viên</option>
                    <option value="Chuyên viên">Chuyên viên</option>
                    <option value="Chuyên viên cao cấp">Chuyên viên cao cấp</option>
                    <option value="Quản lý">Quản lý</option>
                </select>
            </div>

            <div class="col-12">
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem;">
                    <a href="?act=guide-management" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Thêm HDV
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include "views/layout/footer.php"; ?>
