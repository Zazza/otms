<?php
class Cmd extends PreModule implements Modules_Interface {

	function __construct() {
		$module = new ReflectionClass($this);
		parent::__construct($module->getName());
	}
	
	function preInit() {
		$this->registry["cmd"] = new Model_CmdCommands($this->config);
	}
	
	function postInit() {
		$this->setFastMenu("CMD", $this->view->render("fastmenu", array()));
	}
}
?>
