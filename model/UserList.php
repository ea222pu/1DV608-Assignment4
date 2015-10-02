<?php

class UserList {

    private $list = array();

    /**
    * Add user to list
    *
    * @param User
    */
    public function addUser(User $user) {
        $this->list[] = $user;
    }

    /**
    * Iterates over list to find user by $username. Returns user if found, else false
    *
    * @param String $username
    * @return User | boolean
    */
    public function findUserByUsername($username) {
        foreach($this->list as $user)
            if($username == $user->getUsername()) {
                return $user;
            }
        else
            return false;
    }

}