<?php
class Logs_Public_Functions extends Modules_Functions {
	function addChatMessageAll($params) {
		$room = $params[0];
		
		$string = $this->view->render("logs_invite", array("room" => $room));
		 
		$users = $this->registry["user"]->getUsersList();
		 
		foreach($users as $user) {
			if ($user["id"] != $this->registry["ui"]["id"]) {
				$this->registry["logs"]->uid = $user["id"];
				$this->registry["logs"]->set("service", $string, "");
			}
		}
	}
	
	function addChatMessageGroup($params) {
		$gid = $params[0];
		$room = $params[1];
		
		$string = $this->view->render("logs_invite", array("room" => $room));
		 
		$users = $this->registry["user"]->getUserInfoFromGroup($gid);
		 
		foreach($users as $user) {
			if ($user["uid"] != $this->registry["ui"]["id"]) {
				$this->registry["logs"]->uid = $user["uid"];
				$this->registry["logs"]->set("service", $string, "");
			}
		}
	}
	
	function addChatMessageUser($params) {
		$uid = $params[0];
		$room = $params[1];
		
		$string = $this->view->render("logs_invite", array("room" => $room));
		 
		if ($uid != $this->registry["ui"]["id"]) {
			$this->registry["logs"]->uid = $uid;
			$this->registry["logs"]->set("service", $string, "");
		}
	}
}
?>