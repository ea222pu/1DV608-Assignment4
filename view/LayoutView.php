<?php

class LayoutView {

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

	/**
	 * Renders correct link based on what view is displayed to the user.
	 * 
	 * @param  boolean $isLoggedIn
	 * @param  \view\iView $v 	  \view\LoginView or \view\RegisterView
	 * @return String | null      Correct link based on $v, else null.      
	 */
	private function renderRegisterLink($isLoggedIn, $v) {
		if(!$isLoggedIn && $v instanceof LoginView) {
			return $v->generateRegisterLink();
		}
		else if($v instanceof RegisterView) {
			return $v->generateBackToLoginLink();
		}
		else {
			return null;
		}
	}

}
