<?php
/**
 * Loads and fills in template files based upon passed in values
 *
 * Provides limited caching ability
 *
 * @author Ben Lobaugh <ben@lobaugh.net>
 * @package core
 * @category Template
 */


require_once('simple_html_dom.php');


/**
 * Loads the template contents
 *
 * This is outside the class because if it were called from inside the template
 * would not have access to anything but the class
 *
 * @param String $file - Path to template file
 * @return String - template file contents
 */
function TM_loadFile($file) {
    ob_start();
    require_once($file);
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}

class TemplateManager {

	/**
	 * Holds the simple_html_dom
	 *
	 * @var simple_html_dom
	 **/
	var $mDom;
	
	/**
	 * Holds special template vars that are not
	 * able to be accessed through the dom
	 *
	 * @var Associative Array
	 **/
	var $mSpecialTags;

        /**
         * Location of the template file
         * 
         * @var String
         */
        var $mFile;

        /**
         * Public constructor
         */
	public function __construct() {
		$this->mDom = new simple_html_dom();
		$this->mSpecialTags = array();
	}

        /**
         * PHP Magic function to convert this class into a string
         * @return String
         */
	public function __toString() { 
		return $this->display(false);
	}

        /**
         * Loads the template file from the hard disk
         *
         * @param String $File - path to template file
         */
	public function loadFile($File) {
                $this->mFile = $File;
                $this->loadString(TM_loadFile($this->mFile));
		//$this->mDom->load_file($File);
	}

        /**
         * Instead of loading in a file, a string containing the template
         * may be used
         *
         * @param String $String
         */
	public function loadString($String) {
		$this->mDom->load($String);
	}

        /**
         * Replaces all the special tags and then displays the completed
         * template
         *
         * @param Boolean $Echo
         * @param Boolean $EraseSpecialTags
         * @return String|N/A
         */
	public function display($Echo = true, $EraseSpecialTags = true) {

		// Replace all the special tags with their values
		$this->replaceSpecialTags(); 
		// Erase non-used special tags if desired. This is the default
		if($EraseSpecialTags) {
			$this->EraseSpecialTags();
		}
		
		// Load up whatever extensions may be needed
	/*	$this->mDom->innertext = preg_replace_callback(
		        '/\[ext\](.*)\[\/ext\]/',
		        'load_extension',
		        $this->mDom->innertext
		    );*/
	
		if($Echo) {
			echo $this->mDom->innertext;
			return;
		}
		
		
		return $this->mDom->innertext;
	}
	
	/**
         * Sets the value of an html tag
         *
         * @example setTag('title', 'The Title') -> <title>The Title</title>
         * @param String $Tag
         * @param String $Value
         * @param String $Type - s => Replace, p => Prepend, a => Append
         */
	public function setTag($Tag, $Value, $Type = 'r') {
		
		switch($Type) {
			case 'r': 
				// Set the tag (relaces existing content)
				$this->mDom->find($Tag, 0)->innertext = $Value;
				break;
			case 'p':
				// Prepend - adds to the beginning of content
				$this->mDom->find($Tag, 0)->innertext = "\n".$Value . $this->mDom->find($Tag, 0)->innertext;
				break;
			case 'a':
				// Append - adds to the end of content
				$this->mDom->find($Tag, 0)->innertext .= $Value;
				break;
		}
		
		// odd hack but it seems to work. 
		// how will it impact load times on high traffic sites?
		// appending multiple times to <head> worked fine, but appending to <body> borked it
		$this->loadString($this->mDom->innertext); 
	}

        /**
         * Sets the inner value of a tag by it's html id
         *
         * @example setById('bleah', 'Hello World') -> <div id="bleah">Hello World</div>
         * @param String $Id
         * @param String $Value
         * @param String $Type - s => Replace, p => Prepend, a => Append
         */
	public function setById($Id, $Value, $Type = 'r') { 
		switch($Type) {
			case 'r': 
				// Set the tag (relaces existing content)
				$this->mDom->find("#$Id", 0)->innertext = $Value;
				break;
			case 'p':
				// Prepend - adds to the beginning of content
				$this->mDom->find("#$Id", 0)->innertext = "\n".$Value . $this->mDom->find("#$Id", 0)->innertext;
				break;
			case 'a':
				// Append - adds to the end of content
				$this->mDom->find("#$Id", 0)->innertext .= $Value;
				break;
		}
		
	}

        /**
         * Sets special template tags. These tags are denoted by {{name}}
         *
         * @example setSpecialTag('bleah', 'Hello World') -> {{bleah}} -> Hello World
         * @param String $Tag
         * @param String $Value
         */
	public function setSpecialTag($Tag, $Value) { 
		// Allow dev to pass in an array of tags to ease setting multiple tags 
		if(is_array($Value)) {
			foreach($Value AS $k => $v) {
				$this->setSpecialTag($k, $v);
			}
		} else {
			// This must be a usable key/value pair
			$this->mSpecialTags["{{".$Tag."}}"] = $Value;
		}
	}

        /**
         * Replaces all the special template tags with their values
         */
	private function replaceSpecialTags() {
		foreach($this->mSpecialTags AS $t => $v) { //echo "replacing $t with $v<br>";
			$this->mDom->innertext = str_replace($t, $v, $this->mDom->innertext);
		}
	}

        public function addCssFile($file) {
            $this->setTag('head', '<link rel="stylesheet" href="'.$file.'" />'."\n", 'a');
        }

		public function addJsFile($file) { //echo "add $file"; error_reporting(E_ALL);
            $this->setTag('head', '<script type="text/javascript" src="'.$file.'" /></script>'."\n", 'a');
        }

		public function addJs($string, $Type = 'a') {
	//		$this->setTag('head', '<script type="text/javascript" src="'.$string.'" /></script>'."\n", 'a');
			$this->setTag('head', "<script type=\"text/javascript\">\n$string\n</script>\n\n", $Type);
		}

        /**
         * Erases all the remaining special template tags
         *
         * @deprecated
         */
	private function eraseSpecialTags() {
		
	}
} // end class TemplateManager