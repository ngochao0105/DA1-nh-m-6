<?php

class TourController
{
       public $modelTour;
    public $modelGuide;

    public function __construct()
    {
        $this->modelTour = new TourModel();
        $this->modelGuide = new GuideModel();
    }

    public function Home()
    {
        // Lấy tổng số Tour
        $totalTour = $this->modelTour->countTours();

        // Lấy tổng số hướng dẫn viên
        $totalHDV = $this->modelGuide->countGuide();

        // Truyền dữ liệu sang View
        require_once './views/Admin/trangchu.php';
    }
    public function TourList()
    {
        
        $categories = $this->modelTour->getAllTour();
       
        require_once "./views/Admin/Quanlytour/TourList.php";
    }
    public function CreateTour()
    {
     $categories = $this->modelTour->getCategories();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $tour_name = $_POST['tour_name'];
            $description = $_POST['description'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $destination = $_POST['destination'];
            $price = $_POST['price'];
            $id_danh_muc = $_POST['id_danh_muc'];
            $status = $_POST['status'];
        if($tour_name === '' || $description === '' || $start_date=== '' || $end_date==='' || $destination==='' || $price==='' || $id_danh_muc==='' || $status===''){

        }else{
            $this->modelTour->createTour(
            $tour_name,
            $description,
            $start_date,
            $end_date,
            $destination,
            $price,
            $id_danh_muc,
            $status
        );
        header("Location: index.php?act=tour-list");
    }
        }
        
        require_once "./views/Admin/Quanlytour/createtour.php";
    }
    public function DeleteTour() 
    {
        if(!isset($_GET['id'])){
        header("Location: index.php?act=tour-list");
        exit;
        }
        $id = $_GET['id'];
        $this->modelTour->deleteTour($id);
         header("Location: index.php?act=tour-list");
         exit;
    }
}
