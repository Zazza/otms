<?php
class Model_Kb extends Model_Index {
    
    public function getGroups() {
		$sql = "SELECT id, name 
        FROM group_kb
        ORDER BY name";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getGroupName($gid) {
		$sql = "SELECT name 
        FROM group_kb
        WHERE id = :gid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["name"];
    }
    
    public function addGroups($gname) {
		$sql = "INSERT INTO group_kb (name) VALUES (:name)";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":name" => $gname);
		$res->execute($param);
    }
    
    public function editGroupName($gid, $gname) {
		$sql = "UPDATE group_kb SET name = :gname WHERE id = :gid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid, ":gname" => $gname);
		$res->execute($param);
    }
    public function delGroup($gid) {
		$sql = "DELETE FROM group_kb WHERE id = :gid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
    }
}
?>