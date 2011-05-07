<?php
class Controller_Exit extends Controller_Index {
	public function __construct($registry, $action, $args) {
		parent::__construct($registry, $action, $args);
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            
            $this->view->setTitle("Выход");
            
            if (isset($_POST["submit"])) {
                session_destroy();

                $this->view->refresh(array("timer" => "1", "url" => "login/"));
            } else {
                $this->view->exit();
            }
        }
        
        $this->view->showPage();
    }
}
?>