<?php
error_reporting(E_ALL);
 require_once("../../Bootstrap.php");

//dBug($_REQUEST);


if(isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['element']) && isset($_POST['content'])){

	setPageElement($_GET['id'], $_GET['element'], $_POST['content']);
	if($Db->affectedRows() > 0) {
/*		header("Pragma: no-cache");
	   	header("cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	   	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header( 'refresh: 0;');*/                                                                                                                                                                                                                                                                                                                                                                                                          
	
		echo "<b>Successfully updated {$_GET['element']} on page {$_GET['id']}</b>";
		echo "<script type='text/javascript'>
		parent.$.fancybox.width = '65%';
		setTimeout( 'close();', 2000); function close() {parent.$.fancybox.close();}</script>";
	} else {
		echo "<b>{$_GET['element']} NOT updated on page {$_GET['id']}</b>";
	}
} //else {
	//echo $_GET['id'] . '<br>' . $_GET['element'];
	$ed = new TemplateManager();
	$ed->loadFile(DOC_ROOT . 'nilbog/views/editor.html');
	$ed->addJsFile(HTTP_ROOT . 'nilbog/js/jquery-1.4.2.min.js');
	$ed->addJsFile(HTTP_ROOT . 'nilbog/ckeditor/ckeditor.js');
	
	
	$js = "$(document).ready(function(){
		// Initialize the editor.
		CKEDITOR.config.ProcessHTMLEntities   = true ;
		CKEDITOR.config.IncludeLatinEntities   = false ;
		CKEDITOR.config.IncludeGreekEntities   = false ;

		CKEDITOR.config.ProcessNumericEntities = false ;
		CKEDITOR.replace( 'editme',
		    {
		        toolbar :
		        [
		            ['Source', '-', 'Save', 'Preview', '-', 'Cut','Copy','Paste','PasteText','PasteFromWord'],
					['Link','Unlink','Anchor'],
					['-','Undo','Redo','-','Find','Replace','-', 'Bold', 'Italic','Underline', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
					['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
					['Styles','Format','Font','FontSize','TextColor','BGColor'],
					['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
					['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
					['UIColor']
		        ],
				width:'940',
				height:'485'
		    });
	});";
	$ed->addJs($js);
	
	echo $ed;
/*	$Tpl->addJsFile(HTTP_ROOT . 'nilbog/js/jquery-ui-1.8.1.custom.min.js');
	$Tpl->addJsFile(HTTP_ROOT . 'nilbog/js/jquery.colorbox-min.js');
	$Tpl->addCssFile(HTTP_ROOT . 'nilbog/css/cms.css');*/
?>


<?php //} // end else?>