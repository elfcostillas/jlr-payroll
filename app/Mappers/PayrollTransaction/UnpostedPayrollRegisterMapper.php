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

    public function getPeriod($id){
        return $this->model->select(DB::raw("payroll_period.*,CASE WHEN DAY(date_from)=1 THEN 1 ELSE 2 END AS period_type"))->from('payroll_period')->where('id',$id)->first();
    }

    public function getPhilRate(){
        return $this->model->select('rate')->from('philhealth')->first();
    }

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
                        SUM(ndays) AS ndays,
                        hdmf_contri,
                        monthly_allowance,
                        daily_allowance
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
                                pay_type, 
                                hdmf_contri,
                                monthly_allowance,
                                daily_allowance'));

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

    public function runGovLoans($period,$biometric_ids)
    {
        //dd($period->id);

        DB::table('unposted_loans')->where('period_id',$period->id)->delete();
        $tmp_loan = [];
        $loans = $this->model->select(DB::raw("id,
                                                deduction_gov_loans.biometric_id,
                                                deduction_gov_loans.deduction_type,
                                                SUM(IFNULL(posted_loans.amount,0)) AS paid,
                                                total_amount-SUM(IFNULL(posted_loans.amount,0)) AS balance,
                                                IF(total_amount-SUM(IFNULL(posted_loans.amount,0))<ammortization,total_amount-SUM(IFNULL(posted_loans.amount,0)),ammortization) AS ammortization"))
                            ->from("deduction_gov_loans")
                            ->leftJoin('posted_loans','deduction_gov_loans.id','=','posted_loans.deduction_id')
                            ->whereRaw("is_stopped = 'N'")
                            ->where('deduction_gov_loans.period_id','>=',$period->id)
                            ->whereIn('deduction_gov_loans.biometric_id',$biometric_ids)
                            ->groupBy(DB::raw("id,deduction_gov_loans.biometric_id,deduction_gov_loans.deduction_type"))
                            ->havingRaw('balance>0')
                            ->get();
        
        foreach($loans as $loan)
        {
            $tmp = [
                'period_id' => $period->id,
                'biometric_id' => $loan->biometric_id,
                'deduction_type' => $loan->deduction_type,
                'amount' => $loan->ammortization,
                'deduction_id' => $loan->id,
            ];

            array_push($tmp_loan,$tmp);
        }

        DB::table('unposted_loans')->insertOrIgnore($tmp_loan);

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




SELECT id,deduction_gov_loans.biometric_id,ammortization,deduction_gov_loans.deduction_type,
SUM(IFNULL(posted_loans.amount,0)) AS paid,total_amount-SUM(IFNULL(posted_loans.amount,0)) AS balance,
IF(total_amount-SUM(IFNULL(posted_loans.amount,0))<ammortization,total_amount-SUM(IFNULL(posted_loans.amount,0)),ammortization) AS ammortization2 FROM deduction_gov_loans 
LEFT JOIN posted_loans ON deduction_gov_loans.id = posted_loans.deduction_id
WHERE is_stopped = 'N' AND deduction_gov_loans.period_id >= 1
HAVING balance>0;

*/