<?php
// File: views/HDV/hdv_my_tours.php

// Gọi layout header DÀNH RIÊNG cho HDV
require_once './views/HDV/header_hdv.php'; 
?>

<div class="container-fluid px-4">
    <br><br>
    
    <h2 class="mt-4 fw-bold">
        <i class="bi bi-map"></i> Tour được phân công
    </h2>
    <p class="text-muted">Đây là danh sách các tour bạn đã và đang phụ trách.</p>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tên Tour</th>
                        <th>Ngày đi</th>
                        <th>Ngày về</th>
                        <th>Điểm đến</th>
                        <th>Vai trò của bạn</th>
                        <th>Trạng thái Tour</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assignedTours)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Bạn chưa được phân công tour nào.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assignedTours as $tour): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($tour['tour_name']) ?></td>
                                <td><?= date("d/m/Y", strtotime($tour['start_date'])) ?></td>
                                <td><?= date("d/m/Y", strtotime($tour['end_date'])) ?></td>
                                <td><?= htmlspecialchars($tour['destination']) ?></td>
                                <td>
                                    <span class="badge bg-primary fs-6">
                                        <?= htmlspecialchars($tour['vai_tro_trong_tour'] ?? 'HDV') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success fs-6">
                                        <?= htmlspecialchars($tour['status'] ?? 'Open') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// Gọi layout footer DÀNH RIÊNG cho HDV
require_once './views/HDV/footer_hdv.php'; 
?>