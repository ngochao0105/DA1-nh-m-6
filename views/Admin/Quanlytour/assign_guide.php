<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>
<br>
<br>

<div class="container mt-4">
  
    <h3><i class="bi bi-person-lines-fill"></i> Phân công hướng dẫn viên</h3>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'assigned'): ?>
    <div class="alert alert-danger">Tour này đã được phân công HDV, không thể phân công thêm!</div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Phân công thành công!</div>
<?php endif; ?>
    <hr>

    <!-- Thông tin tour -->
    <div class="card mb-4">
        <div class="card-body">
          <p><strong>Tên Tour:</strong> <?= htmlspecialchars($tour['tour_name']) ?></p>
            <p><strong>Điểm đến:</strong> <?= $tour['destination'] ?></p>
            <p><strong>Thời gian:</strong> 
                <?= date("d-m-Y", strtotime($tour['start_date'])) ?> →
                <?= date("d-m-Y", strtotime($tour['end_date'])) ?>
            </p>
        </div>
    </div>

    <!-- Form phân công -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <strong>Thêm phân công</strong>
        </div>
        <div class="card-body">
            <form method="POST" action="?act=save-assign-guide&id=<?= $tour['id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Chọn hướng dẫn viên</label>
                    <select name="id_hdv" class="form-select" required>
                        <option value="">-- Chọn HDV --</option>
                        <?php foreach ($guides as $g): ?>
                            <option value="<?= $g['id'] ?>">
                                <?= $g['full_name'] ?> (<?= $g['phone'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Vai trò</label>
                    <select name="role" class="form-select" required>
                        <option value="HDV chính">HDV chính</option>
                        <option value="HDV phụ">HDV phụ</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Lưu phân công
                </button>

            </form>
        </div>
    </div>

    <!-- Danh sách HDV đã phân công -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <strong>Danh sách HDV đã phân công</strong>
        </div>

        <table class="table table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Họ tên</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($assignedGuides)): ?>
                    <?php foreach ($assignedGuides as $a): ?>
                        <tr>
                            <td><?= $a['full_name'] ?></td>
                            <td><?= $a['phone'] ?></td>
                            <td><?= $a['email'] ?></td>
                            <td><span class="badge bg-info"><?= $a['role'] ?></span></td>
                            <td>
                                <a onclick="return confirm('Xóa phân công này?')" 
                                   href="?act=delete-assign&id=<?= $a['id'] ?>&tour=<?= $tour['id'] ?>" 
                                   class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">Chưa phân công HDV nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php ?>
