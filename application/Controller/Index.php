<?php

class Controller_Index {

	protected $registry;
	protected $view;
    protected $helpers;

	protected $model;
	protected $user;
    protected $tt;
    protected $kb;
	
	function __construct($registry, $action, $args = NULL) {
		$this->registry = $registry;
        
        $this->helpers = new Helpers_Helpers($this->registry);

        $this->view = $this->registry['view'];
        $this->model = $this->registry['model'];
        $this->user = $this->registry['user'];
        $this->tt = $this->registry['tt'];
        $this->kb = $this->registry['kb'];

        $this->registry->set("action", $action);

        if ($this->registry["auth"]) {
            $this->view->setLeftContent($this->view->render("left_user", array("ui" => $this->registry["ui"], "now" => $this->model->editDate(date("Y-m-d H:i:s")))));
        }
    }

	// Если Router вызывает несуществующий метод-страницу, отобразими 404 Error
	public function __call($name, $args) {
		$this->view->setTitle("Страница не найдена");
		$this->view->plug_page404();
		// Отобразим страницу

		$this->view->showPage();

        // Остановим вывод
		exit();
	}

	public function index($args) {
        $this->view->setTitle("Главная страница");
        
        if (!$this->registry["auth"]) {
            
            $this->view->index();

            if (isset($_POST["api"])) {
                if ($_POST["api"]) {
                    $api = new Model_Api($this->registry);
                    
                    $tid = $api->addTask($_POST["sid"], $_POST["text"]);
                    
                    if ($_POST["addressee"] == "user") {
                        $api->addResponsible($tid, $this->user->getUserId($_POST["aname"]));
                    } elseif ($_POST["addressee"] == "group") {
                        $users = $this->user->getUserInfoFromGroup($this->user->getGroupId($_POST["aname"])); 
                          
                        foreach ($users as $part) {
                            $api->addResponsible($tid, $part["uid"]);
                        }
                    } elseif ($_POST["addressee"] == "all") {
                        $users = $this->user->getUsersList();
                        
                        foreach ($users as $part) {
                            $api->addResponsible($tid, $part["id"]);
                        }
                    }
                }
            }
        
        }
        
		$this->view->showPage();
	}
}
?>
