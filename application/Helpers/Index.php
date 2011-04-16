<?php
class Helpers_Index {
	protected $view;
	
	public function __construct($registry) {
		$this->registry = $registry;
		
        $this->view = $this->registry['view'];
	}
}
?>