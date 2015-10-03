<?php

require_once('controller/iController.php');

class MainController implements iController {

	private $regCtrlr;
	private $logCtrlr;
	private $layView;

	public function __construct(RegisterController $registerController, LoginController $loginController, LayoutView $layoutView) {
		$this->regCtrlr = $registerController;
		$this->logCtrlr = $loginController;
		$this->layView = $layoutView;
	}

	public function listen() {
		if($this->layView->registerLinkPressed()) {
			$this->regCtrlr->listen();
		}
		else {
			$this->logCtrlr->listen();
		}
	}
}