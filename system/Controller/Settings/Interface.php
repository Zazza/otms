<?php
class Controller_Settings_Interface extends Controller_Settings {
	public function index() {
		$this->view->setTitle("Настройки интерфейса");
		
		$settings = new Model_Settings();

		$this->view->settings_interface();
	}
}
?>