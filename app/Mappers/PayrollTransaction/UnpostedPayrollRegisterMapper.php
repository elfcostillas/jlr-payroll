<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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
        $loans = $this->model->select(DB::raw("deduction_gov_loans.id,
                                                deduction_gov_loans.biometric_id,
                                                deduction_gov_loans.deduction_type,
                                                SUM(IFNULL(posted_loans.amount,0)) AS paid,
                                                total_amount-SUM(IFNULL(posted_loans.amount,0)) AS balance,
                                                IF(total_amount-SUM(IFNULL(posted_loans.amount,0))<ammortization,total_amount-SUM(IFNULL(posted_loans.amount,0)),ammortization) AS ammortization"))
                            ->from("deduction_gov_loans")
                            ->leftJoin('posted_loans','deduction_gov_loans.id','=','posted_loans.deduction_id')
                            ->join('deduction_types','deduction_types.id','=','deduction_gov_loans.deduction_type')
                            ->whereRaw("is_stopped = 'N'")
                            ->whereIn('deduction_types.deduction_sched',[$period->period_type,3])
                            ->where('deduction_gov_loans.period_id','<=',$period->id)
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

    public function runInstallments($period,$biometric_ids)
    {
        //dd($period->id)

        DB::table('unposted_installments')->where('period_id',$period->id)->delete();
        $tmp_loan = [];
        $loans = $this->model->select(DB::raw("deduction_installments.id,
                                                deduction_installments.biometric_id,
                                                deduction_installments.deduction_type,
                                                SUM(IFNULL(posted_installments.amount,0)) AS paid,
                                                total_amount-SUM(IFNULL(posted_installments.amount,0)) AS balance,
                                                IF(total_amount-SUM(IFNULL(posted_installments.amount,0))<ammortization,total_amount-SUM(IFNULL(posted_installments.amount,0)),ammortization) AS ammortization"))
                            ->from("deduction_installments")
                            ->leftJoin('posted_installments','deduction_installments.id','=','posted_installments.deduction_id')
                            ->join('deduction_types','deduction_types.id','=','deduction_installments.deduction_type')
                            ->whereRaw("is_stopped = 'N'")
                            ->where('deduction_installments.period_id','>=',$period->id)
                            ->whereIn('deduction_installments.biometric_id',$biometric_ids)
                            ->whereIn('deduction_types.deduction_sched',[$period->period_type,3])
                            ->whereIn('deduction_installments.biometric_id',$biometric_ids)
                            ->groupBy(DB::raw("id,deduction_installments.biometric_id,deduction_installments.deduction_type")) 
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

        DB::table('unposted_installments')->insertOrIgnore($tmp_loan);

    }

    public function runOneTimeDeduction($period,$biometric_ids)
    {
        DB::table('unposted_onetime_deductions')->where('period_id',$period->id)->delete();
        
        $tmp_loan = [];

        $onetime = $this->model->select(DB::raw("deduction_onetime_headers.period_id,deduction_onetime_details.biometric_id,deduction_type,amount,deduction_onetime_headers.id"))
                            ->from('deduction_onetime_headers')
                            ->join('deduction_onetime_details','deduction_onetime_headers.id','=','deduction_onetime_details.header_id')
                            ->whereIn('deduction_onetime_details.biometric_id',$biometric_ids)
                            ->where([
                                //['doc_status','=','POSTED'],
                                ['deduction_onetime_headers.period_id','=',$period->id]
                            ])->get();
        
        foreach($onetime as $loan)
        {
            $tmp = [
                'period_id' => $period->id,
                'biometric_id' => $loan->biometric_id,
                'deduction_type' => $loan->deduction_type,
                'amount' => $loan->amount,
                'deduction_id' => $loan->id,
            ];

            array_push($tmp_loan,$tmp);
        }

        DB::table('unposted_onetime_deductions')->insertOrIgnore($tmp_loan);
    }

    public function runFixedDeduction($period,$biometric_ids)
    {
        DB::table('unposted_fixed_deductions')->where('period_id',$period->id)->delete();
        $tmp_loan = [];

        $fixed = $this->model->select(DB::raw("deduction_fixed.id,biometric_id,deduction_fixed.deduction_type,amount"))
                ->from('deduction_fixed')
                ->join('deduction_types','deduction_types.id','=','deduction_fixed.deduction_type')
                ->whereIn('deduction_types.deduction_sched',[$period->period_type,3])
                ->whereIn('deduction_fixed.biometric_id',$biometric_ids)
                ->where([
                    ['is_stopped','=','N'],
                    ['deduction_fixed.period_id','<=',$period->id]
                ])->get();
        
        foreach($fixed as $loan)
        {
            $tmp = [
                'period_id' => $period->id,
                'biometric_id' => $loan->biometric_id,
                'deduction_type' => $loan->deduction_type,
                'amount' => $loan->amount,
                'deduction_id' => $loan->id,
            ];

            array_push($tmp_loan,$tmp);
        }

        DB::table('unposted_fixed_deductions')->insertOrIgnore($tmp_loan);
    }

    public function getPprocessed($period){
        $locations = $this->model->select('locations.id','location_name')
                    ->from("employees")
                    ->join('locations','locations.id','=','employees.location_id')
                    ->where('exit_status','=','1')
                    ->distinct()->get();
            if($locations){
                foreach($locations as $location)
                {
                    //SELECT DISTINCT division_id FROM employees WHERE exit_status = 1 AND location_id=1;
                    $divisions = $this->getDivisions($location);

                    foreach($divisions as $division){
                        $departments = $this->getDepartments($location,$division);

                        foreach($departments as $department){
                            $employees = $this->getEmployees($location,$division,$department,$period);
                            $department->employees =  $employees;
                        }

                        $division->departments = $departments;
                    }

                    $location->divisions = $divisions;
                }
            }

        return $locations;
    }

    public function getDivisions($location)
    {
        $divisions = $this->model->select('divisions.id','div_name')
                        ->from("employees")
                        ->join('divisions','divisions.id','=','employees.division_id')
                        ->where('exit_status','=','1')
                        ->where('location_id',$location->id)
                        ->distinct()->get();
        return $divisions;
    }

    public function getDepartments($location,$division)
    {
       // echo var_dump($location,$division)."<hr>";
       /*
       SELECT DISTINCT departments.id,departments.dept_name FROM employees 
        INNER JOIN departments ON departments.id = employees.dept_id
        WHERE exit_status = 1 AND location_id=1 AND division_id = 1;
        */

        $departments = $this->model->select('departments.id','dept_name')
                        ->from("employees")
                        ->join('departments','departments.id','=','employees.dept_id')
                        ->where('exit_status','=','1')
                        ->where('location_id',$location->id)
                        ->where('division_id',$division->id)
                        ->distinct()->get();

        return $departments;
    }

    public function getEmployees($location,$division,$department,$period)
    {   
        $employees = $this->model->select(DB::raw("employee_names_vw.employee_name,payrollregister_unposted.*"))
                                ->from("payrollregister_unposted")
                                ->join("employees",'employees.biometric_id','=','payrollregister_unposted.biometric_id')
                                ->join("employee_names_vw",'employee_names_vw.biometric_id','=','payrollregister_unposted.biometric_id')
                                ->where([
                                    ['division_id','=',$division->id],
                                    ['dept_id','=',$department->id],
                                    ['location_id','=',$location->id],
                                    ['payrollregister_unposted.period_id','=',$period->id]
                                ])->get();
        foreach($employees as $employee)
        {   
            $deductions = $this->getDeductions($employee->biometric_id,$period->id);
            $employee->deductions = $deductions;
            $employee->gov_deductions = collect(
                [
                    'SSS Premium' => $employee->sss_prem,
                    'PhilHealt Premium' => $employee->phil_prem,
                    'PAG IBIG Contri' => $employee->hdmf_contri,
                ]
            );

            // if($employee->biometric_id==847){
            //     dd($employee);
            // }   
        }
                                    
        return $employees;
    }

    public function getDeductions($biometric_id,$period_id)
    {   
        $onetime = DB::table("unposted_onetime_deductions")->select('deduction_type','amount')->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]]);
        $fixed = DB::table("unposted_fixed_deductions")->select('deduction_type','amount')->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]]);
        $install = DB::table("unposted_installments")->select('deduction_type','amount')->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]])
                    ->unionAll($onetime)->unionAll($fixed);
        $deductions = DB::table('deduction_types')
                        ->select('description','deduction_type','amount')
                        ->joinSub($install,'deductions',function($join){
                            $join->on('deductions.deduction_type','=','deduction_types.id');
                        })->orderBy('deduction_type')->get();

        return $deductions;
    }

}


/*
SELECT deduction_onetime_headers.period_id,deduction_onetime_details.biometric_id,deduction_type,amount,deduction_onetime_headers.id 
FROM deduction_onetime_headers 
INNER JOIN deduction_onetime_details ON deduction_onetime_headers.id = deduction_onetime_details.header_id
WHERE doc_status = 'POSTED'
AND deduction_onetime_headers.period_id = 1;

division_id
dept_id
location_id


SELECT employee_names_vw.employee_name,payrollregister_unposted.* FROM payrollregister_unposted 
INNER JOIN employees ON employees.biometric_id = payrollregister_unposted.biometric_id
INNER JOIN employee_names_vw ON employee_names_vw.biometric_id = payrollregister_unposted.biometric_id
WHERE 


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