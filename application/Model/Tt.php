<?php
class Model_Tt extends Model_Index {
    
    public function getGroups() {
		$sql = "SELECT id, name 
        FROM group_tt
        ORDER BY name";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getGroupName($gid) {
		$sql = "SELECT name 
        FROM group_tt
        WHERE id = :gid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["name"];
    }
    
    public function addGroups($gname) {
		$sql = "INSERT INTO group_tt (name) VALUES (:name)";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":name" => $gname);
		$res->execute($param);
    }
    
    public function editGroupName($gid, $gname) {
		$sql = "UPDATE group_tt SET name = :gname WHERE id = :gid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid, ":gname" => $gname);
		$res->execute($param);
    }
    
    public function delGroup($gid) {
		$sql = "DELETE FROM group_tt WHERE id = :gid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
    }
    
    public function getTasks($uid) {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE (t.who = :uid OR tr.uid = :uid)
            AND t.gid = 0
            AND td.opening <= NOW()
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}
        
        return $data;
    }
    
    public function getNumAllTasks() {
        $data = FALSE;
        
		$sql = "SELECT COUNT(DISTINCT(t.id)) AS count
        FROM troubles AS t
        LEFT JOIN users_priv AS u
        WHERE u.id = :uid
            AND t.secure = 0
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"]);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        return $data[0]["count"];
    }
    
    public function getAllTasks() {
        $data = array();
        
        $sql = "SELECT COUNT(u.id) AS count FROM users_priv AS u WHERE u.id = :uid AND u.admin = 1 LIMIT 1";
        
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"]);
		$res->execute($param);
		$admin = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if ($admin[0]["count"]) {
    		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
            FROM troubles AS t
            LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
            WHERE ((t.secure = 0) OR ((t.secure = 1) AND (t.who = :uid OR tr.uid = :uid))) AND t.gid = 0
            ORDER BY t.id DESC
            LIMIT " . $this->startRow .  ", " . $this->limit;
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":uid" => $this->registry["ui"]["id"]);
    		$res->execute($param);
    		$data = $res->fetchAll(PDO::FETCH_ASSOC);
            
            $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
            
    		//Если общее число статей больше показанного, вызовем пейджер
    		if ($this->totalPage < $this->limit+1)  {
    		} else {
    			$this->Pager();
    		}
        }
        
        return $data;
    }
    
    public function getTasksDate($uid, $date) {
        $data = array(); $result = array();
        
        $year = date("Y", strtotime($date));
        $month = date("m", strtotime($date));
        $day = date("d", strtotime($date));
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS t.id, t.gid, td.type, td.deadline, td.iteration, td.opening
        FROM troubles AS t 
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = td.tid)
        WHERE tr.uid = :uid
        ORDER BY td.opening DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
        
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":uid" => $uid));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}

        for($i=0; $i<count($data); $i++) {
            
            $inc_day = 0;
            $inc = $data[$i]["iteration"] / 60 / 60 / 24;            
            $start = strtotime($data[$i]["opening"]);
            if (($days = $data[$i]["deadline"] / 60 / 60 / 24) < 1) {
                $days = 1;
            }
            
            if ($data[$i]["gid"] != "0") {
                $curDay = date("j", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                $curMonth = date("m", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                $curYear = date("Y", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                
                if ( ($curYear == $year) and ($curMonth == $month)  and ($curDay == $day) ) {
                    $result[]["id"] = $data[$i]["id"];                    
                }
            } elseif ($data[$i]["type"] == "0") {
                $curDay = date("j", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                $curMonth = date("m", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                $curYear = date("Y", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                
                if ( ($curYear == $year) and ($curMonth == $month)  and ($curDay == $day) ) {
                    $result[]["id"] = $data[$i]["id"];                    
                }
            } elseif ($data[$i]["type"] == "1") {
                for($l=0; $l<$days; $l++) {
                    $curDay = date("j", mktime(0, 0, 0, date("m", $start), date("d", $start) + $l, date("Y", $start)));
                    $curMonth = date("m", mktime(0, 0, 0, date("m", $start), date("d", $start) + $l, date("Y", $start)));
                    $curYear = date("Y", mktime(0, 0, 0, date("m", $start), date("d", $start) + $l, date("Y", $start)));
                    if ( ($curYear == $year) and ($curMonth == $month)  and ($curDay == $day) ) {
                        $result[]["id"] = $data[$i]["id"];                        
                    }
                } 
            } elseif ($data[$i]["type"] == "2") {
                $curYear = date("Y", $start);
                $curMonth = date("m", $start);
                
                while( ($curYear <= $year) ) {
                    $curDay = date("j", mktime(0, 0, 0, date("m", $start), date("d", $start) + $inc_day, date("Y", $start)));
                    $curMonth = date("m", mktime(0, 0, 0, date("m", $start), date("d", $start) + $inc_day, date("Y", $start)));
                    $curYear = date("Y", mktime(0, 0, 0, date("m", $start), date("d", $start) + $inc_day, date("Y", $start)));
                    
                    if ( ($curYear == $year) and ($curMonth == $month)  and ($curDay == $day) ) {
                        $result[]["id"] = $data[$i]["id"];                        
                    }
    
                    $inc_day = $inc_day + $inc;
                }
            }
        }

        return $result;
    }

    public function getTask($tid) {
        $data = array();
        
		$sql = "SELECT t.id, t.oid, t.who, t.imp, t.secure, t.text, t.opening AS start, t.ending, t.gid, g.name AS `group`, tr.uid, td.type, td.opening, td.deadline, td.iteration, ts.id AS spam
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_spam AS ts ON (ts.tid = t.id)
        LEFT JOIN group_tt AS g ON (t.gid = g.id)
        WHERE t.id = :tid AND ((t.secure = 0) OR ((t.secure = 1) AND (t.who = :uid OR tr.uid = :uid)))";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":tid" => $tid, ":uid" => $this->registry["ui"]["id"]);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $flag = FALSE;
        if (count($data) > 0) {
            $data[0]["startdate"] = date("Y-m-d", strtotime($data[0]["opening"]));
            $data[0]["starttime"] = date("H:i:s", strtotime($data[0]["opening"]));
            
            $data[0]["start"] = $this->editDate($data[0]["start"]);
            $data[0]["opening"] = $this->editDate($data[0]["opening"]);
            
            if (($data[0]["deadline"] / 60 /60 / 24) >= 1) {
                $data[0]["deadline"] = ($data[0]["deadline"] / 60 /60 / 24);
                $data[0]["deadline_date"] = "дней";
            } else {
                if (($data[0]["deadline"] / 60 /60 ) >= 1) { 
                    $data[0]["deadline"] = ($data[0]["deadline"] / 60 /60);
                    $data[0]["deadline_date"] = "часов";
                } elseif (($data[0]["deadline"] / 60 ) >= 1) {
                    $data[0]["deadline"] = ($data[0]["deadline"] / 60 );
                    $data[0]["deadline_date"] = "минут";
                } else {
                    $data[0]["deadline"] = "";
                    $data[0]["deadline_date"] = "0";
                }
            }
            
            $data[0]["iteration"] = ($data[0]["iteration"] / 60 /60 / 24);
            
            if ($data[0]["secure"]) {
                if ($data[0]["who"] == $this->registry["ui"]["id"]) {
                    $flag = TRUE;
                } else {
                    foreach($data as $part) {
                        if ($part["uid"] == $this->registry["ui"]["id"]) {
                            $flag = TRUE;
                        }
                    }
                }
            } else {
                $flag = TRUE;
            }
        }
        
        if ($flag) {
            return $data;
        } else {
            return FALSE;
        }
    }

    public function addTask($oid, $post) {
        if ($post["task"] != '') {
            if ($oid != '') {
            
                if (isset($post["secure"])) {
                    $secure = 1;
                } else {
                    $secure = 0;
                }
                
        		$sql = "INSERT INTO troubles (oid, who, imp, secure, text) VALUES (:oid, :who, :imp, :secure, :text)";
        		
        		$res = $this->registry['db']->prepare($sql);
        		$param = array(":oid" => $oid, ":who" => $this->registry["ui"]["id"], ":imp" => $post["imp"], ":secure" => $secure, ":text" => $post["task"]);
        		$res->execute($param);
                
                $sql = "SELECT id FROM troubles ORDER BY id DESC LIMIT 1";		
        		$res = $this->registry['db']->prepare($sql);
        		$res->execute();
        		$tid = $res->fetchAll(PDO::FETCH_ASSOC);
                
                $tid = $tid[0]["id"];
                
                if (!isset($post["ruser"])) { $post["ruser"] = array(); }
                
                foreach($post["ruser"] as $part) {
            		$sql = "INSERT INTO troubles_responsible (tid, uid) VALUES (:tid, :uid)";
            		
            		$res = $this->registry['db']->prepare($sql);
            		$param = array(":tid" => $tid, ":uid" => $part);
            		$res->execute($param);
                }
                
                if ($post["type"] == "0") {
                    
                    $starttime = $post["startdate_global"] . " " . $post["starttime_global"];
                    $lifetime = 0;
                    $post["itertime"] = "";
                    
                } elseif ($post["type"] == "1") {
                    $post["itertime"] = "";
                    
                    $starttime = $post["startdate_noiter"] . " " . $post["starttime_noiter"];
                    
                    if ($post["timetype_noiter"] == "min") {
                        
                        $lifetime = $post["lifetime_noiter"] * 60;
                        
                    } elseif ($post["timetype_noiter"] == "hour") {
                        
                        $lifetime = $post["lifetime_noiter"] * 60 * 60;
                        
                    } elseif ($post["timetype_noiter"] == "day") {
                        
                        $lifetime = $post["lifetime_noiter"] * 24 * 60 * 60;
                        
                    } else {
                        
                        $lifetime = 0;
                        
                    }
                } elseif ($post["type"] == "2") {
                    $post["itertime"] = $post["itertime"] * 24 * 60 * 60;
                    
                    $starttime = $post["startdate_iter"] . " " . $post["starttime_iter"];
                    
                    if ($post["timetype_iter"] == "min") {
                        
                        $lifetime = $post["lifetime_iter"] * 60;
                        
                    } elseif ($post["timetype_iter"] == "hour") {
                        
                        $lifetime = $post["lifetime_iter"] * 60 * 60;
                        
                    } elseif ($post["timetype_iter"] == "day") {
                        
                        $lifetime = $post["lifetime_iter"] * 24 * 60 * 60;
                        
                    } else {
                        
                        $lifetime = 0;
                        
                    }
                }
    
                $sql = "INSERT INTO troubles_deadline (tid, type, opening, deadline, iteration) VALUES (:tid, :type, :opening, :deadline, :iteration)";
            		
            	$res = $this->registry['db']->prepare($sql);
            	$param = array(":tid" => $tid, ":type" => $post["type"], ":opening" => $starttime, ":deadline" => $lifetime, ":iteration" => $post["itertime"]);
            	$res->execute($param);
                
                return $tid;
            }
        }
    }
    
    public function editTask($post) {
        $tid = $post["tid"];
        
        if ($post["task"] != '') {

            if (isset($post["secure"])) {
                $secure = 1;
            } else {
                $secure = 0;
            }
            
    		$sql = "UPDATE troubles SET imp = :imp, secure = :secure, text = :text WHERE id = :tid LIMIT 1";
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":tid" => $post["tid"], ":imp" => $post["imp"], ":secure" => $secure, ":text" => $post["task"]);
    		$res->execute($param);
            
    		$sql = "DELETE FROM troubles_responsible WHERE tid = :tid";
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":tid" => $tid);
    		$res->execute($param);

            if (!isset($post["ruser"])) { $post["ruser"] = array(); }
            
            foreach($post["ruser"] as $part) {
        		$sql = "INSERT INTO troubles_responsible (tid, uid) VALUES (:tid, :uid)";
        		
        		$res = $this->registry['db']->prepare($sql);
        		$param = array(":tid" => $tid, ":uid" => $part);
        		$res->execute($param);
            }

            if ($post["type"] == "0") {
                
                $starttime = $post["startdate_global"] . " " . $post["starttime_global"];
                $lifetime = 0;
                $post["itertime"] = "";
                
            } elseif ($post["type"] == "1") {
                $post["itertime"] = "";
                
                $starttime = $post["startdate_noiter"] . " " . $post["starttime_noiter"];
                
                if ($post["timetype_noiter"] == "min") {
                    
                    $lifetime = $post["lifetime_noiter"] * 60;
                    
                } elseif ($post["timetype_noiter"] == "hour") {
                    
                    $lifetime = $post["lifetime_noiter"] * 60 * 60;
                    
                } elseif ($post["timetype_noiter"] == "day") {
                    
                    $lifetime = $post["lifetime_noiter"] * 24 * 60 * 60;
                    
                } else {
                    
                    $lifetime = 0;
                    
                }
            } elseif ($post["type"] == "2") {
                $post["itertime"] = $post["itertime"] * 24 * 60 * 60;
                
                $starttime = $post["startdate_iter"] . " " . $post["starttime_iter"];
                
                if ($post["timetype_iter"] == "min") {
                    
                    $lifetime = $post["lifetime_iter"] * 60;
                    
                } elseif ($post["timetype_iter"] == "hour") {
                    
                    $lifetime = $post["lifetime_iter"] * 60 * 60;
                    
                } elseif ($post["timetype_iter"] == "day") {
                    
                    $lifetime = $post["lifetime_iter"] * 24 * 60 * 60;
                    
                } else {
                    
                    $lifetime = 0;
                    
                }
            }

            $sql = "UPDATE troubles_deadline SET type = :type, opening = :opening, deadline = :deadline, iteration = :iteration WHERE tid = :tid";
        		
        	$res = $this->registry['db']->prepare($sql);
        	$param = array(":tid" => $tid, ":type" => $post["type"], ":opening" => $starttime, ":deadline" => $lifetime, ":iteration" => $post["itertime"]);
        	$res->execute($param);
            
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function getNumComments($tid) {
        $sql = "SELECT COUNT(id) AS count FROM troubles_discussion WHERE tid = :tid";
        	
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":tid" => $tid));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getComments($tid) {
        $data = array();
        
        $sql = "SELECT td.id, td.uid, users.name, users.soname, td.text, td.timestamp
        FROM troubles_discussion AS td
        LEFT JOIN users ON (users.id = td.uid)
        WHERE td.tid = :tid";
        	
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":tid" => $tid));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getOidTasks($oid) {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE (((t.who = :uid OR tr.uid = :uid) AND t.secure = 1) OR (t.secure = 0))
            AND t.oid = :oid
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"], "oid" => $oid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}
        
        return $data;
    }
    
    public function getTasksWithoutMe($uid) {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE tr.uid = :uid
            AND t.gid = 0
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}
        
        return $data;
    }
    
    public function getNumStatTasks($uid) {
        $sql = "SELECT COUNT(t.id) AS count
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE tr.uid = :uid AND t.gid = 0";
        	
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":uid" => $uid));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getMeTasks($uid) {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE t.who = :uid
            AND t.gid = 0
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}
        
        return $data;
    }
    
    public function getNumMeTasks($uid) {
        $sql = "SELECT COUNT(id) AS count FROM troubles WHERE who = :uid AND gid = 0";
        	
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":uid" => $uid));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getIterTasks($uid) {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE tr.uid = :uid
            AND td.type = 2
            AND t.gid = 0
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}
        
        return $data;
    }
    
    public function getNumIterTasks($uid) {
        $sql = "SELECT COUNT(t.id) AS count 
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE tr.uid = :uid
            AND t.gid = 0
            AND td.type = 2";	
        	
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":uid" => $uid));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getTimeTasks($uid) {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE tr.uid = :uid
            AND td.type = 1
            AND t.gid = 0
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}
        
        return $data;
    }
    
    public function getNumTimeTasks($uid) {
        $sql = "SELECT COUNT(t.id) AS count 
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE tr.uid = :uid
            AND t.gid = 0
            AND td.type = 1";	
        	
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":uid" => $uid));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getNoiterTasks($uid) {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE tr.uid = :uid
            AND td.type = 0
            AND t.gid = 0
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}
        
        return $data;
    }
    
    public function getMonthTasks($year, $month, $uid) {
        $data = array(); $result = array();
        
        for($i=0; $i<=31; $i++) {
            $result[$i]["close"]["num"] = 0;
            $result[$i]["time"]["num"] = 0;
            $result[$i]["iter"]["num"] = 0;
            $result[$i]["noiter"]["num"] = 0;
        }

        $sql = "SELECT t.id, t.gid, tr.id AS uid, td.type, td.deadline, td.iteration, td.opening
        FROM troubles AS t 
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = td.tid)
        WHERE tr.uid = :uid
        ORDER BY td.opening";
        
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":uid" => $uid));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        for($i=0; $i<count($data); $i++) {
            
            $inc_day = 0;
            $inc = $data[$i]["iteration"] / 60 / 60 / 24;            
            $start = strtotime($data[$i]["opening"]);
            if (($days = $data[$i]["deadline"] / 60 / 60 / 24) < 1) {
                $days = 1;
            }
            
            if ($data[$i]["gid"] != 0) {
                $curDay = date("j", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                $curMonth = date("m", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                $curYear = date("Y", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                
                if ( ($curYear == $year) and ($curMonth == $month) ) {
                    $result[$curDay]["close"]["num"]++;
                }
            } elseif ($data[$i]["type"] == "0") {
                $curDay = date("j", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                $curMonth = date("m", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                $curYear = date("Y", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                
                if ( ($curYear == $year) and ($curMonth == $month) ) {
                    $result[$curDay]["noiter"]["num"]++;
                }
            } elseif ($data[$i]["type"] == "1") {
                for($l=0; $l<$days; $l++) {
                    $curDay = date("j", mktime(0, 0, 0, date("m", $start), date("d", $start) + $l, date("Y", $start)));
                    $curMonth = date("m", mktime(0, 0, 0, date("m", $start), date("d", $start) + $l, date("Y", $start)));
                    $curYear = date("Y", mktime(0, 0, 0, date("m", $start), date("d", $start) + $l, date("Y", $start)));
                    if ( ($curYear == $year) and ($curMonth == $month) ) {
                        $result[$curDay]["time"]["num"]++;
                    }
                } 
            } elseif ($data[$i]["type"] == "2") {
                $curYear = date("Y", $start);
                $curMonth = date("m", $start);
                
                while( ($curYear <= $year) ) {
                    $curDay = date("j", mktime(0, 0, 0, date("m", $start), date("d", $start) + $inc_day, date("Y", $start)));
                    $curMonth = date("m", mktime(0, 0, 0, date("m", $start), date("d", $start) + $inc_day, date("Y", $start)));
                    $curYear = date("Y", mktime(0, 0, 0, date("m", $start), date("d", $start) + $inc_day, date("Y", $start)));
                    
                    if ( ($curYear == $year) and ($curMonth == $month) ) {
                        $result[$curDay]["iter"]["num"]++;
                    }
    
                    $inc_day = $inc_day + $inc;
                }
            }
        }

        return $result;
    }
    
    public function addComment($tid, $text) {
        $sql = "INSERT INTO troubles_discussion (tid, uid, text) VALUES (:tid, :uid, :text)";
        
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":tid" => $tid, ":uid" => $this->registry["ui"]["id"], ":text" => $text));
    }
    
    public function closeTask($tid, $gid) {
        $sql = "UPDATE troubles SET ending = NOW(), gid = :gid WHERE id = :tid LIMIT 1";
        
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":tid" => $tid, ":gid" => $gid));
    }
    
    public function getNobodyTasks() {
        $data = array();
        
		$sql = "SELECT DISTINCT(t.id), tr.uid
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE tr.uid IS NULL
        ORDER BY t.id DESC";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function spamUsers($theme, $tid) {
        $data = array();
        
		$sql = "SELECT DISTINCT(tr.uid) AS uid, users.email
        FROM troubles_responsible AS tr
        LEFT JOIN users ON (users.id = tr.uid)
        WHERE tr.tid = :tid
        ORDER BY tr.uid DESC";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array("tid" => $tid);
		$res->execute($param);
		$data1 = $res->fetchAll(PDO::FETCH_ASSOC);
        
		$sql = "SELECT DISTINCT(ts.uid) AS uid, users.email
        FROM troubles_spam AS ts
        LEFT JOIN users ON (users.id = ts.uid)
        WHERE ts.tid = :tid
        ORDER BY ts.uid DESC";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array("tid" => $tid);
		$res->execute($param);
		$data2 = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array_merge($data1, $data2);
        $i = 0; $users = array();
        foreach($data as $part) {
            $flag = true;
            for($k=0; $k<count($users); $k++) {
                if ($users[$k]["uid"] == $part["uid"]) {
                    $flag = false;
                }
            }
            
            if ($flag) {
                $users[$i]["uid"] = $part["uid"];
                $users[$i]["email"] = $part["email"];
                
                $i++;
            }
        }
        
        foreach($users as $part) {
            $this->helpers->sendMail($part["email"], $theme, $this->getTask($tid), $this->getComments($tid));
        }
    }
}
?>