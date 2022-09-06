<?php

class imageDatabaseQuery
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

    public function removeImage($image_id){
        $cat = $this->pdo->prepare("select * from image where image_id=:image_id");
        $cat->bindParam(":image_id",$image_id);
        $cat->execute();
        if($cat->rowCount()==0){
            return ["invalid image"];
        }
        $data = $cat->fetchColumn(3);
        $query = $this->pdo->prepare("delete from image where image_id=:image_id");
        $query->bindParam(":image_id",$image_id);
        $query->execute();
        $q = $this->pdo->prepare("delete from category where cid=:data");
        $q->bindParam(":data",$data);
        $q->execute();
        return ["valid image","$image_id"];
    }
}