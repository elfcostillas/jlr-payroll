<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UnpostedPayrollRegisterMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\Location';
    protected $rules = [
    	
    ];

    public function unpostedPeriodList($type)
    {
        if($type=='semi'){
            $result = $this->model->select(DB::raw("period_id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS period_range"))
            ->from('payrollregister_unposted')
            ->whereNotIn('period_id',DB::table('payrollregister_posted')->distinct()->pluck('period_id'))
            ->join('payroll_period','payroll_period.id','=','payrollregister_unposted.period_id')
            ->distinct();
        }
       
        return $result->get();
    }



}
