<?php
/**
 * Programmer: Ben Lobaugh
 * Description: This file is essentially the router that will put the whole
 * 	site together.
 **/
error_reporting(E_ALL);
require_once("Bootstrap.php");

//$Tpl->addCssFile(HTTP_ROOT . 'cms/css/cms.css');
//$Tpl->addJsFile(HTTP_ROOT . 'cms/js/jquery-1.4.2.min.js');


//$Tpl->loadFile(NB_TPL . 'index.php');




//$p = getPageByPath($_SERVER['REQUEST_URI']); 
// extensions are in the form of [ext]extension_name param=something&param2=slkjf[/ext]
/*function load_extension($extension) {
	// Extension call is in $extension[1]
	$ext = explode(' ', $extension[1]);
	// The extension name should now be in $ext[0] with params in $ext[1]
	
	// Build the extension parameters
	$ext_params = array();
	if(isset($ext[1])) {
		$params = explode('&', $ext[1]); 
		
		foreach($params AS $p) {
			$p = explode('=', $p);
			$ext_params[$p[0]] = $p[1];
		}
	}
	
	$file = DOC_ROOT . "extensions/{$ext[0]}/index.php";
	
	ob_start();
		require_once($file);
		$ext = ob_get_contents();
	ob_end_clean();

	return $ext;
}*/

if($p = getPageByPath($_SERVER['REQUEST_URI'])) {
	
	// page found load the template and add the content elements from the db
	$Tpl->loadFile(NB_TPL . $default_template);
	$Tpl->setSpecialTag('PageId', $p['PageId']);
	$Tpl->setTag('title', $p['Title']);
	// Add the content to the proper html id tags
	foreach($p['Content'] as $k => $v) {
		// Look for extension tags
/*		$v = preg_replace_callback(
		        '/\[\[([^\]]*)\]\]/',
		        'self::loadExtension',
		        $v
		    ); */
		$v = $Exts->parseAndRun($v);
	//	$v = preg_replace('/\[ext\](.*)\[\/ext\]/', 'gotcha', $v);
		$Tpl->setById($k, $v);
	}
	
} else {
	// page not found load 404!!
	header("HTTP/1.0 404 Not Found"); 
	$Tpl->loadFile(NB_TPL . 'index.php');
	$Tpl->setSpecialTag('PageId', '0');
	$Tpl->setById('content', '404');
}




/*
 * Check to see if the user is requesting the CMS
 * If requested the login is at HTTP_ROOT/nilbog
 * After logged in the user info should be in the session. That means this
 * page will load like normal with the addition of a couple libraries for 
 * the nilbog CMS
 */
if(isset($_SESSION['CMS_LOGIN_REQUESTED'])) {
	// Load login file to process everything :D
	require_once('nilbog/controllers/login.php');
} else if(isset($_SESSION['USER']['CmsAccess'])) {
//	require_once('nilbog/controllers/navbar.php');
	require_once('nilbog/controllers/front_editor.php');
	
}
//unset($_SESSION['USER']);

// Setup any extra tags
$Tpl->setSpecialTag('HTTP_ROOT', HTTP_ROOT);
$Tpl->addJs("var HTTP_ROOT='".HTTP_ROOT."'", 'p');


// Show the completed page :D
echo $Exts->parseAndRun($Tpl);