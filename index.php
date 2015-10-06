<?php

//INCLUDE THE FILES NEEDED...
require_once('model/LoginModel.php');
require_once('model/RegisterModel.php');
require_once('model/UserDAL.php');
require_once('model/Database.php');

require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');

require_once('controller/LoginController.php');
require_once('controller/RegisterController.php');
require_once('controller/MainController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//Create models
$db = new Database();
$dal = new UserDAL($db);
$loginModel = new LoginModel($dal);
$registerModel = new RegisterModel($dal);

//CREATE OBJECTS OF THE VIEWS
$loginView = new LoginView($loginModel);
$registerView = new RegisterView($registerModel);
$dateTimeView = new DateTimeView();
$layoutView = new LayoutView();

//Create controllers
$loginController = new LoginController($loginView, $loginModel);
$registerController = new RegisterController($registerView, $loginView, $registerModel);
$mainController = new MainController($registerController, $loginController);

$mainController->listen();

if($mainController->renderRegView())
	$layoutView->render(false, $registerView, $dateTimeView);
else
	$layoutView->render($loginModel->isLoggedIn(), $loginView, $dateTimeView);