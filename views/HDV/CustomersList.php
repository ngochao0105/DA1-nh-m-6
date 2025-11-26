<?php
// fallback an toàn nếu controller không cung cấp $profile
if (!isset($profile) && isset($hdvProfile)) {
    $profile = $hdvProfile;
}
$profile = $profile ?? ['full_name' => ''];
?>

<?php include "views/HDV/header_hdv.php"; ?>
<?php include "views/HDV/sidebar_hdv.php"; ?>

<div class="page-header">
    <h2>Danh sách khách tham gia tour</h2>
    <p>HDV: <?= $profile['full_name'] ?></p>
</div>

<div class="card" style="padding:16px; margin:16px 0;">
    <h3>Khách tham gia các tour bạn được phân công</h3>

    <?php if (empty($customers)): ?>
        <div style="padding:20px; color:#6b7280;">
            Không có khách nào thuộc các tour mà bạn phụ trách.
        </div>
    <?php else: ?>
        <div style="overflow:auto">
            <table class="table" style="min-width:900px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Liên hệ</th>
                        <th>Nhóm</th>
                        <th>Ghi chú đặc biệt</th>
                        <th>Tour</th>
                        <th>Booking ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $i => $c): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $c['full_name'] ?></td>
                            <td><?= $c['phone'] ?></td>
                            <td><?= $c['loai_khach'] ?></td>
                            <td><?= nl2br($c['special_note'] ?? '') ?></td>
                            <td><?= $c['tour_name'] ?></td>
                            <td><?= $c['booking_id'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include "views/HDV/footer_hdv.php"; ?>
