<?php
class Controller_Kb_Add extends Controller_Kb {

	public function index() {
		$this->view->setTitle("Добавить информацию");
		
		$this->view->kb_add();
		
		$this->view->showPage();
	}
}
?>