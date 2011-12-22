<?php
class TT_Public_Functions extends Modules_Functions {
	function getNumTasks() {
		return $this->registry["tt"]->getNumTasks();
	}

	function taskshort($param) {
		$taskshort = null;
		
		$part = $param[0];
		
		if ($data = $this->registry["tt"]->getTask($part)) {
			$numComments = $this->registry["tt"]->getNumComments($part);

			$author = $this->registry["user"]->getUserInfo($data[0]["who"]);

			$ruser = array();
			foreach($data as $val) {
				if (isset($val["uid"])) {
					if ($val["uid"] != 0) {
						$uname = $this->registry["user"]->getUserInfo($val["uid"]);
						$ruser[] = "<a style='cursor: pointer' onclick='getUserInfo(" . $val["uid"] . ")'>" . $uname["name"] . " " . $uname["soname"] . "</a>";
					}
				}

				if (isset($val["rgid"])) {
					if ($val["rgid"] != 0) {
						$ruser[] = "<span style='color: #5D7FA6'><b>" . $this->registry["user"]->getGroupName($val["rgid"]) . "</b></span>";
					}
				}

				if ($val["all"] == 1) {
					$ruser[] = "<span style='color: #D9A444'><b>Все</b></span>";
				}
			}

			$object = $this->registry["object"];

			$notObj = true;
			if (!$obj = $object->getShortObject($data[0]["oid"])) {
				$notObj = false;
			}

			$taskshort = $this->view->render("tt_taskshort", (array("ui" => $this->registry["ui"], "data" => $data, "author" => $author, "ruser" => $ruser, "notObj" => $notObj, "obj" => $obj, "numComments" => $numComments, "uid" => $this->registry["ui"]["id"])));
		}
		
		return $taskshort;
	}
	
	function formtask() {
		return $this->view->render("tt_tabs", array());
	}
	
	function closeTask($param) {
		$this->registry["tt"]->closeTask($param[0]);
	}
	
	function getTask($param) {
		return $this->registry["tt"]->getTask($param[0]);
	}
}
?>