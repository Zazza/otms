<?php
class Controller_Objects_List extends Controller_Objects {

	public function index() {
		$this->view->setTitle("Просмотр");

		$template = new Model_Template();
		$list = $template->getTemplates();

		$this->object->links = "/list";

		$data = $this->object->getObjsTree();

		$res_tree = null;

		foreach($data as $part) {
			if (!isset($part["id"])) {
				if (isset($part["tname"])) {
					$template = "[" . $part["tname"] . "]";
					$sub = $part["type_id"];

					if (!isset($res_tree[$template][$sub][0])) {
						$res_tree[$template][$sub][0] = null;
					}
				}
			}
		}

		$this->print_array($res_tree);

		$this->view->objects_find(array("list" => $this->find, "tree" => $this->tree, "templates" => $this->templates));

		$this->view->showPage();
	}
}
?>