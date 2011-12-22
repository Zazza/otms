<?php
class Model_User extends Modules_Model {
	public $noavatar = "img/noavatar.gif";
	public $tid;
	private $online_time = 300;
    
    public function getUserInfo($uid) {
    	$data = array();
    
    	$this->memcached->set("ui" . $uid);
    
    	if (!$this->memcached->load()) {
    
    		$sql = "SELECT users.id AS id, users.login AS `login`, users.pass AS pass, users.name AS `name`, users.soname AS `soname`, users.signature AS signature, users.icq, users.skype, users.adres, users.phone, users.avatar, users.email AS email, users.notify, users.time_notify, users.email_for_task, p.admin AS admin, g.id AS gid, p.group, g.name AS gname
        	        FROM users 
        	        LEFT JOIN users_priv AS p ON (users.id = p.id)
        	        LEFT JOIN users_subgroup AS g ON (p.group = g.id)
        	        WHERE users.id = :uid LIMIT 1";
    		 
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":uid" => $uid);
    		$res->execute($param);
    		$data = $res->fetchAll(PDO::FETCH_ASSOC);
    
    		if (count($data) == 1) {
    			$dbava = $data[0]["avatar"];
    
    			if ($data[0]["avatar"] != "") {
    				$data[0]["avatar"] = $this->registry["siteName"] . $this->registry["uri"] . $this->registry["path"]["avadir"] . $dbava;
    				$data[0]["avatarpath"] = $this->registry["rootPublic"] . $this->registry["path"]["avadir"] . $dbava;
    			} else {
    				$data[0]["avatar"] = $this->registry["siteName"] . $this->registry["uri"] . $this->noavatar;
    				$data[0]["avatarpath"] = $this->registry["rootPublic"] . $this->noavatar;
    			}
    
    			$data[0]["uid"] = $data[0]["id"];
    			$data = $data[0];
    
    			$this->memcached->save($data);
    		}
    	} else {
    		$data = $this->memcached->get();
    	}
    	
    	$data["status"] = $this->getStatus($uid);
    
