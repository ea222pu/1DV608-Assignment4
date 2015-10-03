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
			if($this->regView->registerUser()) {
				$this->logView->setCookieUsername($this->regView->getUsername());
				$this->regView->redirectToLogin();
			}
			else {
				$this->layView->render(false, $this->regView, $this->dtView);
			}
		}
		else {
			$this->layView->render(false, $this->regView, $this->dtView);
		}
	}

}