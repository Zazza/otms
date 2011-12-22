<?php
class Model_Object extends Modules_Model {
    public function addObject($post) {
        $sql = "INSERT INTO objects (template, typeid, uid) VALUES (:tpl, :ttypeid, :uid)";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tpl" => $post["tid"], ":ttypeid" => $post["ttypeid"], ":uid" => $this->registry["ui"]["id"]);
        $res->execute($param);

		$oid = $this->registry['db']->lastInsertId();
		
        if ( (isset($post["email"])) and ($post["email"] != null) ) {
        	$contact = & $_SESSION["contact"];
        	unset($contact["email"]);
        	
	        $sql = "INSERT INTO mail_contacts (oid, email) VALUES (:oid, :email)";
	        
	        $res = $this->registry['db']->prepare($sql);
	        $param = array(":oid" => $oid, ":email" => $post["email"]);
	        $res->execute($param);
    	}
        
        foreach ($post as $key=>$val) {
            if (is_int($key)) {
                $sql = "INSERT INTO objects_vals (oid, fid, val) VALUES (:oid, :fid, :val)";
                
                $res = $this->registry['db']->prepare($sql);
                $param = array(":oid" => $oid, ":fid" => $key, ":val" => strip_tags($val, "<a>"));
                $res->execute($param);
                
                $obj[$key] = strip_tags($val, "<a>");
            	
                $field = $this->getObject($oid);
        		foreach($field as $part) {
        			if ($part["fid"] == $key) $logs_obj[$part["field"]] = strip_tags($val, "<a>");
        		}
            }
        }
            
    	$string = "Добавление объекта <a href='" . $this->registry["uri"] . "objects/" . $oid . "/'>" . $oid . "</a>";
    
    	$this->registry["logs"]->set("obj", $string, $oid, $logs_obj);
    }
    
    public function getShortObject($id) {
    	$this->memcached->set("obj" . $id);
    	
    	if (!$this->memcached->load()) {
	        $sql = "SELECT o.id AS id, o.timestamp AS `timestamp`, o.template AS tid, temp.id AS type_id, temp.name AS type_name, t.name AS tname, ov.fid AS fid, tf.field AS `field`, tf.main AS `main`, ov.val AS val
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
	
			if (count($rows) > 0) {
				$sql = "SELECT `email`
				FROM mail_contacts
				WHERE oid = :id
				LIMIT 1";
				
				$res = $this->registry['db']->prepare($sql);
				$param = array(":id" => $id);
				$res->execute($param);
				$email = $res->fetchAll(PDO::FETCH_ASSOC);
				
				if ( (count($email)) and ($email[0]["email"] != "") ) {
					$rows[0]["email"] = $email[0]["email"];
				}
				
	        	return $rows;
			} else {
				return false;
			}
			
			$this->memcached->save($rows);
    	} else {
    		$rows = $this->memcached->get();
    	}
    	
    	if (count($rows) > 0) {
    		return $rows;
    	} else {
    		return false;
    	}
    }
    
    public function getNumTroubles($oid) {
        $data = FALSE;
        
        $sql = "SELECT COUNT(t.id) AS count
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE t.secure = 0
        	AND t.oid = :oid
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
        WHERE t.secure = 0
        	AND t.oid = :oid
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
        WHERE t.secure = 0
        	AND t.oid = :oid
            AND t.close = 0
            AND td.type = 2";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $data["iter"] = $rows[0]["count"];
        
        $sql = "SELECT COUNT(id) AS count
        FROM troubles
        WHERE troubles.secure = 0
        	AND troubles.oid = :oid
            AND troubles.close = 1";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $data["close"] = $rows[0]["count"];
        
        return $data;
    }
    
    public function getObject($id) {
        $template = new Model_Template();
        
        $rows = FALSE;
        
        $this->memcached->set("obj" . $id);
        
        if (!$this->memcached->load()) {
        
	        $sql = "SELECT o.id AS id, o.timestamp AS `timestamp`, o.template AS tid, t.name AS tname, ov.fid AS fid, tf.field AS `field`, tf.main AS `main`, tf.expand AS `expand`, ov.val AS val, o.uid AS auid, author.name AS aname, author.soname AS asoname, o.timestamp AS adate, ov.uid AS euid, editor.name AS ename, editor.soname AS esoname, ov.timestamp AS edate
					FROM objects AS o
	                LEFT JOIN objects_vals AS ov ON (ov.oid = o.id)
	                LEFT JOIN templates_fields AS tf ON (tf.id = ov.fid)
	                LEFT JOIN templates AS t ON (t.id = tf.tid)
	                LEFT JOIN users AS author ON (author.id = o.uid)
	                LEFT JOIN users AS editor ON (editor.id = ov.uid)
	                WHERE o.id = :id
	                ORDER BY fid";
	                
			$res = $this->registry['db']->prepare($sql);
			$param = array(":id" => $id);
			$res->execute($param);
			$rows = $res->fetchAll(PDO::FETCH_ASSOC);
			
			$sql = "SELECT `email`
			FROM mail_contacts
			WHERE oid = :id";
			
	    	$res = $this->registry['db']->prepare($sql);
			$param = array(":id" => $id);
			$res->execute($param);
			$email = $res->fetchAll(PDO::FETCH_ASSOC);
			
			if ( (count($email)) and ($email[0]["email"] != "") ) {
				$rows[0]["email"] = $email[0]["email"];
			}
	        
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
	        
	        $this->memcached->save($rows);
        } else {
        	$rows = $this->memcached->get();
        }

        return $rows;
    }
    
