<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="page-header">
    <h2>Dashboard HDV</h2>
    <p>Xin chào, <?= ($hdvProfile['full_name'] ?? ''); ?></p>
</div>

<div class="card" style="padding:16px; margin:16px 0;">
    <h3>Danh sách khách tham gia các lịch trình được phân công</h3>

    <?php if (empty($participants)): ?>
        <div style="padding:20px; color:#6b7280;">
            Chưa có khách tham gia cho các lịch trình được phân công.
        </div>
    <?php else: ?>
        <div style="overflow:auto">
            <table class="table" style="min-width:900px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Liên hệ</th>
                        <th>Nhóm</th>
                        <th>Ghi chú đặc biệt</th>
                        <th>Tour</th>
                        <th>Ngày (từ - đến)</th>
                        <th>Booking ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $i => $p): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= ($p['full_name']) ?></td>
                            <td><?= ($p['phone']) ?></td>
                            <td><?= ($p['loai_khach']) ?></td>
                            <td><?= nl2br(($p['special_note'] ?? '')) ?></td>
                            <td><?= ($p['tour_name'] ?? '') ?></td>
                            <td>
                                <?php
                                    $s = $p['start_date'] ?? '';
                                    $e = $p['end_date'] ?? '';
                                    if ($s) $s = date('d/m/Y', strtotime($s));
                                    if ($e) $e = date('d/m/Y', strtotime($e));
                                    echo trim($s . ($e ? ' — ' . $e : ''));
                                ?>
                            </td>
                            <td><?= ($p['booking_id']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include "views/layout/footer.php"; ?>