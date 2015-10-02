<?php

class RegisterView implements iView {

    private static $register = 'RegisterView::Register';
    private static $messageId = 'RegisterView::Message';
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private $rememberName;
    private $message;

    public function response() {
        return '
            <h2>Register new user</h2>
            <form method="post">
                <fieldset>
                    <legend>Register a new user - Write username and password</legend>
                    <p id="' . self::$messageId . '">' . $this->message . '</p>

                    <label for="' . self::$name . '">Username :</label>
                    <input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->rememberName . '" />
                    <br>
                    <label for="' . self::$password . '">Password :</label>
                    <input type="password" id="' . self::$password . '" name="' . self::$password . '" />
                    <br>
                    <label for="' . self::$passwordRepeat . '">Repeat password :</label>
                    <input type="password" id="' . self::$passwordRepeat . '" name="' . self::$passwordRepeat . '"</>
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

    public function getPassword() {
        return $_POST[self::$password];
    }

    public function getPasswordRepeat() {
        return $_POST[self::$passwordRepeat];
    }

    public function setMessage($n) {
        switch($n) {
            case 0:
                $this->message = '';
                break;
            case 1:
                $this->message = 'Username has too few characters, at least 3 characters.
                                <br>Password has too few characters, at least 6 characters.';
                break;
            case 2:
                $this->message =  'Password has too few characters, at least 6 characters.';
                break;
            case 3:
                $this->message = 'Username has too few characters, at least 3 characters.';
                break;
            case 4:
                $this->message = 'Passwords do not match.';
                break;
            case 5:
                $this->message = 'User exists, pick another username.';
                break;
            case 6:
                $this->message = 'Username contains invalid characters.';
                break;
        }

    }

}