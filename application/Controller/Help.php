<?php
class Controller_Help extends Controller_Index {
	public function __construct($registry, $action, $args) {
		parent::__construct($registry, $action, $args);
	}
	
	public function index($args) {
        
        $this->view->showPage();
    }
}
?>