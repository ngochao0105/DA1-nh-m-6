<?php include "views/HDV/header_hdv.php"; ?>
<?php include "views/HDV/sidebar_hdv.php"; ?>

<style>
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    animation: fadeInUp 0.6s ease-out;
}

.stats-card h2 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.stats-card .total-count {
    font-size: 3rem;
    font-weight: 800;
    margin: 0.5rem 0;
}

.stats-card .subtitle {
    opacity: 0.9;
    font-size: 1rem;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.table-container {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-block;
}

.status-cho_xac_nhan {
    background: #fef3c7;
    color: #92400e;
}

.status-da_xac_nhan {
    background: #dbeafe;
    color: #1e40af;
}

.status-dang_dien_ra {
    background: #d1fae5;
    color: #065f46;
}

.status-hoan_tat {
    background: #e0e7ff;
    color: #3730a3;
}

.status-da_huy {
    background: #fee2e2;
    color: #991b1b;
}

.payment-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.payment-da_thanh_toan {
    background: #d1fae5;
    color: #065f46;
}

.payment-chua_thanh_toan {
    background: #fee2e2;
    color: #991b1b;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6b7280;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
    color: #9ca3af;
}

.empty-state p {
    font-size: 1.125rem;
    margin: 0;
}
</style>

<div class="page-header">
    <h1>Lịch sử dẫn tour</h1>
</div>

<!-- Thống kê tổng số booking -->
<div class="stats-card">
    <h2>Tổng số tour đã hoàn thành</h2>
    <div class="total-count"><?= number_format($totalBookings) ?></div>
    <div class="subtitle">Các tour bạn đã hoàn thành</div>
</div>

<!-- Bảng lịch sử -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã Booking</th>
                <th>Tên Tour</th>
                <th>Điểm đến</th>
                <th>Ngày đi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($bookingHistory)): ?>
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Chưa có tour nào đã hoàn thành.</p>
                    </td>
                </tr>
            <?php else: ?>
                <?php $stt = 1; ?>
                <?php foreach ($bookingHistory as $booking): ?>
                <tr>
                    <td><?= $stt++ ?></td>
                    <td><strong>#<?= $booking['booking_id'] ?></strong></td>
                    <td>
                        <strong><?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?></strong>
                    </td>
                    <td><?= htmlspecialchars($booking['destination'] ?? 'N/A') ?></td>
                    <td>
                        <?php if ($booking['ngay_di']): ?>
                            <strong><?= date("d/m/Y", strtotime($booking['ngay_di'])) ?></strong>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "views/HDV/footer_hdv.php"; ?>

