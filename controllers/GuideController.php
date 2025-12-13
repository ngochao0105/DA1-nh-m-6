<?php

class GuideController
{
    private $modelGuide;
    private $tourModel;

    public function __construct()
    {
        $this->modelGuide = new GuideModel();
        $this->tourModel = new TourModel();
    }

    // ==========================
    // QUẢN LÝ HDV
    // ==========================

    // ✔ HÀM DUY NHẤT — ĐÃ SỬA HỖ TRỢ TÌM KIẾM
    public function GuideManagement()
    {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";
        $guides = $this->modelGuide->getAllGuides($keyword);

        require_once './views/Admin/Quanlyhdv/quanlyhdv.php';
    }

    // Xem chi tiết HDV
    public function viewGuideDetail()
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: ?act=guide-management");
            exit();
        }

        // Lấy thông tin HDV
        $guide = $this->modelGuide->getGuideById($id);
        if (!$guide) {
            header("Location: ?act=guide-management");
            exit();
        }

        // Lấy danh sách tour HDV đang dẫn
        $assignedTours = $this->modelGuide->getTourAssignedForGuide($id);

        // Đếm số tour
        $totalTours = count($assignedTours);

        require_once './views/Admin/Quanlyhdv/GuideDetail.php';
    }

    public function addGuide()
    {
        $error = '';
        $formData = [
            'full_name' => '',
            'birth_date' => '',
            'phone' => '',
            'email' => '',
            'guide_type' => '',
            'license_type' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = trim($_POST['full_name'] ?? '');
            $birth_date = trim($_POST['birth_date'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $guide_type = trim($_POST['guide_type'] ?? '');
            $license_type_display = trim($_POST['license_type'] ?? '');
            
            // Lưu dữ liệu form để hiển thị lại khi có lỗi
            $formData = [
                'full_name' => $full_name,
                'birth_date' => $birth_date,
                'phone' => $phone,
                'email' => $email,
                'guide_type' => $guide_type,
                'license_type' => $license_type_display
            ];
            
            // Convert license_type từ tiếng Việt sang enum database
            $license_type_map = [
                'Nội địa' => 'noi_dia',
                'Quốc tế' => 'quoc_te',
                'Thực tập' => 'khong_co'
            ];
            $license_type = $license_type_map[$license_type_display] ?? '';

            // Validation
            if (empty($full_name)) {
                $error = "Vui lòng nhập tên hướng dẫn viên";
            } elseif (strlen(trim($full_name)) < 2) {
                $error = "Tên hướng dẫn viên phải có ít nhất 2 ký tự";
            } elseif (empty($birth_date)) {
                $error = "Vui lòng nhập ngày sinh";
            } elseif (empty($phone)) {
                $error = "Vui lòng nhập số điện thoại";
            } elseif (!preg_match('/^[0-9]{9,11}$/', $phone)) {
                // Chỉ cho phép số, độ dài 9–11 ký tự (bạn có thể chỉnh lại theo quy định của dự án)
                $error = "Số điện thoại không hợp lệ (chỉ gồm số, 9–11 ký tự)";
            } elseif (empty($email)) {
                $error = "Vui lòng nhập email";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Email không đúng định dạng";
            } elseif (empty($guide_type)) {
                $error = "Vui lòng chọn loại hướng dẫn";
            } elseif (empty($license_type_display)) {
                $error = "Vui lòng chọn loại thẻ hướng dẫn";
            } else {
                // Tự động tạo tài khoản đăng nhập ẩn cho HDV (không hiển thị trên form)
                // Username mặc định dựa trên số điện thoại hoặc họ tên + timestamp
                $baseUsername = !empty($phone)
                    ? preg_replace('/\D/', '', $phone)
                    : 'hdv' . time();

                $username = 'hdv_' . $baseUsername;

                // Đảm bảo username là duy nhất
                $userModel = new UserModel();
                $suffix = 1;
                $uniqueUsername = $username;
                while ($userModel->getByUsername($uniqueUsername)) {
                    $uniqueUsername = $username . '_' . $suffix;
                    $suffix++;
                }

                try {
                    $this->modelGuide->insertGuide(
                        $full_name,
                        $birth_date ?: null,
                        $phone,
                        $email,
                        $guide_type,
                        $license_type,
                        $uniqueUsername
                    );

                    header("Location: ?act=guide-management");
                    exit;
                } catch (Exception $e) {
                    $error = "Lỗi khi thêm hướng dẫn viên: " . $e->getMessage();
                }
            }
        }

        require_once './views/Admin/Quanlyhdv/AddGuide.php';
    }

    public function deleteGuide()
    {
        $id = $_GET['id'] ?? 0;

        if ($id > 0) {
            $this->modelGuide->deleteGuide($id);
            $this->modelGuide->resetGuideIds();
        }

        header("Location: ?act=guide-management");
        exit;
    }

    public function editGuide()
    {
        $id = $_GET['id'] ?? 0;
        $guide = $this->modelGuide->getGuideById($id);
        $error = '';

        if (!$guide) {
            header("Location: ?act=guide-management");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = trim($_POST['full_name'] ?? '');
            $birth_date = trim($_POST['birth_date'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $guide_type = trim($_POST['guide_type'] ?? '');
            $license_type_display = trim($_POST['license_type'] ?? '');
            
            // Convert license_type từ tiếng Việt sang enum database
            $license_type_map = [
                'Nội địa' => 'noi_dia',
                'Quốc tế' => 'quoc_te',
                'Thực tập' => 'khong_co'
            ];
            $license_type = $license_type_map[$license_type_display] ?? '';

            if (empty($full_name)) {
                $error = "Vui lòng nhập tên hướng dẫn viên";
            } elseif (empty($phone)) {
                $error = "Vui lòng nhập số điện thoại";
            } else {
                // Khi sửa HDV, không cho chỉnh sửa tài khoản đăng nhập từ form
                // Giữ nguyên tài khoản hiện tại (nếu có), chỉ cập nhật thông tin nhân sự
                try {
                    $this->modelGuide->updateGuide(
                        $id,
                        $full_name,
                        $birth_date ?: null,
                        $phone,
                        $email,
                        $guide_type,
                        $license_type,
                        null       // không thay đổi username
                    );

                    header("Location: ?act=guide-management");
                    exit;
                } catch (Exception $e) {
                    $error = "Lỗi khi cập nhật hướng dẫn viên: " . $e->getMessage();
                }
            }
        }

        require_once './views/Admin/Quanlyhdv/EditGuide.php';
    }

    // ==========================
    // Trang HDV xem tour phân công
    // ==========================

    public function myAssignedTours()
    {
        $id_hdv = $_SESSION['user_id'] ?? 0;

        if ($id_hdv <= 0) {
            header("Location: ?act=login");
            exit();
        }

        $assignedTours = $this->modelGuide->getTourAssignedForGuide($id_hdv);

        require_once './views/HDV/my_tours.php';
    }
}

