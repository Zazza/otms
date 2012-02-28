<?php
class Model_Db_Dashboard extends Engine_Model {
	public function getEvents($date, $where) {
		$sql = "SELECT logs.id, logs.type, logs.event, logs.oid, logs.uid, logs.timestamp, lo.key, lo.val, u.name, u.soname
			FROM logs
			LEFT JOIN logs_object AS lo ON (lo.log_oid = logs.id)
			LEFT JOIN users AS u ON (u.id = logs.uid)
			WHERE " . $date . " " . $where . "
			GROUP BY logs.id
			ORDER BY logs.timestamp DESC, lo.id";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $data;
	}

	public function getDashEvents($date, $where) {
		$sql = "SELECT logs.id, logs.type, logs.event, logs.oid, logs.uid, logs.timestamp, lo.key, lo.val, u.name, u.soname
			FROM logs
			LEFT JOIN logs_object AS lo ON (lo.log_oid = logs.id)
			LEFT JOIN users AS u ON (u.id = logs.uid)
			WHERE " . $date . " " . $where . "
			GROUP BY logs.id
			ORDER BY logs.timestamp DESC, lo.id";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

		$sql = "SELECT eid FROM logs_closed WHERE uid = :uid";
		
		$res = $this->registry['db']->prepare($sql);
		$params = array(":uid" => $this->registry["ui"]["id"]);
		$res->execute($params);
		$data_closed = $res->fetchAll(PDO::FETCH_ASSOC);

		$res_data = array();
		foreach($data as $part) {
		    $flag = false;
		    foreach($data_closed as $lc) {
				if ($part["id"] == $lc["eid"]) {
				    $flag = true;
				}
		    }
		
		    if (!$flag) {
				$res_data[] = $part;
		    }
		}

		return $res_data;
	}
	
	public function getNewEvents($lid, $date) {
		$sql = "SELECT logs.id, logs.type, logs.event, logs.oid, logs.uid, logs.timestamp, lo.key, lo.val, u.name, u.soname
				FROM logs
				LEFT JOIN logs_object AS lo ON (lo.log_oid = logs.id)
				LEFT JOIN users AS u ON (u.id = logs.uid)
				WHERE logs.id > :lid AND " . $date . "
				GROUP BY logs.id
				ORDER BY logs.timestamp DESC";
	
		$res = $this->registry['db']->prepare($sql);
		$params = array(":lid" => $lid);
		$res->execute($params);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
	
		return $data;
	}
	
	public function closeEvent($eid) {
		$sql = "INSERT INTO logs_closed (eid, uid) VALUES (:eid, :uid)";
		
		$res = $this->registry['db']->prepare($sql);
		$params = array(":eid" => $eid, ":uid" => $this->registry["ui"]["id"]);
		$res->execute($params);
	}
}
?>