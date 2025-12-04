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
                <input type="text" class="form-control" name="full_name" required
                       value="<?= htmlspecialchars($formData['full_name'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Ngày sinh <span style="color: #ef4444;">*</span>
                </label>
                <input type="date" class="form-control" name="birth_date" required
                       value="<?= htmlspecialchars($formData['birth_date'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Điện thoại <span style="color: #ef4444;">*</span>
                </label>
                <input type="tel" class="form-control" name="phone" required
                       value="<?= htmlspecialchars($formData['phone'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Email <span style="color: #ef4444;">*</span>
                </label>
                <input type="email" class="form-control" name="email" required
                       value="<?= htmlspecialchars($formData['email'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Loại hướng dẫn <span style="color: #ef4444;">*</span>
                </label>
                <select class="form-select" name="guide_type" required>
                    <option value="">Chọn loại hướng dẫn</option>
                    <option value="Tiếng Anh" <?= (isset($formData['guide_type']) && $formData['guide_type'] === 'Tiếng Anh') ? 'selected' : '' ?>>Tiếng Anh</option>
                    <option value="Tiếng Trung" <?= (isset($formData['guide_type']) && $formData['guide_type'] === 'Tiếng Trung') ? 'selected' : '' ?>>Tiếng Trung</option>
                    <option value="Tiếng Việt" <?= (isset($formData['guide_type']) && $formData['guide_type'] === 'Tiếng Việt') ? 'selected' : '' ?>>Tiếng Việt</option>
 
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Loại thẻ hướng dẫn <span style="color: #ef4444;">*</span>
                </label>
                <select class="form-select" name="license_type" required>
                    <option value="">Chọn loại thẻ</option>
                    <option value="Nội địa" <?= (isset($formData['license_type']) && $formData['license_type'] === 'Nội địa') ? 'selected' : '' ?>>Nội địa</option>
                    <option value="Quốc tế" <?= (isset($formData['license_type']) && $formData['license_type'] === 'Quốc tế') ? 'selected' : '' ?>>Quốc tế</option>
                    <option value="Thực tập" <?= (isset($formData['license_type']) && $formData['license_type'] === 'Thực tập') ? 'selected' : '' ?>>Thực tập</option>
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
