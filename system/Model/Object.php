<?php
class Model_Object extends Model_Index {
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
            AND t.close = 0
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
            AND t.close = 0
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
            AND t.close = 0
            AND td.type = 2";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $data["iter"] = $rows[0]["count"];
        
        $sql = "SELECT COUNT(id) AS count
        FROM troubles
        WHERE troubles.oid = :oid
            AND troubles.close = 1";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $data["close"] = $rows[0]["count"];
        
        return $data;
    }
    
    public function getObject($id) {
        $template = new Model_Template($this->registry);
        
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
            $fields = $template->getTemplate($rows[0]["tid"]);
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
    
    public function moveObj($oid, $tid) {
        $sql = "UPDATE objects SET typeid = :tid WHERE id = :oid";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oid" => $oid, ":tid" => $tid);
        $res->execute($param);
    }
}
?>