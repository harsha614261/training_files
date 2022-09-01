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

$app->get("/userid/{userid}",function (Request $request,Response $response,$args) use ($pdo) {
    $userid = $args['userid'];
    $query = $pdo->prepare("select photographer.pid,photographer.photographer_name,image.image_id,image.image,image.image_cid,category.category from photographer JOIN image ON photographer.pid=image.image_pid and photographer.pid='$userid' JOIN category ON image.image_cid=category.cid");
    $query->execute();
    $res = $query->rowCount();
    return $res;

});