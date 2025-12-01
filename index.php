<?php
// 1. Require file Common (để có session_start() và checkAuth())
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ (CÓ checkAuth() và require_file_view())

// 2. Require toàn bộ file Models (Tải 1 lần ở đầu)
require_once './models/UserModel.php';
require_once './models/TourModel.php';
require_once './models/GuideModel.php';
require_once './models/HdvModel.php';
require_once './models/BookingModel.php';
require_once './models/CustomerModel.php';
require_once './models/ScheduleModel.php';


require_once './controllers/AuthController.php';
require_once './controllers/TourController.php';
require_once './controllers/GuideController.php';
require_once './controllers/HdvController.php';
require_once './controllers/BookingController.php';
require_once './controllers/CustomerController.php';


//db

// env.php hoặc index.php


// 4. Route
$act = $_GET['act'] ?? '/';


$publicRoutes = [
    'login',

];  

if (!in_array($act, $publicRoutes)) {
    checkAuth(); // Hàm này kiểm tra xem CÓ đăng nhập hay không
}

// 7. Routing (Điều hướng)
switch ($act) {

    case 'login':
        (new AuthController())->login();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    case '/':
        (new TourController())->Home();
        break;

    case 'tour-list':
        (new TourController())->TourList();
        break;

    case 'createtour':
        (new TourController())->CreateTour();
        break;

    case 'deletetour':
        (new TourController())->DeleteTour();
        break;

    case 'edit-tour':
        (new TourController())->EditTour();
        break;
    case 'schedule-list':
    (new TourController())->scheduleList();
    break;

    case 'schedule-create':
    (new TourController())->scheduleCreate();
    break;

    case 'schedule-edit':   
    (new TourController())->scheduleEdit();
    break;

    case 'schedule-delete':
    (new TourController())->scheduleDelete();
    break;
        

    case 'booking-list':
        (new BookingController())->BookingList();
        break;
    case 'add-booking' : 
        (new BookingController())->CreateBooking();
        break;
    case 'update-booking-status':
        (new BookingController())->updateStatus();
        break;
    case 'booking-logs':
        (new BookingController())->BookingLogs();
        break;
    case 'ajax-get-schedule':
        (new BookingController())->AjaxGetSchedule();
        break;

    case 'ajax-get-hdv':
        (new BookingController())->AjaxGetHdv();
         break;

    case 'ajax-check-hdv-busy':
        (new BookingController())->AjaxCheckHdvBusy();
        break;

    case 'booking-save':
        (new BookingController())->SaveBooking();
        break;
    case 'booking-detail':
        (new BookingController())->BookingDetail();
        break;

    case 'delete-booking':
        (new BookingController())->deleteBooking();
        break;

    case 'update-payment-status':
        (new BookingController())->updatePaymentStatus();
        break;

    case 'add-customer-to-booking':
        (new BookingController())->addCustomerToBooking();
        break;

    case 'delete-customer-from-booking':
        (new BookingController())->deleteCustomerFromBooking();
        break;

    case 'ajax-check-customer-conflict':
        (new BookingController())->AjaxCheckCustomerConflict();
        break;

    case 'ajax-get-customers':
        (new BookingController())->AjaxGetCustomers();
        break;

    case 'guide-management':
        (new GuideController())->GuideManagement();
        break;

    case 'delete-guide':
        (new GuideController())->deleteGuide();
        break;

    case 'add-guide':
        (new GuideController())->addGuide();
        break;

    case 'edit-guide':
        (new GuideController())->editGuide();
        break;


   case 'assign-guide':
    (new GuideController())->assignGuide();
    break;

case 'save-assign-guide':
    (new GuideController())->saveAssignGuide();
    break;

case 'delete-assign':
    (new GuideController())->deleteAssign();
    break;


        
    case 'hdv_dashboard':
        (new HdvController())->dashboard();
        break;


    case 'hdv_my_tours':
        (new HdvController())->myTours();
        break;

    case 'hdv_tour_schedule':
        (new HdvController())->viewSchedule();
        break;

    case 'hdv_schedule_detail':
        (new HdvController())->viewScheduleDetail();
        break;

    case 'hdv_confirm_booking':
        (new HdvController())->confirmBookingAction();
        break;

    case 'hdv_update_attendance':
        (new HdvController())->updateCustomerAttendance();
        break;

// diem danh hdv
// case 'checklist':
//     $c = new CheckinController();
//     $c->checklist();
//     break;

// case 'save-checkin':
//     $c = new CheckinController();
//     $c->saveCheckin();
//     break;
// case 'checkin-list':
//     $c = new CheckinController();
//     $c->checkinList();
//     break;





  

  // Customer routes
    case 'customer-list':
        (new CustomerController())->list();
        break;

    case 'customer-add':
        (new CustomerController())->add();
        break;

    case 'customer-edit':
        (new CustomerController())->edit();
        break;

    case 'customer-delete':
        (new CustomerController())->delete();
        break;


    default:
        require_file_view('errors/404');
        break;
}
