<?php
class Controller_Tt_Cal extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "tt", "cal");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            
            $this->view->setTitle("Календарь");
            
            $this->view->setLeftContent($this->view->render("left_tt", array("ui" => $this->registry["ui"])));
            
            $allmytask = $this->tt->getNumStatTasks();
            $allmetask = $this->tt->getNumMeTasks();
            $itertask = $this->tt->getNumIterTasks();
            $timetask = $this->tt->getNumTimeTasks();
         
            $this->view->tt_cal(array("ui" => $this->registry["ui"], "day" => date("d"), "month" => date("m"), "year" => date("Y"), "allmytask" => $allmytask, "allmetask" => $allmetask, "itertask" => $itertask, "timetask" => $timetask, "calYear" => $this->registry["calYear"]));
        }
           
        $this->view->showPage();
	}
}
?>