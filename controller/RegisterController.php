<?php

require_once('controller/iController.php');

class RegisterController implements iController {

	/**
	 * @var \view\RegisterView $regView
	 */
	private $regView;

	/**
	 * @var \view\LoginView $logView
	 */
	private $logView;

	/**
	 * @var \model\RegisterModel $regModel
	 */
	private $regModel;

	/**
	 * Constructor
	 * @param \view\RegisterView   $registerView
	 * @param \view\LoginView      $loginView
	 * @param \model\RegisterModel $registerModel
	 */
	public function __construct(RegisterView $registerView, LoginView $loginView, RegisterModel $registerModel) {
		$this->regView = $registerView;
		$this->logView = $loginView;
		$this->regModel = $registerModel;
	}

	/**
	 * Handle user input.
	 */
	public function listen() {
		if($this->regView->registerButtonPost()) {
			$username = $this->regView->getUsername();
			$password = $this->regView->getPassword();
			$passwordRepeat = $this->regView->getPasswordRepeat();
			
			try {
				if($this->regModel->verifyRegisterCredentials($username, $password, $passwordRepeat)) {
					$this->logView->setCookieUsername($this->regView->getUsername());
					$this->regView->redirectToLogin();
				}
			} catch(RUsernameAndPasswordLengthException $e) {
            	$this->regView->setMsgUsernameAndPasswordException();
       		} catch(RPasswordLengthException $e) {
            	$this->regView->setMsgPassWordLengthException();
	        } catch(RUsernameLengthException $e) {
	            $this->regView->setMsgUsernameLengthException();
	        } catch(RPasswordMismatchException $e) {
	            $this->regView->setMsgPasswordMismatchException();
	        } catch(RUserExistsException $e) {
	            $this->regView->setMsgUserExistsException();
	        } catch(RInvalidCharactersException $e) {
	            $this->regView->setMsgInvalidCharacterException();
	        }
		}
	}

	/**
	 * Check if user has clicked 'Register a new user' link.
	 * 
	 * @return boolean
	 */
	public function registerLinkPressed() {
		return $this->logView->registerLinkPressed();
	}

}