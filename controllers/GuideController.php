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

    public function GuideManagement()
    {
        $guides = $this->modelGuide->getAllGuides();
        require_once './views/Admin/Quanlyhdv/quanlyhdv.php';
    }

    public function addGuide()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->modelGuide->insertGuide(
                $_POST['full_name'],
                $_POST['birth_date'],
                $_POST['phone'],
                $_POST['email'],
                $_POST['guide_type'],
                $_POST['average_rating']
            );

            header("Location: ?act=guide-management");
            exit;
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->modelGuide->updateGuide(
                $id,
                $_POST['full_name'],
                $_POST['birth_date'],
                $_POST['phone'],
                $_POST['email'],
                $_POST['guide_type'],
                $_POST['average_rating']
            );

            header("Location: ?act=guide-management");
            exit;
        }

        require_once './views/Admin/Quanlyhdv/EditGuide.php';
    }

    // ==========================
    //  PHÂN CÔNG HDV
    // ==========================

    // Hiển thị giao diện phân công
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

    // Lưu phân công
   public function saveAssignGuide()
{
    $id_tour = $_GET['id'] ?? 0;
    $id_hdv = $_POST['id_hdv'] ?? 0;
    $role = $_POST['role'] ?? '';

    // Nếu tour đã có HDV thì không cho phân công thêm
    if ($this->modelGuide->isTourAssigned($id_tour)) {
        header("Location: ?act=assign-guide&id=$id_tour&error=assigned");
        exit();
    }

    // Nếu chưa có thì tiến hành phân công
    if ($id_tour > 0 && $id_hdv > 0) {
        $this->modelGuide->assignGuideToTour($id_tour, $id_hdv, $role);
    }

    header("Location: ?act=assign-guide&id=$id_tour&success=1");
    exit();
}


    // Xóa phân công
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