    	return $data;
    }
    
    public function setOnline() {
    	$this->memcached->set("status" . $this->registry["ui"]["id"]);
    	if ($this->memcached->load()) {
    		$this->memcached->delete();
    	}
    
    	$this->memcached->saveTime("online", $this->online_time);
    }
    
    public function setOffline() {
    	$this->memcached->set("status" . $this->registry["ui"]["id"]);
    	$this->memcached->delete();
    }
    
    public function getStatus($uid) {
    	$this->memcached->set("status" . $uid);
    	if ($this->memcached->load()) {
    		return true;
    	} else {
    		return false;
    	}
    }

    public function getGidFromUid($uid) {
		$sql = "SELECT up.group AS `group`
        FROM users_priv AS up
        WHERE up.id = :uid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
		if (count($data) > 0) {
        	return $data[0]["group"];
		}
    }
    
    public function getUserId($login) {
		$sql = "SELECT id 
        FROM users
        WHERE login = :login
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $login);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
		if ( (isset($data[0]["id"])) and (is_numeric($data[0]["id"])) ) {
        	return $data[0]["id"];
		}
    }
    
    public function getDraftNumTasks($uid) {
		$sql = "SELECT count(id) AS count
        FROM draft
        WHERE who = :uid";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $data[0]["count"];
    }
    
    public function getUserInfoFromGroup($gid) {
        $data = array();
                    
		$sql = "SELECT users.id AS uid, users.login AS login, users.pass AS pass, users.name AS `name`, users.soname AS `soname`, users.email AS email, users.notify, users.time_notify, p.admin AS admin, g.id AS gid, g.name AS gname 
        FROM users 
        LEFT JOIN users_priv AS p ON (users.id = p.id)
        LEFT JOIN users_subgroup AS g ON (p.group = g.id)
        WHERE g.id = :gid";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
    
    public function addUser($login, $pass, $name, $soname, $signature, $email, $notify, $time_notify) {
        if (!isset($time_notify)) {
            $time_notify = "08:00:00";
        }
        
        $sql = "INSERT INTO users (login, pass, `name`, `soname`, signature, email, notify, time_notify) VALUES (:login, :pass, :name, :soname, :signature, :email, :notify, :time_notify)";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $login, ":pass" => md5(md5($pass)), ":name" => $name, ":soname" => $soname, ":signature" => $signature, ":email" => $email, ":notify" => $notify, ":time_notify" => $time_notify);
		$res->execute($param);

		$uid = $this->registry['db']->lastInsertId();
        
        return $uid;
    }
    
    public function addUserPriv($uid, $priv, $gname) {
        if ($priv == "admin") {
            $admin = 1;
        } else {
            $admin = 0;
        }
        
        $sql = "INSERT INTO users_priv (id, admin, `group`) VALUES (:id, :admin, :group)";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":admin" => $admin, ":group" => $gname);
		$res->execute($param);
    }
    
    public function editUser($uid, $login, $name, $soname, $signature, $email, $notify, $time_notify, $email_for_task = 0) {
    	$this->memcached->set("ui" . $uid);
    	$this->memcached->delete();
    	
        if (!isset($time_notify)) {
            $time_notify = "08:00:00";
        }

        $sql = "UPDATE users SET `login` = :login, `name` = :name, `soname` = :soname, signature = :signature, email = :email, notify = :notify, time_notify = :time_notify, email_for_task = :eft WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":login" => $login, ":name" => $name, ":soname" => $soname, ":signature" => $signature, ":email" => $email, ":notify" => $notify, ":time_notify" => $time_notify, ":eft" => $email_for_task);
		$res->execute($param);
    }
    
    public function editAdvUser($icq, $skype, $adres, $phone) {
    	$this->memcached->set("ui" . $this->registry["ui"]["id"]);
    	$this->memcached->delete();
    	
        $sql = "UPDATE users SET icq = :icq, skype = :skype, adres = :adres, phone = :phone WHERE id = :uid LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"], ":icq" => $icq, ":skype" => $skype, ":adres" => $adres, ":phone" => $phone);
		$res->execute($param);
    }
    
    public function editUserPass($uid, $pass) {
    	$this->memcached->set("ui" . $uid);
    	$this->memcached->delete();
    	
        $sql = "UPDATE users SET pass = :pass WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":pass" => md5(md5($pass)));
		$res->execute($param);
    }
    
    public function editUserPriv($uid, $priv, $gname) {
    	$this->memcached->set("ui" . $uid);
    	$this->memcached->delete();
    	
        if ($priv == "admin") {
            $admin = 1;
        } else {
            $admin = 0;
        }
        
        $sql = "UPDATE users_priv SET id = :id, admin = :admin, `group` = :group WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":admin" => $admin, ":group" => $gname);
		$res->execute($param);
    }
    
    public function getUsersList() {
		$sql = "SELECT users.id AS id, users.login AS login, users.name AS `name`, users.soname AS `soname`, users.email AS email, users.notify, users.time_notify, users.last_notify, p.admin AS admin, p.group AS gid, g.name AS gname
        FROM users 
        LEFT JOIN users_priv AS p ON (users.id = p.id)
        LEFT JOIN users_group AS g ON (p.group = g.id)
        ORDER BY users.id";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
	public function issetLogin($login) {
		$sql = "SELECT COUNT(id) AS count FROM users WHERE login = :login";

		$res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $login);
		$res->execute($param);
		$row = $res->fetchAll(PDO::FETCH_ASSOC);

		if (count($row) > 0) $count = $row[0]["count"];

		if ($count > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function issetRemoteContact($email) {
		$sql = "SELECT id FROM troubles_remote_contact WHERE `email` = :email LIMIT 1";

		$res = $this->registry['db']->prepare($sql);
		$param = array(":email" => $email);
		$res->execute($param);
		$row = $res->fetchAll(PDO::FETCH_ASSOC);

		if (count($row) == 1) {
			$this->tid = $row[0]["id"];
			
			return true;
		} else {
			return false;
		}
	}
    
    public function delUser($uid) {
    	$this->memcached->set("ui" . $uid);
    	$this->memcached->delete();
    	
		$sql = "DELETE FROM users WHERE id = :uid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
        
		$sql = "DELETE FROM users_priv WHERE id = :uid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
    }
    
    public function getGroups() {
		$sql = "SELECT ug.id AS pid, ug.name AS pname, usg.id AS sid, usg.name AS sname
        FROM users_group AS ug
        LEFT JOIN users_subgroup AS usg ON (usg.pid = ug.id)
        ORDER BY ug.id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
    
    public function getUniqGroups() {
		$sql = "SELECT ug.id AS pid, ug.name AS pname
        FROM users_group AS ug
        ORDER BY ug.id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function getGroupName($gid) {
		$sql = "SELECT `name` 
        FROM users_subgroup
        WHERE id = :gid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
		if (count($data) > 0) {
        	return $data[0]["name"];
		}
    }
    
    public function getSubgroupName($sid) {
		$sql = "SELECT `name` 
        FROM users_subgroup
        WHERE id = :sid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":sid" => $sid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["name"];
    }
    
    public function getSubgroups($pid) {
		$sql = "SELECT id, `name` 
        FROM users_subgroup
        WHERE pid = :pid
        ORDER BY id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $pid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function addSubgroup($pid, $name) {
    	$sql = "INSERT INTO users_subgroup (pid, name) VALUES (:pid, :name)";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $pid, ":name" => $name);
		$res->execute($param);
    }
    
	public function delSubgroup($id) {
		$sql = "DELETE FROM users_subgroup WHERE id = :id LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id);
		$res->execute($param);
    }
    
	public function editCat($id, $name) {
		$sql = "UPDATE users_subgroup SET name = :name WHERE id = :id LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id, ":name" => $name);
		$res->execute($param);
    }
    
    public function getCatName($id) {
        $data = array();
        
		$sql = "SELECT id, name
        FROM users_subgroup
        WHERE id = :id
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":id" => $id));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0];
    }
    
    public function getUsersGroups() {
        $data = array();
        
        $sql = "SELECT u.id, ug.id AS gid, ug.name AS gname
        FROM users AS u
        LEFT JOIN users_priv AS up ON (up.id = u.id)
        LEFT JOIN users_subgroup AS ug ON (ug.id = up.group)
        GROUP BY up.id
        ORDER BY u.soname";
        
        $res = $this->registry['db']->prepare($sql);
        $res->execute();
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getGroupId($gname) {
		$sql = "SELECT id 
        FROM users_group
        WHERE `name` = :gname
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gname" => $gname);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["id"];
    }
    
    public function getSubgroupId($subgname) {
    	$sql = "SELECT id
            FROM users_subgroup
            WHERE `name` = :gname
            LIMIT 1";
    
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":gname" => $subgname);
    	$res->execute($param);
    	$data = $res->fetchAll(PDO::FETCH_ASSOC);
    
    	if (count($data) > 0) {
	    	return $data[0]["id"];
    	}
    }
    
    public function addGroups($gname) {
        if ($gname == "") {
            return FALSE;
        }
        
		$sql = "SELECT id
        FROM users_group
        WHERE `name` = :name
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":name" => htmlspecialchars($gname));
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $flag = FALSE;
        
        if (!isset($data[0]["id"])) {
            $flag = TRUE;
        }
        
        if ($flag) {
    		$sql = "INSERT INTO users_group (`name`) VALUES (:name)";
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":name" => htmlspecialchars($gname));
    		$res->execute($param);
            
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function editGroupName($gid, $gname) {
        if ($gname == "") {
            return FALSE;
        }
        
		$sql = "SELECT id
        FROM users_group
        WHERE `name` = :name
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":name" => htmlspecialchars($gname));
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $flag = FALSE;
        
        if (!isset($data[0]["id"])) {
            $flag = TRUE;
        } elseif ($gid == $data[0]["id"]) {
            $flag = TRUE;
        }
        
        if ($flag) {
    		$sql = "UPDATE users_group SET `name` = :gname WHERE id = :gid LIMIT 1";
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":gid" => $gid, ":gname" => htmlspecialchars($gname));
    		$res->execute($param);
            
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function delGroup($gid) {
		$sql = "DELETE FROM users_group WHERE id = :gid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
    }
    
    public function spam($tid) {
        $sql = "SELECT COUNT(id) AS count FROM troubles_spam WHERE uid = :uid AND tid = :tid LIMIT 1";
        
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"], ":tid" => $tid);
		$res->execute($param);
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if ($row[0]["count"] == 0) {
            $sql = "INSERT INTO troubles_spam (tid, uid) VALUES (:tid, :uid)";
            
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":uid" => $this->registry["ui"]["id"], ":tid" => $tid);
    		$res->execute($param);
        } else {
            $sql = "DELETE FROM troubles_spam WHERE uid = :uid AND tid = :tid LIMIT 1";
            
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":uid" => $this->registry["ui"]["id"], ":tid" => $tid);
    		$res->execute($param);
        }
    }
    
    public function setNotifyTime($uid) {
        $sql = "UPDATE users SET last_notify = NOW() WHERE id = :uid LIMIT 1";
        
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
    }
    
    // список пользователей и групп для формы создания или правки задачи
	function getUsers() {
		$k=0;
		$gdata = $this->getGroups();
		$udata = $this->getUsersList();
		for($i=0; $i<count($gdata); $i++) {
			$data[$k]["id"] = $gdata[$i]["sid"];
			$data[$k]["type"] = "g";
			$data[$k]["desc"] = $gdata[$i]["sname"];
	
			foreach($udata as $part) {
				if ($part["gid"] == $gdata[$i]["sid"]) {
	
					$k++;
	
					$data[$k]["id"] = $part["id"];
					$data[$k]["type"] = "u";
					$data[$k]["desc"] = $part["name"] . " " . $part["soname"];
				}
			}
	
			$k++;
		}
	
		$data[$k]["type"] = "all";
		$data[$k]["id"] = 0;
		$data[$k]["desc"] = "все";
		
		return $data; 
	}
	
	function getUniqUsers($post) {
		$uniq = array();
		
		$users = array();
		if ($post["rall"] == "1") {
			$group_users = $this->getUsersList();
			foreach($group_users as $user) {
				$users[] = $user["id"];
			}
		} else {
			foreach($post["gruser"] as $part) {
				$group_users = $this->getUserInfoFromGroup($part);
				foreach($group_users as $user) {
					$users[] = $user["uid"];
				}
			}
			
			foreach($post["ruser"] as $part) {
				$users[] = $part;
			}
		}

		for($i=0; $i<count($users); $i++) {
			$flag = true; 
			foreach($uniq as $part) {
				if ($part == $users[$i]) {
					$flag = false;
				}
			}
			
			if ($flag) {
				$uniq[] = $users[$i];
			}
		}
		
		return $uniq;
	}
}
?>
