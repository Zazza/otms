<?php
abstract class Engine_Interface {
	protected $registry;
	
	function __construct() {
		$this->registry = Engine_Registry::getInstance();
	}
}
?>