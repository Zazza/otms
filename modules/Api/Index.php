<?php
class Api extends PreModule implements Modules_Interface {

	function __construct() {
		$module = new ReflectionClass($this);
		parent::__construct($module->getName());
	}
	
	function preInit() {

	}
	
	function postInit() {

	}
}
?>