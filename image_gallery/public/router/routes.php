<?php
use Slim\Http\Response;
use Slim\Http\Request;

//user registration
$app->post("/register",function (Request $request,Response $response,$args) use ($controller) {
   $photographer_name=$request->getParam('photographer_name');
    $email=$request->getParam('email');
    $password=$request->getParam('password');
    $age=$request->getParam('age');
    $gender=$request->getParam('gender');
    $insert=$controller->registerPhotographer($photographer_name,$email,$password,$age,$gender);
    return $response->withJson([
       "photographer_name"=>$photographer_name,
       "email"=>$insert
    ]);
});
//user login
$app->post("/login",function (Request $request,Response $response,$args) use ($loginController) {
    $email=$request->getParam('email');
    $password=$request->getParam('password');
    $validate=$loginController->photographerLogin($email,$password);
    if(empty($validate)){
        return $response->withJson([
            "message"=>"invalid user or photographer"
        ]);
    }
    return $response->withJson([
        "email"=>$validate
    ]);
});

//posting images
$app->POST("/images",function (Request $request,Response $response,$args) use ($query) {
    $userId = $request->getParam('userid');
    $url = $request->getParam('url');
    $cat = $request->getParam('cat');
    $q = $query->postImages($userId,$url,$cat);
    return $response->withJson([
       "userid"=>$userId,
       "image_url"=>$url,
       "category"=>$cat
    ]);
});

//fetching user specific images
$app->get("/user",function (Request $request,Response $response,$args) use ($query) {
    $userid = $request->getParam("userid");
    $res=$query->fetchUserSpecificImages($userid);
    $json = json_encode($res);
    return $json;
});

//fetching all images and displaying to user
$app->get("/view/images",function (Request $request,Response $response,$args) use ($query) {
    //$userid = $request->getParam("userid");
    $res=$query->fetchAll();
    $json = json_encode($res);
    return $json;
});

//update photographer
$app->put("/user",function(Request $request, Response $response, array $args) use ($query) {
    $id = $request->getParam("userid");
    $age = $request->getParam("age");
    $name = $request->getParam("photographer_name");
    $gender = $request->getParam("gender");
    $res1=$query->modifyPhotographer($id,$name,$age,$gender);
    return $response->withJson($res1);
});

//Delete image from Database

$app->delete('/image',function (Request $request,Response $response,$args) use ($query) {
    $userid = $request->getParam('userid');
    $image_id = $request->getParam('image_id');
    $rImage=$query->removeImage($userid,$image_id);
    return $response->withJson([
        "userid"=>$userid,
        "image_id"=>$image_id
    ]);
});
