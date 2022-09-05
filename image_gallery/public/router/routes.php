<?php
use Slim\Http\Response;
use Slim\Http\Request;


//user registration
$app->post("/user",function (Request $request,Response $response,$args) use ($query) {
    return User::registerUser($request,$response,$args,$query);
});

//user login
$app->post("/login",function (Request $request,Response $response,$args) use ($query) {
    return User::userLogin($request,$response,$args,$query);
});

//posting images
$app->POST("/user/{userid}/images",function (Request $request,Response $response,$args) use ($sqlQuery) {
    return Images::uploadImages($request,$response,$args['userid'],$sqlQuery);
});

//fetching user specific images
$app->get("/user/{userid}/images",function (Request $request,Response $response,$args) use ($sqlQuery) {
    return Images::userImages($request,$response,$args['userid'],$sqlQuery);
});

//fetching all images and displaying to user
$app->get("/view/images",function (Request $request,Response $response,$args) use ($sqlQuery) {
    return Images::fetchAllImages($sqlQuery);
});

//update photographer
$app->put("/user/{userid}",function(Request $request, Response $response, array $args) use ($query) {
    return User::updateUser($request,$response,$args['userid'],$query);
});

//Delete image from Database
$app->delete('/image/{imageid}',function (Request $request,Response $response,$args) use ($sqlQuery) {
    return Images::removeImages($request,$response,$args['imageid'],$sqlQuery);
});
