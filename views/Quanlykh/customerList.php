<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<div class="container mt-4">

<h3>Danh sách khách</h3>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Tên khách</th>
            <th>SĐT</th>
            <th>Loại khách</th>
            <th>Yêu cầu đặc biệt</th>
            <th>Check-in</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($customers as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= ($item['ten_khach']) ?></td>
            <td><?= ($item['sdt']) ?></td>
            <td><?= ($item['loai_khach']) ?></td>
            <td><?= ($item['yeu_cau_dac_biet']) ?></td>
            <td><?= $item['da_checkin'] ? "Đã check-in" : "Chưa" ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>

</table>

</div>

<?php include "views/layout/footer.php"; ?>
