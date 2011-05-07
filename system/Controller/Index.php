<?php

class Controller_Index {

	protected $registry;
	protected $view;
    protected $helpers;

	protected $model;
	protected $user;
    protected $tt;
	
	function __construct($registry, $action, $args = NULL) {
		$this->registry = $registry;
        
        $this->helpers = new Helpers_Helpers($this->registry);

        $this->view = $this->registry['view'];
        $this->model = $this->registry['model'];
        $this->user = $this->registry['user'];
        $this->tt = $this->registry['tt'];

        $this->registry->set("action", $action);
        
        $this->registry->set("args", $args);
        
        $this->view->dateF = $this->model->editDate(date("Y-m-d H:i:s"));
    }

	// Если Router вызывает несуществующий метод-страницу, отобразими 404 Error
	public function __call($name, $args) {
		$this->view->setTitle("404");
		$this->view->plug_page404();
		// Отобразим страницу

		$this->view->showPage();

        // Остановим вывод
		exit();
	}

	public function index($args) {
        $this->view->setTitle("Главная страница");
        
        $flag = FALSE;
        
        if (!$this->registry["auth"]) {
            
            $this->view->index();

            if (isset($_POST["method"])) {
                if ($_POST["method"] == "add") {
                    
                    $flag = TRUE;
                    
                    $api = new Model_Api($this->registry);
                    
                    if ($tid = $api->addTask($_POST["login"], $_POST["pass"], $_POST["oid"], $_POST["text"])) {
                    
                        if ($_POST["recipient"] == "user") {
                            $rid = 0;
                            $rid = $this->user->getUserId($_POST["rid"]);
                            
                            if ($rid != 0) {
                                $api->addResponsible($tid, $rid);
                            }
                        } elseif ($_POST["recipient"] == "group") {
                            $users = $this->user->getUserInfoFromGroup($this->user->getGroupId($_POST["rid"])); 
                              
                            foreach ($users as $part) {
                                $api->addResponsible($tid, $part["uid"]);
                            }
                        } elseif ($_POST["recipient"] == "all") {
                            $users = $this->user->getUsersList();
                            
                            foreach ($users as $part) {
                                $api->addResponsible($tid, $part["id"]);
                            }
                        }
                    }
                    
                    echo $api->err;
                }
            }
        
        }
        
		if (!$flag) {
		  $this->view->showPage();
        }
	}
}
?>
