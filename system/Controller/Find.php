<?php
class Controller_Find extends Modules_Controller {
	protected $findSess = null;
	protected $numFind = null;
	protected $find = null;

	public function index() {
		$this->find = new Model_Find();
		
		$this->findSess = & $_SESSION["find"];
		
		if (isset($_POST["find"])) {
			$_POST["find"] = htmlspecialchars($_POST["find"]);
			$this->findSess["string"] = $_POST["find"];
		} else {
			if (!isset($this->findSess["string"])) {
				$this->findSess["string"] = "";
			}
		}
		
		$tfind = explode(" ", substr($this->findSess["string"], 0, 64));
		
		$this->numFind = $this->find->getNumFinds($tfind);
		
		$this->view->setLeftContent($this->view->render("left_find", array("num" => $this->numFind)));
		
		if (isset($this->args[0])) {
			if ($this->args[0] == "objects") {

				Controller_Find_Objects::index();

			} elseif ($this->args[0] == "tasks") {

				Controller_Find_Tasks::index();

			} elseif ($this->args[0] == "adv") {

				Controller_Find_Adv::index();

			} else {

				Controller_Find_Tasks::index();

			}
		} else {

			Controller_Find_Tasks::index();

		}
	}
}
?>