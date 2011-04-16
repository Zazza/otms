<?php
class Model_User extends Model_Index {

    public function getInfo($loginSession) {
        $data = array();
                    
		$sql = "SELECT users.id, users.name, users.soname, p.admin, p.group, g.name AS gname
        FROM users 
        LEFT JOIN users_priv AS p ON (users.id = p.id)
        LEFT JOIN users_group AS g ON (p.group = g.id)
        WHERE users.id = :id AND users.hash = :hash LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $loginSession["id"], ":hash" => $loginSession["hash"]);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($data) == 1) {
            $data[0]["ttnum"] = $this->getNumTasks($loginSession["id"]);
            $this->registry->set("auth", TRUE);
            $this->registry->set("ui", $data[0]);
            $data = $data[0];
        } else {
            $this->registry->set("auth", FALSE);
            session_destroy();
        }

        return $data;
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
        
        return $data[0]["id"];
    }
    
    public function getNumTasks($uid) {
        $data = array();
        
		$sql = "SELECT COUNT(DISTINCT(t.id)) AS count
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE (t.who = :uid OR tr.uid = :uid)
            AND t.gid = 0
            AND td.opening <= NOW()
        ORDER BY t.id DESC";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getUserInfo($uid) {
        $data = array();
                    
		$sql = "SELECT users.id AS uid, users.pass AS pass, users.name AS name, users.soname AS soname, users.email AS email, p.admin AS admin, g.id AS gid, g.name AS gname 
        FROM users 
        LEFT JOIN users_priv AS p ON (users.id = p.id)
        LEFT JOIN users_group AS g ON (p.group = g.id)
        WHERE users.id = :uid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($data) == 1) {
            $data = $data[0];
        }
        
        return $data;
    }
    
    public function getUserInfoFromGroup($gid) {
        $data = array();
                    
		$sql = "SELECT users.id AS uid, users.pass AS pass, users.name AS name, users.soname AS soname, users.email AS email, p.admin AS admin, g.id AS gid, g.name AS gname 
        FROM users 
        LEFT JOIN users_priv AS p ON (users.id = p.id)
        LEFT JOIN users_group AS g ON (p.group = g.id)
        WHERE g.id = :gid";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
    
    public function addUser($login, $pass, $name, $soname, $email) {
        $sql = "INSERT INTO users (login, pass, name, soname, email) VALUES (:login, :pass, :name, :soname, :email)";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $login, ":pass" => md5(md5($pass)), ":name" => $name, ":soname" => $soname, ":email" => $email);
		$res->execute($param);
        
        $sql = "SELECT id FROM users ORDER BY id DESC LIMIT 1";		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$uid = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $uid[0]["id"];
    }
    
    public function addUserPriv($uid, $admin, $gname) {
        $sql = "INSERT INTO users_priv (id, admin, `group`) VALUES (:id, :admin, :group)";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":admin" => $admin, ":group" => $gname);
		$res->execute($param);
    }
    
    public function editUser($uid, $name, $soname, $email) {
        $sql = "UPDATE users SET name = :name, soname = :soname, email = :email WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":name" => $name, ":soname" => $soname, ":email" => $email);
		$res->execute($param);
    }
    
    public function editUserPass($uid, $pass) {
        $sql = "UPDATE users SET pass = :pass WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":pass" => md5(md5($pass)));
		$res->execute($param);
    }
    
    public function editUserPriv($uid, $admin, $gname) {
        $sql = "UPDATE users_priv SET id = :id, admin = :admin, `group` = :group WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":admin" => $admin, ":group" => $gname);
		$res->execute($param);
    }
    
    public function getUsersList() {
		$sql = "SELECT users.id AS id, users.login AS login, users.name AS name, users.soname AS soname, users.email AS email, p.admin AS admin, p.group AS gid, g.name AS gname
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
    
    public function delUser($uid) {
		$sql = "DELETE FROM users WHERE id = :uid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
    }
    
    public function getGroups() {
		$sql = "SELECT id, name 
        FROM users_group
        ORDER BY name";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getGroupName($gid) {
		$sql = "SELECT name 
        FROM users_group
        WHERE id = :gid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["name"];
    }
    
    public function getGroupId($gname) {
		$sql = "SELECT id 
        FROM users_group
        WHERE name = :gname
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gname" => $gname);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["id"];
    }
    
    public function addGroups($gname) {
		$sql = "INSERT INTO users_group (name) VALUES (:name)";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":name" => $gname);
		$res->execute($param);
    }
    
    public function editGroupName($gid, $gname) {
		$sql = "UPDATE users_group SET name = :gname WHERE id = :gid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid, ":gname" => $gname);
		$res->execute($param);
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
}
?>
