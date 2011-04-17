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
		$sql = "SELECT t.id AS id, t.name AS `name`, f.id AS fid, f.field AS field, f.main AS main, f.expand AS expand
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
            $param = array(":name" => $post["name"]);
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
                        
                        $sql = "INSERT INTO templates_fields (tid, field, main, expand) VALUES (:tid, :field, :main, :expand)";
                        
                        $res = $this->registry['db']->prepare($sql);
                        $param = array(":tid" => $tid[0]["id"], ":field" => $post["field"][$i], ":main" => $post["main"][$i], ":expand" => $post["expand"][$i]);
                        $res->execute($param);
                    }
                }
            }
        }
    }
    
    public function editTemplate($tid, $post) {
        $sql = "UPDATE templates SET `name` = :name WHERE id = :tid LIMIT 1";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tid" => $tid, ":name" => $post["name"]);
        $res->execute($param);
        
        if (isset($post["field"])) {
            if (count($post["field"]) > 0) {
                foreach ($post["field"] as $key=>$part) {
                    if (!isset($post["main"][$key])) { $post["main"][$key] = 0; } else { $post["main"][$key] = 1; }
                    if (!isset($post["expand"][$key])) { $post["expand"][$key] = 0; } else { $post["expand"][$key] = 1; }
                    
                    if ( ($part != "") and ($post["new"][$key] == 0) ) {
                        $sql = "REPLACE INTO templates_fields (id, tid, field, main, expand) VALUES (:id, :tid, :field, :main, :expand)";
                        
                        $res = $this->registry['db']->prepare($sql);
                        $param = array(":id" => $key, ":tid" => $tid, ":field" => $part, ":main" => $post["main"][$key], ":expand" => $post["expand"][$key]);
                        $res->execute($param);
                    } elseif ($post["new"][$key] == 1) {
                        $sql = "INSERT INTO templates_fields (tid, field, main, expand) VALUES (:tid, :field, :main, :expand)";
                        
                        $res = $this->registry['db']->prepare($sql);
                        $param = array(":tid" => $tid, ":field" => $part, ":main" => $post["main"][$key], ":expand" => $post["expand"][$key]);
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
        $sql = "INSERT INTO objects (template) VALUES (:tpl)";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tpl" => $post["tid"]);
        $res->execute($param);
        
        $sql = "SELECT id FROM objects ORDER BY id DESC LIMIT 1";		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$oid = $res->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($post as $key=>$val) {
            if (is_int($key)) {
                $sql = "INSERT INTO objects_vals (oid, fid, val) VALUES (:oid, :fid, :val)";
                
                $res = $this->registry['db']->prepare($sql);
                $param = array(":oid" => $oid[0]["id"], ":fid" => $key, ":val" => $val);
                $res->execute($param);
            }
        }
    }
    
    public function findObjects($find) {
		$res = array();
		foreach ($find as $part) {
			$str = "+".$part;
			$finds[] = $str;
		}

		$finds = implode(" ", $finds);
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT o.id AS id, MATCH (ov.val) AGAINST (:find IN BOOLEAN MODE) AS relev
				FROM objects AS o
                LEFT JOIN objects_vals AS ov ON (ov.oid = o.id)
				HAVING relev > 0
				ORDER BY relev DESC, o.id DESC
				LIMIT " . $this->startRow .  ", " . $this->limit;

		$res = $this->registry['db']->prepare($sql);
		$param = array(":find" => $finds);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}

		return $rows;
    }
    
    public function getShortObject($id) {
        $rows = FALSE;
        
        $sql = "SELECT o.id AS id, o.timestamp AS timestamp, o.template AS tid, t.name AS tname, ov.fid AS fid, tf.field AS field, tf.main AS main, ov.val AS val
				FROM objects AS o
                LEFT JOIN objects_vals AS ov ON (ov.oid = o.id)
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
        
        $sql = "SELECT COUNT(id) AS count FROM troubles WHERE troubles.oid = :oid AND troubles.gid = 0";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $data["open"] = $rows[0]["count"];
        
        $sql = "SELECT COUNT(id) AS count FROM troubles WHERE troubles.oid = :oid AND troubles.gid != 0";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $data["close"] = $rows[0]["count"];
        
        return $data;
    }
    
    public function getObject($id) {
        $rows = FALSE;
        
        $sql = "SELECT o.id AS id, o.timestamp AS timestamp, o.template AS tid, t.name AS tname, ov.fid AS fid, tf.field AS field, tf.main AS main, tf.expand AS expand, ov.val AS val
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
        
        $fields = $this->getTemplate($rows[0]["tid"]);
        
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
        
        if ($post["criterion"] == "0") {
            $sql = " ";
        } elseif ($post["criterion"] == "1") {
            $sql = " AND o.timestamp > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND o.timestamp < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        } elseif ($post["criterion"] == "2") {
            $sql = " AND ov.timestamp > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND ov.timestamp < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        } elseif ($post["criterion"] == "3") {
            $sql = " AND oa.timestamp > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND oa.timestamp < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        } elseif ($post["criterion"] == "4") {
            $sql = " AND t.opening > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND t.opening < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        } elseif ($post["criterion"] == "5") {
            $sql = " AND tdis.timestamp > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND tdis.timestamp < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        } elseif ($post["criterion"] == "6") {
            $sql = " AND t.ending > '" . $post["syear"] . "-0" . $post["smonth"] . "-" . $post["sday"] . " 00:00:00' AND t.ending < '" . $post["fyear"] . "-0" . $post["fmonth"] . "-" . $post["fday"] . " 23:59:59'";
        }
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT DISTINCT(o.id) AS id
				FROM objects AS o
                LEFT JOIN objects_vals AS ov ON (ov.oid = o.id)
                LEFT JOIN objects_advanced AS oa ON (oa.oid = o.id)
                LEFT JOIN troubles AS t ON (t.oid = o.id)
                LEFT JOIN troubles_discussion AS tdis ON (tdis.tid = t.id)
                WHERE o.template = :template
                    " . $sql . "
                ORDER BY o.id DESC
                LIMIT " . $this->startRow .  ", " . $this->limit;
                
		$res = $this->registry['db']->prepare($sql);
		$param = array(":template" => $post["templates"]);
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
    
    public function editObject($post) {
        foreach ($post as $key=>$val) {
            if (is_int($key)) {
                $sql = "REPLACE INTO objects_vals (val, oid, fid) VALUES (:val, :oid, :fid)";
                
                $res = $this->registry['db']->prepare($sql);
                $param = array(":oid" => $post["tid"], ":fid" => $key, ":val" => $val);
                $res->execute($param);
            }
        }
    }
        
    public function getAdvancedInfo($id) {
        $rows = FALSE;
        
        $sql = "SELECT oa.oid AS id, oa.id AS oaid, oa.val AS val, oa.timestamp AS timestamp, u.id AS uid, u.name AS uname, u.soname AS usoname
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
        
        return $rows;
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
        }
    }
    
    public function delAdvanced($oaid) {
        $sql = "DELETE FROM objects_advanced WHERE id = :oaid";
    
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oaid" => $oaid);
        $res->execute($param);
    }
}
?>