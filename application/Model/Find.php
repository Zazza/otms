<?php
class Model_Find extends Model_Index {
    public function getNumFinds($find) {
        $rows = array();
        
		foreach ($find as $part) {
			$str = "+".$part;
			$finds[] = $str;
		}
        
		$finds = implode(" ", $finds);

		$sql = "SELECT DISTINCT o.id, MATCH (ov.val) AGAINST (:find IN BOOLEAN MODE) AS relev
				FROM objects AS o
                LEFT JOIN objects_vals AS ov ON (ov.oid = o.id)
				HAVING relev > 0";

		$res = $this->registry['db']->prepare($sql);
        $param = array(":find" => $finds);
		$res->execute($param);
		$row1 = $res->fetchAll(PDO::FETCH_ASSOC); 
        
        $rows["obj"] = count($row1);
        
		$sql = "SELECT DISTINCT t.id, MATCH (t.text) AGAINST (:find IN BOOLEAN MODE) AS relev, t.oid
				FROM troubles AS t
                LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
                WHERE ((t.secure = 0) OR ((t.secure = 1) AND (t.who = :uid OR tr.uid = :uid)))
				HAVING relev > 0";

		$res = $this->registry['db']->prepare($sql);
        $param = array(":find" => $finds, ":uid" => $this->registry["ui"]["id"]);
		$res->execute($param);
		$row2 = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $rows["tasks"] = count($row2);

		return $rows;
    }
    
    public function findObjects($find) {
        $rows = array();
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
    
    public function findTroubles($find) {
		$rows = array();
		foreach ($find as $part) {
			$str = "+".$part;
			$finds[] = $str;
		}

		$finds = implode(" ", $finds);
        
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT t.id AS id, MATCH (t.text) AGAINST (:find IN BOOLEAN MODE) AS relev, t.oid
				FROM troubles AS t
                LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
                WHERE ((t.secure = 0) OR ((t.secure = 1) AND (t.who = :uid OR tr.uid = :uid)))
				HAVING relev > 0
				ORDER BY relev DESC, t.id DESC
				LIMIT " . $this->startRow .  ", " . $this->limit;

		$res = $this->registry['db']->prepare($sql);
		$param = array(":find" => $finds, ":uid" => $this->registry["ui"]["id"]);
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
}
?>