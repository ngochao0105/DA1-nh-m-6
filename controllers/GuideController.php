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

    public function addGuide()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = trim($_POST['full_name'] ?? '');
            $birth_date = trim($_POST['birth_date'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $guide_type = trim($_POST['guide_type'] ?? '');
            $competency_level = trim($_POST['competency_level'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Validation
            if (empty($full_name)) {
                $error = "Vui lòng nhập tên hướng dẫn viên";
            } elseif (empty($phone)) {
                $error = "Vui lòng nhập số điện thoại";
            } elseif (empty($username)) {
                $error = "Vui lòng nhập tên đăng nhập";
            } elseif (empty($password)) {
                $error = "Vui lòng nhập mật khẩu";
            } elseif (strlen($password) < 6) {
                $error = "Mật khẩu phải có ít nhất 6 ký tự";
            } else {
                $userModel = new UserModel();
                $existingUser = $userModel->getByUsername($username);

                if ($existingUser) {
                    $error = "Tên đăng nhập đã tồn tại";
                } else {
                    try {
                        $this->modelGuide->insertGuide(
                            $full_name,
                            $birth_date ?: null,
                            $phone,
                            $email,
                            $guide_type,
                            $competency_level,
                            $username,
                            $password
                        );

                        header("Location: ?act=guide-management");
                        exit;
                    } catch (Exception $e) {
                        $error = "Lỗi khi thêm hướng dẫn viên: " . $e->getMessage();
                    }
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
            $competency_level = trim($_POST['competency_level'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($full_name)) {
                $error = "Vui lòng nhập tên hướng dẫn viên";
            } elseif (empty($phone)) {
                $error = "Vui lòng nhập số điện thoại";
            } elseif (empty($username)) {
                $error = "Vui lòng nhập tên đăng nhập";
            } else {
                $userModel = new UserModel();
                $existingUser = $userModel->getByUsername($username);

                if ($existingUser && $existingUser['id'] != ($guide['id_taikhoan'] ?? 0)) {
                    $error = "Tên đăng nhập đã tồn tại";
                } else {
                    try {
                        $this->modelGuide->updateGuide(
                            $id,
                            $full_name,
                            $birth_date ?: null,
                            $phone,
                            $email,
                            $guide_type,
                            $competency_level,
                            $username,
                            $password ?: null
                        );

                        header("Location: ?act=guide-management");
                        exit;
                    } catch (Exception $e) {
                        $error = "Lỗi khi cập nhật hướng dẫn viên: " . $e->getMessage();
                    }
                }
            }
        }

        require_once './views/Admin/Quanlyhdv/EditGuide.php';
    }

    // ==========================
    // PHÂN CÔNG HDV
    // ==========================

    public function assignGuide()
    {
        $id_tour = $_GET['id'] ?? 0;

        if ($id_tour <= 0) {
            header("Location: ?act=tour-list");
            exit();
        }

        $tour = $this->tourModel->getTourById($id_tour);
        $guides = $this->modelGuide->getAllGuides();
        $assignedGuides = $this->modelGuide->getAssignedGuidesByTour($id_tour);

        require_once './views/Admin/Quanlytour/assign_guide.php';
    }

    public function saveAssignGuide()
    {
        $id_tour = $_GET['id'] ?? 0;
        $id_hdv = $_POST['id_hdv'] ?? 0;
        $role = $_POST['role'] ?? '';

        if ($this->modelGuide->isTourAssigned($id_tour)) {
            header("Location: ?act=assign-guide&id=$id_tour&error=assigned");
            exit();
        }

        if ($id_tour > 0 && $id_hdv > 0) {
            $this->modelGuide->assignGuideToTour($id_tour, $id_hdv, $role);
        }

        header("Location: ?act=assign-guide&id=$id_tour&success=1");
        exit();
    }

    public function deleteAssign()
    {
        $assign_id = $_GET['id'];
        $id_tour = $_GET['tour'];

        if ($assign_id > 0) {
            $this->modelGuide->deleteAssign($assign_id);
        }

        header("Location: ?act=assign-guide&id=" . $id_tour);
        exit();
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

