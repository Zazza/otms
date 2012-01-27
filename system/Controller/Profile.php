<?php
class Controller_Profile extends Engine_Controller {

	public function index() {
		$this->view->setLeftContent($this->view->render("left_profile", array()));
		
		if (isset($this->args[0])) {
			if ($this->args[0] == "profile") {
				Controller_Profile_Profile::index();
			} else if ($this->args[0] == "skin") {
				Controller_Profile_Skin::index();
			}
		} else {
			Controller_Profile_Profile::index();
		}
    }
}
?>