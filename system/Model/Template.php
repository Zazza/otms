<?php
class Model_Template extends Model_Index {
    public function addTree($id, $pid, $name) {
		$sql = "INSERT INTO templates_type (tid, pid, name) VALUES (:tid, :pid, :name)";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":tid" => $id, ":pid" => $pid, ":name" => $name);
		$res->execute($param);
    }
    
    public function getTree($id) {
        $data = array();
        
		$sql = "SELECT tt.id, tt.pid, tt.name
        FROM templates_type AS tt
        WHERE tt.tid = :tid
        ORDER BY tt.pid, tt.name";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":tid" => $id));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function delCat($id) {
		$sql = "DELETE FROM templates_type WHERE id = :id LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id);
		$res->execute($param);
    }
    
    public function editCat($id, $name) {
		$sql = "UPDATE templates_type SET name = :name WHERE id = :id LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id, ":name" => $name);
		$res->execute($param);
    }
    
    public function getCatName($id) {
        $data = array();
        
		$sql = "SELECT name
        FROM templates_type
        WHERE id = :id
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":id" => $id));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["name"];
    }
    
    public function getPidFromId($id) {
        $data = FALSE;
        
		$sql = "SELECT pid
        FROM templates_type
        WHERE id = :id
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":id" => $id));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["pid"];
    }
    
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
}
?>