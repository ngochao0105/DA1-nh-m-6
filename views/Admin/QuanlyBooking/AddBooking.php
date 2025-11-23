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

    /* Schedule list */
    .schedule-list {
        margin-top: 15px;
    }

    .schedule-item {
        transition: all 0.3s;
    }

    .schedule-item:hover:not(.schedule-disabled) {
        background: #e0e7ff !important;
        border-color: #3b82f6 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .schedule-item input[type="radio"]:checked + div {
        color: #1d3557;
    }

    .schedule-disabled {
        opacity: 0.6;
    }

    .schedule-status {
        display: inline-block;
    }

    #continueBtn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #ccc !important;
    }
    
    #continueHdvBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: #ccc !important;
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

            <?php if (isset($error) && !empty($error)): ?>
                <div style="background: #fee; color: #c33; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #fcc;">
                    <strong>Lỗi:</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

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

                <!-- Khu vực hiển thị lịch trình -->
                <div id="scheduleContainer"></div>

                <!-- Ẩn input ngày đi, sẽ được điền tự động từ schedule -->
                <input type="hidden" name="ngay_di" id="dateSelect">
                <input type="hidden" name="schedule_id" id="scheduleIdInput">

                <button type="button" class="btn primary next-btn" id="continueBtn" disabled>Tiếp tục</button>
            </div>

            <!-- STEP 2 -->
            <div class="step-page">
                <h3 class="step-title">Chọn Hướng dẫn viên</h3>

                <label>Hướng dẫn viên</label>
                <select class="input" name="id_hdv" id="hdvSelect" required>
                    <option value="">-- Vui lòng chọn lịch trình trước --</option>
                </select>
                
                <div id="hdvBusyWarning" style="display: none; margin-top: 10px; padding: 15px; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b; font-size: 14px;">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 18px; margin-right: 8px;"></i>
                    <strong>Không thể tiếp tục:</strong> <span id="hdvBusyMessage"></span>
                    <div style="margin-top: 8px; font-weight: 600;">
                        Vui lòng chọn HDV khác đang rảnh để tiếp tục.
                    </div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn secondary prev-btn">Quay lại</button>
                    <button type="button" class="btn primary next-btn" id="continueHdvBtn" disabled>Tiếp tục</button>
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

/* LOAD SCHEDULE VIA AJAX KHI CHỌN TOUR */
document.getElementById('tourSelect').addEventListener('change', function () {
    const tourId = this.value;
    const scheduleContainer = document.getElementById('scheduleContainer');
    const continueBtn = document.getElementById('continueBtn');
    const dateSelect = document.getElementById('dateSelect');
    const scheduleIdInput = document.getElementById('scheduleIdInput');
    
    // Reset
    scheduleContainer.innerHTML = '';
    dateSelect.value = '';
    scheduleIdInput.value = '';
    continueBtn.disabled = true;
    document.getElementById('hdvSelect').innerHTML = '<option value="">-- Vui lòng chọn lịch trình trước --</option>';
    
    if (!tourId) {
        return;
    }
    
    // Hiển thị loading
    scheduleContainer.innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Đang tải lịch trình...</div>';
    
    fetch("index.php?act=ajax-get-schedule&tour_id=" + tourId)
        .then(res => res.text())
        .then(html => {
            scheduleContainer.innerHTML = html;
            
            // Thêm event listener cho các radio button schedule
            const scheduleRadios = scheduleContainer.querySelectorAll('input[name="schedule_id"]');
            scheduleRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        const startDate = this.getAttribute('data-start-date');
                        dateSelect.value = startDate;
                        scheduleIdInput.value = this.value;
                        continueBtn.disabled = false;
                        
                        // Load HDV cho ngày này
                        loadHdvForDate(startDate);
                    }
                });
            });
        })
        .catch(err => {
            scheduleContainer.innerHTML = '<div style="padding: 20px; text-align: center; color: #ef4444;">Lỗi khi tải lịch trình</div>';
        });
});

