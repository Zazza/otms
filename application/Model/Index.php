<?php
class Model_Index {

	protected $registry;
    protected $helpers;
    
	//Переменные для пейджера
	public $totalPage;
	public $limit = 20;
	public $startRow = 0;
	public $curPage = 1;
    private $num = 5;
    public $links = "";
	public $pager = array();
    public $maxPage = 1; //Последняя страница пейджера

	function __construct($registry) {
		$this->registry = $registry;
        
        $this->memcached = $this->registry['memcached'];
        
        $this->helpers = new Helpers_Helpers($this->registry);
	}

	//Установка выбранной страницы
	public function setPage($page) {
		if ( ($page > 0) and ($page < 1000000000) ) {
			$this->curPage = $page;
			$this->startRow = $this->limit * ($page-1);
            
			return TRUE;
		} else {
			return FALSE;
		};
	}

	//Пейджер
	public function Pager() {
		$numPage = ceil( $this->totalPage / $this->limit );
        $this->maxPage = $numPage;

		//Предыдущая страница
		$prev = ($this->curPage-1);
		if ($prev != 0) {
			$this->pager[0] = "<div class='pager_arr'><a class='pager' href='/".$this->registry["action"].$this->links."/page/".$prev."/'>←</a></div>";
		};

		for ($i=1; $i <= $numPage; $i++) {
			if ($i == $this->curPage) {
				$this->pager[] = "<div class='pager_utext'>".$i." </div>";
			} else {
				// Выводим не более $this->num страниц вокруг выбранной
				if ( ($i >= $this->curPage - $this->num ) and ($i <= $this->curPage + $this->num) ) {
					$this->pager[] = "<div class='pager_text'><a class='pager' href='/".$this->registry["action"].$this->links."/page/".$i."/'>$i</a></div>";
				};
			};
		};

		//Следующая страница
		$next = ($this->curPage+1);
		if ($next <= $numPage) {
			$this->pager[$i+1] = "<div class='pager_arr'><a class='pager' href='/".$this->registry["action"].$this->links."/page/".$next."/'>→</a></div>";
		};

		$this->pager;
	}

	// Приведение даты в нормальный вид
	public function editDate($date) {
		$year = substr($date, 0, 4);
		$month = substr($date, 5, 2);
		$day = substr($date, 8, 2);

		$time = substr($date,11, 8);

		if ($month == "01") { $month = "января"; };
		if ($month == "02") { $month = "февраля"; };
		if ($month == "03") { $month = "марта"; };
		if ($month == "04") { $month = "апреля"; };
		if ($month == "05") { $month = "мая"; };
		if ($month == "06") { $month = "июня"; };
		if ($month == "07") { $month = "июля"; };
		if ($month == "08") { $month = "августа"; };
		if ($month == "09") { $month = "сентября"; };
		if ($month == "10") { $month = "октября"; };
		if ($month == "11") { $month = "ноября"; };
		if ($month == "12") { $month = "декабря"; };

		return $day."&nbsp;".$month."&nbsp;".$year."&nbsp;".$time;
	}
}
?>
