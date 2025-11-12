<?php

class CategoryController
{
    public $modelTour;

    public function __construct()
    {
       
        $this->modelTour = new CategoryModel();
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
}
