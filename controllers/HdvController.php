<?php

class HdvController
{
    public function dashboard()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            header("Location: ?act=login");
            exit;
        }

        $model = new HdvModel();
        $profile = $model->getHdvInfoByAccountId($_SESSION['user_id']);

        if (!$profile) {
            echo "Không tìm thấy hồ sơ HDV.";
            exit;
        }

        $assignedTours = $model->getAssignedTours($profile['id']);

        require_file_view("HDV/hdv_dashboard", [
            'profile' => $profile,
            'assignedTours' => $assignedTours
        ]);
    }

    public function myTours()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            header("Location: ?act=login");
            exit;
        }

        $model = new HdvModel();
        $profile = $model->getHdvInfoByAccountId($_SESSION['user_id']);

        $assignedTours = $model->getAssignedTours($profile['id']);

        require_file_view("HDV/hdv_my_tours", [
            'assignedTours' => $assignedTours
        ]);
    }

    public function CustomerTour()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            header("Location: ?act=login");
            exit;
        }

        $model = new HdvModel();
        $profile = $model->getHdvInfoByAccountId($_SESSION['user_id']);

        $customers = $model->getTourCustomers($profile['id']);

        require_file_view("HDV/CustomersList", [
            'profile' => $profile,
            'customers' => $customers
        ]);
    }
}
