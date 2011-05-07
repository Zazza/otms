<?php
class Model_User extends Model_Index {

    public function getInfo($loginSession) {
        $data = array();
                    
		$sql = "SELECT users.id, users.name AS `name`, users.soname AS `soname`, users.notify, users.time_notify, p.admin, p.readonly, p.group, g.name AS gname
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
            $data[0]["nobodyttnum"] = $this->getNumTasksNobody($loginSession["id"]);
            if ($data[0]["admin"]) {
                $data[0]["allttnum"] = $this->getNumTasksAll($loginSession["id"]);
            }
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
        $data = array(); $result = array();
        
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        
		$sql = "SELECT DISTINCT(t.id), td.type, td.deadline, td.iteration, td.timetype_iteration, td.opening
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE ( ( (t.secure = 0) AND (t.who = :uid OR tr.uid = :uid OR tr.uid IS NULL) ) OR ( (t.secure = 1) AND (t.who = :uid OR tr.uid = :uid) ) )
            AND t.gid = 0
            AND td.opening <= NOW()
        ORDER BY t.id DESC";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        for($i=0; $i<count($data); $i++) {
            
            $inc_day = 0;
            $inc_month = 0;
            $inc = $data[$i]["iteration"];
            $inc_type = $data[$i]["timetype_iteration"];       
            $start = strtotime($data[$i]["opening"]);
            if (($days = $data[$i]["deadline"] / 60 / 60 / 24) < 1) {
                $days = 1;
            }
            
            if ($data[$i]["type"] == "0") {
                $result[]["id"] = $data[$i]["id"];                    
            } elseif ($data[$i]["type"] == "1") {
                $result[]["id"] = $data[$i]["id"];                        
            } elseif ($data[$i]["type"] == "2") {
                $curYear = date("Y", $start);
                $curMonth = date("m", $start);
                
                while( ($curYear <= $year) ) {
                    $curDay = date("j", mktime(0, 0, 0, date("m", $start) + $inc_month, date("d", $start) + $inc_day, date("Y", $start)));
                    $curMonth = date("m", mktime(0, 0, 0, date("m", $start) + $inc_month, date("d", $start) + $inc_day, date("Y", $start)));
                    $curYear = date("Y", mktime(0, 0, 0, date("m", $start) + $inc_month, date("d", $start) + $inc_day, date("Y", $start)));
                    
                    if ( ($curYear == $year) and ($curMonth == $month)  and ($curDay == $day) ) {
                        $result[]["id"] = $data[$i]["id"];                        
                    }
    
                    if ($inc_type == "day") {
                        $inc_day = $inc_day + $inc;
                    } elseif($inc_type == "month") {
                        $inc_month = $inc_month + $inc;
                    }
                }
            }
        }
        
        return count($result);
        
        //return $data[0]["count"];
    }
    
    public function getNumTasksAll($uid) {
        $data = array();
        
		$sql = "SELECT COUNT(DISTINCT(t.id)) AS count
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE ( (t.secure = 0) OR ( (t.secure = 1) AND (t.who = :uid OR tr.uid = :uid) ) )
            AND t.gid = 0
            AND td.opening <= NOW()
        ORDER BY t.id DESC";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getNumTasksNobody($uid) {
        $data = array();
        
		$sql = "SELECT COUNT(DISTINCT(t.id)) AS count
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE tr.uid IS NULL
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
                    
		$sql = "SELECT users.id AS uid, users.pass AS pass, users.name AS `name`, users.soname AS `soname`, users.email AS email, users.notify, users.time_notify, p.admin AS admin, p.readonly AS readonly, g.id AS gid, g.name AS gname 
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
                    
		$sql = "SELECT users.id AS uid, users.pass AS pass, users.name AS `name`, users.soname AS `soname`, users.email AS email, users.notify, users.time_notify, p.admin AS admin, p.readonly AS readonly, g.id AS gid, g.name AS gname 
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
    
    public function addUser($login, $pass, $name, $soname, $email, $notify, $time_notify) {
        if (!isset($time_notify)) {
            $time_notify = "08:00:00";
        }
        
        $sql = "INSERT INTO users (login, pass, `name`, `soname`, email, notify, time_notify) VALUES (:login, :pass, :name, :soname, :email, :notify, :time_notify)";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $login, ":pass" => md5(md5($pass)), ":name" => $name, ":soname" => $soname, ":email" => $email, ":notify" => $notify, ":time_notify" => $time_notify);
		$res->execute($param);
        
        $sql = "SELECT id FROM users ORDER BY id DESC LIMIT 1";		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$uid = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $uid[0]["id"];
    }
    
    public function addUserPriv($uid, $priv, $gname) {
        if ($priv == "admin") {
            $admin = 1;
            $readonly = 0;
        } elseif ($priv == "readonly") {
            $admin = 0;
            $readonly = 1; 
        } else {
            $admin = 0;
            $readonly = 0;
        }
        
        $sql = "INSERT INTO users_priv (id, admin, readonly, `group`) VALUES (:id, :admin, :readonly,  :group)";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":admin" => $admin, ":readonly" => $readonly, ":group" => $gname);
		$res->execute($param);
    }
    
    public function editUser($uid, $name, $soname, $email, $notify, $time_notify) {
        if (!isset($time_notify)) {
            $time_notify = "08:00:00";
        }
        
        $sql = "UPDATE users SET `name` = :name, `soname` = :soname, email = :email, notify = :notify, time_notify = :time_notify WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":name" => $name, ":soname" => $soname, ":email" => $email, ":notify" => $notify, ":time_notify" => $time_notify);
		$res->execute($param);
    }
    
    public function editUserPass($uid, $pass) {
        $sql = "UPDATE users SET pass = :pass WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":pass" => md5(md5($pass)));
		$res->execute($param);
    }
    
    public function editUserPriv($uid, $priv, $gname) {
        if ($priv == "admin") {
            $admin = 1;
            $readonly = 0;
        } elseif ($priv == "readonly") {
            $admin = 0;
            $readonly = 1; 
        } else {
            $admin = 0;
            $readonly = 0;
        }
        
        $sql = "UPDATE users_priv SET id = :id, admin = :admin, readonly = :readonly, `group` = :group WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":admin" => $admin, ":readonly" => $readonly, ":group" => $gname);
		$res->execute($param);
    }
    
    public function getUsersList() {
		$sql = "SELECT users.id AS id, users.login AS login, users.name AS `name`, users.soname AS `soname`, users.email AS email, users.notify, users.time_notify, users.last_notify, p.admin AS admin, p.readonly AS readonly, p.group AS gid, g.name AS gname
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
        
		$sql = "DELETE FROM users_priv WHERE id = :uid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
    }
    
    public function getGroups() {
		$sql = "SELECT id, `name` 
        FROM users_group
        ORDER BY `name`";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getGroupName($gid) {
		$sql = "SELECT `name` 
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
        WHERE `name` = :gname
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gname" => $gname);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["id"];
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
}
?>
