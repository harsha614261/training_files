<?php

class userDatabaseQuery
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
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
    public function photographerRegistration($sanitizedName, $sanitizedEmail, $password, $sanitizedAge, $sanitizedGender){
        $q = $this->pdo->prepare("insert into photographer (photographer_name,email,password,age,gender) values(:sanitizedName,:sanitizedEmail,:password,:sanitizedAge,:sanitizedGender)");
        $q->bindParam(":sanitizedName",$sanitizedName);
        $q->bindParam(":sanitizedEmail",$sanitizedEmail);
        $q->bindParam(":password",$password);
        $q->bindParam(":sanitizedAge",$sanitizedAge);
        $q->bindParam(":sanitizedGender",$sanitizedGender);
        $q->execute();
        $q1 = $this->pdo->prepare("select * from photographer where email=:email");
        $q1->bindParam(":email",$sanitizedEmail);
        $q1->execute();
        $res = $q1->fetchColumn(0);
        return [$sanitizedEmail,$res];
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
