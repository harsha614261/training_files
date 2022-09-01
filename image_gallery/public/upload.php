<?php

$config = ['settings' => ['displayErrorDetails' => true]];
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

require __DIR__. '/../vendor/autoload.php';


$app = new App(['settings' => $config]);

$container = $app->getContainer();

$pdo=require_once "DataBase.php";

//uploading an image into database
$app->POST("/user/{userid}/upload/{url}/{cat}",function (Request $request,Response $response,$args) use ($pdo) {
    $userId = $args['userid'];
    $url = $args['url'];
    $cat = $args['cat'];
    $q = $pdo->prepare("insert into category (category,cat_pid) values ('$cat','$userId')");
    $q->execute();
    $q1 = $pdo->prepare("select * from category where cat_pid='$userId' and category='$cat'");
    $q1->execute();
    $da = $q1->fetchColumn(0);
    $q2 = $pdo->prepare("insert into image (image,image_pid,image_cid) values ('$url','$userId','$da')");
    $q2->execute();
    $result = "image uploaded successfully";
    return json_encode($result);
});

$app->run();