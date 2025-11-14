<?php

class GuideController
{
    public $modelGuide;

    public function __construct()
    {
        $this->modelGuide = new GuideModel();
    }

    public function GuideManagement()
    {
        $guides = $this->modelGuide->getAllGuides();
        require_once './views/Quanlyhdv/quanlyhdv.php';
    }
//thêm hdv
public function addGuide()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $full_name = $_POST['full_name'] ?? '';
            $birth_date = $_POST['birth_date'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            $guide_type = $_POST['guide_type'] ?? '';
            $average_rating = $_POST['average_rating'] ?? 0;

            if (!empty($full_name) && !empty($phone)) {
                $this->modelGuide->insertGuide($full_name, $birth_date, $phone, $email, $guide_type, $average_rating);
                header("Location: ?act=guide-management");
                exit();
            }
        }
        require_once './views/Quanlyhdv/AddGuide.php';
    }


     public function deleteGuide()
    {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $this->modelGuide->deleteGuide($id);
             $this->modelGuide->resetGuideIds();
        }
        header("Location: ?act=guide-management");
        exit();
    }


//sưae hdv

 public function editGuide()
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: ?act=guide-management");
            exit();
        }

        // Lấy dữ liệu hiện tại
        $guide = $this->modelGuide->getGuideById($id);
        if (!$guide) {
            header("Location: ?act=guide-management");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = $_POST['full_name'] ?? '';
            $birth_date = $_POST['birth_date'] ?? null;
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            $guide_type = $_POST['guide_type'] ?? '';
            $average_rating = $_POST['average_rating'] ?? 0;

            // Thực hiện update
            $this->modelGuide->updateGuide(
                $id,
                $full_name,
                $birth_date,
                $phone,
                $email,
                $guide_type,
                $average_rating
            );

            header("Location: ?act=guide-management");
            exit();
        }

        require_once './views/Quanlyhdv/EditGuide.php';
    }






}
?>