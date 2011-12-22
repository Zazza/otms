<?php
class Engine_Memcached extends Engine_Interface {
	private $memcached_enable = true;
	private $memdata = array();
	private $mid;
	private $cache;
    private $timeLife = 2592000; // 1 месяц
	
	public function __construct() {
		parent::__construct();

		if ($this->memcached_enable) {
			$this->memcached_enable = true;
			
			$this->cache = new Memcache();
			$this->cache->connect($this->registry["memcached_adres"], $this->registry["memcached_port"]);
		}
	}
	
	public function set($key) {
		$this->mid = $key;
	}
	
	public function get() {
		return $this->memdata;
	}

	public function load() {
		if ($this->memcached_enable) {
			if ( ($this->memdata = $this->cache->get($this->mid)) === false ) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	public function save($data) {
		if ($this->memcached_enable) {
			$this->cache->set($this->mid, $data, false, $this->timeLife);
		} else {
			return false;
		}
	}
	
	public function saveTime($data, $time) {
		if ($this->memcached_enable) {
			$this->cache->set($this->mid, $data, false, $time);
		} else {
			return false;
		}
	}
	
	public function delete() {
		if ($this->memcached_enable) {
			$this->cache->delete($this->mid, 0);
		} else {
			return false;
		}		
	}

	public function __destruct() {
		if ($this->memcached_enable) {
			$this->cache->close();
		} else {
			return false;
		}
	}
}
?>