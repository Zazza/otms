<?php
class Controller_Tt_Cal extends Controller_Tt {

	public function index() {
		$this->view->setTitle("Календарь");

		$cal = & $_SESSION["cal"];

		$allmytask = $this->registry["tt"]->getNumStatTasks();
		$itertask = $this->registry["tt"]->getNumIterTasks();
		$timetask = $this->registry["tt"]->getNumTimeTasks();
		 
		$this->view->tt_cal(array("ui" => $this->registry["ui"], "day" => date("d"), "month" => date("m"), "year" => date("Y"), "allmytask" => $allmytask, "itertask" => $itertask, "timetask" => $timetask, "calYear" => $this->registry["calYear"], "caltype" => $cal["type"]));
		 
		$this->view->showPage();
	}
}
?>