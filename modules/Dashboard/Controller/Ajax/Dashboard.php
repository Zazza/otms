<?php
class Controller_Ajax_Dashboard extends Modules_Ajax {
	private $dashboard;	
	private $numEvents = 0;
	private $service = false;
	
	function __construct($config) {
		parent::__construct($config);
		$this->dashboard = new Model_Dashboard();
	}
	
	function setNotify($params) {
		$dashboard = & $_SESSION["dashboard"];
	
		if ($params["date"] == "") {
			$dashboard["date"] = date("Ymd");
		} else {
			$dashboard["date"] = $params["date"];
		}
	
		$dashboard["task"] = $params["task"];
		$dashboard["com"] = $params["com"];
		$dashboard["obj"] = $params["obj"];
		$dashboard["info"] = $params["info"];
		$dashboard["mail"] = $params["mail"];
	}
	
    private function findEvents() {
    	$arr_events = null;

    	$listevents = $this->dashboard->getDashEvents();
    	
    	if (count($listevents) > 0) {
    		
    		$max = $listevents[0]["id"];
    		for($i=0; $i<count($listevents); $i++) {
    			if ($max < $listevents[$i]["id"]) {
    				$max = $listevents[$i]["id"];
    			}
    		}
    			
    		$this->registry["logs"]->addLastDashId($max);
    	
    	}

    	foreach($listevents as $event) {
			if ($event["type"] == "service") {
				$this->service = true;
			}
	
			$this->numEvents++;
			$arr_events .= $this->view->render("events_dash", array("event" => $event));
    	}

    	return $arr_events;
    }
	
	function newevents() {
		$arr_events = null;

		$listevents = $this->dashboard->getNewEvents();
		
		if (count($listevents) > 0) {
			
			$max = $listevents[0]["id"];
			for($i=0; $i<count($listevents); $i++) {
				if ($max < $listevents[$i]["id"]) { $max = $listevents[$i]["id"]; }
			}
			
			$this->registry["logs"]->addLastDashId($max);
		}
		
		foreach($listevents as $event) {
			if ($event["type"] == "service") {
				$this->service = true;
			}
			
			$this->numEvents++;
			$arr_events .= $this->view->render("events_dash", array("event" => $event));
		}
		
		if ($this->service) { $dash["service"] = true; }
		$dash["events"] = $arr_events;
		$dash["notify"] = $this->numEvents;
		
		$chat = $this->registry["module_chat"];
		$rooms = $chat->getChatsRoom();
		$dash["rooms"] = $chat->getRenderRooms();
		$dash["numChats"] = count($rooms);
		
		echo json_encode($dash);
	}
	
	function closeEvent($params) {
		$eid = $params["eid"];
		
		$this->dashboard->closeEvent($eid);
		
		$this->findEvents();
		
		if ($this->service) { $service = true; } else { $service = false; }
		
		echo $service;
	}
	
	function clearEvents() {
		$events = $this->dashboard->getDashEvents();
		foreach($events as $event) {
			$this->dashboard->closeEvent($event["id"]);
		}
	}
}
?>