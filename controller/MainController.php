<?php

require_once('controller/iController.php');

class MainController implements iController {

	/**
	 * @var \controller\RegisterController $regCtrlr
	 */
	private $regCtrlr;

	/**
	 * @var \controller\LoginController $logCtrlr
	 */
	private $logCtrlr;

	/**
	 * @var boolean $renderRegView
	 */
	private $renderRegView;

	/**
	 * Constructor
	 * @param \controller\RegisterController $registerController
	 * @param \controller\LoginController    $loginController
	 */
	public function __construct(RegisterController $registerController, LoginController $loginController) {
		$this->regCtrlr = $registerController;
		$this->logCtrlr = $loginController;
		$this->renderRegView = false;
	}

	/**
	 * Handle user input 
	 */
	public function listen() {
		if($this->regCtrlr->registerLinkPressed()) {
			$this->regCtrlr->listen();
			$this->renderRegView = true;
		}
		else {
			$this->logCtrlr->listen();
			$this->renderRegView = false;
		}
	}

	/**
	 * Returns true if user has clicked the 
	 * 'Register a new user' link.
	 * 
	 * @return boolean
	 */
	public function renderRegView() {
		return $this->renderRegView;
	}
}