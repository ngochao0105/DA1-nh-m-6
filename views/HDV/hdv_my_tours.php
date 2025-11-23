<?php include "views/HDV/header_hdv.php"; ?>
<?php include "views/HDV/sidebar_hdv.php"; ?>

<div class="page-header">
    <h1>Tour được phân công</h1>
</div>

<div class="action-bar">
    <div class="action-bar-left">
        <div class="action-bar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Tìm kiếm tour...">
        </div>
    </div>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Tên Tour</th>
                <th>Ngày đi</th>
                <th>Ngày về</th>
                <th>Điểm đến</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($assignedTours)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                        <i class="bi bi-inbox" style="font-size: 3rem; display: block; margin-bottom: 15px; opacity: 0.5;"></i>
                        <p>Bạn chưa được phân công tour nào.</p>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($assignedTours as $tour): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($tour['tour_name'] ?? 'N/A') ?></strong></td>
                        <td>
                            <?php 
                            if (isset($tour['start_date'])) {
                                echo date("d/m/Y", strtotime($tour['start_date']));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            if (isset($tour['end_date'])) {
                                echo date("d/m/Y", strtotime($tour['end_date']));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($tour['destination'] ?? 'N/A') ?></td>
                        <td>
                            <div class="guide-cell">
                                <div class="guide-info">
                                    <i class="bi bi-person-badge"></i>
                                    <span class="guide-name"><?= htmlspecialchars($tour['vai_tro_trong_tour'] ?? 'HDV') ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="status-cell">
                                <?php 
                                $status = $tour['status'] ?? 'Open';
                                if($status == 'Open' || $status == 'open') {
                                    echo "<span class='status-badge status-info'><i class='bi bi-check-circle'></i> Mở</span>";
                                } elseif($status == 'Closed' || $status == 'closed') {
                                    echo "<span class='status-badge status-secondary'><i class='bi bi-x-circle'></i> Đóng</span>";
                                } elseif($status == 'Completed' || $status == 'completed') {
                                    echo "<span class='status-badge status-success'><i class='bi bi-check-circle-fill'></i> Hoàn tất</span>";
                                } else {
                                    echo "<span class='status-badge status-info'><i class='bi bi-info-circle'></i> " . htmlspecialchars($status) . "</span>";
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "views/HDV/footer_hdv.php"; ?>
