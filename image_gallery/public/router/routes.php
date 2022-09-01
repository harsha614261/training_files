<?php
use Slim\Http\Response;
use Slim\Http\Request;
$pdo = require 'models/DataBase.php';

//posting images
$app->POST("/images",function (Request $request,Response $response,$args) use ($pdo) {
    $userId = $request->getParam('userid');
    $url = $request->getParam('url');
    $cat = $request->getParam('cat');
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

//fetching user specific images
$app->get("/user",function (Request $request,Response $response,$args) use ($pdo) {
    $userid = $request->getParam("userid");
    $query = $pdo->prepare("select photographer.pid,photographer.photographer_name,image.image_id,image.image,image.image_cid,category.category from photographer JOIN image ON photographer.pid=image.image_pid and photographer.pid='$userid' JOIN category ON image.image_cid=category.cid");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $json = json_encode($res);
    return $json;
});

//fetching all images and displaying to user
$app->get("/view/images",function (Request $request,Response $response,$args) use ($pdo) {
    //$userid = $request->getParam("userid");
    $query = $pdo->prepare("select photographer.photographer_name,image.image,category.category from photographer JOIN image ON photographer.pid=image.image_pid JOIN category ON image.image_cid=category.cid");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $json = json_encode($res);
    return $json;
});

//update photographer
$app->put("/user",function(Request $request, Response $response, array $args) use ($pdo, $container) {
    $id = $request->getParam("userid");
    $age = $request->getParam("age");
    $name = $request->getParam("photographer_name");
    $gender = $request->getParam("gender");
    $partialQuery = "update photographer set";
    $j=0;
    $k=0;
    if(!empty($name)){
        $partialQuery .=" photographer_name='$name'";
        $j=1;
    }
    if(!empty($age)){
        if($j==1){
            $partialQuery .=", age='$age'";
            $k=1;
        }
        else{
            $partialQuery .=" age='$age'";
            $k=1;
        }
    }
    if(!empty($gender)){
        if($k==1){
            $partialQuery .=", gender='$gender'";
        }
        else{
            $partialQuery .=" gender='$gender'";
        }
    }
    $partialQuery .= " where pid='$id'";
    $partialQuery .= ";";
    //return $partialQuery;
    $check =$pdo->prepare($partialQuery);
    $check->execute();
    $res = $pdo->prepare("select pid,photographer_name,email,age,gender from photographer where pid='$id'");
    $res->execute();
    $res1 = $res->fetchAll(PDO::FETCH_ASSOC);
    return $response->withJson($res1);
});

//Delete image from Database

$app->delete('/user',function (Request $request,Response $response,$args) use ($pdo) {
    $userid = $request->getParam('userid');
    $image_id = $request->getParam('image_id');
    $cat = $pdo->prepare("select * from image where image_id='$image_id' and image_pid='$userid'");
    $cat->execute();
    $data = $cat->fetchColumn(3);
    $query = $pdo->prepare("delete from image where image_pid='$userid' and image_id='$image_id'");
    $query->execute();
    $q = $pdo->prepare("delete from category where cid='$data'");
    $q->execute();
    return "done";
});
