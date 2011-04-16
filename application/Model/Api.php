<?php
class Model_Api extends Model_Index {
    public function addTask($oid, $text, $imp = 3, $secure = 0) {
        $sql = "INSERT INTO troubles (oid, who, imp, secure, text) VALUES (:oid, :who, :imp, :secure, :text)";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":oid" => $oid, ":who" => 0, ":imp" => $imp, ":secure" => $secure, ":text" => $text);
        $res->execute($param);
        
        $sql = "SELECT id FROM troubles ORDER BY id DESC LIMIT 1";		
        $res = $this->registry['db']->prepare($sql);
        $res->execute();
        $tid = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $tid = $tid[0]["id"];

        $sql = "INSERT INTO troubles_deadline (tid, type) VALUES (:tid, :type)";
        	
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tid" => $tid, ":type" => 0);
        $res->execute($param);
        
        return $tid;
    }
    
    public function addResponsible($tid, $rid) {
        $sql = "INSERT INTO troubles_responsible (tid, uid) VALUES (:tid, :uid)";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tid" => $tid, ":uid" => $rid);
        $res->execute($param);
    } 
}
?>