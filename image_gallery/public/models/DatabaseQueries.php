<?php

class DatabaseQueries
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function postImages($userId, $url, $cat)
    {
        $q = $this->pdo->prepare("insert into category (category,cat_pid) values (:cat,:userId)");
        $q->bindParam(':cat',$cat);
        $q->bindParam(':userId',$userId);
        $q->execute();
        $q1 = $this->pdo->prepare("select * from category where cat_pid=:userId and category=:cat");
        $q1->bindParam(':cat',$cat);
        $q1->bindParam(':userId',$userId);
        $q1->execute();
        $da = $q1->fetchColumn(0);
        $q2 = $this->pdo->prepare("insert into image (image,image_pid,image_cid) values (:url,:userId,:da)");
        $q2->bindParam(':userId',$userId);
        $q2->bindParam(':url',$url);
        $q2->bindParam(':da',$da);
        $q2->execute();
    }
    public function fetchUserSpecificImages($userid){
        $query = $this->pdo->prepare("select photographer.pid,photographer.photographer_name,image.image_id,image.image,image.image_cid,category.category from photographer JOIN image ON photographer.pid=image.image_pid and photographer.pid=:userid JOIN category ON image.image_cid=category.cid");
        $query->bindParam(':userid',$userid);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function fetchAll(){
        $query = $this->pdo->prepare("select photographer.photographer_name,image.image,category.category from photographer JOIN image ON photographer.pid=image.image_pid JOIN category ON image.image_cid=category.cid");
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function modifyPhotographer($id,$name,$age,$gender){
        $partialQuery = "update photographer set";
        $j=0;
        $k=0;
        if(!empty($name)){
            $partialQuery .=" photographer_name=:name";
            $j=1;
        }
        if(!empty($age)){
            if($j==1){
                $partialQuery .=", age=:age";
                $k=1;
            }
            else{
                $partialQuery .=" age=:age";
                $k=1;
            }
        }
        if(!empty($gender)){
            if($k==1){
                $partialQuery .=", gender=:gender";
            }
            else{
                $partialQuery .=" gender=:gender";
            }
        }
        $partialQuery .= " where pid=:id";
        $partialQuery .= ";";
        //return $partialQuery;
        $check =$this->pdo->prepare($partialQuery);
        if(!empty($name)){
            $check->bindParam(':name',$name);
            if(!empty($age)){
                $check->bindParam(':age',$age);
                if(!empty($gender)){
                    $check->bindParam(':gender',$gender);
                }
            }
        }
        elseif (!empty($age)){
            $check->bindParam(':age',$age);
            if(!empty($gender)){
                $check->bindParam(':gender',$gender);
            }
        }
        elseif (!empty($gender)){
            $check->bindParam(':gender',$gender);
        }

        $check->bindParam(':id',$id);
        $check->execute();
        $res = $this->pdo->prepare("select pid,photographer_name,email,age,gender from photographer where pid=:id");
        $res->bindParam(':id',$id);
        $res->execute();
        $res1 = $res->fetchAll(PDO::FETCH_ASSOC);
        return $res1;
    }
    public function removeImage($userid,$image_id){
        $cat = $this->pdo->prepare("select * from image where image_id=:image_id and image_pid=:userid");
        $cat->bindParam(":image_id",$image_id);
        $cat->bindParam(":userid",$userid);
        $cat->execute();
        $data = $cat->fetchColumn(3);
        $query = $this->pdo->prepare("delete from image where image_pid=:userid and image_id=:image_id");
        $query->bindParam(":userid",$userid);
        $query->bindParam(":image_id",$image_id);
        $query->execute();
        $q = $this->pdo->prepare("delete from category where cid=:data");
        $q->bindParam(":data",$data);
        $q->execute();
    }
    public function photographerRegistration($sanitizedName, $sanitizedEmail, $password, $sanitizedAge, $sanitizedGender){
        $q = $this->pdo->prepare("insert into photographer (photographer_name,email,password,age,gender) values(:sanitizedName,:sanitizedEmail,:password,:sanitizedAge,:sanitizedGender)");
        $q->bindParam(":sanitizedName",$sanitizedName);
        $q->bindParam(":sanitizedEmail",$sanitizedEmail);
        $q->bindParam(":password",$password);
        $q->bindParam(":sanitizedAge",$sanitizedAge);
        $q->bindParam(":sanitizedGender",$sanitizedGender);
        $q->execute();
        return $sanitizedEmail;
    }
    public function photographerLogin($sanitizedEmail,$password){
        $query = $this->pdo->prepare("select * from photographer where email=:sanitizedEmail and password=:password");
        $query->bindParam(":sanitizedEmail",$sanitizedEmail);
        $query->bindParam(":password",$password);
        $query->execute();
        if($query->rowCount()==1){
            return $sanitizedEmail;
        }

    }

}
