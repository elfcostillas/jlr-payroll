<?php

namespace App\Mappers\SettingsMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PhilHealthMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\PhilHealth';
    protected $rules = [
    	
    ];

    public function getRate()
    {
        $result = $this->model->select('rate')->from('philhealth');


        return $result->first();
    }
  

}
