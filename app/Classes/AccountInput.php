<?php
namespace App\Classes;

class AccountInput{
    private $email;
    private $username;
    private $password;
    private $passwordConfirm;

    public function __construct(String $username, String $password, String $passwordConfirm = null, String $email = null) {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->passwordConfirm = $passwordConfirm;
    }

    public function getEmail():String{
        return $this->email;
    }

    public function getUsername():String{
        return $this->username;
    }

    public function getPassword():String{
        return $this->password;
    }

    public function getPasswordConfirm():String{
        return $this->passwordConfirm;
    }
}
?>