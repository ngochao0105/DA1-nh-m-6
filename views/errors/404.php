<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>404 - Trang không tìm thấy</h1>
</div>

<div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
    <div style="font-size: 6rem; color: var(--text-secondary); margin-bottom: 1rem;">
        <i class="bi bi-exclamation-triangle"></i>
    </div>
    <h2 style="color: var(--text-primary); margin-bottom: 1rem;">Trang không tồn tại</h2>
    <p style="color: var(--text-secondary); margin-bottom: 2rem;">
        Trang bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="?act=/" class="btn btn-primary">
            <i class="bi bi-house"></i> Về trang chủ
        </a>
        <a href="javascript:history.back()" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<?php include "views/layout/footer.php"; ?>

