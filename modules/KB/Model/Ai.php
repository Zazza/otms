<?php
class Model_Ai extends Modules_Model {
/*
*
*   AI
*
*/
    public function getAdvancedInfo($id) {
        $rows = FALSE; $data = array();
        
        $sql = "SELECT oa.oid AS id, oa.id AS oaid, oa.title AS `title`, oa.val AS val, oa.timestamp AS `timestamp`, u.id AS uid, u.name AS uname, u.soname AS usoname
                FROM objects_advanced AS oa
                LEFT JOIN users AS u ON (u.id = oa.who)
                WHERE oa.oid = :id
                ORDER BY oa.id DESC";
                
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id);
		$res->execute($param);
		$rows = $res->fetchAll(PDO::FETCH_ASSOC);

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
        
        $sql = "SELECT oa.id, oa.oid, oa.title, oa.val, oa.who AS auid, author.name AS aname, author.soname AS asoname, oa.timestamp AS adate, oa.euid AS euid, editor.name AS ename, editor.soname AS esoname, oa.edittime AS edate
        FROM objects_advanced AS oa
        LEFT JOIN users AS author ON (author.id = oa.who)
        LEFT JOIN users AS editor ON (editor.id = oa.euid)
        WHERE oa.id = :oaid";
        
		$res = $this->registry['db']->prepare($sql);
		$param = array(":oaid" => $oaid);
		$res->execute($param);
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $row[0];
    }
    
    public function editAdvanced($oaid, $title, $text) {
        $sql = "UPDATE objects_advanced SET title = :title, val = :val, euid = :uid, edittime = NOW() WHERE id = :oaid LIMIT 1";
        
		$res = $this->registry['db']->prepare($sql);
		$param = array(":title" => $title, ":val" => $text, ":oaid" => $oaid, ":uid" => $this->registry["ui"]["id"]);
		$res->execute($param);

    	$data = $this->getAdvanced($oaid);
    	
    	$string = "Правка информации у объекта <a href='" . $this->registry["uri"] . "objects/" . $data["oid"] . "/'>" . $data["oid"] . "</a>";
    	
    	
    	$obj["Название"] = $title;
    	$obj["Текст"] = $text;
    	
    	$this->registry["logs"]->set("info", $string, $oaid, $obj);
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
    
    public function addAdvanced($oid, $title, $text) {
        if ($text != "") {
            $sql = "INSERT INTO objects_advanced (oid, title, val, who) VALUES (:oid, :title, :val, :who)";
        
            $res = $this->registry['db']->prepare($sql);
            $param = array(":oid" => $oid, ":title" => $title, ":val" => $text, ":who" =>$this->registry["ui"]["id"]);
            $res->execute($param);
            
    		$oaid = $this->registry['db']->lastInsertId();
            
            $data = $this->getAdvanced($oaid);
            
    		$string = "Добавление информации объекту <a href='" . $this->registry["uri"] . "objects/" . $data["oid"] . "/'>" . $data["oid"] . "</a>";
    	
    		$obj["Название"] = $title;
    		$obj["Текст"] = $text;
    	
    		$this->registry["logs"]->set("info", $string, $oaid, $obj);
    		
    		return $oaid;
        }
    }
    
    public function delAdvanced($oaid) {
    	$data = $this->getAdvanced($oaid);
    	
    	if ($data["oid"] == 0) {
    		$string = "Удаление информации";
    	} else {
    		$string = "Удаление информации у объекта <a href='" . $this->registry["uri"] . "objects/" . $data["oid"] . "/'>" . $data["oid"] . "</a>";
    	}
    	
    	$obj["Название"] = $data["title"];
    	
    	$log_text = null;
    	if ($post = json_decode($data["val"])) {
    		foreach($post as $key=>$val) {
    			$log_text .= "<b>" . $key . "</b>: " . $val . " ";
    		}
    	} else {
    		$log_text = $data["val"];
    	}
    	
    	$obj["Текст"] = $log_text;
    	
    	$this->registry["logs"]->set("info", $string, $oaid, $obj);
    	
        $sql = "DELETE FROM objects_advanced WHERE id = :oaid";
    
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oaid" => $oaid);
        $res->execute($param);
        
        $sql = "DELETE FROM objects_tags WHERE oaid = :oaid";
    
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oaid" => $oaid);
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

    public function getAIFromTag($tag) {
        
        $sql = "SELECT oaid FROM objects_tags WHERE tag = :tag ORDER BY id DESC";	
        	
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tag" => $tag);
        $res->execute($param);
		$oaids = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array();
        for($i=0; $i<count($oaids); $i++) {
            $sql = "SELECT oa.id, oa.oid, oa.title, oa.val, tf.field AS `field`, ov.val AS param, oa.who AS auid, author.name AS aname, author.soname AS asoname, oa.timestamp AS adate, oa.euid AS euid, editor.name AS ename, editor.soname AS esoname, oa.edittime AS edate
            FROM objects_advanced AS oa
            LEFT JOIN objects_vals AS ov ON (ov.oid = oa.oid)
            LEFT JOIN templates_fields AS tf ON (tf.id = ov.fid)
            LEFT JOIN templates AS t ON (t.id = tf.tid)
        	LEFT JOIN users AS author ON (author.id = oa.who)
        	LEFT JOIN users AS editor ON (editor.id = oa.euid)
            WHERE oa.id = :oaid
                AND tf.main = 1";
            
    		$res = $this->registry['db']->prepare($sql);
            $param = array(":oaid" => $oaids[$i]["oaid"]);
    		$res->execute($param);
    		$row = $res->fetchAll(PDO::FETCH_ASSOC);

    		if (count($row) > 0) {
	            $data[$i] = $row[0];
	            $data[$i]["param"] = "";
	            
	            foreach($row as $part) {
	            	$data[$i]["param"] .= "<p><b>" . $part["field"] . ":</b> " . $part["param"] . "</p>";
	            }
    		} else {
            	$sql = "SELECT oa.id, oa.title, oa.val, oa.who AS auid, author.name AS aname, author.soname AS asoname, oa.timestamp AS adate, oa.euid AS euid, editor.name AS ename, editor.soname AS esoname, oa.edittime AS edate
					FROM objects_advanced AS oa
					LEFT JOIN objects_vals AS ov ON (ov.oid = oa.oid)
					LEFT JOIN users AS author ON (author.id = oa.who)
        			LEFT JOIN users AS editor ON (editor.id = oa.euid)
					WHERE oa.id = :oaid";
            	 
            	$res = $this->registry['db']->prepare($sql);
            	$param = array(":oaid" => $oaids[$i]["oaid"]);
            	$res->execute($param);
            	$row = $res->fetchAll(PDO::FETCH_ASSOC);
            	 
            	$data[0] = $row[0];
            	$data[0]["param"] = 'Заметка';
            }
        }

        return $data;
    }
/*
*
*   Tags
*
*/
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
/*
*
*   Forms
*
*/
    public function getForms() {
    	$data = array();
    	
    	$sql = "SELECT id, name FROM objects_forms ORDER BY id";
    	$res = $this->registry['db']->prepare($sql);
    	$res->execute();
    	$data = $res->fetchAll(PDO::FETCH_ASSOC);
    	
    	return $data;
    }
    
    public function getFormFieldFromId($id) {
    	$data = array();
    	
    	$sql = "SELECT field FROM objects_forms_fields WHERE id = :id LIMIT 1";

    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":id" => $id);
    	$res->execute($param);
    	$data = $res->fetchAll(PDO::FETCH_ASSOC);
    	 
    	return $data[0]["field"];
    }
    
    public function getFormName($id) {
    	$data = array();
    	 
    	$sql = "SELECT f.name AS `name`
    	        FROM objects_forms AS f
    	        WHERE f.id = :id";
    	
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":id" => $id);
    	$res->execute($param);
    	$data = $res->fetchAll(PDO::FETCH_ASSOC);
    	
    	return $data[0]["name"];
    }
    
    public function getForm($id) {
    	$data = array();
    	
    	$sql = "SELECT f.id AS id, f.name AS `name`, ff.id AS fid, ff.field AS `field`, ff.expand AS `expand`
        FROM objects_forms AS f
        LEFT JOIN objects_forms_fields AS ff ON (f.id = ff.ofid)
        WHERE f.id = :id
        ORDER BY ff.id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function addForm($post) {
    	if ($post["name"] != '') {
    		$sql = "INSERT INTO objects_forms (`name`) VALUES (:name)";
    	
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":name" => htmlspecialchars($post["name"]));
    		$res->execute($param);
    	
    		$ofid = $this->registry['db']->lastInsertId();
    	
    		if (isset($post["field"])) {
    			if (count($post["field"]) > 0) {
    				for($i=0; $i<count($post["field"]); $i++) {
    	
    					if (!isset($post["main"][$i])) {
    						$post["main"][$i] = 0;
    					} else { $post["main"][$i] = 1;
    					}
    					if (!isset($post["expand"][$i])) {
    						$post["expand"][$i] = 0;
    					} else { $post["expand"][$i] = 1;
    					}
    	
    					$sql = "INSERT INTO objects_forms_fields (`ofid`, `field`, `expand`) VALUES (:ofid, :field, :expand)";
    	
    					$res = $this->registry['db']->prepare($sql);
    					$param = array(":ofid" => $ofid, ":field" => htmlspecialchars($post["field"][$i]), ":expand" => $post["expand"][$i]);
    					$res->execute($param);
    				}
    			}
    		}
    	}
    }
    
    public function delForm($id) {
    	$sql = "DELETE FROM objects_forms WHERE id = :id LIMIT 1";
    	
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":id" => $id);
    	$res->execute($param);
    	
    	$sql = "DELETE FROM objects_forms_fields WHERE ofid = :ofid";
    	 
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":ofid" => $id);
    	$res->execute($param);
    }
    
    public function editForm($ofid, $post) {
    	$sql = "UPDATE objects_forms SET `name` = :name WHERE id = :tid LIMIT 1";
    
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":tid" => $ofid, ":name" => htmlspecialchars($post["name"]));
    	$res->execute($param);
    
    	if (isset($post["field"])) {
    		if (count($post["field"]) > 0) {
    			foreach ($post["field"] as $key=>$part) {
    				if (!isset($post["main"][$key])) {
    					$post["main"][$key] = 0;
    				} else { $post["main"][$key] = 1;
    				}
    				if (!isset($post["expand"][$key])) {
    					$post["expand"][$key] = 0;
    				} else { $post["expand"][$key] = 1;
    				}
    
    				if ( ($part != "") and ($post["new"][$key] == 0) ) {
    					$sql = "REPLACE INTO objects_forms_fields (`id`, `ofid`, `field`, `expand`) VALUES (:id, :ofid, :field, :expand)";
    
    					$res = $this->registry['db']->prepare($sql);
    					$param = array(":id" => $key, ":ofid" => $ofid, ":field" => htmlspecialchars($part), ":expand" => $post["expand"][$key]);
    					$res->execute($param);
    				} elseif ($post["new"][$key] == 1) {
    					$sql = "INSERT INTO objects_forms_fields (`ofid`, `field`, `expand`) VALUES (:ofid, :field, :expand)";
    
    					$res = $this->registry['db']->prepare($sql);
    					$param = array(":ofid" => $ofid, ":field" => htmlspecialchars($part), ":expand" => $post["expand"][$key]);
    					$res->execute($param);
    				} elseif ($part == "") {
    					$sql = "DELETE FROM objects_forms_fields WHERE id = :id AND ofid = :ofid LIMIT 1";
    
    					$res = $this->registry['db']->prepare($sql);
    					$param = array(":id" => $key, ":ofid" => $ofid);
    					$res->execute($param);
    				}
    			}
    		}
    	}
    }
    
    public function addObjectFormInfo($oid, $title, $post) {
    	$log_text = null;
    	foreach($post as $key=>$val) {
    		$new_key = $this->getFormFieldFromId($key);
    		$text[$new_key] = $val;
    		
    		$log_text .= "<b>" . $new_key . "</b>: " . $val . " ";
    	}
    	
    	$text = json_encode($text);
    	
		$sql = "INSERT INTO objects_advanced (oid, title, val, who) VALUES (:oid, :title, :val, :who)";
        
		$res = $this->registry['db']->prepare($sql);
		$param = array(":oid" => $oid, ":title" => $title, ":val" => $text, ":who" =>$this->registry["ui"]["id"]);
		$res->execute($param);
            
		$oaid = $this->registry['db']->lastInsertId();
            
		$data = $this->getAdvanced($oaid);
            
		$string = "Добавление информации объекту <a href='" . $this->registry["uri"] . "objects/" . $data["oid"] . "/'>" . $data["oid"] . "</a>";
    	
		$obj["Название"] = $title;
		$obj["Текст"] = $log_text;
    	
		$this->registry["logs"]->set("info", $string, $oaid, $obj);
    		
		return $oaid;
    }
    
    public function editObjectFormInfo($oaid, $post) {
    	$text = json_encode($post);
    	
    	$sql = "SELECT oid FROM objects_advanced WHERE id = :id LIMIT 1";
    	
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":id" => $oaid);
    	$res->execute($param);
    	$row = $res->fetchAll(PDO::FETCH_ASSOC);
    	
    	$sql = "UPDATE objects_advanced SET val = :text, euid = :uid, edittime = NOW() WHERE oid = :oid LIMIT 1";
    	
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":oid" => $row[0]["oid"], ":text" => $text, ":uid" => $this->registry["ui"]["id"]);
    	$res->execute($param);
    	
    	$string = "Правка информации у объекта <a href='" . $this->registry["uri"] . "objects/" . $row[0]["oid"] . "/'>" . $row[0]["oid"] . "</a>";
    	
    	$sql = "SELECT `title` FROM objects_advanced WHERE id = :id LIMIT 1";
    	 
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":id" => $oaid);
    	$res->execute($param);
    	$title = $res->fetchAll(PDO::FETCH_ASSOC);
    	
    	$obj["Название"] = $title[0]["title"];
    	
    	$log_text = null;
    	foreach($post as $key=>$val) {
    		$log_text .= "<b>" . $key . "</b>: " . $val . " ";
    	}
    	$obj["Текст"] = $log_text;
    	 
    	$this->registry["logs"]->set("info", $string, $oaid, $obj);
    }
}
?>