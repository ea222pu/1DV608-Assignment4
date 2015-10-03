<?php

require_once("view/iView.php");

class RegisterView implements iView {

    private static $register = 'RegisterView::Register';
    private static $messageId = 'RegisterView::Message';
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    public static $successfulRegister = 'RegisterView::SuccessfulRegister';
    private $rememberName;
    private $message;
    private $regModel;

    public function __construct(RegisterModel $registerModel) {
        $this->regModel = $registerModel;
    }

    public function response() {
        return '
            <h2>Register new user</h2>
            <form method="post">
                <fieldset>
                    <legend>Register a new user - Write username and password</legend>
                    <p id="' . self::$messageId . '">' . $this->message . '</p>

                    <label for="' . self::$name . '">Username :</label>
                    <input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . strip_tags($this->rememberName) . '" />
                    <br>
                    <label for="' . self::$password . '">Password :</label>
                    <input type="password" id="' . self::$password . '" name="' . self::$password . '" />
                    <br>
                    <label for="' . self::$passwordRepeat . '">Repeat password :</label>
                    <input type="password" id="' . self::$passwordRepeat . '" name="' . self::$passwordRepeat . '"</>
                    <br>
                    <input type="submit" name="' . self::$register . '" value="Register"/>
                </fieldset>
            </form>
        ';
    }

    public function registerButtonPost() {
        if(isset($_POST[self::$register]))
            $this->rememberName = $_POST[self::$name];
        return isset($_POST[self::$register]);
    }

    
    public function getUsername() {
        return $_POST[self::$name];
    }
    
    public function registerUser() {
        $username = $_POST[self::$name];
        $password = $_POST[self::$password];
        $passwordRepeat = $_POST[self::$passwordRepeat];

        try {
            return $this->regModel->verifyRegisterCredentials($username, $password, $passwordRepeat);
        } catch(RUsernameAndPasswordLengthException $e) {
            $this->message = 'Username has too few characters, at least 3 characters.
                            <br>Password has too few characters, at least 6 characters.';
        } catch(RPasswordLengthException $e) {
            $this->message =  'Password has too few characters, at least 6 characters.';
        } catch(RUsernameLengthException $e) {
            $this->message = 'Username has too few characters, at least 3 characters.';
        } catch(RPasswordMismatchException $e) {
            $this->message = 'Passwords do not match.';
        } catch(RUserExistsException $e) {
            $this->message = 'User exists, pick another username.';
        } catch(RInvalidCharactersException $e) {
            $this->message = 'Username contains invalid characters.';
        }
        return false;
    }

    public function redirectToLogin() {
        setcookie(self::$successfulRegister, true, time()+3600);
        header("Location: ?");
    }

}