<?php
class Preload extends Engine_Bootstrap {
    function run() {
        $view = new View_Index();
        $this->registry->set('view', $view);
        
		$view->setDescription($this->registry["keywords"]);
		$view->setKeywords($this->registry["description"]);
		
		$ui = new Model_Ui();

		$loginSession = & $_SESSION["login"];
		if (isset($loginSession["id"])) {
			$ui->getInfo($loginSession);
		} else if (mb_substr($this->registry["url"], 1, 3) == "api") {
			$api = new Api_Model_Login();
			if (!$api->login()) {
				exit();
			}
		} else {
			$login = new Controller_Login();
			$login->index();
			 
			exit();
		}
		
		Modules_Modules::load();
    }
}
?>