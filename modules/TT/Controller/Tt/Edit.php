<?php
class Controller_Tt_Edit extends Controller_Tt {

	public function index() {
		$tid = false;
		if (isset($_POST["tid"])) {
			if (is_numeric($_POST["tid"])) {
				$tid = $_POST["tid"];
			}
		}
		if (!$tid) {
			$tid = $this->args[1];
		}

		$data = $this->registry["tt"]->getTask($tid);
		// Если задача создана нами
		if ($this->registry["ui"]["id"] == $data[0]["who"]) {

			$this->view->setTitle("Правка задачи");

			if (isset($_POST["submit"])) {

				$_POST["task"] = $_POST["textfield"];
				unset($_POST["textfield"]);

				if ($tid = $this->registry["tt"]->editTask($_POST, $data)) {
					$this->registry["tt"]->spamUsers("Изменения в задаче", $_POST["tid"]);
						
					$this->view->refresh(array("timer" => "1", "url" => "tt/" . $_POST["tid"] . "/"));
				} else {
					$this->view->setMainContent("<p style='margin: 30px 0 0 50px; color: red'>Заполните текст задачи!</span>");
						
					$this->view->refresh(array("timer" => "1", "url" => "tt/edit/" . $_POST["tid"] . "/"));
				}
			} else {

				$data = $this->registry["tt"]->getTask($this->args[1]);

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

				$this->view->tt_edit(array("data" => $data, "obj" => $obj, "issRusers" => $issRusers));

			}
		}

		$this->view->showPage();
	}
}
?>