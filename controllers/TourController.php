<?php
// có class chứa các function thực thi xử lý logic 
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
}
