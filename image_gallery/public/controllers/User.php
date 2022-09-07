<?php

class User
{
    public static function registerUser($request,$response,$args,$query){
        $photographer_name=$request->getParam('photographer_name');
        $email=$request->getParam('email');
        $password=$request->getParam('password');
        $age=$request->getParam('age');
        $gender=$request->getParam('gender');
        $sanitizedName = filter_var($photographer_name,
            FILTER_UNSAFE_RAW);
        $sanitizedAge = filter_var($age,
            FILTER_SANITIZE_NUMBER_INT);
        $sanitizedEmail = filter_var($email,
            FILTER_SANITIZE_EMAIL);
        $sanitizedGender = filter_var($gender,
            FILTER_UNSAFE_RAW);
        $insert=$query->photographerRegistration($photographer_name,$email,sha1($password),$age,$gender);
        if(count($insert)==1) {
            return $response->withJson([
                "message"=>$insert[0]
            ]);
        }
        else{
            return $response->withJson([
                "pid"=>$insert[1],
                "photographer_name"=>$photographer_name,
                "email"=>$insert[0]

            ]);
        }


    }
    public static function userLogin($request,$response,$args,$query){

        $email = $request->getParam('email');
        $password = $request->getParam('password');
        $sanitizedEmail = filter_var($email,
            FILTER_SANITIZE_EMAIL);
        $validate = $query->photographerLogin($sanitizedEmail, sha1($password));

        if (empty($validate)) {
            return $response->withJson([
                "message" => "invalid user or photographer"
            ]);
        }
        if(count($validate)==2){
            return $response
                ->withStatus(500);
        }
        return $response->withJson([
            "email" => $validate[0]
        ]);

    }

    public static function updateUser($request,$response,$userid,$query){
        $age = $request->getParam("age");
        $name = $request->getParam("photographer_name");
        $gender = $request->getParam("gender");
        $res1=$query->modifyPhotographer($userid,$name,$age,$gender);
        return $response->withJson($res1);

    }
}