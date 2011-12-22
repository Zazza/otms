<?php
class Logs extends PreModule implements Modules_Interface {
	function __construct() {
		$module = new ReflectionClass($this);
		parent::__construct($module->getName());
	}
	
	function preInit() {
		$this->registry["logs"] = new Model_Logs();
	}
	
	function postInit() {
		
	}
}
?>