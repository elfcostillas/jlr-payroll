<?php

namespace App\Libraries;


class FiscalYear {

	protected $year = null;
	protected $incMonths = [10,11,12,1,2,3,4,5,6,7,8,9];

	protected $month = [];

	public function __construct($year){
		$this->year = $year;
		//$this->month = $month;

		$this->build();
	}

	public function build(){
		foreach ($this->incMonths as $indexMonth) {

			//$tmpYear = ($indexMonth<10) ? $this->year + 1 : $this->year  ;
			$tmpYear = ($indexMonth<10) ? $this->year  : $this->year-1  ;
			$noOfDays = date('t',strtotime("$indexMonth/01/$tmpYear"));
			$this->month[] = [
				'month' => $indexMonth,
				'year' => $tmpYear,
				'noOfDays' => (int)$noOfDays ,
				'monthName' => date('F',strtotime("$indexMonth/01/$tmpYear")),
			];
		}
	}

	public function boom(){
		return $this->month;
	}

	



}
