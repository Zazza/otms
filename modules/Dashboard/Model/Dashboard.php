<?php
class Model_Dashboard extends Modules_Model {
	private $dashboard;
	
	function __construct() {
		parent::__construct();
				
		$this->dashboard = & $_SESSION["dashboard"];
	}
	
	private function rightLogs($data) {
		$k = 0;
		$result = array();
		
		for($i=0; $i<count($data); $i++) {
			$data[$i]["timestamp"] = date("d F Y H:i:s", strtotime($data[$i]["timestamp"]));
		
			if ( ($data[$i]["type"] == "task") or ($data[$i]["type"] == "com") ) {
				if ($task = $this->registry["tt"]->getTask($data[$i]["oid"])) {
					if ($this->registry["tt"]->acceptReadTask($task)) {
						$result[$k] = $data[$i];
						$result[$k]["param"] = $this->registry["logs"]->getHistoryFromId($data[$i]["id"]);
		
						$k++;
					}
				}
			} elseif ( ($data[$i]["type"] == "mail") or ($data[$i]["type"] == "service") ) {
				if ($data[$i]["uid"] == $this->registry["ui"]["id"]) {
					$result[$k] = $data[$i];
					$result[$k]["param"] = $this->registry["logs"]->getHistoryFromId($data[$i]["id"]);
		
					$k++;
				}
			} else {
				$result[$k] = $data[$i];
				$result[$k]["param"] = $this->registry["logs"]->getHistoryFromId($data[$i]["id"]);
					
				$k++;
			}
		}
		
		return $result;
	}
	
	private function getSessionWhere() {
	
		$where[] = "logs.type = 'service'";
			
		if ($this->dashboard["task"]) {
			$where[] = "logs.type = 'task'";
		};
		if ($this->dashboard["com"]) {
			$where[] = "logs.type = 'com'";
		};
		if ($this->dashboard["obj"]) {
			$where[] = "logs.type = 'obj'";
		};
		if ($this->dashboard["info"]) {
			$where[] = "logs.type = 'info'";
		};
		if ($this->dashboard["mail"]) {
			$where[] = "logs.type = 'mail'";
		};
		
		$where = implode(" OR ", $where);
		$where = "AND (" . $where . ")";
		
		return $where;
	}
	
	private function getSessionDate() {
		if (isset($this->dashboard["date"])) {
			$date = "TO_DAYS(logs.timestamp) = TO_DAYS('" . date("Y-m-d", strtotime($this->dashboard["date"])) . "')";
		} else {
			$date = "TO_DAYS(logs.timestamp) = TO_DAYS('" . date("Y-m-d") . "')";
		}
		
		return $date;
	}
	
	function getEvents() {
		$data = array();
		
		$where = $this->getSessionWhere();

		$date = $this->getSessionDate();

		$data = Model_Db_Dashboard::getEvents($date, $where);

		return $this->rightLogs($data);
	}
	
	function getDashEvents() {
		$data = array();
	
		$where = "AND logs.timestamp LIKE '%'";
	
		$date = "logs.type LIKE '%'";
	
		$data = Model_Db_Dashboard::getDashEvents($date, $where);
	
		return $this->rightLogs($data);
	}
	
	function getNewEvents() {
		$lid = $this->registry["logs"]->getLastDashId();
		
		$date = $this->getSessionDate();
		
		$data = Model_Db_Dashboard::getNewEvents($lid, $date);
		
		return $this->rightLogs($data);
	}

	function getNotify() {
		// Если сессии нет - по умолчанию включены все уведомления
		if (isset($this->dashboard["task"])) {
			$notify["task"] = $this->dashboard["task"];
		} else { $notify["task"] = true; $this->dashboard["task"] = true;
		}
		if (isset($this->dashboard["com"])) {
			$notify["com"] = $this->dashboard["com"];
		} else { $notify["com"] = true; $this->dashboard["com"] = true;
		}
		if (isset($this->dashboard["obj"])) {
			$notify["obj"] = $this->dashboard["obj"];
		} else { $notify["obj"] = true; $this->dashboard["obj"] = true;
		}
		if (isset($this->dashboard["info"])) {
			$notify["info"] = $this->dashboard["info"];
		} else { $notify["info"] = true; $this->dashboard["info"] = true;
		}
		if (isset($this->dashboard["mail"])) {
			$notify["mail"] = $this->dashboard["mail"];
		} else { $notify["mail"] = true; $this->dashboard["mail"] = true;
		}
		 
		return $notify;
	}

	public function closeEvent($eid) {
		Model_Db_Dashboard::closeEvent($eid);
	}
}
?>