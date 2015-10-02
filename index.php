<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');

require_once('controller/LoginController.php');
require_once('controller/RegisterController.php');
require_once('controller/MainController.php');

require_once('model/LoginModel.php');
require_once('model/RegisterModel.php');
require_once('model/UserList.php');
require_once('model/User.php');
require_once('model/UserDAL.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//Create user with username 'Admin' and password 'Password'
$user = new User("Admin", "Password");
//Create userlist
$userList = new UserList();
//Add user to userlist
$userList->addUser($user);

//Create model
$mysqli = new mysqli("localhost", "root", "", "register");
if(mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit;
}
$dal = new UserDAL($mysqli);
$model = new LoginModel($userList);
$rm = new RegisterModel($dal);

//CREATE OBJECTS OF THE VIEWS
$v = new LoginView($model);
$dtv = new DateTimeView();
$lv = new LayoutView();
$r = new RegisterView();

//Create controller
$loginController = new LoginController($lv, $v, $dtv, $model);
$registerController = new RegisterController($lv, $r, $v, $dtv, $rm);
$mainController = new MainController($registerController, $loginController, $lv);

$mainController->listen();