<?php
/**
 * Loads and runs extensions
 *
 * @author Ben Lobaugh <ben@lobaugh.net>
 * @package core
 */


class ExtensionManager {
	
	
	/**
	 * Extensions that failed to load
	 *
	 * @var Array
	 **/
	private $mFailed;
	
	/**
	 * Extensions that loaded succesfully
	 * 
	 * @var Array
	 **/
	private $mLoaded;
	
	public function __construct() { 
		$this->mFailed = array();
		$this->mLoaded = array();
	}
	
	/**
	 * Parses a string and runs all extension tags found
	 * Returns the string with the executed extension
	 *
	 * @param String $String
	 * @return String
	 */
	public function parseAndRun($String) {
		$String = preg_replace_callback(
		        '/\[\[([^\]]*)\]\]/',
		        array(&$this, 'loadExtension'),
		        $String
		    );
		return $String;
		
	}
	
	private function loadExtension($Ext) {
		// These must be globalled here in order for extensions to access them
		global $Db, $Exts, $Tpl;
		// This is where the real extension is located
		// Pull the string apart. The ext name will be in the first position
		// Space seperated. Name first, then parameters
		// $Ext[0] = extension name
		// $Ext[1] = params
		$Ext = explode(' ', $Ext[1]);
		
		$extFile = NB_EXT . $Ext[0] . '/index.php';
		
		
		
		// Ensure the extension exists before going any further
		if(file_exists($extFile)) {
			$this->mLoaded[] = array($Ext[0], &$Ext[1], $extFile);
			// Build the parameter list
			// Build the extension parameters
			$ext_params = array();
			if(isset($Ext[1])) {
				$params = explode('&', $Ext[1]); 

				foreach($params AS $p) {
					$p = explode('=', $p);
					$ext_params[$p[0]] = $p[1];
				}
			}
		
			ob_start();
				include($extFile);
				$Ext = ob_get_contents();
			ob_end_clean();
			
			
			return $Ext;
		} else {
			// Failed to find or load extension
			$this->mFailed[] = array($Ext[0], &$Ext[1], $extFile);
		}
		
		//dBug($Ext); 
		
		
		return 'nope';
	}
	
	public function __toString() {
		$s = "<ul>";
		foreach($this->mLoaded AS $e) {
			$s .= '<li style="color:green">';
			$s .= implode(' | ', $e);
			$s .= '</li>';
		}
		foreach($this->mFailed AS $e) {
			$s .= '<li style="color:red">';
			$s .= implode(' | ', $e);
			$s .='</li>';
		}
		$s .= '</ul>';
		return $s;
	}
	
} // end class