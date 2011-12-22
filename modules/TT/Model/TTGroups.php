<?php
class Model_TTGroups extends Modules_Model {
	public $open;
	public $close;
    
    public function getRusersStatFromRid($statSess, $rid) {
		if (is_numeric($rid)) {
            $gid = $this->registry["user"]->getGidFromUid($rid);
            $sql_inc = "AND (t.who = " . $rid . " OR tr.uid = " . $rid . " OR tr.all = 1 OR tr.gid = " . $gid . ")";
        }
        
        $data = array();
        
        if ($statSess["sday"] < 10) { $statSess["sday"] = "0" . $statSess["sday"]; }
    	if ($statSess["smonth"] < 10) { $statSess["smonth"] = "0" . $statSess["smonth"]; }
    	if ($statSess["fday"] < 10) { $statSess["fday"] = "0" . $statSess["fday"]; }
    	if ($statSess["fmonth"] < 10) { $statSess["fmonth"] = "0" . $statSess["fmonth"]; }
        
        $start = $statSess["syear"] . "-" . $statSess["smonth"] . "-" . $statSess["sday"];
        $end = $statSess["fyear"] . "-" . $statSess["fmonth"] . "-" . $statSess["fday"] . " 23:59:59";
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE ((t.ending >= :start AND t.ending <= :end AND t.close = 1)
        	OR (td.opening >= :start AND td.opening <= :end AND t.close = 0))
            AND t.secure = 0
            " . $sql_inc . "
        ORDER BY t.close, t.ending DESC, t.imp DESC, t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":start" => $start, ":end" => $end);
        $res->execute($param);
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $this->totalPage = $this->registry['db']->query("SELECT FOUND_ROWS()")->fetchColumn();
                
        // получим число открытых задач
        $sql = "SELECT COUNT(DISTINCT(t.id)) AS count
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE td.opening >= :start AND td.opening <= :end
            AND t.secure = 0
            AND t.close = 0
            " . $sql_inc;
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":start" => $start, ":end" => $end);
        $res->execute($param);
        $count = $res->fetchAll(PDO::FETCH_ASSOC);
        $this->open = $count[0]["count"];
        
        // получим число заверёшнных задач
        $sql = "SELECT COUNT(DISTINCT(t.id)) AS count
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE t.ending >= :start AND t.ending <= :end
            AND t.secure = 0
            AND t.close = 1
            " . $sql_inc;
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":start" => $start, ":end" => $end);
        $res->execute($param);
        $count = $res->fetchAll(PDO::FETCH_ASSOC);
        $this->close = $count[0]["count"];
        
		//Если общее число статей больше показанного, вызовем пейджер
		if ($this->totalPage < $this->limit+1)  {
		} else {
			$this->Pager();
		}

        return $data;
    }
    
    public function getGroupsStat($statSess) {
        $data = array();
        
        if ($statSess["sday"] < 10) { $statSess["sday"] = "0" . $statSess["sday"]; }
    	if ($statSess["smonth"] < 10) { $statSess["smonth"] = "0" . $statSess["smonth"]; }
    	if ($statSess["fday"] < 10) { $statSess["fday"] = "0" . $statSess["fday"]; }
    	if ($statSess["fmonth"] < 10) { $statSess["fmonth"] = "0" . $statSess["fmonth"]; }
        
        $start = $statSess["syear"] . "-" . $statSess["smonth"] . "-" . $statSess["sday"] . " 00:00:00";
        $end = $statSess["fyear"] . "-" . $statSess["fmonth"] . "-" . $statSess["fday"] . " 23:59:59";

        $sql = "SELECT COUNT(t.id) AS open, t.gid, g.name
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN group_tt AS g ON (g.id = t.gid)
        WHERE (td.opening >= :start AND td.opening <= :end)
            AND t.secure = 0
            AND t.close = 0
        GROUP BY t.gid";
        	
        $res = $this->registry['db']->prepare($sql);
        $param = array(":start" => $start, ":end" => $end);
        $res->execute($param);
        $data1 = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $sql = "SELECT COUNT(t.id) AS close, t.gid, g.name
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN group_tt AS g ON (g.id = t.gid)
        WHERE (t.ending >= :start AND t.ending <= :end)
            AND t.secure = 0
            AND t.close = 1
        GROUP BY t.gid";
        	
        $res = $this->registry['db']->prepare($sql);
        $param = array(":start" => $start, ":end" => $end);
        $res->execute($param);
        $data2 = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $data = $this->registry["tt"]->getGroups();
        
        for ($i=0; $i<count($data); $i++) {
        	$data[$i]["open"] = 0;
        	$data[$i]["close"] = 0;
        	
        	if ($data1 > $data2) $max = $data1; else $max = $data2;
        	
        	for ($j=0; $j<count($max); $j++) {
        		
        		if (isset($data1[$j])) {
	        		if ($data[$i]["id"] == $data1[$j]["gid"]) {
	        			if ($data1[$j]["open"] != 0) {
	        				$data[$i]["open"] = $data1[$j]["open"];
	        			}
	        		}
        		}
        		
        		if (isset($data2[$j])) {
	        	    if ($data[$i]["id"] == $data2[$j]["gid"]) {
	        			if ($data2[$j]["close"] != 0) {
	        				$data[$i]["close"] = $data2[$j]["close"];
	        			}
	        		}
        		}
        		
        	}
        }

        return $data;
    }
    
    public function getGroupsStatFromGroups($statSess, $gid) {
        $data = array();
        
        if ($statSess["sday"] < 10) { $statSess["sday"] = "0" . $statSess["sday"]; }
    	if ($statSess["smonth"] < 10) { $statSess["smonth"] = "0" . $statSess["smonth"]; }
    	if ($statSess["fday"] < 10) { $statSess["fday"] = "0" . $statSess["fday"]; }
    	if ($statSess["fmonth"] < 10) { $statSess["fmonth"] = "0" . $statSess["fmonth"]; }
        
        $start = $statSess["syear"] . "-" . $statSess["smonth"] . "-" . $statSess["sday"] . " 00:00:00";
        $end = $statSess["fyear"] . "-" . $statSess["fmonth"] . "-" . $statSess["fday"] . " 23:59:59";
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE ((t.ending >= :start AND t.ending <= :end AND t.close = 1)
        	OR (td.opening >= :start AND td.opening <= :end AND t.close = 0))
            AND t.secure = 0
            AND t.gid = :gid
        ORDER BY t.close, t.ending DESC, t.imp DESC, t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
        	
        $res = $this->registry['db']->prepare($sql);
        $param = array(":start" => $start, ":end" => $end, ":gid" => $gid);
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
}
?>