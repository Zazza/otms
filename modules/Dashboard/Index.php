<?php
class Dashboard extends PreModule implements Modules_Interface {
	private $numEvents = 0;
	private $service = false;
	
	function __construct() {
		$module = new ReflectionClass($this);
		parent::__construct($module->getName());
	}
	
	function preInit() {
		
	}
	
	private function findEvents() {
		$this->dashboard = new Model_Dashboard();
		
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

	function postInit() {
		$dash["events"] = $this->findEvents();
		
		if ($this->service) {
			$dash["service"] = true;
		}
		
		$dash["notify"] = $this->numEvents;
		
		if (count($dash["events"]) == 0) {
			$dash["events"] = "<p id='emptyEvents'>Новых событий нет</p>";
		}
		
		if (isset($this->registry["module_chat"])) {
			$chat = $this->registry["module_chat"];
			$rooms = $chat->getChatsRoom();
			$dash["rooms"] = $chat->getRenderRooms();
			$dash["numChats"] = count($rooms);
		}
		
		$dash = json_encode($dash);

		$this->setFastMenu("Dashboard", $this->view->render("fastmenu", array("dash" => $dash)));
	}
}
?>