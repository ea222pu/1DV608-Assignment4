<?php

class User {

    private $username;
    private $password;

    /**
    * Constructor
    * @param String $username
    * @param String $password
    */
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
    * @return String $username
    */
    public function getUsername() {
        return $this->username;
    }

    /**
    * @return String $password
    */
    public function getPassword() {
        return $this->password;
    }


}