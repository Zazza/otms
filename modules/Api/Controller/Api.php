<?php
class Controller_Api extends Modules_Controller {
	private $_login = null;
	private $_password = null;
	private $_uid = 0;
	private $_oid = 0;
	public $json;
	
	private function getOidFromUniqId($tid, $uniqId) {
		return $this->registry["module_objects"]->getOidFromUniqId($tid, $uniqId);
	}
	
	public function index() {
		$this->get = $_REQUEST;
		
		$json = new Helpers_JsonGet();
		if (isset($_GET['callback'])) {
			$json->call = $_GET['callback'];
		}

		$this->_login = $this->get["login"];
		$this->_password = $this->get["password"];
		$this->_uid = $this->registry["module_users"]->getUserId($this->_login);

		if ( (isset($this->get["uniq_id"])) and (isset($this->get["tempid"])) ) {
			$this->_oid = $this->getOidFromUniqId($this->get["tempid"], $this->get["uniq_id"]);
		}
		
		if (isset($this->get["module"])) {
			if (($this->get["module"]) == "task") {
				$api = new Controller_Api_Tasks();
				$api->index($this->_uid, $json, $this->get, $this->_oid);
			} else if (($this->get["module"]) == "objects") {
				$api = new Controller_Api_Objects();
				$api->index($this->_uid, $json, $this->get, $this->_oid);
			} else if (($this->get["module"]) == "events") {
				$api = new Controller_Api_Events();
				$api->index($this->_uid, $json, $this->get, $this->_oid);
			}
		}
 	}
 }
 ?>