<?php
class Controller_Tt_Draftedit extends Controller_Tt {

	public function index() {
		$this->view->setTitle("Редактирование черновики");

		if (isset($_POST["createtask"])) {

			if ( (isset($_GET["oid"])) and ($_GET["oid"] != "") ) {
				$oid = $_GET["oid"];
			} elseif (isset($_POST["selObjHid"])) {
				$oid = $_POST["selObjHid"];
			} else {
				$oid = 0;
			}

			$_POST["task"] = $_POST["textfield"];
			unset($_POST["textfield"]);

			if ($tid = $this->registry["tt"]->addTask($oid, $_POST)) {
				$this->registry["tt"]->SpamUsers("Новая задача", $tid);

				$this->view->refresh(array("timer" => "1", "url" => "tt/" . $tid . "/"));
			} else {
				$this->view->setMainContent("<p style='margin: 30px 0 0 50px; color: red'>Заполните текст задачи!</span>");

				$this->view->refresh(array("timer" => "1", "url" => "tt/add/?oid=" . $oid . "&date=" . $_GET["date"]));
			}
		} elseif (isset($_POST["submit"])) {
				
			$_POST["task"] = $_POST["textfield"];

			if ($tid = $this->registry["tt"]->editDraft($_POST)) {

				$this->view->refresh(array("timer" => "1", "url" => "tt/draft/"));
			}
		} else {
			$data = $this->registry["tt"]->getDraft($this->args[1]);

			$object = new Model_Object();
			$obj = $object->getShortObject($data[0]["oid"]);

			$issRusers = array(); $k = 0;
			foreach($data as $part) {

				if (($part["uid"]) != null) {
					if (($part["uid"]) != "0") {
						$row = $this->registry["user"]->getUserInfo($part["uid"]);

						$k++;

						$issRusers[$k]["desc"] = '<p><span style="font-size: 11px; margin-right: 10px;" id="udesc[' . $row["uid"] . ']">' . $row["name"] . ' ' . $row["soname"] . '</span>';
						$issRusers[$k]["desc"] .= '<input id="uhid[' . $row["uid"] . ']" type="hidden" name="ruser[]" value="' . $row["uid"] . '" /></p>';
					}
				}

				if (($part["rgid"]) != null) {
					if (($part["rgid"]) != "0") {
						$gname = $this->registry["user"]->getGroupName($part["rgid"]);

						$k++;

						$issRusers[$k]["desc"] = '<p style="font-size: 11px; margin-right: 10px">' . $gname . '<input type="hidden" name="gruser[]" value="' . $part["rgid"] . '" /></p>';
					}
				}

				if (($part["all"]) == "1") {
					$k++;

					$issRusers[$k]["desc"] = '<p style="font-size: 11px; margin-right: 10px">Все<input type="hidden" name="rall" value="1" /></p>';
				}
			}

			$this->view->tt_draftedit(array("data" => $data, "obj" => $obj, "issRusers" => $issRusers));

		}

		$this->view->showPage();
	}
}
?>