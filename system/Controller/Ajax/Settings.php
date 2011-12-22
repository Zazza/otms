<?php
class Controller_Ajax_Settings extends Engine_Interface {
	
	public function saveMenu($params) {
		$menu = $params["json"];
		
		$settings = new Model_Settings();
		$settings->setMenu($menu);
	}
	
	public function saveFastmenu($params) {
		$fastmenu = $params["json"];
		
		$settings = new Model_Settings();
		$settings->setFastmenu($fastmenu);
	}
}
?>