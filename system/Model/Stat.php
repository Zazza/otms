<?php
class Model_Stat extends Model_Index {
    public function getStat($statSess) {
        print_r($statSess);
    }
    
    public function getRusersStat($statSess) {
        $data = array();
        
        $start = $statSess["syear"] . "-" . $statSess["smonth"] . "-" . $statSess["sday"];
        $end = $statSess["fyear"] . "-" . $statSess["fmonth"] . "-" . $statSess["fday"] . " 23:59:59";
        
        $sql = "SELECT tr.uid, u.name, u.soname, COUNT(t.id) AS count
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        LEFT JOIN users AS u ON (u.id = tr.uid)
        WHERE td.opening >= :start AND t.ending <= :end
            AND t.secure = 0
            AND t.close = 1
        GROUP BY tr.uid";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":start" => $start, ":end" => $end);
        $res->execute($param);
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getRusersStatFromRid($statSess, $rid) {
        if ($rid == 0) { $sql = "AND tr.uid IS NULL"; } else {
            if (is_numeric($rid)) {
                $sql = "AND tr.uid = " . $rid;
            } else {
                $sql = "AND tr.uid = 0";
            }
        }
        
        $data = array();
        
        $start = $statSess["syear"] . "-" . $statSess["smonth"] . "-" . $statSess["sday"];
        $end = $statSess["fyear"] . "-" . $statSess["fmonth"] . "-" . $statSess["fday"] . " 23:59:59";
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN troubles_responsible AS tr ON (tr.tid = t.id)
        WHERE td.opening >= :start AND t.ending <= :end
            AND t.secure = 0
            " . $sql . "
            AND t.close = 1
        ORDER BY t.id DESC
        LIMIT " . $this->startRow .  ", " . $this->limit;
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":start" => $start, ":end" => $end,);
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
    
    public function getGroupsStat($statSess) {
        $data = array();
        
        $start = $statSess["syear"] . "-" . $statSess["smonth"] . "-" . $statSess["sday"];
        $end = $statSess["fyear"] . "-" . $statSess["fmonth"] . "-" . $statSess["fday"] . " 23:59:59";
        
        $sql = "SELECT t.gid, g.name, COUNT(t.id) AS count
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        LEFT JOIN group_tt AS g ON (g.id = t.gid)
        WHERE td.opening >= :start AND t.ending <= :end
            AND t.secure = 0
            AND t.close = 1
        GROUP BY t.gid";
        	
        $res = $this->registry['db']->prepare($sql);
        $param = array(":start" => $start, ":end" => $end);
        $res->execute($param);
        $data = $res->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
    
    public function getGroupsStatFromGroups($statSess, $gid) {
        $data = array();
        
        $start = $statSess["syear"] . "-" . $statSess["smonth"] . "-" . $statSess["sday"];
        $end = $statSess["fyear"] . "-" . $statSess["fmonth"] . "-" . $statSess["fday"] . " 23:59:59";
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(t.id)
        FROM troubles AS t
        LEFT JOIN troubles_deadline AS td ON (td.tid = t.id)
        WHERE td.opening >= :start AND t.ending <= :end
            AND t.secure = 0
            AND t.gid = :gid
            AND t.close = 1
        ORDER BY t.id DESC
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