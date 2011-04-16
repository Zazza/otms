<?php
class Controller_Login extends Controller_Index {
	public function __construct($registry, $action, $args) {
		parent::__construct($registry, $action, $args);
	}
	
	public function index($args) {
        if (!$this->registry["auth"]) {
            
            $this->view->setTitle("Вход");
            
            $login = new Model_Login($this->registry);
            
            if (isset($_POST["submit"])) {
                if ($login->login($_POST["login"], $_POST["pass"])) {
                    $this->view->refresh(array("timer" => "1", "url" => $this->registry["siteName"] . "/tt/"));
                } else {
                    $this->view->login(array("err" => TRUE, "url" => $this->registry["siteName"]));
                }
            } else {        
                $this->view->login(array("url" => $this->registry["siteName"]));
            }
        }
        
        $this->view->showPage();
    }
}
?>