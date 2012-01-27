<?php
class Controller_Objects_Forms extends Controller_Objects {

	public function index() {
		$this->view->setTitle("Добавить объект");
		
		$ai = $this->registry["module_kb"];
		
		if (isset($this->args[1])) {
			if ($this->args[1] == "add") {
				if (isset($_POST["submit"])) {
					$ai->addForm($_POST);
					
					$this->view->refresh(array("timer" => "1", "url" => "objects/forms/"));
				} else {
					$this->view->objects_formadd();
				}
			} else if ($this->args[1] == "edit") {
				if (isset($_POST["submit"])) {
					$ai->editForm($_GET["id"], $_POST);
					
					$this->view->refresh(array("timer" => "1", "url" => "objects/forms/"));
				} else if (isset($_GET["id"])) {
					$post = $ai->getForm($_GET["id"]);
					
					$this->view->objects_formedit(array("post" => $post));
				}
			}
		} else {
			$forms = $ai->getForms();
			
			$this->view->objects_formslist(array("forms" => $forms));
		}
		
		$this->view->showPage();
	}
}
?>