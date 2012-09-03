<?php
// Handles displaying the login form, or processing login

	error_reporting(0);


//require_once("Bootstrap.php");



if(isset($_GET['q'])) {
	// user wants to do something, figure it out
	require_once("../../Bootstrap.php");
	error_reporting(0);
	switch($_GET['q']) {
		case 'login':
			$_GET['user'] = $Db->sanitize($_GET['user']);
			$_GET['pass'] = $Db->sanitize($_GET['pass']);
			$query = "SELECT * FROM `".DB_PREFIX."User` WHERE Username='{$_GET['user']}' AND Password=MD5(MD5('{$_GET['pass']}'))";
			
			
		/*	$myFile = DOC_ROOT . 'db.txt';
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, $query);
			fclose($fh);*/
			
			
			
			
			
			$result = $Db->query($query);
			if($result->num_rows > 0) {
				$result = $result->fetch_assoc();
				unset($result['Password']);
				$_SESSION['USER'] = $result;
				unset($_SESSION['CMS_LOGIN_REQUESTED']);
				echo (json_encode(array('success'=>'true')));
			} else {
				echo (json_encode(array('success'=>'false')));
			}
			break;
	}
} else {
// nothing to do, build the form



// Add the required cms files to the html
$Tpl->addJsFile(HTTP_ROOT . 'nilbog/js/jquery-1.4.2.min.js');
$Tpl->addCssFile(HTTP_ROOT . 'nilbog/css/cms.css');
$Tpl->addJsFile(HTTP_ROOT . 'nilbog/js/login.js');



$cms_login = '
	<div id="cms-login">
		<div id="cms-login-bar"><img src="'.HTTP_ROOT.'nilbog/images/icons/tux.png" /> Company Name</div>
		<div id="cms-login-form">
		<!--	<p>Welcome to your custom content management system. Please enter your username and password below. <br /><br />NOTE: username and password are CASE-SENSITIVE</p>

			<br />-->
			<p id="invalid_user" id="cms-login-invalid">Username or Password was invalid.</p>
			<br />

			<!--<form action="" method="post" onsubmit="return cms_validate_user(this)">--><input type="text" name="password" id="password" value="honeypower" style="display: none;">
				<b>Username:</b>
				<br /><input type="text" name="user" id="user">
				<br />
				<br />

				<b>Password:</b>
				<br /><input type="password" id="pass" name="pass">
				<br />
				<br /><div id="cms-login-button">Login</div>
			</form>
			<br /><a href="recover_password.php">Forgot Password?</a>
		</div>
	</div>
	<div id="cms-back-cover"></div>
';

$Tpl->setTag('body', $cms_login, 'a');
}