<?php
class Controller_Api_Events extends Controller_Api {
	private $_uid;
	private $_json;
	private $_get;
	private $_oid;
	
	private function _addEvent() {
		return $this->registry["module_logs"]->addEvent($this->_uid, $this->_get["message"], $this->_oid);
	}
	
	public function index($uid, $json, $get, $oid) {
		$this->_uid = $uid;
		$this->_json = $json;
		$this->_get = $get;
		$this->_oid = $oid;
		
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
			if ($this->_get["action"] == "add") {
				$row[] = $this->_addEvent();
			}
		}
		
		$this->_json->JSONGet($row);
	}
}
?>