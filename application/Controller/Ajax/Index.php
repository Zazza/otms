<?php
class Controller_Ajax_Index {
    
	protected $registry;
    
	protected $model;
	protected $user;
    protected $tt;
    protected $kb;
    
    protected $memcached;

	function __construct($registry) {
		$this->registry = $registry;
        
        $this->view = $this->registry['view'];
        $this->model = $this->registry['model'];
        $this->user = $this->registry['user'];
        $this->tt = $this->registry['tt'];
        $this->kb = $this->registry['kb'];
	}
    
    public function __call($name, $args) {
        $action = $args[0]["action"];
        unset($args[0]["action"]);
        $this->errorload($action, $args[0]);
    }
    
    public function errorload($name, $args) {
        echo "Error load Ajax controller!";
    }
    
    public function getMonth($params) {
        $month = $params["month"];
        $year = $params["year"];
        
        $data = $this->tt->getMonthTasks($year, $month, $this->registry["ui"]["id"]);
        foreach($data as $key=>$value) {
            if ($value["close"]["num"] > 0) {
                $close = '<span style=" margin-right: 10px"><img border="0" style="vertical-align: middle" alt="" src="/img/flag.png"><b>' . $value["close"]["num"] . '</b></span>';
            } else {
                $close = '';
            }
            if ($value["time"]["num"] > 0) {
                $time = '<span style=" margin-right: 10px"><img border="0" style="vertical-align: middle" alt="" src="/img/alarm-clock.png"><b>' . $value["time"]["num"] . '</b></span>';
            } else {
                $time = '';
            }
            if ($value["iter"]["num"] > 0) {
                $iter = '<span style="margin-right: 10px"><img border="0" alt="" src="/img/calendar-blue.png" style="position: relative; top: 3px"><b>' . $value["iter"]["num"] . '</b></span>';
            } else {
                $iter = '';
            }
            if ($value["noiter"]["num"] > 0) {
                $noiter = '<span style="margin-right: 10px"><img border="0" style="vertical-align: middle" alt="" src="/img/clock.png"><b>' . $value["noiter"]["num"] . '</b></span>';
            } else {
                $noiter = '';
            }
            
            $row[$key] = $close . $iter . $time . $noiter;
        }
        
        $row["first"] = date("N", mktime(0, 0, 0, $month, 1, $year));
        $row["num"] = date("t", mktime(0, 0, 0, $month, 1, $year));

        echo json_encode($row);
    }
}
?>