<?php
class Controller_Find_Tasks extends Controller_Find {

	public function index() {

        $this->view->setTitle("Поиск");
       
        $find = new Model_Find();
        $object = new Model_Object();
        
        if (isset($this->findSess["string"])) {
            
            $this->view->setMainContent("<p style='font-weight: bold; margin-bottom: 20px'>Поиск: " . $this->findSess["string"] . "</p>");

        	if (isset($_GET["page"])) {
    			if (is_numeric($_GET["page"])) {
    				if (!$find->setPage($_GET["page"])) {
    					$this->__call("find", "tasks");
    				}
    			}
    		}
    		
    		$find->links = "/" . $this->args[0] . "/";
            
            $text = substr($this->findSess["string"], 0, 64);
			$text = explode(" ", $text);

            $findArr = $find->findTroubles($text);
            
            if (!isset($this->args[1]) or ($this->args[1] == "page"))  {
                
                foreach($findArr as $part) {
                    
                    if ($data = $this->registry["tt"]->getTask($part["id"])) {
                    	$numComments = $this->registry["tt"]->getNumComments($part["id"]);
                    	$newComments = $this->registry["tt"]->getNewCommentsFromTid($part["id"]);
                    	
                    	if ($data[0]["remote_id"] == 0) {
                    		if (isset($this->registry["module_users"])) {
                    			$author = $this->registry["user"]->getUserInfo($data[0]["who"]);
                    		} else {
                    			$ui = new Model_Ui();
                    			$user = $ui->getInfo($val["uid"]);
                    		}
                    	} else {
                    		$author = $this->registry["tt_user"]->getRemoteUserInfo($data[0]["who"]);
                    	}
                    	
                    	$ruser = array();
                    	
                    	foreach($data as $val) {
                    		if (isset($val["uid"])) {
                    			if ($val["uid"] != 0) {
                    				if (isset($this->registry["module_users"])) {
                    					$user = $this->registry["user"]->getUserInfo($val["uid"]);
                    				} else {
                    					$ui = new Model_Ui();
                    					$user = $ui->getInfo($val["uid"]);
                    				}
                    	
                    				$ruser[] = "<a style='cursor: pointer' onclick='getUserInfo(" . $val["uid"] . ")'>" . $user["name"] . " " . $user["soname"] . "</a>";
                    			}
                    		}
                    	
                    		if (isset($val["rgid"])) {
                    			if ($val["rgid"] != 0) {
                    				$ruser[] = "<span style='color: #5D7FA6'><b>" . $this->registry["user"]->getSubgroupName($val["rgid"]) . "</b></span>";
                    			}
                    		}
                    	
                    		if ($val["all"] == 1) {
                    			$ruser[] = "<span style='color: #D9A444'><b>Все</b></span>";
                    		}
                    	}
                    	
                    	$cuser = $this->registry["user"]->getUserInfo($data[0]["cuid"]);
                    	
                    	$notObj = true;
                    	if (!$obj = $object->getShortObject($data[0]["oid"])) {
                    		$notObj = false;
                    	}
                    	
                        $this->view->find_tt_task(array("data" => $data, "author" => $author, "ruser" => $ruser, "cuser" => $cuser, "notObj" => $notObj, "obj" => $obj, "numComments" => $numComments, "newComments" => $newComments));
                    }
                }
            
                //Отобразим пейджер
    			if (count($find->pager) != 0) {
    				$this->view->pager(array("pages" => $find->pager));
    			}
            }
        }
    }
}
?>