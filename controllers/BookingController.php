<?php
class BookingController 
{
    public $modelBooking;

    public function __construct()
    {
        $this->modelBooking = new BookingModel();
    }
    public function BookingList() 
    {
    $bookings = $this->modelBooking->getAllBooking();

        require_once "./views/Admin/QuanlyBooking/BookingList.php";
    }
}