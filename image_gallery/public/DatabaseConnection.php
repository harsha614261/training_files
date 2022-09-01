<?php

class DatabaseConnection
{
    public static function establishConnection($config){
        //var_dump($config['database']['connection']);
        try{
            //return new PDO('mysql:host=localhost;dbname=test1','harsha','Harsha@123');
            return new PDO(
                $config['database']['connection'] . ';dbname=' . $config['database']['dbName'],
                $config['database']['userName'],
                $config['database']['password'],
            );
        }
        catch (PDOException $e){
            die("sorry");
        }
    }
}

