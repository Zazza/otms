<?php
class Model_Tt extends Model_Index {
    
    public function getGroups() {
		$sql = "SELECT id, `name` 
        FROM group_tt
        ORDER BY `name`";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getGroupName($gid) {
		$sql = "SELECT `name` 
        FROM group_tt
        WHERE id = :gid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($data) > 0) {
            return $data[0]["name"];
        } else {
            return "Без группы";
        }
    }
    
    public function addGroups($gname) {
        if ($gname == "") {
            return FALSE;
        }
        
		$sql = "SELECT id
        FROM group_tt
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
    		$sql = "INSERT INTO group_tt (`name`) VALUES (:name)";
    		
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
        FROM group_tt
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
    		$sql = "UPDATE group_tt SET `name` = :gname WHERE id = :gid LIMIT 1";
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":gid" => $gid, ":gname" => htmlspecialchars($gname));
    		$res->execute($param);        
            
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function delGroup($gid) {
		$sql = "DELETE FROM group_tt WHERE id = :gid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
    }
    
    public function getTasks() {
        $data = array(); $result = array();
        
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id), td.type, td.deadline, td.iteration, td.timetype_iteration, td.opening
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE ( ( (t.secure = 0) AND (t.who = :uid OR tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid OR tr.uid IS NULL) ) OR ( (t.secure = 1) AND (t.who = :uid OR tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid) ) )
            AND t.close = 0
            AND td.opening <= NOW()
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}
        
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
        
        return $result;
    }
    
    public function getAllTasks() {
        $data = array();
        
        if ($this->registry["ui"]["admin"]) {
    		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
            FROM troubles AS t
            LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
            LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
            WHERE ( (t.secure = 0) OR ( (t.secure = 1) AND (t.who = :uid OR tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid) ) )
                AND t.close = 0
                AND td.opening <= NOW()
            ORDER BY t.id DESC
            LIMIT " . $this->startRow .  ", " . $this->limit;
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]);
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
        $user = new Model_User($this->registry);
        
        $data = array(); $result = array();
        
        $year = date("Y", strtotime($date));
        $month = date("m", strtotime($date));
        $day = date("d", strtotime($date));
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id), t.close, td.type, td.deadline, td.iteration, td.timetype_iteration, td.opening, t.ending
        FROM troubles AS t 
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = td.tid)
        WHERE ( ( (t.secure = 0) AND (t.who = :uid OR tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid OR tr.uid IS NULL) ) OR ( (t.secure = 1) AND (t.who = :uid OR tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid) ) )
        ORDER BY td.opening DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
        
		$res = $this->registry['db']->prepare($sql);
        $param = array(":uid" => $uid, ":gid" => $user->getGidFromUid($uid));
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}

        for($i=0; $i<count($data); $i++) {
            
            $inc_day = 0;
            $inc_month = 0;
            $inc = $data[$i]["iteration"];
            $inc_type = $data[$i]["timetype_iteration"];       
            $start = strtotime($data[$i]["opening"]);
            $end = strtotime($data[$i]["ending"]);
            if (($days = $data[$i]["deadline"] / 60 / 60 / 24) < 1) {
                $days = 1;
            }
            
            if ($data[$i]["close"] != "0") {
                $curDay = date("d", mktime(0, 0, 0, date("m", $end), date("d", $end), date("Y", $end)));
                $curMonth = date("m", mktime(0, 0, 0, date("m", $end), date("d", $end), date("Y", $end)));
                $curYear = date("Y", mktime(0, 0, 0, date("m", $end), date("d", $end), date("Y", $end)));
                
                if ( ($curYear == $year) and ($curMonth == $month)  and ($curDay == $day) ) {
                    $result[]["id"] = $data[$i]["id"];                    
                }
            } elseif ($data[$i]["type"] == "0") {
                $curDay = date("d", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                $curMonth = date("m", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                $curYear = date("Y", mktime(0, 0, 0, date("m", $start), date("d", $start), date("Y", $start)));
                
                if ($curYear . $curMonth . $curDay <= $year . $month . $day) {
                    $result[]["id"] = $data[$i]["id"];                    
                }
            } elseif ($data[$i]["type"] == "1") {
                for($l=0; $l<$days; $l++) {
                    $curDay = date("d", mktime(0, 0, 0, date("m", $start), date("d", $start) + $l, date("Y", $start)));
                    $curMonth = date("m", mktime(0, 0, 0, date("m", $start), date("d", $start) + $l, date("Y", $start)));
                    $curYear = date("Y", mktime(0, 0, 0, date("m", $start), date("d", $start) + $l, date("Y", $start)));
                    
                    if ( ($curYear == $year) and ($curMonth == $month) and ($curDay == $day) ) {
                        $result[]["id"] = $data[$i]["id"];
                    }
                } 
            } elseif ($data[$i]["type"] == "2") {
                $curYear = date("Y", $start);
                $curMonth = date("m", $start);
                
                while( ($curYear <= $year) ) {
                    $curDay = date("d", mktime(0, 0, 0, date("m", $start) + $inc_month, date("d", $start) + $inc_day, date("Y", $start)));
                    $curMonth = date("m", mktime(0, 0, 0, date("m", $start) + $inc_month, date("d", $start) + $inc_day, date("Y", $start)));
                    $curYear = date("Y", mktime(0, 0, 0, date("m", $start) + $inc_month, date("d", $start) + $inc_day, date("Y", $start)));
                    
                    if ( ($curYear == $year) and ($curMonth == $month) and ($curDay == $day) ) {
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

        return $result;
    }

    public function getTask($tid) {
        $data = array();
        
		$sql = "SELECT t.id, t.oid, t.who, t.imp, t.secure, t.text, t.opening AS start, t.ending, t.gid, t.close, g.name AS `group`, t.cuid AS cuid, tr.uid, tr.gid AS rgid, tr.all AS `all`, td.type, td.opening, td.deadline, td.iteration, td.timetype_iteration, ts.id AS spam
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_spam AS ts ON (ts.tid = t.id)
        LEFT JOIN group_tt AS g ON (t.gid = g.id)
        WHERE t.id = :tid AND ( (t.secure = 0) OR ( (t.secure = 1) AND (t.who = :uid OR tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid) ) )";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":tid" => $tid, ":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $flag = FALSE;
        if (count($data) > 0) {
            $data[0]["startdate"] = date("Y-m-d", strtotime($data[0]["opening"]));
            $data[0]["starttime"] = date("H:i:s", strtotime($data[0]["opening"]));
            
            $data[0]["startF"] = $this->editDate($data[0]["start"]);
            $data[0]["openingF"] = $this->editDate($data[0]["opening"]);
            $data[0]["endingF"] = $this->editDate($data[0]["ending"]);

            $d = strtotime($data[0]["opening"]);
            $deadline = $data[0]["deadline"];
            $expire = date("YmdHis", mktime(date("H", $d), date("i", $d), date("s", $d) + $deadline, date("m", $d), date("d", $d), date("Y", $d)));
            if ($data[0]["close"] != 0) {
                $end = date("YmdHis", strtotime($data[0]["ending"]));
            } else {
                $end = date("YmdHis");
            }

            if (($data[0]["deadline"] / 60 /60 / 24) >= 1) {
                $data[0]["deadline"] = ($data[0]["deadline"] / 60 /60 / 24);
                $data[0]["deadline_date"] = "дней";
                if ($expire < $end) { $data[0]["expire"] = TRUE; } else { $data[0]["expire"] = FALSE; }
            } else {
                if (($data[0]["deadline"] / 60 /60 ) >= 1) { 
                    $data[0]["deadline"] = ($data[0]["deadline"] / 60 /60);
                    $data[0]["deadline_date"] = "часов";
                    if ($expire < $end) { $data[0]["expire"] = TRUE; } else { $data[0]["expire"] = FALSE; }
                } elseif (($data[0]["deadline"] / 60 ) >= 1) {
                    $data[0]["deadline"] = ($data[0]["deadline"] / 60 );
                    $data[0]["deadline_date"] = "минут";
                    if ($expire < $end) { $data[0]["expire"] = TRUE; } else { $data[0]["expire"] = FALSE; }
                } else {
                    $data[0]["deadline"] = "";
                    $data[0]["deadline_date"] = "0";
                }
            }
            
            if ($data[0]["group"] == "") {
                $data[0]["group"] = "Без группы";
            }
            
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
                
        		$sql = "INSERT INTO troubles (oid, who, imp, secure, text, gid) VALUES (:oid, :who, :imp, :secure, :text, :gid)";
        		
        		$res = $this->registry['db']->prepare($sql);
        		$param = array(":oid" => $oid, ":who" => $this->registry["ui"]["id"], ":imp" => $post["imp"], ":secure" => $secure, ":text" => $post["task"], ":gid" => $post["ttgid"]);
        		$res->execute($param);
                
                $sql = "SELECT id FROM troubles ORDER BY id DESC LIMIT 1";		
        		$res = $this->registry['db']->prepare($sql);
        		$res->execute();
        		$tid = $res->fetchAll(PDO::FETCH_ASSOC);
                
                $tid = $tid[0]["id"];
                
                // ответственные
                if (!isset($post["ruser"])) { $post["ruser"] = array(); }
                if (!isset($post["gruser"])) { $post["gruser"] = array(); }
                if (!isset($post["rall"])) { $post["rall"] = array(); }
                
                foreach($post["ruser"] as $part) {
            		$sql = "INSERT INTO troubles_responsible (tid, uid) VALUES (:tid, :uid)";
            		
            		$res = $this->registry['db']->prepare($sql);
            		$param = array(":tid" => $tid, ":uid" => $part);
            		@$res->execute($param);
                }
                
                foreach($post["gruser"] as $part) {
            		$sql = "INSERT INTO troubles_responsible (tid, gid) VALUES (:tid, :gid)";
            		
            		$res = $this->registry['db']->prepare($sql);
            		$param = array(":tid" => $tid, ":gid" => $part);
            		@$res->execute($param);
                }
                
                if ($post["rall"] == "1") {
            		$sql = "INSERT INTO troubles_responsible (tid, `all`) VALUES (:tid, 1)";
            		
            		$res = $this->registry['db']->prepare($sql);
            		$param = array(":tid" => $tid);
            		@$res->execute($param);
                }
                // END ответственные
                
                
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

                $sql = "INSERT INTO troubles_deadline (tid, type, opening, deadline, iteration, timetype_iteration) VALUES (:tid, :type, :opening, :deadline, :iteration, :timetype_iteration)";
            		
            	$res = $this->registry['db']->prepare($sql);
            	$param = array(":tid" => $tid, ":type" => $post["type"], ":opening" => $starttime, ":deadline" => $lifetime, ":iteration" => $post["itertime"], ":timetype_iteration" => $post["timetype_itertime"]);
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
            
    		$sql = "UPDATE troubles SET imp = :imp, secure = :secure, text = :text, gid = :gid WHERE id = :tid LIMIT 1";
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":tid" => $post["tid"], ":imp" => $post["imp"], ":secure" => $secure, ":text" => $post["task"], ":gid" => $post["ttgid"]);
    		$res->execute($param);
            
    		$sql = "DELETE FROM troubles_responsible WHERE tid = :tid";
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":tid" => $tid);
    		$res->execute($param);
            
            // ответственные
            if (!isset($post["ruser"])) { $post["ruser"] = array(); }
            if (!isset($post["gruser"])) { $post["gruser"] = array(); }
            if (!isset($post["rall"])) { $post["rall"] = array(); }
            
            foreach($post["ruser"] as $part) {
        		$sql = "INSERT INTO troubles_responsible (tid, uid) VALUES (:tid, :uid)";
        		
        		$res = $this->registry['db']->prepare($sql);
        		$param = array(":tid" => $tid, ":uid" => $part);
        		@$res->execute($param);
            }
            
            foreach($post["gruser"] as $part) {
        		$sql = "INSERT INTO troubles_responsible (tid, gid) VALUES (:tid, :gid)";
        		
        		$res = $this->registry['db']->prepare($sql);
        		$param = array(":tid" => $tid, ":gid" => $part);
        		@$res->execute($param);
            }
            
            if ($post["rall"] == "1") {
        		$sql = "INSERT INTO troubles_responsible (tid, `all`) VALUES (:tid, 1)";
        		
        		$res = $this->registry['db']->prepare($sql);
        		$param = array(":tid" => $tid);
        		@$res->execute($param);
            }
            // END ответственные

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

            $sql = "UPDATE troubles_deadline SET type = :type, opening = :opening, deadline = :deadline, iteration = :iteration, timetype_iteration = :timetype_iteration WHERE tid = :tid";
        		
        	$res = $this->registry['db']->prepare($sql);
        	$param = array(":tid" => $tid, ":type" => $post["type"], ":opening" => $starttime, ":deadline" => $lifetime, ":iteration" => $post["itertime"], ":timetype_iteration" => $post["timetype_itertime"]);
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
        
        $sql = "SELECT td.id, td.uid, users.name AS `name`, users.soname, td.text, td.timestamp AS `timestamp`
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
        WHERE ( ( (t.secure = 0) AND (t.who = :uid OR tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid OR tr.uid IS NULL) ) OR ( (t.secure = 1) AND (t.who = :uid OR tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid) ) )
            AND t.oid = :oid
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"], "oid" => $oid);
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
    
    public function getTasksAllMe() {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE (tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid)
            AND t.close = 0
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]);
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
    
    public function getNumStatTasks() {
        $sql = "SELECT COUNT(t.id) AS count
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE (tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid)
            AND t.close = 0";
        	
		$res = $this->registry['db']->prepare($sql);        
        $param = array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getMeTasks() {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE t.who = :uid
            AND t.close = 0
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
        
        return $data;
    }
    
    public function getNumMeTasks() {
        $sql = "SELECT COUNT(id) AS count FROM troubles WHERE who = :uid AND close = 0";
        	
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":uid" => $this->registry["ui"]["id"]));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getIterTasks() {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE (tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid)
            AND td.type = 2
            AND t.close = 0
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]);
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
    
    public function getNumIterTasks() {
        $sql = "SELECT COUNT(t.id) AS count 
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE (tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid)
            AND t.close = 0
            AND td.type = 2";	
        	
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getTimeTasks() {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE (tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid)
            AND td.type = 1
            AND t.close = 0
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]);
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
    
    public function getNumTimeTasks() {
        $sql = "SELECT COUNT(t.id) AS count 
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE (tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid)
            AND t.close = 0
            AND td.type = 1";	
        	
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["count"];
    }
    
    public function getNoiterTasks() {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE (tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid)
            AND td.type = 0
            AND t.close = 0
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]);
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
    
    public function getMonthTasks($year, $month) {
        $data = array(); $result = array();
        
        for($i=0; $i<=31; $i++) {
            $result[$i]["close"]["num"] = 0;
            $result[$i]["time"]["num"] = 0;
            $result[$i]["iter"]["num"] = 0;
            $result[$i]["noiter"]["num"] = 0;
        }

        $sql = "SELECT DISTINCT(t.id), t.close, td.type, td.deadline, td.iteration, td.timetype_iteration, td.opening, t.ending
        FROM troubles AS t 
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = td.tid)
        WHERE ( ( (t.secure = 0) AND (t.who = :uid OR tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid OR tr.uid IS NULL) ) OR ( (t.secure = 1) AND (t.who = :uid OR tr.uid = :uid OR tr.all = 1 OR tr.gid = :gid) ) )
        ORDER BY td.opening";
        
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":uid" => $this->registry["ui"]["id"], ":gid" => $this->registry["ui"]["group"]));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        for($i=0; $i<count($data); $i++) {
            
            $inc_day = 0;
            $inc_month = 0;
            $inc = $data[$i]["iteration"];
            $inc_type = $data[$i]["timetype_iteration"];
            $start = strtotime($data[$i]["opening"]);
            $end = strtotime($data[$i]["ending"]);
            if (($days = $data[$i]["deadline"] / 60 / 60 / 24) < 1) {
                $days = 1;
            }
            
            if ($data[$i]["close"] != 0) {
                $curDay = date("j", mktime(0, 0, 0, date("m", $end), date("d", $end), date("Y", $end)));
                $curMonth = date("m", mktime(0, 0, 0, date("m", $end), date("d", $end), date("Y", $end)));
                $curYear = date("Y", mktime(0, 0, 0, date("m", $end), date("d", $end), date("Y", $end)));
                
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
                    $curDay = date("j", mktime(0, 0, 0, date("m", $start) + $inc_month, date("d", $start) + $inc_day, date("Y", $start)));
                    $curMonth = date("m", mktime(0, 0, 0, date("m", $start) + $inc_month, date("d", $start) + $inc_day, date("Y", $start)));
                    $curYear = date("Y", mktime(0, 0, 0, date("m", $start) + $inc_month, date("d", $start) + $inc_day, date("Y", $start)));
                    
                    if ( ($curYear == $year) and ($curMonth == $month) ) {
                        $result[$curDay]["iter"]["num"]++;
                    }
                    
                    if ($inc_type == "day") {
                        $inc_day = $inc_day + $inc;
                    } elseif($inc_type == "month") {
                        $inc_month = $inc_month + $inc;
                    }
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
    
    public function closeTask($tid) {
        $sql = "UPDATE troubles SET ending = NOW(), close = 1, cuid = :cuid WHERE id = :tid LIMIT 1";
        
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":tid" => $tid, "cuid" => $this->registry["ui"]["id"]));
    }
    
    public function getNobodyTasks() {
        $data = array();
        
		$sql = "SELECT DISTINCT(t.id), tr.uid
        FROM troubles AS t
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE tr.uid IS NULL
            AND t.close = 0
        ORDER BY t.id DESC";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function spamUsers($theme, $tid) {
        $user = new Model_User($this->registry);
        
        $data1 = array(); $data = array(); $i = 0; $flag = TRUE;
        
		$sql = "SELECT tr.uid AS `uid`, users.email, tr.gid AS `gid`, tr.all AS `all`
        FROM troubles_responsible AS tr
        LEFT JOIN users ON (users.id = tr.uid)
        WHERE tr.tid = :tid";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array("tid" => $tid);
		$res->execute($param);
		$resp = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($resp) > 0) {
            foreach($resp as $part) {
                if ($part["all"]) {
                    $flag = FALSE;
                    
                    $rusers = array();
                    
                    $allusers = $user->getUsersList();
                    
                    foreach($allusers AS $uid) {
                        $data1[$i]["uid"] = $uid["id"];
                        $data1[$i]["email"] = $uid["email"];
                        
                        $i++;
                    }
                }
                
                if (($part["gid"] != 0) and ($flag)) {
                    $gusers = $user->getUserInfoFromGroup($part["gid"]);
                    
                    foreach($gusers AS $uid) {
                        $data1[$i]["uid"] = $uid["uid"];
                        $data1[$i]["email"] = $uid["email"];
                        
                        $i++;
                    }
                }
                
                if (($part["uid"] != 0) and ($flag)) {
                    $data1[$i]["uid"] = $part["uid"];
                    $data1[$i]["email"] = $part["email"];
                    
                    $i++;
                }
            }
        }
        
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