<?php
class Controller_Api extends Modules_Controller {
	private $_login = null;
	private $_password = null;
	private $_uid = 0;
	private $_oid = 0;

	private function _getTask($tid) {
		$task = $this->registry["tt"]->getTask($tid);
		$author = $this->registry["user"]->getUserInfo($task[0]["who"]);
		$numComments = $this->registry["tt"]->getNumComments($tid);
		$newComments = $this->registry["tt"]->getNewCommentsFromTid($tid, $this->_uid);
		
		$row["id"] = $task[0]["id"];
		$row["text"] = $task[0]["text"];
		$row["open_data"] = $task[0]["start"];
		$row["close_data"] = $task[0]["ending"];
		$row["author"] = $author["name"] . " " . $author["soname"];
		$row["numComments"] = $numComments;
		$row["newComments"] = $newComments;
		
		return $row;
	}
	
	private function _getComments($tid) {
		$row = array();
		
		$comments = $this->registry["tt"]->getComments($tid);
		$this->registry["tt"]->addTaskView($tid, $this->_uid);
		
		$i = 0;
		foreach($comments as $part) {
			$i++;
			$row[$i]["text"] = $part["text"];
			$row[$i]["date"] = $part["timestamp"];
			if ($part["object"]) {
				$row[$i]["author"] = "Я";
			} else {
				if ( (isset($part["ui"]["name"])) and (isset($part["ui"]["soname"])) ) {
					$row[$i]["author"] = $part["ui"]["name"] . " " . $part["ui"]["soname"];
				}
			}
		}
		
		return $row;
	}
	
	private function _addTask($post) {
		$post["imp"] = "3";
		$post["ttgid"] = "0";
		$post["type"] = "0";
		$post["startdate_global"] = date("Y-m-d");
		$post["starttime_global"] = date("H:i:s");
		$post["timetype_itertime"] = "0";
		$post["task"] = nl2br(htmlspecialchars($post["text"]));
		$post["delegate"] = $this->_uid;
		$this->registry["tt"]->addTask($this->_oid, $post);
	}
	
	private function _addComment($tid, $text) {
		$this->registry["tt"]->addCommentFromUid($tid, $this->_uid, nl2br(htmlspecialchars($text)));
	}
	
	private function _closeTask($tid) {
		$this->registry["tt"]->closeTask($tid, $this->_uid);
	}
	
	public function index() {
		$this->json = new Helpers_JsonGet();
		if (isset($_GET['callback'])) {
			$this->json->call = $_GET['callback'];
		}
		
		if (isset($this->get["oid"])) {
			if (is_numeric($this->get["oid"])) {
				$this->_oid = $this->get["oid"];
			}
		}
		
		if ($this->_oid == 0) {
			$row["error"] = "<p>Access denied!</p>";
			$this->json->JSONGet($row);
		
			return false;
		}

		$this->_login = $this->get["login"];
		$this->_password = $this->get["password"];
		$this->_uid = $this->registry["module_users"]->getUserId($this->_login);

		if (isset($this->get["action"])) {
			if ($this->get["action"] == "getTaskList") {
				$tid = $this->registry["tt"]->getOidTasks($this->_oid, true);
				
				foreach($tid as $part) {
					$row[] = $this->_getTask($part["id"]);
				}
			} else if ($this->get["action"] == "getTask") {
				if ( (isset($this->get["tid"])) and (is_numeric($this->get["tid"])) ) {
					$row["task"] = $this->_getTask($this->get["tid"]);
					$row["comments"] = $this->_getComments($this->get["tid"]);
				} else {
					$row = array();
				}
			} else if ($this->get["action"] == "addTask") {
				$post["text"] = rawurldecode($this->get["text"]);
				if (!isset($this->get["ruser"])) {
					$post["ruser"] = array();
				} else {
					$post["ruser"] = $this->get["ruser"];
				}
				if (!isset($this->get["gruser"])) {
					$post["gruser"] = array();
				} else {
					$post["gruser"] = $this->get["gruser"];
				}
				if (!isset($this->get["rall"])) {
					$post["rall"] = array();
				} else {
					$post["rall"] = $this->get["rall"];
				}
				$this->_addTask($post);
				
				$row[] = true;
			} else if ($this->get["action"] == "addComment") {
				if ( (isset($this->get["tid"])) and (is_numeric($this->get["tid"])) ) {
					$row["task"] = $this->_addComment($this->get["tid"], $this->get["text"]);
				} else {
					$row = array();
				}
			} else if ($this->get["action"] == "closeTask") {
				if ( (isset($this->get["tid"])) and (is_numeric($this->get["tid"])) ) {
					$row["task"] = $this->_closeTask($this->get["tid"]);
				} else {
					$row = array();
				}
			} else {
				$row = array();
			}
		}

		$this->json->JSONGet($row);
    }
 }
 ?>