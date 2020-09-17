<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// require '../vendor/autoload.php';
require '../src/models/categoryModel.php';
// $app = new \Slim\App;


//Get All Categories
$app->get('/api/categories', function ($request, $response, $args) {
    $model = new CategoryModel();
    echo json_encode($model->GetAll());
    return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
});

//Get Single Category
$app->get('/api/categories/{id}', function ($request, $response, $args) {
    $id = $request->getAttribute('id');
    $model = new CategoryModel();
    echo json_encode($model->GetSingle($id));
    return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
});

//add category
$app->post('/api/categories/add', function ($request, $response, $args) {
    $tenloai = $request->getParam('tenloai');
    $model = new CategoryModel();
    echo json_encode($model->Add($tenloai));
    return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
});

// update category
$app->put('/api/categories/update/{id}', function ($request, $response, $args) {

    $id = $request->getAttribute('id');
    $tenloai = $request->getParam('tenloai');

    $model = new CategoryModel();
    echo json_encode($model->Update($id,$tenloai));
    return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
});

//delete category
$app->delete('/api/categories/delete/{id}', function ($request, $response, $args) {

    $id = $request->getAttribute('id');

    $model = new CategoryModel();
    echo json_encode($model->Delete($id));
    return $response->withHeader('Content-type', 'application/json;charset=UTF-8');
});