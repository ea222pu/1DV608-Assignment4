<?php

require_once('controller/iController.php');

class LoginController implements iController {

	private $logModel;
	private $logView;
	private $layView;
	private $dtView;

	public function __construct(LayoutView $layoutView, LoginView $loginView, DateTimeView $dateTimeView, LoginModel $loginModel) {
		$this->layView = $layoutView;
		$this->logView = $loginView;
		$this->logModel = $loginModel;
		$this->dtView = $dateTimeView;
	}

	public function listen() {
		if($this->logView->isRegisteredCookieSet()) {
			$this->logView->registrationMessage();
			$this->logView->deleteRegisteredCookie();
		}

		if($this->logView->isCookieNameSet()) 
			$this->logView->setLoginName($this->logView->getCookieName());

		//Login
		if($this->logView->loginButtonPost() && !$this->logModel->isLoggedIn())
			$this->logView->loginUser();

		//Logout
		else if($this->logView->logoutButtonPost() && $this->logModel->isLoggedIn()) {
			$this->logModel->logout();
			$this->logView->deleteCredentialCookies();
		}

		else if($_POST) {
			header('Location: ' . $_SERVER['REQUEST_URI']);
			exit;
		}
		else if($this->logView->isCookiesSet())
			$this->logView->persistentLogin();

		$this->layView->render($this->logModel->isLoggedIn(), $this->logView, $this->dtView);

	}
	
}