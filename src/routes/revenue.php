<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// require '../vendor/autoload.php';
require '../src/services/revenueService.php';
// $app = new \Slim\App;

//Revenue DAY
$app->get('/api/revenue/{year}/{month}/{day}', function ($request, $response, $args) {
    $year = $request->getAttribute('year');
    $month = $request->getAttribute('month');
    $day = $request->getAttribute('day');
    $day = $year.'-'.$month.'-'.$day;
    $service = new RevenueService();
    echo json_encode($service->RevenueDay($day));
    return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
});

//Revenue MONTH
$app->get('/api/revenue/{year}/{month}', function ($request, $response, $args) {
    $year = $request->getAttribute('year');
    $month = $request->getAttribute('month');
    $month = $year.'-'.$month.'-'.'00';
    $service = new RevenueService();
    echo json_encode($service->RevenueMonth($month));
    return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
});

//Revenue YEAR
$app->get('/api/revenue/{year}', function ($request, $response, $args) {
    $year = $request->getAttribute('year');
    $service = new RevenueService();
    echo json_encode($service->RevenueYear($year));
    return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
});



//Get Single brand
// $app->get('/api/bills/{id}', function ($request, $response, $args) {
//     $id = $request->getAttribute('id');
//     $service = new BillingService();
//     echo json_encode($service->GetSingleBill($id));
//     return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
// });
// //admin add new bill
// $app->post('/api/bills/addbyadmin', function ($request, $response, $args) {
//     // thong tin khach hang
//     $email           = $request->getParam('email');
//     $name           = $request->getParam('name');
//     $phone           = $request->getParam('phone');
//     $district           = $request->getParam('district');
//     $city           = $request->getParam('city');
//     $address           = $request->getParam('address');

//     // thong tin bill

//     $payment_method           = $request->getParam('payment_method');
//     $shipping_method           = $request->getParam('shipping_method');
//     // $total           = $request->getParam('name'); // auto 0
//     // $date           = $request->getParam('date');
//     // $status           = $request->getParam('name'); // auto 'Set up'

//     $service = new BillingService();
//     echo json_encode($service->SetupNewBill($email,$name,$phone,$city,$district,$address,$payment_method,$shipping_method));
//     return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
// });

// //change status bill
// $app->put('/api/bills/update-status/{id}/{status}', function ($request, $response, $args) {
//     $id_billing = $request->getAttribute('id');
//     $status = $request->getAttribute('status');
//     $service = new BillingService();
//     echo json_encode($service->ChangeStatusBill($id_billing,$status));
//     return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
// });


?>