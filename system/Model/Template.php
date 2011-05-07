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
}
?>