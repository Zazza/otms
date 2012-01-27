<?php
class Controller_Users_List extends Controller_Users {

	public function index() {
		$this->view->setTitle("Пользователи");

		$groups = $this->registry["user"]->getGroups();
		$uniq_groups = $this->registry["user"]->getUniqGroups();
		$users = $this->registry["user"]->getUsersGroups();

		$sortlist = array();
		foreach($groups as $group) {
			foreach($users as $user) {
				if ( ($user["gname"] == $group["sname"]) and ($group["sname"] != null) ) {
					$udata = $this->view->render("users_data", array("data" => $this->registry["user"]->getUserInfo($user["id"])));
					$sortlist[$group["pname"]][$group["sid"]][] = $udata;
				}
			}
		}

		$this->print_array($sortlist);

		$this->view->users_tree(array("group" => $uniq_groups, "list" => $this->tree));

		$this->view->showPage();
	}
}
?>