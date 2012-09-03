<?php
require_once("Bootstrap.php");
$Tpl->addJsFile(HTTP_ROOT . 'nilbog/js/jquery-1.4.2.min.js');

$Tpl->addJsFile(HTTP_ROOT . 'nilbog/js/jquery-ui-1.8.1.custom.min.js');
$Tpl->addJsFile(HTTP_ROOT . 'nilbog/js/fancybox/jquery.fancybox-1.3.1.pack.js');

//$Tpl->addJsFile(HTTP_ROOT . 'nilbog/js/jquery.tools.min.js');
$Tpl->addCssFile(HTTP_ROOT . 'nilbog/css/cms.css');
//$Tpl->addCssFile(HTTP_ROOT . 'nilbog/css/colorbox.css');
$Tpl->addCssFile(HTTP_ROOT . 'nilbog/js/fancybox/jquery.fancybox-1.3.1.css');
$Tpl->addJsFile(HTTP_ROOT . 'nilbog/js/editor.js');
$Tpl->addJsFile(HTTP_ROOT . 'nilbog/js/navbar.js');


$nav = new TemplateManager();
$nav->loadFile(DOC_ROOT . 'nilbog/views/navbar.html');

$Tpl->setTag('body', $nav, 'p');

//dBug($_SESSION);