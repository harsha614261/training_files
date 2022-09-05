<?php

class Images
{
    public static function uploadImages($request,$response,$userId,$query){
        $url = $request->getParam('url');
        $cat = $request->getParam('category');
        $q = $query->postImages($userId,$url,$cat);
        return $response->withJson([
            "userid"=>$userId,
            "image_url"=>$url,
            "category"=>$cat
        ]);
    }

    public static function userImages($request,$response,$userid,$query){
        $res=$query->fetchUserSpecificImages($userid);
        $json = json_encode($res);
        return $json;
    }

    public static function fetchAllImages($query){
        $res=$query->fetchAll();
        $json = json_encode($res);
        return $json;
    }

    public static function removeImages($request,$response,$image_id,$query){
        $rImage=$query->removeImage($image_id);
        return $response->withJson([
            "image_id"=>$image_id
        ]);
    }
}