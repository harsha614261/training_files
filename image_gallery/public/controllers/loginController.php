<?php

class loginController
{
    protected $query;
    public function __construct($query){
        $this->query=$query;
    }
    public function photographerLogin($email, $password)
    {
        $sanitizedEmail = filter_var($email,
            FILTER_SANITIZE_EMAIL);
        $a = $this->query->photographerLogin($sanitizedEmail, sha1($password));
        return $a;
    }
}