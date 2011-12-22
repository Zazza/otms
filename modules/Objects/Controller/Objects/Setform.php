<?php
class Controller_Objects_Setform extends Controller_Objects {

	public function index() {

		$this->view->setTitle("Форма для выбранного объектаы");

		$ai = $this->registry["module_kb"];
		$obj = new Model_Object();

		if ( (isset($this->args[1])) and ($this->args[1] == "edit") ) {
			if (isset($_POST["submit"])) {
				unset($_POST["submit"]);

				$ai->editObjectFormInfo($_GET["oaid"], $_POST);

				$this->view->refresh(array("timer" => "1", "url" => "objects/"));
					
			} else {
				if (isset($_GET["oaid"])) {
					$afinfo = $ai->getAdvanced($_GET["oaid"]);
					$afinfo = json_decode($afinfo["val"], true);

					$this->view->objects_setformedit(array("oaid" => $_GET["oaid"], "afinfo" => $afinfo));
				}
			}
		} else if (isset($_POST["submit"])) {
			unset($_POST["submit"]);

			$form = $ai->getFormName($_GET["fid"]);

			$title = "[" . $form . "] ";
			$sObj = $obj->getShortObject($_GET["oid"]);
			foreach($sObj as $part) {
				$title .= $part["val"] . " ";
			}

			$oaid = $ai->addObjectFormInfo($_GET["oid"], $title, $_POST);

			$ai->addTags($oaid, $form);

			$this->view->refresh(array("timer" => "1", "url" => "objects/"));

		} else {
			if ( (isset($_GET["oid"])) and (isset($_GET["fid"])) ) {
				$this->view->objects_setform(array("oid" => $_GET["oid"], "fid" => $_GET["fid"]));
			}
		}

		$this->view->showPage();
	}
}
?>