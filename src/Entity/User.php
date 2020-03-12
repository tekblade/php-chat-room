<?php
namespace App\Entity;
class User {
    protected $user;
    public function getUser(){
        return $this->user;
    }
    public function setUser($user){
        $this->user=$user;
    }
}

