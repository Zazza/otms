<?php
class Controller_Settings extends Modules_Controller {
	public function index() {
		if ($this->registry["ui"]["admin"]) {
			$this->view->setLeftContent($this->view->render("left_settings", array()));
			
			if (isset($this->args[0])) {
				if ($this->args[0] == "mail") {
					Controller_Settings_Mail::index();
				} else if ($this->args[0] == "interface") {
					Controller_Settings_Interface::index();
				} else {
					$this->view->settings_index();
				}
			} else {
				$this->view->settings_index();
			}
		}
	}
}
?>