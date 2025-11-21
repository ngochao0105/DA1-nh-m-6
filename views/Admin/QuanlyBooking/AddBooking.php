<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #9CECFB, #65C7F7, #0052D4);
        min-height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding-top: 40px;
    }

    .step-wrapper {
        width: 100%;
        max-width: 780px;
        margin: 40px auto;
    }

    .step-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 35px rgba(0,0,0,0.1);
    }

    .step-header h2 {
        text-align: center;
        margin-bottom: 25px;
        font-weight: 700;
        color: #1d3557;
        font-size: 32px;
    }

    /* Progress bar */
    .progressbar {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 10px 0 0;
    }

    .progress-step {
        width: 30px;
        height: 30px;
        background: #E0E7FF;
        border-radius: 50%;
        transition: 0.3s;
    }

    .progress-step.active {
        background: #00A6FF;
    }

    .progress-line {
        width: 110px;
        height: 5px;
        background: #E0E7FF;
        border-radius: 10px;
        margin: 0 8px;
        transition: 0.3s;
    }

    .progress-line.active {
        background: #00A6FF;
    }

    .progress-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        padding: 0 20px;
        font-size: 15px;
        color: #444;
        font-weight: 500;
    }

    /* Content pages */
    .step-page {
        display: none;
        animation: fadeIn .3s ease-in-out;
        margin-top: 25px;
    }
    .step-page.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Title inside form */
    .step-title {
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 600;
        color: #1d3557;
    }

    /* Inputs */
    .input {
        width: 100%;
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #ccc;
        outline: none;
        margin: 5px 0 20px;
        font-size: 15px;
    }

    /* Buttons */
    .btn {
        padding: 10px 25px;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
        border: none;
        transition: 0.3s;
    }
    .btn.primary {
        background: #0099ff;
        color: white;
    }
    .btn.primary:hover {
        background: #007ed6;
    }

    .btn.secondary {
        background: #ccc;
    }
    .btn.secondary:hover {
        background: #b4b4b4;
    }

    .btn-group {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .add-btn {
        background: #28c76f;
        color: white;
        margin-bottom: 15px;
    }
    .add-btn:hover {
        background: #1e9d56;
    }

    /* Customer box */
    .customer-box {
        background: #f3f4f6;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 10px;
    }
</style>

<div class="step-wrapper">
    <div class="step-card">

        <!-- Header -->
        <div class="step-header">
            <h2>Tạo Booking</h2>
        </div>

        <!-- Progress Bar -->
        <div class="progressbar">
            <div class="progress-step active"></div>
            <div class="progress-line active"></div>
            <div class="progress-step"></div>
            <div class="progress-line"></div>
            <div class="progress-step"></div>
        </div>

        <div class="progress-labels">
            <span>Chọn Tour</span>
            <span>Chọn HDV</span>
            <span>Khách hàng</span>
        </div>

        <!-- FORM -->
        <form id="bookingForm" action="index.php?act=booking-save" method="POST">

            <!-- STEP 1 -->
            <div class="step-page active">
                <h3 class="step-title"> Chọn Tour</h3>

                <label>Tour</label>
                <select class="input" name="id_tour" id="tourSelect">
                    <option value="">-- Chọn tour --</option>
                    <?php foreach ($tours as $tour): ?>
                        <option value="<?= $tour['id'] ?>"><?= htmlspecialchars($tour['tour_name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Ngày khởi hành</label>
                <input type="date" class="input" name="ngay_di" id="dateSelect">

                <button type="button" class="btn primary next-btn">Tiếp tục</button>
            </div>

            <!-- STEP 2 -->
            <div class="step-page">
                <h3 class="step-title">Chọn Hướng dẫn viên</h3>

                <label>Hướng dẫn viên</label>
                <select class="input" name="id_hdv" id="hdvSelect">
                    <option value="">-- Vui lòng chọn ngày trước --</option>
                </select>

                <div class="btn-group">
                    <button type="button" class="btn secondary prev-btn">Quay lại</button>
                    <button type="button" class="btn primary next-btn">Tiếp tục</button>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="step-page">
                <h3 class="step-title">Khách hàng</h3>

                <div id="customerList"></div>

                <button type="button" class="btn add-btn" onclick="addCustomer()">+ Thêm khách</button>

                <div class="btn-group">
                    <button type="button" class="btn secondary prev-btn">Quay lại</button>
                    <button type="submit" class="btn primary">Hoàn tất Booking</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
/* STEP FORM */
const steps = document.querySelectorAll(".progress-step");
const lines = document.querySelectorAll(".progress-line");
const pages = document.querySelectorAll(".step-page");
const nextBtns = document.querySelectorAll(".next-btn");
const prevBtns = document.querySelectorAll(".prev-btn");

let currentStep = 0;

function updateStep() {
    pages.forEach((p, i) => p.classList.toggle("active", i === currentStep));
    steps.forEach((s, i) => s.classList.toggle("active", i <= currentStep));
    lines.forEach((l, i) => l.classList.toggle("active", i < currentStep));
}

nextBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        if (currentStep < pages.length - 1) currentStep++;
        updateStep();
    });
});

prevBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        if (currentStep > 0) currentStep--;
        updateStep();
    });
});

updateStep();

/* LOAD HDV VIA AJAX */
document.getElementById('dateSelect').addEventListener('change', function () {
    const date = this.value;
    if (!date) return;

    fetch("index.php?act=ajax-get-hdv&ngay_di=" + date)
        .then(res => res.text())
        .then(html => {
            document.getElementById('hdvSelect').innerHTML = html;
        });
});

/* ADD CUSTOMER */
function addCustomer() {
    const html = `
        <div class="customer-box">
            <input class="input" name="ten_khach[]" placeholder="Tên khách" required>
            <input class="input" name="sdt[]" placeholder="Số điện thoại" required>
            <textarea class="input" name="yeu_cau_dac_biet[]" placeholder="Yêu cầu đặc biệt"></textarea>
        </div>
    `;
    document.getElementById('customerList').insertAdjacentHTML('beforeend', html);
}
</script>

<?php include "views/layout/footer.php"; ?>
