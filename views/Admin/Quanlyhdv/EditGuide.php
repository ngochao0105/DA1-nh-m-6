<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Sửa Hướng dẫn viên</h1>
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
                <input type="text" class="form-control" name="full_name" required
                       value="<?= htmlspecialchars($guide['full_name'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Ngày sinh</label>
                <input type="date" class="form-control" name="birth_date"
                       value="<?= htmlspecialchars($guide['birth_date'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Điện thoại <span style="color: #ef4444;">*</span>
                </label>
                <input type="tel" class="form-control" name="phone" required
                       value="<?= htmlspecialchars($guide['phone'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email"
                       value="<?= htmlspecialchars($guide['email'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Tên đăng nhập <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" class="form-control" name="username" required
                       value="<?= htmlspecialchars($guide['username'] ?? '') ?>">
                <small class="text-muted" style="font-size: 0.75rem; color: #6b7280;">Dùng để đăng nhập hệ thống</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" name="password" minlength="6">
                <small class="text-muted" style="font-size: 0.75rem; color: #6b7280;">Để trống nếu không muốn đổi mật khẩu</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Loại hướng dẫn</label>
                <select class="form-select" name="guide_type">
                    <option value="">Chọn loại hướng dẫn</option>
                    <?php
                    $types = ['Tiếng Anh', 'Tiếng Pháp', 'Tiếng Trung', 'Tiếng Nhật', 'Tiếng Việt'];
                    foreach ($types as $t) {
                        $sel = (isset($guide['guide_type']) && $guide['guide_type'] === $t) ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($t) . "\" $sel>" . htmlspecialchars($t) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Đánh giá (0-5)</label>
                <input type="number" class="form-control" name="average_rating" 
                       min="0" max="5" step="0.1" value="<?= htmlspecialchars($guide['average_rating'] ?? 0) ?>">
            </div>

            <div class="col-12">
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem;">
                    <a href="?act=guide-management" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Cập nhật
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include "views/layout/footer.php"; ?>
