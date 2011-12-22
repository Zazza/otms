<?php
class Model_Settings extends Engine_Model {
	function getMailbox() {
		$data = array();
		
	    $sql = "SELECT `email`, `server`, `port`, `auth`, `login`, `password`, `ssl`
        FROM otms_mail
        LIMIT 1";
	    
        $res = $this->registry['db']->prepare($sql);
		$res->execute();
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($row) > 0) {
			$data = $row[0];
			if ($row[0]["auth"] == "0") {
				$data["login"] = "";
				$data["password"] = "";
				$data["auth"] = 0;
			} else {
				$data["login"] = $row[0]["login"];
				$data["password"] = $row[0]["password"];
				$data["auth"] = 1;
			}
		}

        return $data;
	}
	
	function editMailbox($post) {
		$sql = "SELECT `email` FROM otms_mail LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($row) == 1) {
			if ( (!isset($post["login"])) or ($post["login"] == "") ) { $post["login"] = ""; };
			if ( (!isset($post["password"])) or ($post["password"] == "") ) { $post["password"] = ""; };
		
			$sql = "UPDATE otms_mail SET `email` = :email, `server` = :server, `protocol` = :protocol, `port` = :port, `auth` = :auth, `login` = :login, `password` = :password, `ssl` = :ssl";
			
	        $res = $this->registry['db']->prepare($sql);
			$param = array(":email" => $post["email"], ":server" => $post["server"], ":protocol" => "SMTP", ":port" => $post["port"], ":auth" => $post["auth"], ":login" => $post["login"], ":password" => $post["password"], ":ssl" => $post["ssl"]);
			$res->execute($param);
		} else {
			$sql = "INSERT INTO otms_mail (`email`, `server`,`protocol`, `port`, `auth`, `login`, `password`, `ssl`) VALUES (:email, :server, :protocol, :port, :auth, :login, :password, :ssl)";
			
	        $res = $this->registry['db']->prepare($sql);
			$param = array(":email" => $post["email"], ":server" => $post["server"], ":protocol" => "SMTP", ":port" => $post["port"], ":auth" => $post["auth"], ":login" => $post["login"], ":password" => $post["password"], ":ssl" => $post["ssl"]);
			$res->execute($param);
		}
	}
	
	function setMenu($content) {
		if (count($this->getMenu()) > 0) {
			$sql = "UPDATE otms_menu SET content = :content WHERE id = 1";
		} else {
			$sql = "INSERT INTO otms_menu (content) VALUES (:content)";
		}
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":content" => $content);
		$res->execute($param);
	}
	
	function getMenu() {
		$this->memcached->set("menu");
		
		$content = array();
		
		if (!$this->memcached->load()) {
			$sql = "SELECT `content` FROM otms_menu LIMIT 1";
			
			$res = $this->registry['db']->prepare($sql);
			$res->execute();
			$content = $res->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$content = $this->memcached->get();
		}

		if (count($content) > 0) {
			$content[0]["content"] = preg_replace("/\s+/"," ", strip_tags($content[0]["content"]));
			$content = json_decode($content[0]["content"], true);
		}
		
		for($i=0; $i<count($content); $i++) {
			$content[$i] = trim($content[$i]);
		}
		
		return $content;
	}
	
	function setFastmenu($content) {
		if (count($this->getFastmenu()) > 0) {
			$sql = "UPDATE otms_fastmenu SET content = :content WHERE id = 1";
		} else {
			$sql = "INSERT INTO otms_fastmenu (content) VALUES (:content)";
		}
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":content" => $content);
		$res->execute($param);
	}
	
	function getFastmenu() {
		$this->memcached->set("fastmenu");
		
		$content = array();
		
		if (!$this->memcached->load()) {
			$sql = "SELECT `content` FROM otms_fastmenu LIMIT 1";
				
			$res = $this->registry['db']->prepare($sql);
			$res->execute();
			$content = $res->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$content = $this->memcached->get();
		}

		if (count($content) > 0) {
			$content[0]["content"] = preg_replace("/\s+/"," ", strip_tags($content[0]["content"]));
			$content = json_decode($content[0]["content"], true);
		}
		
		for($i=0; $i<count($content); $i++) {
			$content[$i] = trim($content[$i]);
		}

		return $content;
	}
}
?>