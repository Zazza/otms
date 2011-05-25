<?php
class Model_Ai extends Model_Index {
/*
*
*   AI
*
*/
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
}
?>