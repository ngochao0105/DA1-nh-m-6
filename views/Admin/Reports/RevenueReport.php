<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h1>📊 Báo cáo doanh thu</h1>
    <p>Thống kê doanh thu theo tháng — Năm <?= ($year) ?></p>
</div>

<!-- Chọn năm -->
<div class="action-bar" style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center;">
    <form method="get" style="display: flex; gap: 12px; align-items: center;">
        <input type="hidden" name="act" value="revenue-report">
        <select name="year" class="form-select" onchange="this.form.submit()" style="min-width: 150px;">
            <?php foreach ($availableYears as $y): ?>
                <option value="<?= (int)$y ?>" <?= $y == $year ? 'selected' : '' ?>>Năm <?= (int)$y ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <a href="index.php?act=add-booking" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-plus-circle"></i> Tạo booking
    </a>
</div>

<?php
$hasRevenue = $totalRevenue > 0;
$maxRevenue = $maxRevenueMonth ? $maxRevenueMonth['doanh_thu'] : 0;
$maxRevenueMonthNumber = $maxRevenueMonth ? $maxRevenueMonth['thang'] : null;
?>

<?php if (!$hasRevenue): ?>
    <div class="alert alert-warning" style="padding: 30px; text-align: center; border-radius: 10px;">
        <i class="bi bi-graph-up" style="font-size: 3rem; opacity: 0.6; display: block; margin-bottom: 15px;"></i>
        <h4>Chưa có doanh thu nào trong năm <?= $year ?></h4>
        <p style="margin: 15px 0;">Hệ thống chưa ghi nhận booking nào có <strong>tổng tiền > 0</strong>.</p>
        <div style="margin-top: 20px;"> 
            <a href="migration_update_revenue.php" class="btn btn-outline-secondary" style="margin-left: 10px;">
                <i class="bi bi-arrow-repeat"></i> Cập nhật dữ liệu cũ
            </a>
        </div>
    </div>
<?php else: ?>
    <!-- Tổng quan -->  
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 30px;">
        <div class="card" style="padding: 20px; text-align: center; background: #f0fdf4; border-left: 4px solid #10b981;">
            <div style="font-size: 14px; color: #065f46; margin-bottom: 10px;">💰 Tổng doanh thu năm</div>
            <div style="font-size: 32px; font-weight: bold; color: #059669;">
                <?= number_format($totalRevenue, 0, ',', '.') ?> VNĐ
            </div>
        </div>
       
        <div class="card" style="padding: 20px; text-align: center; background: #fffbeb; border-left: 4px solid #f59e0b;">
            <div style="font-size: 14px; color: #b45309; margin-bottom: 10px;">📊 Doanh thu trung bình/tháng</div>
            <div style="font-size: 32px; font-weight: bold; color: #d97706;">
                <?= number_format($avgRevenuePerMonth, 0, ',', '.') ?> VNĐ
            </div>
        </div>
        <div class="card" style="padding: 20px; text-align: center; background: #fef2f2; border-left: 4px solid #ef4444;">
            <div style="font-size: 14px; color: #991b1b; margin-bottom: 10px;">🏆 Doanh thu cao nhất</div>
            <div style="font-size: 32px; font-weight: bold; color: #dc2626;">
                <?= number_format($maxRevenue, 0, ',', '.') ?> VNĐ
            </div>
            <?php if ($maxRevenueMonthNumber): ?>
                <div style="font-size: 12px; color: #991b1b; margin-top: 5px;">
                    (Tháng <?= str_pad($maxRevenueMonthNumber, 2, '0', STR_PAD_LEFT) ?>)
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bảng doanh thu theo tháng -->
    <div class="card">
        <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3>Doanh thu chi tiết theo tháng</h3>
            <?php if ($countMonths > 0): ?>
                <span class="badge bg-success"><?= $countMonths ?> tháng có doanh thu</span>
            <?php endif; ?>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tháng</th>
                        <th>Số booking</th>
                        <th>Số khách</th>
                        <th>Doanh thu</th>
                        <th>Doanh thu trung bình/booking</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (range(1, 12) as $m): // Hiển thị đủ 12 tháng ?>
                        <?php
                        $data = null;
                        foreach ($monthlyRevenue as $row) {
                            if ($row['thang'] == $m) {
                                $data = $row;
                                break;
                            }
                        }
                        if (!$data) {
                            $data = [
                                'thang' => $m,
                                'nam' => $year,
                                'so_booking' => 0,
                                'so_khach' => 0,
                                'doanh_thu' => 0,
                                'doanh_thu_trung_binh' => 0
                            ];
                        }
                        ?>
                        <tr style="<?= $data['doanh_thu'] > 0 ? '' : 'opacity: 0.6; background-color: #fafafa;' ?>">
                            <td><strong>Tháng <?= str_pad($data['thang'], 2, '0', STR_PAD_LEFT) ?></strong></td>
                            <td><?= (int)($data['so_booking'] ?? 0) ?></td>
                            <td><?= (int)($data['so_khach'] ?? 0) ?></td>
                            <td>
                                <?php if ($data['doanh_thu'] > 0): ?>
                                    <span style="color: #059669; font-weight: bold;">
                                        <?= number_format($data['doanh_thu'], 0, ',', '.') ?> VNĐ
                                    </span>
                                <?php else: ?>
                                    <span style="color: #9ca3af;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($data['so_booking'] > 0): ?>
                                    <?= number_format($data['doanh_thu'] / $data['so_booking'], 0, ',', '.') ?> VNĐ
                                <?php else: ?>
                                    <span style="color: #9ca3af;">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            
            </table>
        </div>
    </div>
<?php endif; ?>

<?php include "views/layout/footer.php"; ?>