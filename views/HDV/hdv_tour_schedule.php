<?php require_once 'header_hdv.php'; ?>
<?php require_once 'sidebar_hdv.php'; ?>

<div class="content">
    <div class="page-header">
        <div class="page-header-content">
            <h1>
                <i class="bi bi-calendar2-event"></i>
                Lịch trình tour của tôi
            </h1>
            <p class="subtitle">Quản lý lịch trình và thông tin tour được phân công</p>
        </div>
    </div>

    <div class="page-content">
        <?php if (empty($schedules)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h3>Không có lịch trình nào</h3>
                <p>Bạn chưa được phân công tour nào. Vui lòng liên hệ quản lý để được phân công.</p>
            </div>
        <?php else: ?>
            <!-- Bộ lọc -->
            <div class="filter-section">
                <div class="filter-group">
                    <label for="filterDate">Lọc theo ngày khởi hành:</label>
                    <select id="filterDate" class="form-select" onchange="filterSchedules()">
                        <option value="">Tất cả</option>
                        <option value="tomorrow">Ngày mai</option>
                        <option value="2days">2 ngày tới</option>
                        <option value="3days">3 ngày tới</option>
                        <option value="7days">7 ngày tới</option>
                        <option value="14days">14 ngày tới</option>
                        <option value="30days">30 ngày tới</option>
                    </select>
                </div>
            </div>

            <!-- Danh sách lịch trình -->
            <div class="schedules-grid">
                <?php 
                $currentDate = date('Y-m-d');
                foreach ($schedules as $schedule): 
                    $startDateRaw = $schedule['start_date'] ?? null;
                    $endDateRaw = $schedule['end_date'] ?? null;
                    $bookingStatus = $schedule['booking_status'] ?? '';

                    $startTimestamp = $startDateRaw ? strtotime($startDateRaw) : null;
                    $endTimestamp = $endDateRaw ? strtotime($endDateRaw) : null;

                    $startDisplay = 'Chưa cập nhật';
                    $endDisplay = 'Chưa cập nhật';

                    if ($startTimestamp) {
                        $startDisplay = date('d/m/Y', $startTimestamp);
                    }

                    $durationValue = $schedule['duration'] ?? null;
                    if (!is_numeric($durationValue)) {
                        if (is_string($durationValue) && preg_match('/\d+/', $durationValue, $matches)) {
                            $durationValue = (int)$matches[0];
                        } else {
                            $durationValue = 1;
                        }
                    }
                    $durationDays = max((int)$durationValue, 1);

                    if ($endTimestamp) {
                        $calculatedEndTimestamp = $endTimestamp;
                    } elseif ($startTimestamp) {
                        $calculatedEndTimestamp = $startTimestamp + max($durationDays - 1, 0) * 86400;
                    } else {
                        $calculatedEndTimestamp = null;
                    }

                    if ($calculatedEndTimestamp) {
                        $endDisplay = date('d/m/Y', $calculatedEndTimestamp);
                    }

                    $startDate = $startTimestamp ? date('Y-m-d', $startTimestamp) : null;
                    $effectiveEndDate = $calculatedEndTimestamp ? date('Y-m-d', $calculatedEndTimestamp) : null;

                    $timeStatus = 'unknown';
                    if ($bookingStatus === 'da_huy' || $bookingStatus === 'hoan_tat') {
                        $timeStatus = 'finished';
                    } elseif ($bookingStatus === 'dang_dien_ra') {
                        $timeStatus = 'ongoing';
                    } elseif ($startDate) {
                        if ($startDate > $currentDate) {
                            $timeStatus = 'upcoming';
                        } elseif ($effectiveEndDate && $currentDate > $effectiveEndDate) {
                            $timeStatus = 'finished';
                        } else {
                            $timeStatus = 'ongoing';
                        }
                    }
                ?>
                    <div class="schedule-card" 
                         data-status="<?= htmlspecialchars($schedule['tour_status'] ?? '') ?>"
                         data-time-status="<?= $timeStatus ?>"
                         data-booking-status="<?= htmlspecialchars($bookingStatus) ?>"
                         data-start-date="<?= htmlspecialchars($startDate) ?>">
                        <!-- Header -->
                        <div class="card-header">
                            <div class="header-left">
                                <h3><?= htmlspecialchars($schedule['tour_name'] ?? '') ?></h3>
                                <p class="location">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <?= htmlspecialchars($schedule['destination'] ?? '') ?>
                                </p>
                            </div>
                            <div class="header-right">
                                <span class="badge badge-<?= htmlspecialchars($schedule['tour_status'] ?? '') ?>">
                                    <?php 
                                    $statusMap = [
                                        'sap_mo' => 'Sắp mở',
                                        'dang_mo' => 'Đang mở',
                                        'da_dong' => 'Đã đóng'
                                    ];
                                    echo $statusMap[$schedule['tour_status'] ?? ''] ?? ($schedule['tour_status'] ?? '');
                                    ?>
                                </span>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-box">
                                    <label>Ngày khởi hành</label>
                                    <p class="info-value">
                                        <i class="bi bi-calendar-event"></i>
                                        <?= $startDisplay ?>
                                    </p>
                                </div>

                                <div class="info-box">
                                    <label>Ngày kết thúc</label>
                                    <p class="info-value">
                                        <i class="bi bi-calendar-event"></i>
                                        <?= $endDisplay ?>
                                    </p>
                                </div>

                                <div class="info-box">
                                    <label>Thời lượng</label>
                                    <p class="info-value">
                                        <i class="bi bi-hourglass-split"></i>
                                        <?= htmlspecialchars($schedule['duration'] ?? '') ?> ngày
                                    </p>
                                </div>

                                <div class="info-box">
                                    <label>Số khách</label>
                                    <p class="info-value">
                                        <i class="bi bi-people-fill"></i>
                                        <?= htmlspecialchars($schedule['so_khach'] ?? '') ?> khách
                                    </p>
                                </div>
                            </div>

                            <?php if (!empty($schedule['description'])): ?>
                                <div class="description-box">
                                    <label>Mô tả tour</label>
                                    <p><?= htmlspecialchars($schedule['description'] ?? '') ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer">
                            <small class="text-muted">
                                Tour ID: #<?= htmlspecialchars($schedule['tour_id'] ?? '') ?> | 
                                Booking ID: #<?= htmlspecialchars($schedule['booking_id'] ?? '') ?>
                            </small>
                            <a href="?act=hdv_schedule_detail&booking_id=<?= urlencode($schedule['booking_id'] ?? '') ?>" class="btn-detail">
                                <i class="bi bi-eye"></i>
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.page-header-content h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.subtitle {
    margin: 8px 0 0 0;
    font-size: 14px;
    opacity: 0.9;
}

.filter-section {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 24px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 15px;
}

.filter-group label {
    font-weight: 600;
    margin: 0;
    white-space: nowrap;
    min-width: 150px;
}

.filter-group select {
    min-width: 200px;
    padding: 8px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
}

.schedules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 24px;
}

