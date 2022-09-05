<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UnpostedPayrollRegisterMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\PayrollTransaction\UnpostedPayrollRegister';
    protected $rules = [
    	
    ];

    public function unpostedPeriodList($type)
    {
        if($type=='semi'){
            $result = $this->model->select(DB::raw("id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS period_range"))
            ->from('payroll_period')
            ->whereNotIn('id',DB::table('payrollregister_posted')->distinct()->pluck('period_id'))
            //->join('payroll_period','payroll_period.id','=','payrollregister_unposted.period_id')
            ->distinct();
        }
       
        return $result->get();
    }

    public function getEmployeeWithDTR($period_id)
    {
        $result = $this->model->select(DB::raw("
                        payroll_period.id AS period_id,
                        employees.biometric_id,
                        lastname,
                        firstname,
                        middlename,
                        suffixname,
                        basic_salary,
                        is_daily,
                        deduct_phic,
                        deduct_sss,
                        pay_type,
                        SUM(late) AS late,
                        SUM(late_eq) AS late_eq,
                        SUM(under_time) AS under_time,
                        SUM(over_time) AS overtime,
                        SUM(night_diff) AS night_diff,
                        SUM(ndays) AS ndays
                        "))
                    ->from('edtr')
                    ->join('payroll_period',function($join){
                        $join->whereRaw('edtr.dtr_date between payroll_period.date_from and payroll_period.date_to');
                    })
                    ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                    ->where('payroll_period.id','=',$period_id)
                    ->whereIn('pay_type',[1,2])
                    ->where('exit_status',1)
                    ->whereNotNull('time_in')
                    ->whereNotNull('time_out')
                    ->groupBy(DB::raw('
                                payroll_period.id,
                                employees.biometric_id,
                                lastname,
                                firstname,
                                middlename,
                                suffixname,
                                basic_salary,
                                is_daily,
                                deduct_phic,
                                deduct_sss,
                                pay_type'));

        return $result->get();
    }

    public function reInsert($period_id,$employees){
        $blank = [];
        $this->model->where('period_id',$period_id)->delete();

        foreach($employees as $employee)
        {       
           
            array_push($blank,$employee->toColumnArray());
        }

        $result = DB::table('payrollregister_unposted')->insertOrIgnore($blank);

    }



}


/*

SELECT 
employees.biometric_id,
lastname,
firstname,
middlename,
suffixname,
basic_salary,
is_daily,
deduct_phic,
deduct_sss,
SUM(late) AS late,
SUM(late_eq) AS late_eq,
SUM(under_time) AS under_time,
SUM(over_time) AS overtime,
SUM(night_diff) AS night_diff,
SUM(ndays) AS ndays
FROM edtr INNER JOIN employees ON edtr.biometric_id = employees.biometric_id
INNER JOIN payroll_period ON edtr.dtr_date BETWEEN payroll_period.date_from AND payroll_period.date_to
WHERE payroll_period.id = 1
AND time_in IS NOT NULL AND time_out IS NOT NULL

GROUP BY 
employees.biometric_id,
lastname,
firstname,
middlename,
suffixname,
basic_salary,
is_daily,
deduct_phic,
deduct_sss;
*/