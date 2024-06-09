<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollPeriodMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\PayrollPeriod';
    protected $rules = [
    	'date_from' => 'required|sometimes',
		'date_to' => 'required|sometimes',
		'man_hours' => 'required|sometimes',
    ];

	public function find($id)
	{
		return $this->model->find($id);
	}

    public function list($filter)
    {
        $result = $this->model->select('id','date_from','date_to','date_release','man_hours','inProgress');

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('id','DESC');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        return $result->get();
    }

	public function getPayrollPeriod()
    {
        $result = $this->model->select()->from('payroll_period_vw')->orderBy('id','desc');

        return $result->get();
    }

	public function checkAtherForProgress($data)
	{
		$result = DB::table('payroll_period')->where('inProgress','=','Y')
					->where('id','!=',$data['id'])
					->count();
		return ($result>0) ? true : false;
	}


}
 

/*    public function emptoprocess($period_id){
    	$result = $this->model->select('edtr_empid')
    						  ->from('hris_edtr as a')
    						  ->join('hris_payperiod as b',function($join){
    						  	$join->whereRaw('a.edtr_date between b.payperiod_start and b.payperiod_end');
    						  })
                  ->join('hris_employee_workinfo','hris_employee_workinfo.line_id','=','edtr_empid')
    						  ->where('b.payperiod_id','=',(int)$period_id)
                  ->where('employee_exitstatus','ACT')
                  ->groupBy('edtr_empid')
                  ->havingRaw('sum(edtr_hrs) > ?', [0])
    						  ->distinct();

    	return $result->get();
    }
	
	$period = CarbonPeriod::create('2018-06-14', '2018-06-20');

// Iterate over the period
foreach ($period as $date) {
    echo $date->format('Y-m-d');
}
*/

