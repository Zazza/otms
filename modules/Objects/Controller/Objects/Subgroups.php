<?php
class Controller_Objects_Subgroups extends Controller_Objects {

	public function index() {
		$this->view->setTitle("Просмотр объектов");

		if (isset($this->args[1])) {
			
			if (isset($_GET["page"])) {
				if (is_numeric($_GET["page"])) {
					if (!$this->object->setPage($_GET["page"])) {
						$this->__call("objects", "list");
					}
				}
			}
			
			$ai = $this->registry["module_kb"];
			$forms = $ai->getForms();

			$this->object->links = "objects/" . $this->args[0] . "/" . $this->args[1] . "/";
			
			$data = $this->object->getObjects($this->args[1]);
			
			$template = new Model_Template();
			$tid = $template->getTidFromTtid($this->args[1]);
			$tt = $template->getTree($tid[0]["tid"]);
			
			foreach($data as $part) {			
				$arr_objs[] = $this->object->getShortObject($part["id"]);
			}
			
			if (isset($this->registry["module_mail"])) {
				$mail = true;
			} else {
				$mail = false;
			}
			
			$this->view->objects_subgroups(array("objs" => $arr_objs, "mail" => $mail, "forms" => $forms, "gid" => $this->args[1], "tt" => $tt));
			
			//Отобразим пейджер
			if (count($this->object->pager) != 0) {
				$this->view->pager(array("pages" => $this->object->pager));
			}
		}

		$this->view->showPage();
	}
	
}
?>