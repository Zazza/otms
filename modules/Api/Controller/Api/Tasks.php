<?php
class Controller_Api_Tasks extends Controller_Api {
	private $_uid = 0;
	private $_json = null;
	private $_oid = 0;
	private $_get = array();

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
		return $this->registry["tt"]->addTask($this->_oid, $post);
	}

	private function _addComment($tid, $text) {
		$this->registry["tt"]->addCommentFromUid($tid, $this->_uid, nl2br(htmlspecialchars($text)));
	}

	private function _closeTask($tid) {
		$this->registry["tt"]->closeTask($tid, $this->_uid);
	}
	
	public function index($uid, $json, $get, $oid = 0) {
		$this->_uid = $uid;
		$this->_json = $json;
		$this->_get = $get;

		if (isset($this->_get["oid"])) {
			if (is_numeric($this->_get["oid"])) {
				$this->_oid = $this->_get["oid"];
			}
		} else if ($oid != 0) {
			$this->_oid = $oid;
		}
		
		if ($this->_oid == 0) {
			$row["error"] = "<p>Access denied!</p>";
			$this->_json->JSONGet($row);
		
			return false;
		}
		
		if (isset($this->_get["action"])) {
			if ($this->_get["action"] == "closeTask") {
				$row[] = $this->registry["tt"]->closeTask($this->_get["tid"]);
			} else if ($this->_get["action"] == "getTaskList") {
		
				$tid = $this->registry["tt"]->getOidTasks($this->_oid, true);

				foreach($tid as $part) {
					$row[] = $this->_getTask($part["id"]);
				}
			} else if ($this->_get["action"] == "getTask") {
		
				if ( (isset($this->_get["tid"])) and (is_numeric($this->_get["tid"])) ) {
					$row["task"] = $this->_getTask($this->_get["tid"]);
					$row["comments"] = $this->_getComments($this->_get["tid"]);
				} else {
					$row = array();
				}
			} else if ($this->_get["action"] == "addTask") {
		
				$post["text"] = rawurldecode($this->_get["text"]);
				if (!isset($this->_get["ruser"])) {
					$post["ruser"] = array();
				} else {
					$post["ruser"] = $this->_get["ruser"];
				}
				if (!isset($this->_get["gruser"])) {
					$post["gruser"] = array();
				} else {
					$post["gruser"] = $this->_get["gruser"];
				}
				if (!isset($this->_get["rall"])) {
					$post["rall"] = array();
				} else {
					$post["rall"] = $this->_get["rall"];
				}
				
				$row[] = $this->_addTask($post);
			} else if ($this->_get["action"] == "addComment") {
		
				if ( (isset($this->_get["tid"])) and (is_numeric($this->_get["tid"])) ) {
					$row["task"] = $this->_addComment($this->_get["tid"], $this->_get["text"]);
				} else {
					$row = array();
				}
			} else {
				$row = array();
			}
		}
		
		$this->_json->JSONGet($row);
	}
}
?>