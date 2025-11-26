<?php include "views/HDV/header_hdv.php"; ?>
<?php include "views/HDV/sidebar_hdv.php"; ?>

<style>
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

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

@keyframes countUp {
    from {
        opacity: 0;
        transform: scale(0.5);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.stat-card {
    background: white;
    border-radius: 1rem;
    padding: 1.75rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, currentColor, transparent);
    opacity: 0;
    transition: opacity 0.3s;
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-card:nth-child(1) {
    animation-delay: 0.1s;
    color: #3b82f6;
}

.stat-card:nth-child(1) .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    animation: float 3s ease-in-out infinite;
}

.stat-card:nth-child(2) {
    animation-delay: 0.2s;
    color: #10b981;
}

.stat-card:nth-child(2) .stat-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    animation: float 3s ease-in-out infinite 0.5s;
}

.stat-card:nth-child(3) {
    animation-delay: 0.3s;
    color: #3b82f6;
}

.stat-card:nth-child(3) .stat-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    animation: float 3s ease-in-out infinite 1s;
}

.stat-card:nth-child(4) {
    animation-delay: 0.4s;
    color: #f59e0b;
}

.stat-card:nth-child(4) .stat-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    animation: float 3s ease-in-out infinite 1.5s;
}

.stat-icon {
    width: 64px;
    height: 64px;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.75rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.stat-icon::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: shimmer 3s infinite;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 2.25rem;
    font-weight: 800;
    color: #111827;
    line-height: 1;
    animation: countUp 0.8s ease-out;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-card:nth-child(1) .stat-value {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-card:nth-child(2) .stat-value {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-card:nth-child(3) .stat-value {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-card:nth-child(4) .stat-value {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-top: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.stat-trend.up {
    color: #10b981;
}

.stat-trend.down {
    color: #ef4444;
}

.activity-card {
    background: white;
    border-radius: 1rem;
    padding: 1.75rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
    animation: fadeInUp 0.8s ease-out 0.5s both;
}

.activity-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.activity-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.activity-header i {
    font-size: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 0.75rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    animation: fadeInUp 0.6s ease-out;
}

.activity-item:nth-child(1) {
    animation-delay: 0.6s;
    border-left-color: #10b981;
}

.activity-item:nth-child(2) {
    animation-delay: 0.7s;
    border-left-color: #3b82f6;
}

.activity-item:nth-child(3) {
    animation-delay: 0.8s;
    border-left-color: #3b82f6;
}

.activity-item:hover {
    transform: translateX(8px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
}

.activity-icon {
    width: 48px;
    height: 48px;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.activity-item:nth-child(1) .activity-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
}

.activity-item:nth-child(2) .activity-icon,
.activity-item:nth-child(3) .activity-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.25rem;
    font-size: 0.9375rem;
}

.activity-time {
    font-size: 0.8125rem;
    color: #6b7280;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-value {
        font-size: 1.75rem;
    }
}
</style>

<div class="page-header">
    <h1>Trang của tôi</h1>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1rem;">
            <div class="stat-icon">
                <i class="bi bi-map"></i>
            </div>
            <div style="flex: 1;">
                <div class="stat-label">Tổng Tour được gán</div>
                <div class="stat-value"><?= $totalTours ?? 2 ?></div>
                <div class="stat-trend up">
                    <i class="bi bi-arrow-up"></i>
                    <span>Đang hoạt động</span>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1rem;">
            <div class="stat-icon">
                <i class="bi bi-star-fill"></i>
            </div>
            <div style="flex: 1;">
                <div class="stat-label">Đánh giá trung bình</div>
                <div class="stat-value"><?= number_format($_SESSION['average_rating'] ?? 4.5, 1) ?></div>
                <div class="stat-trend up">
                    <i class="bi bi-arrow-up"></i>
                    <span>Xuất sắc</span>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1rem;">
            <div class="stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div style="flex: 1;">
                <div class="stat-label">Lịch trình sắp tới</div>
                <div class="stat-value"><?= $upcomingSchedules ?? 1 ?></div>
                <div class="stat-trend up">
                    <i class="bi bi-arrow-up"></i>
                    <span>Đã lên lịch</span>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1rem;">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <div style="flex: 1;">
                <div class="stat-label">Tổng khách hàng</div>
                <div class="stat-value"><?= $totalCustomers ?? 0 ?></div>
                <div class="stat-trend up">
                    <i class="bi bi-arrow-up"></i>
                    <span>Đã phục vụ</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="activity-card">
    <div class="activity-header">
        <i class="bi bi-clock-history"></i>
        <h3>Hoạt động gần đây</h3>
    </div>
    <div>
        <div class="activity-item">
            <div class="activity-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="activity-content">
                <div class="activity-title">Tour được phân công mới</div>
                <div class="activity-time">5 phút trước</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="activity-content">
                <div class="activity-title">Lịch làm việc đã được cập nhật</div>
                <div class="activity-time">1 giờ trước</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon">
                <i class="bi bi-star-fill"></i>
            </div>
            <div class="activity-content">
                <div class="activity-title">Nhận được đánh giá mới</div>
                <div class="activity-time">2 giờ trước</div>
            </div>
        </div>
    </div>
</div>

<script>
// Counter animation
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const current = Math.floor(progress * (end - start) + start);
        element.textContent = current;
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Animate counters when page loads
document.addEventListener('DOMContentLoaded', function() {
    const statValues = document.querySelectorAll('.stat-value');
    statValues.forEach(stat => {
        const text = stat.textContent.trim();
        const value = parseFloat(text) || parseInt(text) || 0;
        if (value > 0 && !isNaN(value)) {
            const originalText = stat.textContent;
            if (value % 1 === 0) {
                stat.textContent = '0';
                setTimeout(() => {
                    animateValue(stat, 0, value, 1000);
                }, 500);
            }
        }
    });
});
</script>


<?php include "views/HDV/footer_hdv.php"; ?>
