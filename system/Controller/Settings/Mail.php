<?php
class Controller_Settings_Mail extends Controller_Settings {
	public function index() {
		$this->view->setTitle("Настройки почты");
		
		$settings = new Model_Settings();
		
		if (isset($_POST["submit"])) {
			$flag = true;

			if ($_POST["email"] == "") { $flag = false; }
			if ($_POST["server"] == "") { $flag = false; }
			if ($_POST["auth"] == "0") {
				$_POST["login"] = "";
				$_POST["password"] = "";
			} else {
				if ($_POST["login"] == "") { $flag = false; }
				if ($_POST["password"] == "") { $flag = false; }
			}
			if ($_POST["port"] == "") { $flag = false; }
			if ($_POST["ssl"] == "") { $flag = false; }
			
			if ($flag) {
				$settings->editMailbox($_POST);
				
				$this->view->refresh(array("timer" => "1", "url" => "settings/mail/"));
			} else {
				$this->view->settings_mail(array("err" => true, "post" => $_POST));
			}
		} else {
			$mailboxes = $settings->getMailbox();
		
			$this->view->settings_mail(array("post" => $mailboxes));
		}
	}
}
?>