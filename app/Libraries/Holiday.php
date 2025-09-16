<?php

namespace App\Libraries;


class Holiday {

	public $holiday_id;
	public $holiday_date;
	public $holiday_desc;

	public function __construct($holiday_id,$holiday_date,$holiday_desc){
		$this->holiday_id = $holiday_id;
		$this->holiday_date = $holiday_date;
		$this->holiday_desc = $holiday_desc;

	}
}