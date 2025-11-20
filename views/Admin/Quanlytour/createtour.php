<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>Thêm Tour Du Lịch</h1>
</div>

<div style="background: white; border-radius: 0.75rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="margin-bottom: 1.5rem; padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="POST" id="createTourForm" onsubmit="return validateForm()">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">
                    Tên tour <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="tour_name" class="form-control" 
                       placeholder="Ví dụ: Tour Đà Lạt 3N2Đ" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Điểm đến <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="destination" class="form-control" 
                       placeholder="Ví dụ: Đà Lạt, Nha Trang..." required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Danh mục tour</label>
                <select name="id_danh_muc" class="form-select">
                    <option value="">-- Chọn danh mục --</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>">
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="5" 
                          placeholder="Nhập mô tả chi tiết về tour..."></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="1">Đang mở</option>
                    <option value="0">Đã đóng</option>
                    <option value="2">Sắp mở</option>
                </select>
            </div>

            <div class="col-12">
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem;">
                    <a href="?act=tour-list" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Thêm tour
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function validateForm() {
    return true;
}
</script>

<?php include "views/layout/footer.php"; ?>
