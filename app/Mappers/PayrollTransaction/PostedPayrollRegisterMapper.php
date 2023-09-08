<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PostedPayrollRegisterMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\PayrollPeriod';
    protected $rules = [
    	
    ];

    public function getPostedPeriods($user){
        //dd($user->biometric_id);
        //SELECT dept_id,emp_level FROM employees WHERE biometric_id = 847 AND exit_status = 1;
        $result = $this->model->select('dept_id','emp_level')->from('employees')
                ->where('exit_status',1)
                ->where('biometric_id',$user->biometric_id)
                ->first();
        if($result){
            $periods = $this->model->select(DB::raw("period_id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS period_range"))
                            ->from('posting_info')
                            ->join('payroll_period','posting_info.period_id','=','payroll_period.id')
                            ->orderBy('period_id','DESC');

            if($result->emp_level<5){
                $periods->where('trans_type','confi');
            }else{
                $periods->where('trans_type','non-confi');
            }
                return $periods->get();
        }else{
            return 'No User Rights.';
        }
        //dd($result->emp_level);

    }

    public function getPostedSummary($user,$period){
        $user_rights = $this->model->select('dept_id','emp_level')->from('employees')
            ->where('exit_status',1)
            ->where('biometric_id',$user->biometric_id)
            ->first();

        if($user_rights){
            $result = $this->model->select(DB::raw("bank_acct,net_pay,CONCAT(lastname,', ',firstname,' ',IFNULL(middlename,''),' ',IFNULL(suffixname,'')) AS employee_name"))
            ->from('payrollregister_posted_s')
            ->join('employees','payrollregister_posted_s.biometric_id','=','employees.biometric_id')
            ->where('payrollregister_posted_s.period_id',$period);

            if($user_rights->emp_level<5){
               $result->where('payrollregister_posted_s.emp_level','<',5);
            }else{
                $result->where('payrollregister_posted_s.emp_level','>=',5);
            }

            return $result->get();
        }else{
            return 'No User Rights.';
        }
    }

}


/*
SELECT bank_acct,net_pay,CONCAT(lastname,', ',firstname,' ',IFNULL(middlename,''),' ',IFNULL(suffixname,'')) AS employee_name 
FROM payrollregister_posted_s 
INNER JOIN employees ON payrollregister_posted_s.biometric_id = employees.biometric_id
WHERE payrollregister_posted_s.period_id = 1;

*/