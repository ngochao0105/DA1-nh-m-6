<?php include "views/HDV/header_hdv.php"; ?>
<?php include "views/HDV/sidebar_hdv.php"; ?>

<style>
.profile-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.profile-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out;
}

.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2.5rem 2rem;
    color: white;
    text-align: center;
    position: relative;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 3rem;
    border: 4px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.profile-name {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.profile-role {
    font-size: 1rem;
    opacity: 0.9;
    font-weight: 400;
}

.profile-body {
    padding: 2rem;
}

.info-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 0.5rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #f3f4f6;
    transform: translateX(5px);
}

.info-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.info-value {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 500;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rating-stars {
    display: flex;
    gap: 0.25rem;
}

.rating-stars .star {
    color: #fbbf24;
    font-size: 1.25rem;
}

.rating-stars .star.empty {
    color: #d1d5db;
}

.rating-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
}

.rating-text {
    font-size: 0.875rem;
    color: #6b7280;
    margin-left: 0.5rem;
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

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}
</style>

<div class="profile-container">
    <div class="profile-card">
        <!-- Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="profile-name">
                <?= htmlspecialchars($hdvProfile['full_name'] ?? $_SESSION['username'] ?? 'Hướng dẫn viên') ?>
            </div>
            <div class="profile-role">Hướng dẫn viên</div>
        </div>

        <!-- Body -->
        <div class="profile-body">
            <!-- Thông tin liên hệ -->
            <div class="info-section">
                <div class="section-title">
                    <i class="bi bi-info-circle"></i>
                    Thông tin liên hệ
                </div>

                <!-- Số điện thoại -->
                <?php if (!empty($hdvProfile['phone'])): ?>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Số điện thoại</div>
                        <div class="info-value"><?= htmlspecialchars($hdvProfile['phone']) ?></div>
                    </div>
                </div>
                <?php else: ?>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Số điện thoại</div>
                        <div class="info-value" style="color: #9ca3af;">Chưa cập nhật</div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Email -->
                <?php if (!empty($hdvProfile['email'])): ?>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?= htmlspecialchars($hdvProfile['email']) ?></div>
                    </div>
                </div>
                <?php else: ?>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Email</div>
                        <div class="info-value" style="color: #9ca3af;">Chưa cập nhật</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Đánh giá -->
            <div class="info-section">
                <div class="section-title">
                    <i class="bi bi-star-fill"></i>
                    Đánh giá
                </div>

                <div class="info-item">
                    <div class="info-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Đánh giá trung bình</div>
                        <div class="rating-display">
                            <?php if ($rating > 0): ?>
                                <div class="rating-stars">
                                    <?php 
                                    $fullStars = floor($rating);
                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                    for ($i = 1; $i <= 5; $i++): 
                                        if ($i <= $fullStars):
                                    ?>
                                        <i class="bi bi-star-fill star"></i>
                                    <?php elseif ($i == $fullStars + 1 && $hasHalfStar): ?>
                                        <i class="bi bi-star-half star"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star star empty"></i>
                                    <?php endif; endfor; ?>
                                </div>
                                <span class="rating-value"><?= number_format($rating, 1) ?></span>
                                <span class="rating-text">/ 5.0</span>
                            <?php else: ?>
                                <div class="rating-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star star empty"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="rating-value" style="color: #9ca3af;">Chưa có đánh giá</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin bổ sung (nếu có) -->
            <?php if (!empty($hdvProfile['guide_type']) || !empty($hdvProfile['license_type'])): ?>
            <div class="info-section">
                <div class="section-title">
                    <i class="bi bi-card-checklist"></i>
                    Thông tin bổ sung
                </div>

                <?php if (!empty($hdvProfile['guide_type'])): ?>
                <div class="info-item">
                    <div class="info-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Loại hướng dẫn viên</div>
                        <div class="info-value"><?= htmlspecialchars($hdvProfile['guide_type']) ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include "views/HDV/footer_hdv.php"; ?>

