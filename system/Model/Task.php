<?php
class Model_Task extends Model_Index {
    public function getTemplates() {
		$sql = "SELECT t.id AS id, t.name AS `name`
        FROM templates AS t
        ORDER BY t.id";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getTemplate($tid) {
		$sql = "SELECT t.id AS id, t.name AS `name`, f.id AS fid, f.field AS `field`, f.main AS `main`, f.expand AS `expand`
        FROM templates AS t
        LEFT JOIN templates_fields AS f ON (t.id = f.tid)
        WHERE t.id = :tid
        ORDER BY f.id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":tid" => $tid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getTypeTemplate($id) {
		$sql = "SELECT t.id AS id, tt.id AS ttypeid, t.name AS `name`, f.id AS fid, f.field AS `field`, f.main AS `main`, f.expand AS `expand`
        FROM templates AS t
        LEFT JOIN templates_fields AS f ON (t.id = f.tid)
        LEFT JOIN templates_type AS tt ON (tt.tid = t.id)
        WHERE tt.id = :id
        ORDER BY f.id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getTidFromPid($oid) {
        $data = "0";
        
		$sql = "SELECT t.id AS id
        FROM templates AS t
        LEFT JOIN objects AS o ON (o.template = t.id)
        WHERE o.id = :oid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["id"];
    }
    
    public function delTemplate($tid) {
        $sql = "DELETE FROM templates WHERE id = :tid";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tid" => $tid);
        $res->execute($param);
        
        $sql = "DELETE FROM templates_fields WHERE tid = :tid";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tid" => $tid);
        $res->execute($param);
    }
    
    public function addTemplate($post) {
        if ($post["name"] != '') {
            $sql = "INSERT INTO templates (`name`) VALUES (:name)";
            
            $res = $this->registry['db']->prepare($sql);
            $param = array(":name" => htmlspecialchars($post["name"]));
            $res->execute($param);
            
            $sql = "SELECT id FROM templates ORDER BY id DESC LIMIT 1";
            
    		$res = $this->registry['db']->prepare($sql);
    		$res->execute();
    		$tid = $res->fetchAll(PDO::FETCH_ASSOC);
            
            if (isset($post["field"])) {
                if (count($post["field"]) > 0) {
                    for($i=0; $i<count($post["field"]); $i++) {
                        
                        if (!isset($post["main"][$i])) { $post["main"][$i] = 0; } else { $post["main"][$i] = 1; }
                        if (!isset($post["expand"][$i])) { $post["expand"][$i] = 0; } else { $post["expand"][$i] = 1; }
                        
                        $sql = "INSERT INTO templates_fields (`tid`, `field`, `main`, `expand`) VALUES (:tid, :field, :main, :expand)";
                        
                        $res = $this->registry['db']->prepare($sql);
                        $param = array(":tid" => $tid[0]["id"], ":field" => htmlspecialchars($post["field"][$i]), ":main" => $post["main"][$i], ":expand" => $post["expand"][$i]);
                        $res->execute($param);
                    }
                }
            }
        }
    }
    
    public function editTemplate($tid, $post) {
        $sql = "UPDATE templates SET `name` = :name WHERE id = :tid LIMIT 1";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tid" => $tid, ":name" => htmlspecialchars($post["name"]));
        $res->execute($param);
        
        if (isset($post["field"])) {
            if (count($post["field"]) > 0) {
                foreach ($post["field"] as $key=>$part) {
                    if (!isset($post["main"][$key])) { $post["main"][$key] = 0; } else { $post["main"][$key] = 1; }
                    if (!isset($post["expand"][$key])) { $post["expand"][$key] = 0; } else { $post["expand"][$key] = 1; }
                    
                    if ( ($part != "") and ($post["new"][$key] == 0) ) {
                        $sql = "REPLACE INTO templates_fields (`id`, `tid`, `field`, `main`, `expand`) VALUES (:id, :tid, :field, :main, :expand)";
                        
                        $res = $this->registry['db']->prepare($sql);
                        $param = array(":id" => $key, ":tid" => $tid, ":field" => htmlspecialchars($part), ":main" => $post["main"][$key], ":expand" => $post["expand"][$key]);
                        $res->execute($param);
                    } elseif ($post["new"][$key] == 1) {
                        $sql = "INSERT INTO templates_fields (`tid`, `field`, `main`, `expand`) VALUES (:tid, :field, :main, :expand)";
                        
                        $res = $this->registry['db']->prepare($sql);
                        $param = array(":tid" => $tid, ":field" => htmlspecialchars($part), ":main" => $post["main"][$key], ":expand" => $post["expand"][$key]);
                        $res->execute($param);
                    } elseif ($part == "") {
                        $sql = "DELETE FROM templates_fields WHERE id = :id AND tid = :tid LIMIT 1";
                        
                        $res = $this->registry['db']->prepare($sql);
                        $param = array(":id" => $key, ":tid" => $tid);
                        $res->execute($param);
                    }
                }
            }
        }
    }
    
    public function addObject($post) {
        $sql = "INSERT INTO objects (template, typeid) VALUES (:tpl, :ttypeid)";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tpl" => $post["tid"], ":ttypeid" => $post["ttypeid"]);
        $res->execute($param);
        
        $sql = "SELECT id FROM objects ORDER BY id DESC LIMIT 1";
        
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$oid = $res->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($post as $key=>$val) {
            if (is_int($key)) {
                $sql = "INSERT INTO objects_vals (oid, fid, val) VALUES (:oid, :fid, :val)";
                
                $res = $this->registry['db']->prepare($sql);
                $param = array(":oid" => $oid[0]["id"], ":fid" => $key, ":val" => htmlspecialchars($val));
                $res->execute($param);
            }
        }
    }
    
    public function getShortObject($id) {
        $rows = FALSE;
        
        $sql = "SELECT o.id AS id, o.timestamp AS `timestamp`, o.template AS tid, temp.id AS type_id, temp.pid AS type_pid, temp.name AS type_name, t.name AS tname, ov.fid AS fid, tf.field AS `field`, tf.main AS `main`, ov.val AS val
				FROM objects AS o
                LEFT JOIN objects_vals AS ov ON (ov.oid = o.id)
                LEFT JOIN templates_type AS temp ON (temp.id = o.typeid)
                LEFT JOIN templates_fields AS tf ON (tf.id = ov.fid)
                LEFT JOIN templates AS t ON (t.id = tf.tid)
                WHERE o.id = :id
                    AND tf.main = 1
                ORDER BY fid";
                
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
    
    public function getNumTroubles($oid) {
        $data = FALSE;
        
        $sql = "SELECT COUNT(t.id) AS count
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE t.oid = :oid
            AND t.gid = 0
            AND td.type = 0";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $data["global"] = $rows[0]["count"];
        
        $sql = "SELECT COUNT(t.id) AS count
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE t.oid = :oid
            AND t.gid = 0
            AND td.type = 1";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $data["time"] = $rows[0]["count"];
        
        $sql = "SELECT COUNT(t.id) AS count
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE t.oid = :oid
            AND t.gid = 0
            AND td.type = 2";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $data["iter"] = $rows[0]["count"];
        
        $sql = "SELECT COUNT(id) AS count
        FROM troubles
        WHERE troubles.oid = :oid
            AND troubles.gid != 0";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $data["close"] = $rows[0]["count"];
        
        return $data;
    }
    
    public function getObject($id) {
        $rows = FALSE;
        
        $sql = "SELECT o.id AS id, o.timestamp AS `timestamp`, o.template AS tid, t.name AS tname, ov.fid AS fid, tf.field AS `field`, tf.main AS `main`, tf.expand AS `expand`, ov.val AS val
				FROM objects AS o
                LEFT JOIN objects_vals AS ov ON (ov.oid = o.id)
                LEFT JOIN templates_fields AS tf ON (tf.id = ov.fid)
                LEFT JOIN templates AS t ON (t.id = tf.tid)
                WHERE o.id = :id
                ORDER BY fid";
                
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if ($rows) {
            $fields = $this->getTemplate($rows[0]["tid"]);
        } else {
            return FALSE;
        }
        
        foreach($fields as $part) {
            $flag = FALSE;
            foreach($rows as $row) {
                if ($part["fid"] == $row["fid"]) {
                    $flag = TRUE;
                }
            }
            
            if (!$flag) {
                $rows[] = $part;
            }
        }

        return $rows;
    }
    
    public function getObjectsByClause($post) {
        $data = array();
        
        if ( ($post["criterion"] == "0") or ($post["criterion"] == "") ) {
            
            $sql = "SELECT null AS id, tt.tid AS tid, t.name AS tname, tt.id AS type_id, tt.name AS type_name
                    FROM templates_type AS tt
                    LEFT JOIN templates AS t ON (t.id = tt.tid)
                    ORDER BY tt.pid DESC";
                    
    		$res = $this->registry['db']->prepare($sql);
    		$res->execute();
    		$data = $res->fetchAll(PDO::FETCH_ASSOC);
            
            $sql = "SELECT o.id AS id, t.name AS tname
                    FROM objects AS o
                    LEFT JOIN templates AS t ON (t.id = o.template)
                    ORDER BY o.id DESC";
                    
    		$res = $this->registry['db']->prepare($sql);
    		$res->execute();
    		$data = array_merge($data, $res->fetchAll(PDO::FETCH_ASSOC));
                    
        } elseif ($post["criterion"] == "1") {
            $sql = "WHERE o.timestamp > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND o.timestamp < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        } elseif ($post["criterion"] == "2") {
            $sql = "WHERE ov.timestamp > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND ov.timestamp < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        } elseif ($post["criterion"] == "3") {
            $sql = "WHERE oa.timestamp > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND oa.timestamp < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        } elseif ($post["criterion"] == "4") {
            $sql = "WHERE t.opening > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND t.opening < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        } elseif ($post["criterion"] == "5") {
            $sql = "WHERE tdis.timestamp > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND tdis.timestamp < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        } elseif ($post["criterion"] == "6") {
            $sql = "WHERE t.ending > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND t.ending < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        }
        
        if ( ($post["criterion"] != "0") and ($post["criterion"] != "") ) {
            $sql = "SELECT DISTINCT(o.id) AS id
    				FROM objects AS o
                    LEFT JOIN objects_vals AS ov ON (ov.oid = o.id)
                    LEFT JOIN objects_advanced AS oa ON (oa.oid = o.id)
                    LEFT JOIN templates_type AS tt ON (tt.id = o.typeid)
                    LEFT JOIN troubles AS t ON (t.oid = o.id)
                    LEFT JOIN troubles_discussion AS tdis ON (tdis.tid = t.id)
                    " . $sql . "
                    ORDER BY tt.pid DESC, o.id DESC";
                    
    		$res = $this->registry['db']->prepare($sql);
    		$res->execute();
    		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        }

        return $data;
    }
    
    public function editObject($post) {
        foreach ($post as $key=>$val) {
            if (is_int($key)) {
                $sql = "REPLACE INTO objects_vals (val, oid, fid) VALUES (:val, :oid, :fid)";
                
                $res = $this->registry['db']->prepare($sql);
                $param = array(":oid" => $post["tid"], ":fid" => $key, ":val" => htmlspecialchars($val));
                $res->execute($param);
            }
        }
    }
        
    public function getAdvancedInfo($id) {
        $rows = FALSE; $data = array();
        
        $sql = "SELECT oa.oid AS id, oa.id AS oaid, oa.val AS val, oa.timestamp AS `timestamp`, u.id AS uid, u.name AS uname, u.soname AS usoname
                FROM objects_advanced AS oa
                LEFT JOIN users AS u ON (u.id = oa.who)
                WHERE oa.oid = :id
                ORDER BY oa.id DESC";
                
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        
        for($i=0; $i<count($rows); $i++) {
            $rows[$i]["timestamp"] = $this->editDate($rows[$i]["timestamp"]);
        }

        for($i=0; $i<count($rows); $i++) {
            $sql = "SELECT ot.tag
                    FROM objects_tags AS ot
                    WHERE ot.oaid = :oaid
                    ORDER BY ot.id";
                    
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":oaid" => $rows[$i]["oaid"]);
    		$res->execute($param);
            $rtags = $res->fetchAll(PDO::FETCH_ASSOC);
            
            $tags = array();
            foreach($rtags as $tag) {
                $tags["tags"][] = $tag["tag"];
            }
            
    		$data[$i] = array_merge($rows[$i], $tags);
        }

        return $data;
    }
    
    public function getAdvanced($oaid) {
        $row = FALSE;
        
        $sql = "SELECT val FROM objects_advanced WHERE id = :oaid";
        
		$res = $this->registry['db']->prepare($sql);
		$param = array(":oaid" => $oaid);
		$res->execute($param);
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $row[0]["val"];
    }
    
    public function editAdvanced($oaid, $text) {
        $sql = "UPDATE objects_advanced SET val = :val WHERE id = :oaid LIMIT 1";
        
		$res = $this->registry['db']->prepare($sql);
		$param = array(":val" => $text, ":oaid" => $oaid);
		$res->execute($param);
    }
    
    public function getNumAdvancedInfo($id) {
        $rows = FALSE;
        
        $sql = "SELECT COUNT(id) AS count
                FROM objects_advanced
                WHERE oid = :id";
                
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id);
		$res->execute($param);
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $row[0]["count"];
    }
    
    public function addAdvanced($oid, $text) {
        if ($text != "") {
            $sql = "INSERT INTO objects_advanced (oid, val, who) VALUES (:oid, :val, :who)";
        
            $res = $this->registry['db']->prepare($sql);
            $param = array(":oid" => $oid, ":val" => $text, ":who" =>$this->registry["ui"]["id"]);
            $res->execute($param);
            
            $sql = "SELECT id FROM objects_advanced ORDER BY id DESC LIMIT 1";
            
    		$res = $this->registry['db']->prepare($sql);
    		$res->execute();
    		$oaid = $res->fetchAll(PDO::FETCH_ASSOC);
            
            return $oaid[0]["id"];
        }
    }
    
    public function delAdvanced($oaid) {
        $sql = "DELETE FROM objects_advanced WHERE id = :oaid";
    
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oaid" => $oaid);
        $res->execute($param);
        
        $sql = "DELETE FROM objects_tags WHERE oaid = :oaid";
    
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oaid" => $oaid);
        $res->execute($param);
    }
    
    public function moveObj($oid, $tid) {
        $sql = "UPDATE objects SET typeid = :tid WHERE id = :oid";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oid" => $oid, ":tid" => $tid);
        $res->execute($param);
    }
    
    public function changeTags($oaid, $tags) {
        
        $sql = "DELETE FROM objects_tags WHERE oaid = :oaid";
    
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oaid" => $oaid);
        $res->execute($param);
        
        $arr = explode(",", $tags);
		$arr = array_unique($arr);
        foreach($arr as $part) {
            $tag = trim($part);
            if ($tag != "") {
                $this->addTags($oaid, $tag);
            }
        }
    }
    
    public function addTags($oaid, $tag) {
        $sql = "REPLACE INTO objects_tags (oaid, tag) VALUES (:oaid, :tag)";
    
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oaid" => $oaid, ":tag" => $tag);
        $res->execute($param);
    }
    
    public function getAi() {
        $data = array();
        
        $sql = "SELECT oa.id, oa.oid, oa.val, t.name, ot.tag
        FROM objects_advanced AS oa
        LEFT JOIN objects AS o ON (o.id = oa.oid)
        LEFT JOIN templates AS t ON (t.id = o.template)
        LEFT JOIN objects_tags AS ot ON (ot.oaid = oa.id)
        WHERE ot.id != ''
        GROUP BY ot.tag
        ORDER BY t.name";
        
        $res = $this->registry['db']->prepare($sql);
        $res->execute();
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getTags($oaid) {
        $tags = array();
        
        $sql = "SELECT tag FROM objects_tags WHERE oaid = :oaid";	
        	
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oaid" => $oaid);
        $res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $tags = array();
        foreach($data as $tag) {
            $tags[] = $tag["tag"];
        }
        
        return $tags;
    }
    
    public function getAIFromTag($tag) {
        
        $sql = "SELECT oaid FROM objects_tags WHERE tag = :tag ORDER BY id DESC";	
        	
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tag" => $tag);
        $res->execute($param);
		$oaids = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array();
        for($i=0; $i<count($oaids); $i++) {
            $sql = "SELECT oa.id, oa.oid, oa.val, tf.field AS `field`, ov.val AS param
            FROM objects_advanced AS oa
            LEFT JOIN objects_vals AS ov ON (ov.oid = oa.oid)
            LEFT JOIN templates_fields AS tf ON (tf.id = ov.fid)
            LEFT JOIN templates AS t ON (t.id = tf.tid)
            WHERE oa.id = :oaid
                AND tf.main = 1";
            
    		$res = $this->registry['db']->prepare($sql);
            $param = array(":oaid" => $oaids[$i]["oaid"]);
    		$res->execute($param);
    		$row = $res->fetchAll(PDO::FETCH_ASSOC);
            
            $data[$i]["id"] = $row[0]["id"];
            $data[$i]["oid"] = $row[0]["oid"];
            $data[$i]["val"] = $row[0]["val"];
            $data[$i]["param"] = "";
            foreach($row as $part) {
                $data[$i]["param"] .= "<b>" . $part["field"] . ":</b> " . $part["param"] . " ";
            }
        }

        return $data;
    }
}
?>