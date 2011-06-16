<?php
class Model_Api extends Model_Index {
    public $err;
    public function addTask($login, $pass, $oid, $text) {
        if ($text == "") {
            $this->err = "<p>Текст задачи должен быть заполнен!</p>";
            
            return FALSE;
        }
        
        $user = array();
        
        $sql = "SELECT users.id
        FROM users
        LEFT JOIN users_priv AS up ON (up.id = users.id)
        WHERE users.login = :login AND users.pass = :pass
            AND up.readonly = 0
        LIMIT 1";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":login" => $login, ":pass" => md5(md5($pass)));
        $res->execute($param);
        $user = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($user) > 0) {
			$object = new Model_Object($this->registry);
			if (!$obj = $object->getObject($oid)) {
				$this->err = "<p>Объект не найден!</p>";
				
				return FALSE;
			}
			
			$sql = "INSERT INTO troubles (oid, who, imp, secure, text) VALUES (:oid, :who, 3, 0, :text)";
			
			$res = $this->registry['db']->prepare($sql);
			$param = array(":oid" => $oid, ":who" => $user[0]["id"], ":text" => $text);
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
        
        $this->err = "<p>Логин или пароль указаны не верно!</p>";
        
        return FALSE;
    }
    
    public function addResponsible($tid, $rid) {
        $sql = "INSERT INTO troubles_responsible (tid, uid) VALUES (:tid, :uid)";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":tid" => $tid, ":uid" => $rid);
        $res->execute($param);
    } 
}
?>
