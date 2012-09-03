<?php
/*
 * This file contains several  'magic functions'. They are not quite
 * as magic as PHP magic functions, but they are awesome in their own right
 * never the less
 * 
 * @author Ben Lobaugh (ben@lobaugh.net)
 * @package core
 */
error_reporting(E_ALL);
// Ensure there is a config file or bork
if(!file_exists('config.php')) {
//	die('Unable to find config file');
}

require_once('core/lib/nilbog_magic.php');



function http_tpl_dir() {
	return HTTP_TPL;
}

function pathDiff($P1, $P2) {
	$P1 = array_filter(explode('/', $P1));
	$P2 = array_filter(explode('/', $P2));
	$diff = array_diff($P1, $P2);
	sort($diff);
	return $diff;
	
}

function getPageByPath($Path) {
	global $default_page;
	$ret = false;
	$diff = pathDiff($Path, HTTP_ROOT);
	if(empty($diff)) {
		$diff[] = $default_page;
	}
	$diff = array_reverse($diff);

	// Look through the tree and see if we can find the page requested
	$parent_page_id = 0;
	foreach($diff AS $p) {
		if(!($page_id = getPageIdByTitleAndParentPageId($p, $parent_page_id))) {
			return false; // page not found!
		}
		$parent_page_id = $page_id;
	}
	
	return getPageById($page_id);
}


function getPageIdByTitleAndParentPageId($Title, $ParentPageId) {
	global $Db;
	$Title = $Db->sanitize($Title);
	$query = "SELECT PageId FROM `".DB_PREFIX."Pages` WHERE Title='$Title' AND ParentPageId='$ParentPageId'";
	$result = $Db->query($query); 
	if($result->num_rows > 0) {
		$result = $result->fetch_assoc(); 
		return $result['PageId'];
	}
	return false; // page not found!
}

function getPageIdByTitle($Title) {
	global $Db;
	$Title = $Db->sanitize($Title);
	$query = "SELECT PageId FROM `".DB_PREFIX."Pages` WHERE Title='$Title'";
	$result = $Db->query($query); 
	if($result->num_rows > 0) {
		$result = $result->fetch_assoc(); 
		return $result['PageId'];
	}
	return false; // page not found!
}

function getPageByTitle($Title) {
	global $Db;
	$Title = $Db->sanitize($Title);
	$query = "SELECT * FROM `".DB_PREFIX."Pages` WHERE Title='$Title'"; 
	$result = $Db->query($query); 
	if($result->num_rows > 0) {
		$result = $result->fetch_assoc(); 
		$result['Content'] = decode(($result['Content']), true); 
		return $result;
	}
	return false; // page not found!
}

function getPageById($PageId) {
	global $Db;
	$query = "SELECT * FROM `".DB_PREFIX."Pages` WHERE PageId='$PageId'"; 
	$result = $Db->query($query); 
	if($result->num_rows > 0) {
		$result = $result->fetch_assoc(); 
		$result['Content'] = decode(($result['Content']), true); 
		return $result;
	}
	return false; // page not found!
}

function decode($json, $assoc = FALSE){
	// Strip out the characters that json cannot handle
   	$json = str_replace(array("\n","\r","\t", ';'),"",$json);
   // $json = preg_replace('/([{,])(\s*)([^"]+?)\s*:/','$1"$3":',$json);*/
	return (array)json_decode($json, $assoc);
}

function encode($json) {
	return json_encode($json);
}

function getPageElement($PageId, $Element) { 
	global $Db;
	// Grab the appropriate record
	$query = "SELECT Content FROM `".DB_PREFIX."Pages` WHERE PageId='$PageId'";
	$e = $Db->query($query); 
	$e = $e->fetch_assoc();
	// Get just the content section of the record 
	$e = $e['Content']; 
	$e = decode($e, true); 
	// Return only the element requested
	if(isset($e[$Element])) {
		$e = $e[$Element];
	} else {
		$e = ''; // element did not exist. send back an empty string
	}

	return $e;
}

function setPageElement($PageId, $Element, $Content) { 
	global $Db;
	
	// Grab the current content from the database. This is so we can update the json object
	$query = "SELECT Content FROM `".DB_PREFIX."Pages` WHERE PageId='$PageId'";
	$e = $Db->query($query);
	$e = $e->fetch_assoc(); 
	$e = decode($e['Content']); 
	// Need to loop through and escape each page element again or we risk breaking the page!
	foreach($e AS $k => $v) {
		$e[$k] = $Db->sanitize($v);
	}
	
	// Make the new content safe for the database
	$Content = trim(str_replace(array("\r","\n", "\t"), '', $Content));
	$Content = $Db->sanitize($Content);
	
	// Add the content back into the json element
	$e[$Element] = $Content;
	$e = encode($e); 
	
	// Update the content in the database
	$query = "UPDATE `".DB_PREFIX."Pages` SET Content='$e' WHERE PageId='$PageId' LIMIT 1"; 
	$Db->query($query); 
}