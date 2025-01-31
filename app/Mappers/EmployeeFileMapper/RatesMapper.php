<?php

namespace App\Mappers\EmployeeFileMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RatesMapper extends AbstractMapper {
	protected $modelClassName = 'App\Models\EmployeeFile\Rates';
    protected $rules = [
    	
    ];

    public function get_rates($emp_id)
    {
        $result = $this->model->select('id','rates','date_added','emp_id')
                    ->from('employee_rates')
                    ->where('emp_id','=',$emp_id)
                    ->orderBy('date_added','DESC')
                    ->get();
                    
        return ['data' => $result];
    }
}