/* LOAD HDV VIA AJAX */
function loadHdvForDate(date) {
    if (!date) {
        document.getElementById('hdvSelect').innerHTML = '<option value="">-- Vui lòng chọn lịch trình trước --</option>';
        document.getElementById('hdvBusyWarning').style.display = 'none';
        document.getElementById('continueHdvBtn').disabled = true;
        return;
    }

    const hdvSelect = document.getElementById('hdvSelect');
    const hdvBusyWarning = document.getElementById('hdvBusyWarning');
    const continueBtn = document.getElementById('continueHdvBtn');
    
    hdvSelect.innerHTML = '<option value="">Đang tải...</option>';
    hdvSelect.disabled = true;
    hdvBusyWarning.style.display = 'none';

    fetch("index.php?act=ajax-get-hdv&ngay_di=" + date)
        .then(res => res.text())
        .then(html => {
            hdvSelect.innerHTML = html;
            hdvSelect.disabled = false;
            
            // Kiểm tra nếu có HDV được chọn
            checkHdvSelected();
        })
        .catch(err => {
            hdvSelect.innerHTML = '<option value="">Lỗi khi tải HDV</option>';
            hdvSelect.disabled = false;
            hdvBusyWarning.style.display = 'none';
        });
}

// Kiểm tra HDV đã được chọn chưa và có bận không
function checkHdvSelected() {
    const hdvSelect = document.getElementById('hdvSelect');
    const continueBtn = document.getElementById('continueHdvBtn');
    const hdvBusyWarning = document.getElementById('hdvBusyWarning');
    const hdvBusyMessage = document.getElementById('hdvBusyMessage');
    const dateSelect = document.getElementById('dateSelect');
    
    if (hdvSelect.value && hdvSelect.value !== '' && dateSelect.value) {
        // Kiểm tra xem option được chọn có bận không
        const selectedOption = hdvSelect.options[hdvSelect.selectedIndex];
        const isBusy = selectedOption.getAttribute('data-busy') === 'true';
        
        if (isBusy) {
            // Gọi AJAX để lấy thông tin chi tiết
            fetch(`index.php?act=ajax-check-hdv-busy&hdv_id=${hdvSelect.value}&ngay_di=${dateSelect.value}`)
                .then(res => res.json())
                .then(data => {
                    if (data.busy) {
                        hdvBusyMessage.textContent = data.message;
                        hdvBusyWarning.style.display = 'block';
                        // KHÔNG cho phép tiếp tục nếu HDV đã bận
                        continueBtn.disabled = true;
                        continueBtn.style.background = '#ccc';
                        continueBtn.style.cursor = 'not-allowed';
                        continueBtn.title = 'Vui lòng chọn HDV rảnh để tiếp tục';
                    } else {
                        hdvBusyWarning.style.display = 'none';
                        continueBtn.disabled = false;
                        continueBtn.style.background = '#0099ff';
                        continueBtn.style.cursor = 'pointer';
                        continueBtn.title = '';
                    }
                })
                .catch(err => {
                    console.error('Lỗi khi kiểm tra HDV:', err);
                    // Nếu lỗi, vẫn disable để an toàn
                    continueBtn.disabled = true;
                });
        } else {
            // HDV rảnh - cho phép tiếp tục
            hdvBusyWarning.style.display = 'none';
            continueBtn.disabled = false;
            continueBtn.style.background = '#0099ff';
            continueBtn.style.cursor = 'pointer';
            continueBtn.title = '';
        }
    } else {
        hdvBusyWarning.style.display = 'none';
        continueBtn.disabled = true;
        continueBtn.style.background = '#0099ff';
        continueBtn.style.cursor = 'not-allowed';
    }
}

// Lắng nghe sự kiện thay đổi HDV
document.getElementById('hdvSelect').addEventListener('change', function() {
    checkHdvSelected();
});

document.getElementById('dateSelect').addEventListener('change', function () {
    loadHdvForDate(this.value);
});

/* ADD CUSTOMER */
function addCustomer() {
    const html = `
        <div class="customer-box">
            <input class="input" name="ten_khach[]" placeholder="Tên khách" required>
            <input class="input" name="sdt[]" placeholder="Số điện thoại" required>
            <select class="input" name="loai_khach[]" required>
                <option value="nguoi_lon">Người lớn</option>
                <option value="tre_em">Trẻ em</option>
                <option value="em_be">Em bé</option>
            </select>
            <textarea class="input" name="yeu_cau_dac_biet[]" placeholder="Yêu cầu đặc biệt"></textarea>
        </div>
    `;
    document.getElementById('customerList').insertAdjacentHTML('beforeend', html);
}
</script>

<?php include "views/layout/footer.php"; ?>
