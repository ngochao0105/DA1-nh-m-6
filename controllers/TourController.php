<?php

class TourController
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

    public function TourList()
    {
        
        $categories = $this->modelTour->getAllTour();
       
        require_once "./views/tours/TourList.php";
    }
    public function CreateTour()
    {
     $categories = $this->modelTour->getAllTour();
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
        
        require_once "./views/tours/createtour.php";
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
