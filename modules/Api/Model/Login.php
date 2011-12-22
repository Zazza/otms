<?php
class Api_Model_Login extends Modules_Model {
	private $_login = null;
	private $_password = null;
	
	function login() {
		if (isset($_GET["login"])) {
			$this->_login = $_GET["login"];
		} else {
			return false;
		}

		if (isset($_GET["password"])) {
			$this->_password = $_GET["password"];
		} else {
			return false;
		}

		$sql = "SELECT * FROM users WHERE login = :login AND pass = :password LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $this->_login, ":password" => md5($this->_password));
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($data) == 1) {
			return true;
		} else {
			return false;
		}
	}
}
?>