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
            //    $result->where('payrollregister_posted_s.emp_level','<',5);
               $result->where('payrollregister_posted_s.emp_level','=','confi');
            }else{
                // $result->where('payrollregister_posted_s.emp_level','>=',5);
                $result->where('payrollregister_posted_s.emp_level','>=','non-confi');
            }

            return $result->get();
        }else{
            return 'No User Rights.';
        }
    }

    public function getPostedPeriod($type)
    {
      
        $result = $this->model->select(DB::raw("period_id as id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS period_range"))
                                ->from('posting_info')->join('payroll_period','payroll_period.id','=','posting_info.period_id')
                                ->where('trans_type',$type)
                                ->orderBy('period_id','DESC');

        return $result->get();
    }

    public function getPostedDataforRCBC($period_id,$emp_level)
    {
        $result = DB::table('payrollregister_posted_s')->select(DB::raw('employee_names_vw.employee_name,employees.bank_acct,payrollregister_posted_s.net_pay'))
        ->leftJoin('employees','payrollregister_posted_s.biometric_id','=','employees.biometric_id')
        ->leftJoin('employee_names_vw','payrollregister_posted_s.biometric_id','=','employee_names_vw.biometric_id')
        ->where('period_id','=',$period_id)
        ->where('payrollregister_posted_s.emp_level','=',$emp_level);


        return $result->get();

    }

    public function unpost($period_id,$emp_level)
    {
        DB::beginTransaction();

        $posted_fixed_compensations = DB::table('posted_fixed_compensations')->where('period_id',$period_id)->where('emp_level',$emp_level)->delete();
        $posted_fixed_deductions = DB::table('posted_fixed_deductions')->where('period_id',$period_id)->where('emp_level',$emp_level)->delete();
        $posted_installments = DB::table('posted_installments')->where('period_id',$period_id)->where('emp_level',$emp_level)->delete();
        $posted_loans = DB::table('posted_loans')->where('period_id',$period_id)->where('emp_level',$emp_level)->delete();
        $posted_onetime_deductions = DB::table('posted_onetime_deductions')->where('period_id',$period_id)->where('emp_level',$emp_level)->delete();
        $posted_other_compensations = DB::table('posted_other_compensations')->where('period_id',$period_id)->where('emp_level',$emp_level)->delete();
        $payrollregister_posted_s = DB::table('payrollregister_posted_s')->where('period_id',$period_id)->where('emp_level',$emp_level)->delete();
        $posting_info = DB::table('posting_info')->where('period_id',$period_id)->where('trans_type',$emp_level)->delete();

        //if($posted_fixed_compensations && $posted_fixed_deductions && $posted_installments && $posted_loans && $posted_onetime_deductions && $posted_other_compensations && $payrollregister_posted_s && $posting_info){
            DB::commit();
            $flag = true;
        // }else{
        //     DB::rollBack();
        //     $flag = false;
        // }

        return array('success'=>'Payroll Period was unposted successfully.');
    }

}


/*
SELECT bank_acct,net_pay,CONCAT(lastname,', ',firstname,' ',IFNULL(middlename,''),' ',IFNULL(suffixname,'')) AS employee_name 
FROM payrollregister_posted_s 
INNER JOIN employees ON payrollregister_posted_s.biometric_id = employees.biometric_id
WHERE payrollregister_posted_s.period_id = 1;

*/