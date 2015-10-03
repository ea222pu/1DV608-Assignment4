<?php

require_once('exceptions/LPasswordMissingException.php');
require_once('exceptions/LUsernameMissingException.php');
require_once('exceptions/LUsernameOrPasswordException.php');
require_once('exceptions/LWrongCookieInformationException.php');

//Initialize session
session_start();

class LoginModel {

    private static $loggedIn = 'LoginModel::LoggedIn';
    private $dal;

    private $message = 0;

    /**
    * Constructor
    *
    * @param UserList
    */
    public function __construct(UserDAL $userDAL) {
        $this->dal = $userDAL;
    }

    /**
    * @param String $username
    * @param String $password
    * @param boolean $persistentLogin
    * @return boolean
    */
    public function verifyLoginCredentials($username, $password, $persistentLogin) {
        if(empty($username))
            throw new LUsernameMissingException();

        else if(empty($password))
            throw new LPasswordMissingException();

        else {
            if(!$this->dal->findUserByUsername($username))
                throw new LUsernameOrPasswordException();

            else {
                $user = $this->dal->findUserByUsername($username);
                if($user->getPassword() == $password) {
                    if(!isset($_SESSION[self::$loggedIn]))
                        $_SESSION[self::$loggedIn] = true;
                    return true;
                }
                else
                    throw new LUsernameOrPasswordException();
            }
        }
    }

    public function verifyPersistentLogin($cookieName, $cookiePassword) {
        if(!$this->dal->findUserByUsername($cookieName))
            throw new LWrongCookieInformation();

        else {
            $user = $this->dal->findUserByUsername($cookieName);
            if(base64_encode($user->getPassword()) == $cookiePassword) {
                if(!isset($_SESSION[self::$loggedIn]))
                    $_SESSION[self::$loggedIn] = true;
                return true;
            }
            else
                throw new LWrongCookieInformationException();
        }
    }

    /**
    * Logout user, destroy session
    */
    public function logout() {
        if(isset($_SESSION[self::$loggedIn]))
            if($_SESSION[self::$loggedIn])
                $_SESSION[self::$loggedIn] = false;
        session_destroy();
    }

    /**
    * Login user, set session = true
    */
    public function isLoggedIn() {
        if(isset($_SESSION[self::$loggedIn]))
            if($_SESSION[self::$loggedIn])
                return true;
        return false;
    }
    
}