.schedule-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.schedule-card:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);
}

.card-header {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
}

.header-left h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #333;
}

.location {
    margin: 8px 0 0 0;
    font-size: 13px;
    color: #666;
}

.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    white-space: nowrap;
}

.badge-sap_mo {
    background-color: #e3f2fd;
    color: #1976d2;
}

.badge-dang_mo {
    background-color: #f3e5f5;
    color: #7b1fa2;
}

.badge-da_dong {
    background-color: #ede7f6;
    color: #512da8;
}

.card-body {
    padding: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}

.info-box {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 6px;
}

.info-box label {
    display: block;
    font-size: 11px;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.info-value {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-value i {
    color: #667eea;
}

.description-box {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 6px;
    margin-top: 12px;
}

.description-box label {
    display: block;
    font-size: 11px;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.description-box p {
    margin: 0;
    font-size: 13px;
    color: #555;
    line-height: 1.5;
}

.card-footer {
    padding: 12px 20px;
    background: #f8f9fa;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.text-muted {
    color: #999;
    font-size: 12px;
}

.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #667eea;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-detail:hover {
    background: #5568d3;
    transform: translateX(2px);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 8px;
}

.empty-icon {
    font-size: 56px;
    color: #ccc;
    margin-bottom: 16px;
}

.empty-state h3 {
    margin: 0;
    font-size: 20px;
    color: #333;
}

.empty-state p {
    margin: 10px 0 0 0;
    color: #999;
}

@media (max-width: 768px) {
    .schedules-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        padding: 20px;
    }

    .page-header-content h1 {
        font-size: 22px;
    }

    .filter-group {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .filter-group label {
        min-width: auto;
    }

    .filter-group select {
        width: 100%;
    }
}
</style>

<script>
function filterSchedules() {
    const filterDate = document.getElementById('filterDate').value;
    const cards = document.querySelectorAll('.schedule-card');
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    cards.forEach(card => {
        const timeStatus = card.getAttribute('data-time-status');
        const bookingStatus = card.getAttribute('data-booking-status');
        const startDateStr = card.getAttribute('data-start-date');
        let shouldShow = false;

        // Hiển thị tất cả trạng thái
        let statusMatch = true;

        // Filter theo ngày
        let dateMatch = false;
        if (filterDate === '') {
            dateMatch = true;
        } else {
            if (startDateStr) {
                const startDate = new Date(startDateStr);
                startDate.setHours(0, 0, 0, 0);
                const diffTime = startDate - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                switch (filterDate) {
                    case 'tomorrow':
                        dateMatch = diffDays === 1;
                        break;
                    case '2days':
                        dateMatch = diffDays === 2;
                        break;
                    case '3days':
                        dateMatch = diffDays === 3;
                        break;
                    case '7days':
                        dateMatch = diffDays <= 7 && diffDays >= 0;
                        break;
                    case '14days':
                        dateMatch = diffDays <= 14 && diffDays >= 0;
                        break;
                    case '30days':
                        dateMatch = diffDays <= 30 && diffDays >= 0;
                        break;
                }
            }
        }

        shouldShow = statusMatch && dateMatch;
        card.style.display = shouldShow ? '' : 'none';
    });
}
</script>

<?php require_once 'footer_hdv.php'; ?>
