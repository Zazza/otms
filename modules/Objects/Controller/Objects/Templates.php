<?php
class Controller_Objects_Templates extends Controller_Objects {

	public function index() {
		if ($this->registry["ui"]["admin"]) {

			$this->view->setTitle("Шаблоны");

			$template = new Model_Template();
			$list = $template->getTemplates();

			if (isset($this->args[1])) {
				if ($this->args[1] == "add") {
					if (isset($_POST["submit"])) {
						$template->addTemplate($_POST);

						$this->view->refresh(array("timer" => "1", "url" => "objects/"));
					} else {
						$this->view->objects_templateadd();
					}
				} elseif ($this->args[1] == "edit") {
					if (isset($this->args[2])) {
						if (isset($_POST["submit"])) {
							$template->editTemplate($this->args[2], $_POST);

							$this->view->refresh(array("timer" => "1", "url" => "objects/"));
						} else {
							$param = $template->getTemplate($this->args[2]);
							$this->view->objects_templateedit(array("post" => $param));
						}
					}
				} elseif ($this->args[1] == "list") {
					$this->view->objects_templatelist(array("id" => $this->args[2]));
				}
			} else {
				$this->view->objects_templates(array("list" => $list));
			}
		}

		$this->view->showPage();
	}
}
?>