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
		if($this->logView->isCookieNameSet())
			$this->logView->setLoginName($this->logView->getCookieName());

		//Login
		if($this->logView->loginButtonPost() && !$this->logModel->isLoggedIn())
			$this->ctrlrLogin();

		//Logout
		else if($this->logView->logoutButtonPost() && $this->logModel->isLoggedIn())
			$this->ctrlrLogout();

		else if($this->logModel->isLoggedIn() && $_POST) {
			header('Location: ' . $_SERVER['REQUEST_URI']);
			exit;
		}
		else if($this->logView->isCookiesSet())
			$this->ctrlrCookieLogin();

		$this->layView->render($this->logModel->isLoggedIn(), $this->logView, $this->dtView);

	}

	private function ctrlrLogin() {
		$username = $this->logView->getUsername();
		$password = $this->logView->getPassword();
		$persistentLogin = $this->logView->getPersistentLogin();

		$this->logModel->verifyLoginCredentials($username, $password, $persistentLogin);
		$this->logView->setMessage($this->logModel->getMessage());
	}

	private function ctrlrCookieLogin() {
		$cookieName = $this->logView->getCookieName();
		$cookiePassword = $this->logView->getCookiePassword();

		$this->logModel->verifyPersistentLogin($cookieName, $cookiePassword);
		if(!$this->logModel->isLoggedIn())
			$this->logView->deleteCredentialCookies();
		$this->logView->setMessage($this->logModel->getMessage());
	}

	private function ctrlrLogout() {
		$this->logModel->logout();
		$this->logView->setMessage($this->logModel->getMessage());
		$this->logView->deleteCredentialCookies();
	}
}