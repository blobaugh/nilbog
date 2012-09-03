<?php
/*
 * This file contains several nilbog 'magic functions'. They are not quite
 * as magic as PHP magic functions, but they are awesome in their own right
 * never the less
 * 
 * @author Ben Lobaugh (ben@lobaugh.net)
 * @package core
 */

// This can be used to make sure a file is running inside the site
define('SITE_RUNNING', 1);

session_start();

require_once('dBug.php'); // Love this debugger!
require_once('nilbog_config.php');

// Just to make things a bit easier, some path constants
define('DOC_ROOT', doc_root());
define('NB_CORE', DOC_ROOT . 'core/');
define('NB_LIB', NB_CORE . 'lib/');
define('NB_EXT', DOC_ROOT . 'extensions/');
define('NB_TPL', DOC_ROOT . 'templates/');

// More useful constants
define('HTTP_ROOT', http_root());
define('HTTP_TPL', HTTP_ROOT . '/templates/');

// Grab user config
require_once(DOC_ROOT . 'config.php');

// DB Prefix
define('DB_PREFIX', $dbprefix);

// Setup the database connection
require_once(NB_LIB . 'Database/Database.class.php');
$Db = Database::getDatabase(); 
unset($dblocation, $dbuser, $dbpass, $dbname, $dbprefix); // Rid ourselves of this pesky password problem right away

// Setup the template object
require_once(NB_LIB . 'Template/TemplateManager.class.php');
$Tpl = new TemplateManager();

require_once(NB_LIB . 'ExtensionManager.class.php');
$Exts = new ExtensionManager();

/**
 * Find the document root on the local file system.
 * This is accomplished by looking at $_SERVER['DOCUMENT_ROOT'].
 * Starting from the last folder in the list and going backwards
 * until all the items in the base array are found in one directory
 *
 * @return String - Path of Document Root
 */
function doc_root() {
        // These items will be looked for in the current dir. If present it must be base
		global $base_files, $base_dirs;

        $path = $_SERVER['SCRIPT_FILENAME']; // Where the current script is executing
        while(strlen($path) > 0) { // As long as we have a path
                $count = 0; // If this is 6 the base is found
                if (is_dir($path)) { // If we are in a directory
                        foreach($base_dirs AS $dir) { // Look through the base dirs
                                if(is_dir("$path/$dir")) $count++; // Count up the dirs to make sure the exist
                        }

                        if($count == count($base_dirs)) { // If all the dirs aren't there don't bother looking at the files
                                foreach($base_files AS $file) {// Look through the base files
                                        if(is_file("$path/$file")) $count++;
                                }
                        }
                }

                if ($count == count($base_dirs)+count($base_files)) { // Have all the files been found?
                        return $path;
                }


                $path = preg_replace("#/$#", '', $path);
                $path = preg_replace("#[^/]+$#", '', $path);
            }




        // If have reached this point something really bad happened!
        $error_message = "The site base could not be found. Please check your installation for the following files or contact your site administrator. The following files MUST be in the root of your site:
                          <ul>";
                                foreach($base_files AS $r) $error_message .= "\n<li>$r</li>";
								foreach($base_dirs AS $r) $error_message .= "\n<li>$r</li>";
                          $error_message .= "</ul>
                          <br /><br />global_functions:find_doc_root";
      //  echo ($error_message);
}

/**
 * Find the root directory of the site. Useful for includes and such
 * @return String
 */
function http_root() {
	$doc_root = explode('/', $_SERVER['DOCUMENT_ROOT']);
	$blam_root = explode('/', DOC_ROOT);
	$path = array_diff($blam_root,$doc_root);
	
	$addy = "http://" . $_SERVER['HTTP_HOST'] . "/";
	
	foreach($path AS $p) {
		$addy .= "$p/";
	}
	return $addy;
}

/**
 * Shows all of the defined constants in the system
 */
function nb_vars() {
	$e = get_defined_constants(true);
	dBug($e['user']);
}