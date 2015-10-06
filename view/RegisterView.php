<?php

require_once("view/iView.php");

class RegisterView implements iView {

    /**
     * @var String $register
     */
    private static $register = 'RegisterView::Register';

    /**
     * @var String $messageId
     */
    private static $messageId = 'RegisterView::Message';

    /**
     * @var String $name
     */
    private static $name = 'RegisterView::UserName';

    /**
     * @var String $password
     */
    private static $password = 'RegisterView::Password';

    /**
     * @var String $passwordRepeat
     */
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';

    /**
     * @var String $successfulRegister
     */
    public static $successfulRegister = 'RegisterView::SuccessfulRegister';

    /**
     * Used for remembering the name during registration attempt.
     * 
     * @var String $rememberName
     */
    private $rememberName;

    /**
     * The message being displayed in the register form.
     * 
     * @var String $message
     */
    private $message;

    /**
     * @var \model\RegisterModel $regModel
     */
    private $regModel;

    /**
     * Constructor
     * @param \model\RegisterModel $registerModel
     */
    public function __construct(RegisterModel $registerModel) {
        $this->regModel = $registerModel;
    }

    /**
     * Generates the register form.
     * 
     * @return String HTML-code
     */
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

    /**
     * Generates the link for returning back to \view\LoginView from \view\RegisterView.
     * 
     * @return String HTML-code
     */
    public function generateBackToLoginLink() {
        return "<a href='?'>Back to login</a>";
    }

    /**
     * Check if register button has been pressed.
     * Sets $rememberName to the requested username.
     * 
     * @return boolean
     */
    public function registerButtonPost() {
        if(isset($_POST[self::$register])) {
            $this->rememberName = $_POST[self::$name];
        }
        return isset($_POST[self::$register]);
    }
    
    /**
     * Returns the username used for registration.
     * 
     * @return String
     */
    public function getUsername() {
        return $_POST[self::$name];
    }

    /**
     * Returns the password used for registration.
     * 
     * @return String
     */
    public function getPassword() {
        return $_POST[self::$password];
    }

    /**
     * Returns the repeated password used for registration.
     * 
     * @return String
     */
    public function getPasswordRepeat() {
        return $_POST[self::$passwordRepeat];
    }

    /**
     * Set message.
     */
    public function setMsgUsernameAndPasswordException() {
        $this->message = 'Username has too few characters, at least 3 characters.
                        <br>Password has too few characters, at least 6 characters.';
    }

    /**
     * Set message.
     */
    public function setMsgPassWordLengthException() {
        $this->message =  'Password has too few characters, at least 6 characters.';
    }

    /**
     * Set message.
     */
    public function setMsgUsernameLengthException() {
        $this->message = 'Username has too few characters, at least 3 characters.';
    }

    /**
     * Set message.
     */
    public function setMsgPasswordMismatchException() {
        $this->message = 'Passwords do not match.';
    }

    /**
     * Set message.
     */
    public function setMsgUserExistsException() {
        $this->message = 'User exists, pick another username.';
    }

    /**
     * Set message.
     */
    public function setMsgInvalidCharacterException() {
        $this->message = 'Username contains invalid characters.';
    }

    /**
     * Redirects back to \view\LoginView.
     */
    public function redirectToLogin() {
        setcookie(self::$successfulRegister, true, time()+3600);
        header("Location: ?");
    }

}