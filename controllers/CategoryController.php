<?php

class CategoryController
{
    public $modelTour;

    public function __construct()
    {
       
        $this->modelTour = new TourModel();
    }

    public function Home()
    {
        require_once './views/trangchu.php';
    }

    public function TourCategory()
    {
        
        $categories = $this->modelTour->getAllCategories();
       
        require_once "./views/TourCategory.php";
    }
    public function CreateTour()
    {
     $categories = $this->modelTour->getAllCategories();
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
            $this->modelTour->CreateTour(
            $tour_name,
            $description,
            $start_date,
            $end_date,
            $destination,
            $price,
            $id_danh_muc,
            $status
        );
        header("Location: index.php?act=tour-category");
    }
}
        
        require_once "./views/tours/createtour.php";
    }
}
