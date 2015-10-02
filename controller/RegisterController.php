<?php

require_once('controller/iController.php');

class RegisterController implements iController {

	private $layView;
	private $regView;
	private $dtView;
	private $regModel;


	public function __construct(LayoutView $layoutView, RegisterView $registerView, LoginView $loginView, DateTimeView $dateTimeView, RegisterModel $registerModel) {
		$this->layView = $layoutView;
		$this->regView = $registerView;
		$this->logView = $loginView;
		$this->dtView = $dateTimeView;
		$this->regModel = $registerModel;
	}

	public function listen() {
		if($this->regView->registerButtonPost()) {
			if($this->ctrlrRegister()) {
				$this->logView->setCookieUsername($this->regView->getUsername());
				$this->logView->setMessage($this->regModel->getMessage());
				//$this->layView->render(false, $this->logView, $this->dtView);
				return true;
			}
			else {
				$this->regView->setMessage($this->regModel->getMessage());
				$this->layView->render(false, $this->regView, $this->dtView);
				return false;
			}
		}
		else {
			$this->layView->render(false, $this->regView, $this->dtView);
			return false;
		}
	}

	public function ctrlrRegister() {
		$username = $this->regView->getUsername();
		$password = $this->regView->getPassword();
		$passwordRepeat = $this->regView->getPasswordRepeat();

		return $this->regModel->verifyRegisterCredentials($username, $password, $passwordRepeat);
	}
}