<?php
class Controller_Api_Objects extends Controller_Api {
	private $_uid = 0;
	private $_json = null;
	private $_get = array();
	private $_tid = 0;
	private $_ttypeid = 0;
	private $_oid = 0;

	private function _addObject($post) {
		foreach($post as $key=>$val) {
			if (mb_substr($key, 0, 1) == "_") {
				$fid = $this->registry["module_objects"]->getFidFromFname($this->_tid, mb_substr($key, 1, mb_strlen($key)-1));
				$new_post[$fid] = $val;
			}
		}
		
		$new_post["tid"] = $this->_tid;
		$new_post["ttypeid"] = $this->_ttypeid;
		
		$this->registry["module_objects"]->addObject($new_post, $this->_uid);
	}
	
	private function _editObject($post) {
		foreach($post as $key=>$val) {
			if (mb_substr($key, 0, 1) == "_") {
				$fid = $this->registry["module_objects"]->getFidFromFname($this->_tid, mb_substr($key, 1, mb_strlen($key)-1));
				$new_post[$fid] = $val;
			}
		}
		
		$new_post["tid"] = $this->_oid;

		$this->registry["module_objects"]->editObject($new_post, $this->_uid);
	}
	
	public function index($uid, $json, $get, $oid) {
		$this->_uid = $uid;
		$this->_json = $json;
		$this->_get = $get;
		$this->_oid = $oid;
		
		if (isset($this->_get["tempid"])) {
			if (is_numeric($this->_get["tempid"])) {
				$this->_tid = $this->_get["tempid"];
			}
		}
		
		if (isset($this->_get["ttypeid"])) {
			if (is_numeric($this->_get["ttypeid"])) {
				$this->_ttypeid = $this->_get["ttypeid"];
			}
		}
		
		if ($this->_tid == 0) {
			$row["error"] = "<p>Access denied!</p>";
			$this->_json->JSONGet($row);
		
			return false;
		}
		if ($this->_ttypeid == 0) {
			$row["error"] = "<p>Access denied!</p>";
			$this->_json->JSONGet($row);
		
			return false;
		}
		
		if (isset($this->_get["action"])) {
			if ($this->_get["action"] == "addObject") {
				$row[] = $this->_addObject($this->_get);
			} else if ($this->_get["action"] == "editObject") {
				$row[] = $this->_editObject($this->_get);
			} else {
				$row = array();
			}
		}
		
		$this->_json->JSONGet($row);
	}
}
?>