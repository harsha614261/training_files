<?php
//phpinfo();

$config = ['settings' => ['displayErrorDetails' => true]];
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';


$app = new App(['settings' => $config]);

$container = $app->getContainer();
$query=require "models/DataBase.php";

$controller = require "controllers/controllerMiddleware.php";
$loginController = require "controllers/controllerMiddlewareForLogin.php";
require __DIR__ . '/router/routes.php';


////fetching username by using id
////$app->get('/userid/{id}',function(Request $request,Response $response,$args) use ($pdo, $container) {
////    $id = $args['id'];
////    $check = $pdo->prepare("select * from photographer where pid='$id'");
////    $check->execute();
////    $res = $check->fetchColumn(1);
////    $a = json_encode($res);
////    return $a;
////});
//
////fetching user specific images
////$app->get("/user/{userid}",function (Request $request,Response $response,$args) use ($pdo) {
////    $userid = $args['userid'];
////    $query = $pdo->prepare("select photographer.pid,photographer.photographer_name,image.image_id,image.image,image.image_cid,category.category from photographer JOIN image ON photographer.pid=image.image_pid and photographer.pid='$userid' JOIN category ON image.image_cid=category.cid");
////    $query->execute();
////    $res = $query->fetchAll(PDO::FETCH_ASSOC);
////    $json = json_encode($res);
////    return $json;
////});
//
////fetching all images and displaying to user
//$app->get("/view_images",function (Request $request,Response $response,$args) use ($pdo) {
//    $userid = $args['userid'];
//    $query = $pdo->prepare("select photographer.photographer_name,image.image,category.category from photographer JOIN image ON photographer.pid=image.image_pid JOIN category ON image.image_cid=category.cid");
//    $query->execute();
//    $res = $query->fetchAll(PDO::FETCH_ASSOC);
//    $json = json_encode($res);
//    return $json;
//});
//
////update photographer age
//$app->put("/user/age",function(Request $request, Response $response, array $args) use ($pdo, $container) {
//    $id = $request->getParam("userid");
//    $age = $request->getParam("age");
//    $check =$pdo->prepare("update photographer set age='$age' where pid='$id'");
//    $check->execute();
//    //$res = $check->fetchColumn(1);
//    $res = "updated age";
//    return json_encode($res);
//});
//
////Delete image from Database
//
//$app->delete('/delete/{userid}/{image_id}',function (Request $request,Response $response,$args) use ($pdo) {
//    $userid = $args['userid'];
//    $image_id = $args['image_id'];
//    $q = $pdo->prepare();
//});

$app->run();