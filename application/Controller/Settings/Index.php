<?php
class Controller_Settings_Index extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "settings", "index");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            if ($this->registry["ui"]["admin"]) {
            
                $this->view->setTitle("Настройки");
                
                $this->view->setLeftContent($this->view->render("left_settings", array()));
            }
        }
        
        $this->view->showPage();
	}
}
?>