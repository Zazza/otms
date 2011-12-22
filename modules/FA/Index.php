<?php
class FA extends PreModule implements Modules_Interface {
	function __construct() {
		$module = new ReflectionClass($this);
		parent::__construct($module->getName());
	}
	
	function preInit() {
		
	}
	
	function postInit() {
		$fm = new FA_Public_Functions($this->config);
		$content = $fm->renderFM();
		$this->setContent($content);
	}
}
?>