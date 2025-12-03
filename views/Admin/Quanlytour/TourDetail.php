<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <a href="?act=tour-list" class="back-btn" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 15px; color: var(--primary-blue); text-decoration: none;">
        <i class="bi bi-chevron-left"></i> Quay lại
    </a>
    <h1><?= htmlspecialchars($tour['tour_name']) ?></h1>
    <p class="subtitle" style="color: var(--text-secondary); margin-top: 8px;">Chi tiết tour</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
    <!-- Thông tin chính -->
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-info-circle"></i> Thông tin chung</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <!-- Tên tour -->
                <div>
                    <label style="font-weight: 600; color: var(--text-secondary); font-size: 13px; text-transform: uppercase;">Tên Tour</label>
                    <p style="margin: 8px 0 0 0; font-size: 16px; color: #333;"><?= htmlspecialchars($tour['tour_name']) ?></p>
                </div>

                <!-- Điểm đến -->
                <div>
                    <label style="font-weight: 600; color: var(--text-secondary); font-size: 13px; text-transform: uppercase;">Điểm đến</label>
                    <p style="margin: 8px 0 0 0; font-size: 16px; color: #333;">
                        <i class="bi bi-geo-alt-fill" style="color: #e74c3c; margin-right: 8px;"></i>
                        <?= htmlspecialchars($tour['destination']) ?>
                    </p>
                </div>

                <!-- Danh mục -->
                <div>
                    <label style="font-weight: 600; color: var(--text-secondary); font-size: 13px; text-transform: uppercase;">Danh mục</label>
                    <p style="margin: 8px 0 0 0; font-size: 16px; color: #333;">
                        <span class="badge" style="background-color: var(--primary-blue); color: white; padding: 6px 12px; border-radius: 20px;">
                            <?= htmlspecialchars($tour['category_name'] ?? 'N/A') ?>
                        </span>
                    </p>
                </div>

                <!-- Thời lượng -->
                <div>
                    <label style="font-weight: 600; color: var(--text-secondary); font-size: 13px; text-transform: uppercase;">Thời lượng</label>
                    <p style="margin: 8px 0 0 0; font-size: 16px; color: #333;">
                        <i class="bi bi-clock" style="color: #3498db; margin-right: 8px;"></i>
                        <?= htmlspecialchars($tour['duration']) ?> ngày
                    </p>
                </div>

                <!-- Trạng thái -->
                <div>
                    <label style="font-weight: 600; color: var(--text-secondary); font-size: 13px; text-transform: uppercase;">Trạng thái</label>
                    <p style="margin: 8px 0 0 0;">
                        <?php
                        $status = $tour['status'];
                        if ($status == 1 || $status == 'open') {
                            echo "<span class='badge bg-success' style='padding: 8px 15px;'><i class='bi bi-check-circle'></i> Đang mở</span>";
                        } elseif ($status == 0 || $status == 'closed') {
                            echo "<span class='badge bg-danger' style='padding: 8px 15px;'><i class='bi bi-x-circle'></i> Đã đóng</span>";
                        } elseif ($status == 2 || $status == 'upcoming') {
                            echo "<span class='badge bg-warning' style='padding: 8px 15px;'><i class='bi bi-exclamation-circle'></i> Sắp mở</span>";
                        } else {
                            echo "<span class='badge bg-info' style='padding: 8px 15px;'>Không xác định</span>";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mô tả và giá -->
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-file-text"></i> Mô tả và Giá</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <!-- Giá -->
                <div>
                    <label style="font-weight: 600; color: var(--text-secondary); font-size: 13px; text-transform: uppercase;">Giá tour</label>
                    <p style="margin: 8px 0 0 0; font-size: 18px; color: var(--primary-blue); font-weight: bold;">
                        <?= number_format($tour['price'] ?? 0, 0, ',', '.') ?> VNĐ
                    </p>
                </div>

                <!-- Mô tả -->
                <div>
                    <label style="font-weight: 600; color: var(--text-secondary); font-size: 13px; text-transform: uppercase;">Mô tả</label>
                    <div style="margin: 8px 0 0 0; background-color: #f8f9fa; padding: 15px; border-radius: 8px; line-height: 1.6; color: #555;">
                        <?= nl2br(htmlspecialchars($tour['description'] ?? 'Chưa có mô tả')) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hành động -->
<div class="card">
    <div class="card-body">
        <div style="display: flex; gap: 10px;">
            <a href="?act=edit-tour&id=<?= $tour['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Sửa Tour
            </a>
            <a href="?act=schedule-list&id=<?= $tour['id'] ?>" class="btn btn-info">
                <i class="bi bi-calendar"></i> Lịch trình
            </a>
            <a href="?act=tour-list" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
</div>

<style>
.card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, #5b7ec6 100%);
    color: white;
    padding: 20px;
    border-bottom: 1px solid #ddd;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 20px;
}

.back-btn {
    color: var(--primary-blue);
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
}

.back-btn:hover {
    color: #5b7ec6;
    transform: translateX(-5px);
}

.badge {
    display: inline-block;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.badge.bg-success {
    background-color: #28a745;
    color: white;
}

.badge.bg-danger {
    background-color: #dc3545;
    color: white;
}

.badge.bg-warning {
    background-color: #ffc107;
    color: #333;
}

.badge.bg-info {
    background-color: #17a2b8;
    color: white;
}

@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php include "views/layout/footer.php"; ?>
