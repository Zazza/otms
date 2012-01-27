<?php
class Users extends PreModule implements Modules_Interface {
	function __construct() {
		$module = new ReflectionClass($this);
		parent::__construct($module->getName());
	}
	
	function preInit() {
		$this->registry["user"] = new Model_User();
		
		$this->registry["user"]->setOnline();
	}
	
	function postInit() {
		$this->setMenu("Пользователи");
	}
}
?>