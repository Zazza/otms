<?php
class Controller_Tt_Index extends Controller_Tt {

	public function index() {
		$this->view->setTitle("Задачи");

		$object = new Model_Object();
		
		$cal = & $_SESSION["cal"];

		if (isset($_GET["page"])) {
			if (is_numeric($_GET["page"])) {
				if (!$this->registry["tt"]->setPage($_GET["page"])) {
					$this->__call("tt", "index");
				}
			}
		}

		if (isset($this->args[0])) {

			if ($this->args[0] == "task") {
					
				if (isset($this->args[1])) {
					$this->registry["tt"]->links = "tt/" . $this->args[0] . "/" . $this->args[1] . "/";
				} else {
					$this->registry["tt"]->links = "tt/" . $this->args[0] . "/";
				}
					
				if (isset($this->args[1])) {
					if($this->args[1] == "iter") {
						$tasks = $this->registry["tt"]->getIterTasks();
						$this->view->tt_caltype(array("caltype" => $cal["type"], "date" => $this->model->editDate(date("Y-m-d"))));
					} elseif($this->args[1] == "time") {
						$tasks = $this->registry["tt"]->getTimeTasks();
						$this->view->tt_caltype(array("caltype" => $cal["type"], "date" => $this->model->editDate(date("Y-m-d"))));
					} elseif($this->args[1] == "noiter") {
						$tasks = $this->registry["tt"]->getNoiterTasks();
						$this->view->tt_caltype(array("caltype" => $cal["type"], "date" => $this->model->editDate(date("Y-m-d"))));
					} elseif($this->args[1] == "me") {
						$sortmytt = & $_SESSION["sortmytt"];
						if ( (!isset($sortmytt["sort"])) or (!isset($sortmytt["id"])) ) {
							$sortmytt["sort"] = "date";
							$sortmytt["id"] = "false";
						}

						$sort_groups = $this->registry["tt"]->getSortGroupsMe();
						$this->view->setLeftContent($this->view->render("left_sortmytt", array("sort" => $sortmytt, "sg" => $sort_groups)));
						
						$tasks = $this->registry["tt"]->getMeTasks();
					}
				} else {
					$this->__call("tt", "index");
				}
			} elseif($this->args[0] == "date") {
				$this->registry["tt"]->links = "tt/" . $this->args[0] . "/" . $this->args[1] . "/";

				$this->view->tt_caltype(array("caltype" => $cal["type"], "date" => $this->model->editDate(date("Y-m-d", strtotime($this->args[1])))));
				
				$tasks = $this->registry["tt"]->getTasksDate($this->registry["ui"]["id"], $this->args[1]);
			} elseif($this->args[0] == "oid") {
				$this->registry["tt"]->links = "tt/" . $this->args[0] . "/" . $this->args[1] . "/";
				
				$tasks = $this->registry["tt"]->getOidTasks($this->args[1]);
			}
		} else {
			$sortmytt = & $_SESSION["sortmytt"];
			if ( (!isset($sortmytt["sort"])) or (!isset($sortmytt["id"])) ) {
				$sortmytt["sort"] = "date";
				$sortmytt["id"] = "false";
			}
			
			$sort_groups = $this->registry["tt"]->getSortGroups();
			$this->view->setLeftContent($this->view->render("left_sortmytt", array("sort" => $sortmytt, "sg" => $sort_groups)));
				
			$this->registry["tt"]->links = "tt/";

			$tasks = $this->registry["tt"]->getTasks();
		}

		if (count($tasks) == 0) {
			$this->view->setMainContent("<p style='margin: 10px'>Задачи не найдены</p>");
		}

		foreach($tasks as $part) {

			if ($data = $this->registry["tt"]->getTask($part["id"])) {
				$numComments = $this->registry["tt"]->getNumComments($part["id"]);
				$newComments = $this->registry["tt"]->getNewCommentsFromTid($part["id"]);

				if ($data[0]["remote_id"] == 0) {
					if (isset($this->registry["module_users"])) {
						$author = $this->registry["user"]->getUserInfo($data[0]["who"]);
					} else {
						$ui = new Model_Ui();
						$user = $ui->getInfo($val["uid"]);
					}
				} else {
					$author = $this->registry["tt_user"]->getRemoteUserInfo($data[0]["who"]);
				}

				$ruser = array();

				foreach($data as $val) {
					if (isset($val["uid"])) {
						if ($val["uid"] != 0) {
							if (isset($this->registry["module_users"])) {
								$user = $this->registry["user"]->getUserInfo($val["uid"]);
							} else {
								$ui = new Model_Ui();
								$user = $ui->getInfo($val["uid"]);
							}

							$ruser[] = "<a style='cursor: pointer' onclick='getUserInfo(" . $val["uid"] . ")'>" . $user["name"] . " " . $user["soname"] . "</a>";
						}
					}

					if (isset($val["rgid"])) {
						if ($val["rgid"] != 0) {
							$ruser[] = "<span style='color: #5D7FA6'><b>" . $this->registry["user"]->getSubgroupName($val["rgid"]) . "</b></span>";
						}
					}

					if ($val["all"] == 1) {
						$ruser[] = "<span style='color: #D9A444'><b>Все</b></span>";
					}
				}

				$cuser = $this->registry["user"]->getUserInfo($data[0]["cuid"]);

				$notObj = true;
				if (!$obj = $object->getShortObject($data[0]["oid"])) {
					$notObj = false;
				}

				$this->view->tt_task(array("data" => $data, "author" => $author, "ruser" => $ruser, "cuser" => $cuser, "notObj" => $notObj, "obj" => $obj, "numComments" => $numComments, "newComments" => $newComments));

				unset($ruser);
			} else {
				$this->view->setMainContent("<p style='margin: 10px0'>Задача не найдена</p>");
			}
		}

		//Отобразим пейджер
		if (count($this->registry["tt"]->pager) != 0) {
			$this->view->pager(array("pages" => $this->registry["tt"]->pager));
		}
			
		$this->view->showPage();
	}
}
?>