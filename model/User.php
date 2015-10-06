<?php

class User {

    /**
     * This user's username.
     * @var String $username
     */
    private $username;

    /**
     * This user's password
     * @var String $password
     */
    private $password;

    /**
     * Constructor
     * @param String $username   Username for user.
     * @param String $password   Password for user.
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