    public function getObjsTree() {
    	$data = array();
    	
    	$sql = "SELECT null AS id, tt.tid AS tid, t.name AS tname, tt.id AS type_id, tt.name AS type_name
    	                    FROM templates_type AS tt
    	                    LEFT JOIN templates AS t ON (t.id = tt.tid)
    	                    ORDER BY tt.id DESC";
    	
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

    	return $data;
    }
    
    public function getObjects($sid) {
        $data = array();
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(o.id) AS id, tt.tid AS gid
			FROM objects AS o
			LEFT JOIN templates_type AS tt ON (tt.id = o.typeid)
			WHERE o.typeid = :sid
			ORDER BY o.id DESC
			LIMIT " . $this->startRow .  ", " . $this->limit;
                    
		$res = $this->registry['db']->prepare($sql);
		$param = array(":sid" => $sid);
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
    	$template = new Model_Template();
    	
        foreach ($post as $key=>$val) {
            if (is_int($key)) {
                $sql = "REPLACE INTO objects_vals (val, oid, fid, uid) VALUES (:val, :oid, :fid, :uid)";
                
                $res = $this->registry['db']->prepare($sql);
                $param = array(":oid" => $post["tid"], ":fid" => $key, ":val" => strip_tags($val, "<a>"), ":uid" => $this->registry["ui"]["id"]);
                $res->execute($param);
                
                $obj[$key] = strip_tags($val, "<a>");

				$field = $this->getObject($post["tid"]);
        		foreach($field as $part) {
        			if ($part["fid"] == $key) $logs_obj[$part["field"]] = strip_tags($val, "<a>");
        		}
            }
        }
        
        if (isset($post["email"])) {
        	$contact = & $_SESSION["contact"];
        	unset($contact["email"]);
        	
        	$data = array();
        	
        	$sql = "SELECT id FROM mail_contacts WHERE oid = :oid LIMIT 1";
	        
	        $res = $this->registry['db']->prepare($sql);
	        $param = array(":oid" => $post["tid"]);
	        $res->execute($param);
	        $data = $res->fetchAll(PDO::FETCH_ASSOC);
	        
	        if (count($data) == 0) {        	
	        	$sql = "INSERT INTO mail_contacts (oid, email) VALUES (:oid, :email)";
	        
	        	$res = $this->registry['db']->prepare($sql);
	        	$param = array(":oid" => $post["tid"], ":email" => $post["email"]);
	        	$res->execute($param);
	        } else {
	        	if ($post["email"] == "") {
	        		$sql = "DELETE FROM mail_contacts WHERE oid = :oid LIMIT 1";
	        
	        		$res = $this->registry['db']->prepare($sql);
	        		$param = array(":oid" => $post["tid"]);
	        		$res->execute($param);
	        	} else {
	        		$sql = "UPDATE mail_contacts SET email = :email WHERE oid = :oid LIMIT 1";
	        
	        		$res = $this->registry['db']->prepare($sql);
	        		$param = array(":oid" => $post["tid"], ":email" => $post["email"]);
	        		$res->execute($param);
	        	}
	        }
    	}
        
        $string = "Правка объекта <a href='" . $this->registry["uri"] . "objects/" . $post["tid"] . "/'>" . $post["tid"] . "</a>";

    	$this->registry["logs"]->set("obj", $string, $post["tid"], $logs_obj);
    	
    	$this->memcached->set("obj" . $post["tid"]);
    	$this->memcached->delete();
    }
    
    public function moveObj($oid, $tid) {
    	$tprev = $this->getShortObject($oid);

        $sql = "UPDATE objects SET typeid = :tid WHERE id = :oid";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oid" => $oid, ":tid" => $tid);
        $res->execute($param);
        
        $tnext = $this->getShortObject($oid);

        $string = "Перемещение объекта <a href='" . $this->registry["uri"] . "objects/" . $oid . "/'>" . $oid . "</a> из группы " .  $tprev[0]["tname"] . "-" . $tprev[0]["type_name"] . " в группу " .  $tnext[0]["tname"] . "-" . $tnext[0]["type_name"];
    
    	$this->registry["logs"]->set("obj", $string, $oid);
    	
    	$this->memcached->set("obj" . $oid);
    	$this->memcached->delete();
    }
    
    public function getEmailFromOid($oid) {
    	$data = array();
    	
    	$sql = "SELECT `email` FROM mail_contacts WHERE oid = :oid LIMIT 1";
    	
    	$res = $this->registry['db']->prepare($sql);
        $param = array(":oid" => $oid);
        $res->execute($param);
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if ( (count($data) == 1) and ($data[0]["email"] != null) ) {
        	return $data[0]["email"];
        }
    }
}
?>