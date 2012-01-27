<?php
class Controller_Objects_Edit extends Controller_Objects {

	public function index() {
		$this->view->setTitle("Правка объекта");

		$object = new Model_Object();

		if (isset($_POST["submit"])) {
			$object->editObject($_POST);

			$this->view->refresh(array("timer" => "1", "url" => "objects/edit/" . $_POST["tid"] . "/"));

		} else {

			if (isset($this->args[1])) {
				$data = $object->getObject($this->args[1]);
				$contact = & $_SESSION["contact"];
				if ((isset($contact["email"])) and ($contact["email"] != null)) {
					$email = $contact["email"];
				} else {
					$email = null;
				}
				$this->view->objects_edit(array("vals" => $data, "email" => $email));
			}

		}

		$this->view->showPage();
	}
}
?>