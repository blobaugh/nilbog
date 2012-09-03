<?php

// Someone wants access to the nilbog cms. Send them to the main page
// with a var set
session_start();
if(!isset($_SESSION['USER'])) {
	$_SESSION['CMS_LOGIN_REQUESTED'] = true;
} 

header("Location: ../");
exit();