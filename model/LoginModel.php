<?php

require_once('exceptions/LPasswordMissingException.php');
require_once('exceptions/LUsernameMissingException.php');
require_once('exceptions/LUsernameOrPasswordException.php');
require_once('exceptions/LWrongCookieInformationException.php');

//Initialize session
session_start();

class LoginModel {

    /**
     * @var string $loggedIn
     */
    private static $loggedIn = 'LoginModel::LoggedIn';

    /**
     * @var \model\UserDAL $dal
     */
    private $dal;

    /**
    * Constructor
    * @param \model\UserDAL $userDAL
    */
    public function __construct(UserDAL $userDAL) {
        $this->dal = $userDAL;
    }

    /**
     * Verifies login data from user.
     * 
     * @param  String $username            Input username
     * @param  String $password            Input password
     * @param  boolean $persistentLogin    Keep me logged in
     *
     * @throws LUsernameMissingException   When $username is empty.
     * @throws LPasswordMissingException   When $password is empty.
     * @throws LUsernameOrPasswordException  When user does not exist in database, or if user exist 
     *                                       but password does not match.
     */
    public function verifyLoginCredentials($username, $password, $persistentLogin) {
        if(empty($username)) {
            throw new LUsernameMissingException();
        }
        else if(empty($password)) {
            throw new LPasswordMissingException();
        }
        else {
            if(!$this->dal->findUserByUsername($username)) {
                throw new LUsernameOrPasswordException();
            }
            else {
                $user = $this->dal->findUserByUsername($username);
                if($user->getPassword() == $password) {
                    if(!isset($_SESSION[self::$loggedIn])) {
                        $_SESSION[self::$loggedIn] = true;
                    }
                }
                else {
                    throw new LUsernameOrPasswordException();
                }
            }
        }
    }

    /**
     * Verifies login data stored in cookies.
     * 
     * @param  String $cookieName        Username stored in cookie.
     * @param  String $cookiePassword    Password stored in cookie.
     *
     * @throws LWrongCookieInformationException  When user does not exist in database, or if user exist 
     *                                           but password does not match.
     */
    public function verifyPersistentLogin($cookieName, $cookiePassword) {
        if(!$this->dal->findUserByUsername($cookieName)) {
            throw new LWrongCookieInformationException();
        }
        else {
            $user = $this->dal->findUserByUsername($cookieName);
            if(base64_encode($user->getPassword()) == $cookiePassword) {
                if(!isset($_SESSION[self::$loggedIn])) {
                    $_SESSION[self::$loggedIn] = true;
                }
            }
            else {
                throw new LWrongCookieInformationException();
            }
        }
    }

    /**
     * Logout user.
     */
    public function logout() {
        if(isset($_SESSION[self::$loggedIn])) {
            if($_SESSION[self::$loggedIn]) {
                $_SESSION[self::$loggedIn] = false;
            }
        }
        session_destroy();
    }

    /**
     * Check if user is logged in.
     * 
     * @return boolean
     */
    public function isLoggedIn() {
        if(isset($_SESSION[self::$loggedIn])) {
            if($_SESSION[self::$loggedIn]) {
                return true;
            }
        }
        return false;
    }
    
}