<?php
class registrationController
{
    protected $query;
    public function __construct($query){
        $this->query=$query;
    }
    public function registerPhotographer($photographer_name, $email, $password, $age, $gender)
    {

        $sanitizedName = filter_var($photographer_name,
            FILTER_UNSAFE_RAW);
        $sanitizedAge = filter_var($age,
            FILTER_SANITIZE_NUMBER_INT);
        $sanitizedEmail = filter_var($email,
            FILTER_SANITIZE_EMAIL);
        $sanitizedGender = filter_var($gender,
            FILTER_UNSAFE_RAW);
        $a = $this->query->photographerRegistration($sanitizedName, $sanitizedEmail, sha1($password), $sanitizedAge, $sanitizedGender);
        return $a;
    }
}