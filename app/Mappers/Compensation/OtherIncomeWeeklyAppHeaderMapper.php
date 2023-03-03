<?php

namespace App\Mappers\Compensation;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OtherIncomeWeeklyAppHeaderMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\PayrollPeriodWeekly';
    protected $rules = [
    	
    ];

    public function find($id)
    {
        $result = $this->model->find($id);
        return $result;
    }

    public function list($filter)
    {
        $result = $this->model->select('id','date_from','date_to','date_release',DB::raw("CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS drange"));

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
     
    }

    public function empList($period_id)
    {   
        /*
        SELECT employee_names_vw.biometric_id,employee_names_vw.employee_name,earnings,deductions FROM employee_names_vw 
INNER JOIN employees ON employee_names_vw.biometric_id = employees.biometric_id
LEFT JOIN unposted_weekly_compensation ON employees.biometric_id = unposted_weekly_compensation.biometric_id
WHERE employees.exit_status = 1 AND employees.pay_type = 3
AND unposted_weekly_compensation.period_id IN 1;
*/
        $employees = $this->model->select(DB::raw("employee_names_vw.biometric_id,employee_names_vw.employee_name,0 as period_id,0.00 as earnings,0.00 as deductions"))
                    ->from('employee_names_vw')
                    ->join('employees','employee_names_vw.biometric_id','=','employees.biometric_id')
                    ->leftJoin('unposted_weekly_compensation','employees.biometric_id','=','unposted_weekly_compensation.biometric_id')
                    ->where('employees.exit_status',1)
                    ->where('employees.pay_type',3);

        return $employees->get();
    }   

}