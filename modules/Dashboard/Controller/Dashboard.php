<?php
class Controller_Dashboard extends Modules_Controller {
	public function index() {
		
		if (isset($_GET["clear"])) {
			unset($_SESSION["dashboard"]);
		}

		$this->view->setTitle("Dashboard");
		
		$dashboard = new Model_Dashboard();
		
		$sess = & $_SESSION["dashboard"];
		if (isset($sess["date"])) {
			$date = date("m/d/y", strtotime($sess["date"]));
			$formatDate = date("d F Y", strtotime($sess["date"]));
		} else {
			$date = date("m/d/y");
			$formatDate = date("d F Y");
		}
		$this->view->setLeftContent($this->view->render("left_dashboard", array("notify" => $dashboard->getNotify(), "date" => $date, "formatDate" => $formatDate)));
		 
		if (isset($_GET["page"])) {
			if (is_numeric($_GET["page"])) {
				if (!$dashboard->setPage($_GET["page"])) {
					$this->__call("tt", "index");
				}
			}
		}

		$dashboard->links = "/";

		$list = null;
			
		if (!isset($this->args[0]) or ($this->args[0] == "page"))  {
			$listevents = $dashboard->getEvents();
			
			if (count($listevents) == 0) {
				$list = "Событий нет";
			}

			foreach($listevents as $event) {
				
				$list .= $this->view->render("events_event", array("event" => $event));
			}

			$this->view->dashboard(array("list" => $list));
			 
			//Отобразим пейджер
			if (count($dashboard->pager) != 0) {
				$this->view->pager(array("pages" => $dashboard->pager));
			}
		}

		$this->view->showPage();
	}
}
?>