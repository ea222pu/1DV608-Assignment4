<?php

class LayoutView {

	private $register = "register";

	public function render($isLoggedIn, iView $v, DateTimeView $dtv) {
		echo '<!DOCTYPE html>
			<html>
				<head>
					<meta charset="utf-8">
					<title>Registration Example</title>
				</head>
				<body>
					<h1>Assignment 4</h1>
					' . $this->renderRegisterLink($isLoggedIn, $v) . $this->renderIsLoggedIn($isLoggedIn) . '

					<div class="container">
							' . $v->response() . '

							' . $dtv->show() . '
					</div>
				 </body>
			</html>
		';
	}

	private function renderIsLoggedIn($isLoggedIn) {
		if ($isLoggedIn) {
			return '<h2>Logged in</h2>';
		}
		else {
			return '<h2>Not logged in</h2>';
		}
	}

	private function renderRegisterLink($isLoggedIn, $v) {
		if(!$isLoggedIn && $v instanceof LoginView)
			return "<a href='?" . $this->register . "'>Register a new user</a>";
		else if($v instanceof RegisterView)
			return "<a href='?'>Back to login</a>";
		else
			return null;
	}

	public function registerLinkPressed() {
		return isset($_GET[$this->register]);
	}

}
