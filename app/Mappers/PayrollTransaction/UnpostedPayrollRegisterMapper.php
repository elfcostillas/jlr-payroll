<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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

    public function unpostedPeriodList($type,$position)
    {
        switch($position->job_title_id){
            case 11 : case 10 : case 105 : case 15 :
                   
                    if($type=='semi'){
                        $result = $this->model->select(DB::raw("id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS period_range"))
                        ->from('payroll_period')
                        ->whereNotIn('id',DB::table('posting_info')->where('trans_type','non-confi')->distinct()->pluck('period_id'))
                        //->join('payroll_period','payroll_period.id','=','payrollregister_unposted.period_id')
                        ->distinct();
                    }
                break;
            
            case 6: case 60 :

                break;
            
            default : 
                    return response()->json(['error' => 'Unauthorized Access.']);
                break;
        }   

       
       
        return $result->get();
    }

    public function getEmployeeWithDTR($period_id,$emp_level)
    {
        $user = Auth::user();
        $result = $this->model->select(DB::raw("
                        IF(emp_level>=5 || ISNULL(emp_level),'non-confi','confi') AS emp_level,
                        payroll_period.id AS period_id,
                        employees.biometric_id,
                        lastname,
                        firstname,
                        middlename,
                        suffixname,
                        basic_salary,
                        date_hired,
                        is_daily,
                        deduct_phic,
                        deduct_sss,
                        pay_type,
                        late AS late,
                        late_eq AS late_eq,
                        under_time AS under_time,
                        over_time AS reg_ot,
                        night_diff AS reg_nd,
                        night_diff_ot AS reg_ndot,
                        ndays AS ndays,
                        hdmf_contri,
                        monthly_allowance,
                        daily_allowance,
                        restday_hrs as rd_hrs,
                        restday_ot as rd_ot,
                        restday_nd as rd_nd,
                        restday_ndot as rd_ndot,

                        reghol_pay as leghol_count,
                        reghol_hrs as leghol_hrs,
                        reghol_ot as leghol_ot,
                        reghol_rd as leghol_rd,
                        reghol_rdot as leghol_rdot,
                        reghol_nd as leghol_nd,
                        reghol_rdnd as leghol_rdnd,
                        reghol_ndot as leghol_ndot,
                        reghol_rdndot as leghol_rdndot,

                        sphol_pay as sphol_count,
                        sphol_hrs as sphol_hrs,
                        sphol_ot as sphol_ot,
                        sphol_rd as sphol_rd,
                        sphol_rdot as sphol_rdot,
                        sphol_nd as sphol_nd,
                        sphol_rdnd as sphol_rdnd,
                        sphol_ndot as sphol_ndot,
                        sphol_rdndot as sphol_rdndot,

                        dblhol_pay as dblhol_count,
                        dblhol_hrs as dblhol_hrs,
                        dblhol_ot as dblhol_ot,
                        dblhol_rd as dblhol_rd,
                        dblhol_rdot as dblhol_rdot,
                        dblhol_rdnd as dblhol_rdnd,
                        dblhol_nd as dblhol_nd,
                        dblhol_ndot as dblhol_ndot,
                        dblhol_rdndot as dblhol_rdndot
                        "))
                    ->from('edtr_totals')
                    ->join('payroll_period',function($join){
                        $join->whereRaw('edtr_totals.period_id = payroll_period.id');
                    })
                    ->join('employees','edtr_totals.biometric_id','=','employees.biometric_id')
                    ->where('payroll_period.id','=',$period_id)
                    ->whereIn('pay_type',[1,2])
                    ->where('exit_status',1)
                    ->where('edtr_totals.ndays','>',0)
                  
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
                                
            if($emp_level=='non-confi')
            {
                $result = $result->where('emp_level','>=',5);
            }
            else
            {
                $result = $result->where('emp_level','<',5);
            }
           

        return $result->get();
    }

    public function getEmployeeWithDTRW($period_id,$emp_level)
    {

        $user = Auth::user();
        $result = $this->model->select(DB::raw("
                        IF(emp_level>=5 || ISNULL(emp_level),'non-confi','confi') AS emp_level,
                        payroll_period.id AS period_id,
                        employees.biometric_id,
                        lastname,
                        firstname,
                        middlename,
                        suffixname,
                        basic_salary,
                        date_hired,
                        is_daily,
                        deduct_phic,
                        deduct_sss,
                        pay_type,
                        SUM(late) AS late,
                        SUM(late_eq) AS late_eq,
                        SUM(under_time) AS under_time,
                        SUM(over_time) AS reg_ot,
                        SUM(night_diff) AS reg_nd,
                        SUM(night_diff_ot) AS reg_ndot,
                        SUM(ndays) AS ndays,
                        hdmf_contri,
                        monthly_allowance,
                        daily_allowance,
                        sum(restday_hrs) as rd_hrs,
                        sum(restday_ot) as rd_ot,
                        sum(restday_nd) as rd_nd,
                        sum(restday_ndot) as rd_ndot,

                        sum(reghol_pay) as leghol_count,
                        sum(reghol_hrs) as leghol_hrs,
                        sum(reghol_ot) as leghol_ot,
                        sum(reghol_rd) as leghol_rd,
                        sum(reghol_rdot) as leghol_rdot,
                        sum(reghol_nd) as leghol_nd,
                        sum(reghol_rdnd) as leghol_rdnd,
                        sum(reghol_ndot) as leghol_ndot,
                        sum(reghol_rdndot) as leghol_rdndot,

                        sum(sphol_pay) as sphol_count,
                        sum(sphol_hrs) as sphol_hrs,
                        sum(sphol_ot) as sphol_ot,
                        sum(sphol_rd) as sphol_rd,
                        sum(sphol_rdot) as sphol_rdot,
                        sum(sphol_nd) as sphol_nd,
                        sum(sphol_rdnd) as sphol_rdnd,
                        sum(sphol_ndot) as sphol_ndot,
                        sum(sphol_rdndot) as sphol_rdndot,

                        sum(dblhol_pay) as dblhol_count,
                        sum(dblhol_hrs) as dblhol_hrs,
                        sum(dblhol_ot) as dblhol_ot,
                        sum(dblhol_rd) as dblhol_rd,
                        sum(dblhol_rdot) as dblhol_rdot,
                        sum(dblhol_rdnd) as dblhol_rdnd,
                        sum(dblhol_nd) as dblhol_nd,
                        sum(dblhol_ndot) as dblhol_ndot,
                        sum(dblhol_rdndot) as dblhol_rdndot
                        "))
                    ->from('edtr')
                    ->join('payroll_period_weekly',function($join){
                        $join->whereRaw('edtr.dtr_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
                    })
                    ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                    ->where('payroll_period.id','=',$period_id)
                    ->whereIn('pay_type',[3])
                    ->where('exit_status',1)
                    ->whereNotNull('time_in')
                    ->whereNotNull('time_out')
                    ->groupBy(DB::raw('
                                payroll_period_weekly.id,
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
                                
            if($emp_level=='non-confi')
            {
                $result = $result->where('emp_level','>=',5);
            }
            else
            {
                $result = $result->where('emp_level','<',5);
            }
           

        return $result->get();
    }

    public function reInsert($period_id,$employees,$emp_level){
        $user = Auth::user();
        $blank = [];
        
        $this->model->where('period_id',$period_id)
        ->where('user_id',$user->id)
        ->where('emp_level',$emp_level)
        ->delete();

        $info = array(
            'user_id' => $user->id,
            'generated_on' => now()
        );

        foreach($employees as $employee)
        {   
            array_push($blank,array_merge($employee->toColumnArray(),$info));

        }

        $result = DB::table('payrollregister_unposted_s')->insertOrIgnore($blank);

        return $result;
    }

    public function runGovLoans($period,$biometric_ids,$user_id,$emp_level)
    {
        //dd($period->id);

        DB::table('unposted_loans')->where('period_id',$period->id)->where('user_id',$user_id)->where('emp_level',$emp_level)->delete();
        $tmp_loan = [];
        $loans = $this->model->select(DB::raw("deduction_gov_loans.id,
                                                deduction_gov_loans.biometric_id,
                                                deduction_gov_loans.deduction_type,
                                                SUM(IFNULL(posted_loans.amount,0)) AS paid,
                                                total_amount-SUM(IFNULL(posted_loans.amount,0)) AS balance,
                                                IF(total_amount-SUM(IFNULL(posted_loans.amount,0))<ammortization,total_amount-SUM(IFNULL(posted_loans.amount,0)),ammortization) AS ammortization"))
                            ->from("deduction_gov_loans")
                            ->leftJoin('posted_loans','deduction_gov_loans.id','=','posted_loans.deduction_id')
                            ->join('loan_types','loan_types.id','=','deduction_gov_loans.deduction_type')
                            ->whereRaw("is_stopped = 'N'")
                            ->whereIn('loan_types.sched',[$period->period_type,3])
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
                'emp_level' => $emp_level,
                'user_id' => $user_id
            ];

            array_push($tmp_loan,$tmp);
        }

        DB::table('unposted_loans')->insertOrIgnore($tmp_loan);

    }

    public function runInstallments($period,$biometric_ids,$user_id,$emp_level)
    {
        //dd($period->id)

        DB::table('unposted_installments')->where('period_id',$period->id)->where('user_id',$user_id)->where('emp_level',$emp_level)->delete();
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
                            ->where('deduction_installments.period_id','<=',$period->id)
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
                'emp_level' => $emp_level,
                'user_id' => $user_id
            ];

            array_push($tmp_loan,$tmp);
        }

        DB::table('unposted_installments')->insertOrIgnore($tmp_loan);

    }

    public function runOneTimeDeduction($period,$biometric_ids,$user_id,$emp_level)
    {
        DB::table('unposted_onetime_deductions')->where('period_id',$period->id)->where('user_id',$user_id)->where('emp_level',$emp_level)->delete();
        
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
                'emp_level' => $emp_level,
                'user_id' => $user_id
            ];

            array_push($tmp_loan,$tmp);
        }

        DB::table('unposted_onetime_deductions')->insertOrIgnore($tmp_loan);
    }

    public function runFixedDeduction($period,$biometric_ids,$user_id,$emp_level)
    {
        DB::table('unposted_fixed_deductions')->where('period_id',$period->id)->where('user_id',$user_id)->where('emp_level',$emp_level)->delete();
        $tmp_loan = [];

        $fixed = $this->model->select(DB::raw("deduction_fixed.id,biometric_id,deduction_fixed.deduction_type,amount"))
                ->from('deduction_fixed')
                ->join('deduction_types','deduction_types.id','=','deduction_fixed.deduction_type')
                ->whereIn('deduction_types.deduction_sched',[$period->period_type,3])
                ->whereIn('deduction_fixed.biometric_id',$biometric_ids)
                ->where([
                    ['is_stopped','=','N'],
                   // ['deduction_fixed.period_id','<=',$period->id]
                ])->get();
        
        foreach($fixed as $loan)
        {
            $tmp = [
                'period_id' => $period->id,
                'biometric_id' => $loan->biometric_id,
                'deduction_type' => $loan->deduction_type,
                'amount' => $loan->amount,
                'deduction_id' => $loan->id,
                'emp_level' => $emp_level,
                'user_id' => $user_id
            ];

            array_push($tmp_loan,$tmp);
        }

        DB::table('unposted_fixed_deductions')->insertOrIgnore($tmp_loan);
    }

    public function getPprocessed($period,$emp_level){
        // $locations = $this->model->select('locations.id','location_name')
        //             ->from("employees")
        //             ->join('locations','locations.id','=','employees.location_id')
        //             ->where('exit_status','=','1')
        //             ->distinct()->get();
            // if($locations){
            //     foreach($locations as $location)
            //     {
                    //SELECT DISTINCT division_id FROM employees WHERE exit_status = 1 AND location_id=1;
                    $divisions = $this->getDivisions(0);

                    foreach($divisions as $division){
                        $departments = $this->getDepartments(0,$division);

                        foreach($departments as $department){
                            $employees = $this->getEmployees(0,$division,$department,$period,$emp_level);
                            $department->employees =  $employees;
                        }

                        $division->departments = $departments;
                    }

                    //$location->divisions = $divisions;
            //     }
            // }

        return $divisions;
    }

    public function getHeaders($period)
    {
        if(is_object($period)){
            $period = $period->id;
        }else {
            $period = $period;
        }
        $result = $this->model->select(DB::raw("SUM(reg_ot) AS reg_ot, 
        SUM(reg_ot_amount) AS reg_ot_amount,
        SUM(reg_nd) AS reg_nd,
        SUM(reg_nd_amount) AS reg_nd_amount,
        SUM(reg_ndot) AS reg_ndot,
        SUM(reg_ndot_amount) AS reg_ndot_amount,
        SUM(rd_hrs) AS rd_hrs,
        SUM(rd_hrs_amount) AS rd_hrs_amount,
        SUM(rd_ot) AS rd_ot,
        SUM(rd_ot_amount) AS rd_ot_amount,
        SUM(rd_nd) AS rd_nd,
        SUM(rd_nd_amount) AS rd_nd_amount,
        SUM(rd_ndot) AS rd_ndot,
        SUM(rd_ndot_amount) AS rd_ndot_amount,
        SUM(leghol_count) AS leghol_count,
        SUM(leghol_count_amount) AS leghol_count_amount,
        SUM(leghol_hrs) AS leghol_hrs,
        SUM(leghol_hrs_amount) AS leghol_hrs_amount,
        SUM(leghol_ot) AS leghol_ot,
        SUM(leghol_ot_amount) AS leghol_ot_amount,
        SUM(leghol_nd) AS leghol_nd,
        SUM(leghol_nd_amount) AS leghol_nd_amount,
        SUM(leghol_rd) AS leghol_rd,
        SUM(leghol_rd_amount) AS leghol_rd_amount,
        SUM(leghol_rdot) AS leghol_rdot,
        SUM(leghol_rdot_amount) AS leghol_rdot_amount,
        SUM(leghol_ndot) AS leghol_ndot,
        SUM(leghol_ndot_amount) AS leghol_ndot_amount,
        SUM(leghol_rdnd) AS leghol_rdnd,
        SUM(leghol_rdnd_amount) AS leghol_rdnd_amount,
        SUM(leghol_rdndot) AS leghol_rdndot,
        SUM(leghol_rdndot_amount) AS leghol_rdndot_amount,
        SUM(sphol_count) AS sphol_count,
        SUM(sphol_count_amount) AS sphol_count_amount,
        SUM(sphol_hrs) AS sphol_hrs,
        SUM(sphol_hrs_amount) AS sphol_hrs_amount,
        SUM(sphol_ot) AS sphol_ot,
        SUM(sphol_ot_amount) AS sphol_ot_amount,
        SUM(sphol_nd) AS sphol_nd,
        SUM(sphol_nd_amount) AS sphol_nd_amount,
        SUM(sphol_rd) AS sphol_rd,
        SUM(sphol_rd_amount) AS sphol_rd_amount,
        SUM(sphol_rdot) AS sphol_rdot,
        SUM(sphol_rdot_amount) AS sphol_rdot_amount,
        SUM(sphol_ndot) AS sphol_ndot,
        SUM(sphol_ndot_amount) AS sphol_ndot_amount,
        SUM(sphol_rdnd) AS sphol_rdnd,
        SUM(sphol_rdnd_amount) AS sphol_rdnd_amount,
        SUM(sphol_rdndot) AS sphol_rdndot,
        SUM(sphol_rdndot_amount) AS sphol_rdndot_amount,
        SUM(dblhol_count) AS dblhol_count,
        SUM(dblhol_count_amount) AS dblhol_count_amount,
        SUM(dblhol_hrs) AS dblhol_hrs,
        SUM(dblhol_hrs_amount) AS dblhol_hrs_amount,
        SUM(dblhol_ot) AS dblhol_ot,
        SUM(dblhol_ot_amount) AS dblhol_ot_amount,
        SUM(dblhol_nd) AS dblhol_nd,
        SUM(dblhol_nd_amount) AS dblhol_nd_amount,
        SUM(dblhol_rd) AS dblhol_rd,
        SUM(dblhol_rd_amount) AS dblhol_rd_amount,
        SUM(dblhol_rdot) AS dblhol_rdot,
        SUM(dblhol_rdot_amount) AS dblhol_rdot_amount,
        SUM(dblhol_ndot) AS dblhol_ndot,
        SUM(dblhol_ndot_amount) AS dblhol_ndot_amount,
        SUM(dblhol_rdnd) AS dblhol_rdnd,
        SUM(dblhol_rdnd_amount) AS dblhol_rdnd_amount,
        SUM(dblhol_rdndot) AS dblhol_rdndot,
        SUM(dblhol_rdndot_amount) AS dblhol_rdndot_amount"))
        ->where('period_id',$period);


        return $result->first();
    }

    public function getDivisions($location)
    {
        $divisions = $this->model->select('divisions.id','div_name')
                        ->from("employees")
                        ->join('divisions','divisions.id','=','employees.division_id')
                        ->where('exit_status','=','1')
                        //->where('location_id',$location->id)
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
                        ->where('division_id',$division->id)
                        ->distinct()->get();
            // ->where('location_id',$location->id)

        return $departments;
    }

    public function buildHeader($period)
    {

    }

    public function getEmployees($location,$division,$department,$period,$emp_level) /* Earnings and Deductions here */
    {   
        $user = Auth::user();
        $employees = $this->model->select(DB::raw("employee_names_vw.employee_name,payrollregister_unposted_s.*,employees.pay_type,employees.monthly_allowance as mallowance,
        employees.daily_allowance as dallowance,IF(employees.pay_type=1,employees.basic_salary/2,employees.basic_salary) AS basicpay"))
                                ->from("payrollregister_unposted_s")
                                ->join("employees",'employees.biometric_id','=','payrollregister_unposted_s.biometric_id')
                                ->join("employee_names_vw",'employee_names_vw.biometric_id','=','payrollregister_unposted_s.biometric_id')
                                ->where([
                                    ['division_id','=',$division->id],
                                    ['dept_id','=',$department->id],
                                    //['location_id','=',$location->id],
                                    ['payrollregister_unposted_s.period_id','=',$period->id],
                                    ['user_id','=',$user->id],
                                    ['payrollregister_unposted_s.emp_level','=',$emp_level]
                                ])->orderBy('employees.pay_type','DESC')->orderBy('employee_names_vw.employee_name','ASC')->get();
        foreach($employees as $employee)
        {   
            $deductions = $this->getDeductions($employee->biometric_id,$period->id);
           
            //$employee->earnings = $this->earnings($employee->biometric_id,$period->id);
            //$employee->basicEarnings = $this->basicEarnings($employee,$period);
            $employee->otherEarnings = $this->otherEarnings($employee->biometric_id,$period->id);
            //$otherEarnings = $this->otherEarnings($employee->biometric_id,$period->id);
           
            $employee->deductions = $deductions;
            $employee->gov_deductions = collect(
                [
                    'SSS Premium' => $employee->sss_prem,
                    'SSS WISP' => $employee->sss_wisp,
                    'PhilHealt Premium' => $employee->phil_prem,
                    'PAG IBIG Contri' => $employee->hdmf_contri,
                    'WTAx' => $employee->wtax,
                ]
            );
            $employee->loans = $this->getGovLoans($employee->biometric_id,$period->id);
            //$employee->absences = $this->getAbsences($employee,$period->id);
            // if($employee->biometric_id==847){
            //     dd($employee);
            // }   
        }
                           
        return $employees;
    }

    public function otherEarnings($biometric_id,$period_id)
    {
        /*
         
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
        */

        $earning_array = [];

        $others = DB::table('unposted_other_compensations')->select('compensation_type','amount')->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]]);
        $fixed = DB::table('unposted_fixed_compensations')->select('compensation_type','amount')->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]])
        ->unionAll($others);

        $earnings = DB::table('compensation_types')
                    ->select('description','compensation_type','amount')
                    ->joinSub($fixed,'earnings',function($join){
                        $join->on('earnings.compensation_type','=','compensation_types.id');
                    })->orderBy('compensation_type')->get();
        
        foreach($earnings as $earn){
            $earning_array[$earn->compensation_type] = $earn->amount;
        }
        
        return $earning_array;
       
    }

    public function runFixedCompensation($period,$biometric_ids,$user_id,$emp_level)
    {
        //unposted_fixed_compensations
        //unposted_other_compensations
        /*
        SELECT period_id,compensation_type,biometric_id,total_amount,header_id 
        FROM compensation_fixed_headers header
INNER JOIN compensation_fixed_details details ON header.id = details.header_id
WHERE period_id = 1 AND total_amount > 0;*/


        DB::table('unposted_fixed_compensations')->where('period_id',$period->id)->where('user_id',$user_id)->where('emp_level',$emp_level)->delete();
        $tmp_earn = [];

        $fixed = $this->model->select(DB::raw("period_id,compensation_type,biometric_id,total_amount,id"))
                ->from('compensation_fixed_headers as header')
                ->join('compensation_fixed_details','header.id','=','header_id')
                ->whereIn('compensation_fixed_details.biometric_id',$biometric_ids)
                ->where([
                  ['period_id','=',$period->id],
                  ['total_amount','>',0]
                ])->get();
        
        foreach($fixed as $earn)
        {
            $tmp = [
                'period_id' => $period->id,
                'biometric_id' => $earn->biometric_id,
                'compensation_type' => $earn->compensation_type,
                'amount' => $earn->total_amount,
                'deduction_id' => $earn->id,
                'emp_level' => $emp_level,
                'user_id' => $user_id
            ];

            array_push($tmp_earn,$tmp);
        }

        //dd($fixed->toSql(),$fixed->getBindings());

        DB::table('unposted_fixed_compensations')->insertOrIgnore($tmp_earn);
    }

    public function runOtherCompensation($period,$biometric_ids,$user_id,$emp_level)
    {
        //unposted_fixed_compensations
        //unposted_other_compensations

        DB::table('unposted_other_compensations')->where('period_id',$period->id)->where('user_id',$user_id)->where('emp_level',$emp_level)->delete();
        $tmp_earn = [];

        $fixed = $this->model->select(DB::raw("period_id,compensation_type,biometric_id,total_amount,id"))
                ->from('compensation_other_headers as header')
                ->join('compensation_other_details','header.id','=','header_id')
                ->whereIn('compensation_other_details.biometric_id',$biometric_ids)
                ->where([
                  ['period_id','=',$period->id],
                  ['total_amount','>',0]
                ])->get();
        
        foreach($fixed as $earn)
        {
            $tmp = [
                'period_id' => $period->id,
                'biometric_id' => $earn->biometric_id,
                'compensation_type' => $earn->compensation_type,
                'amount' => $earn->total_amount,
                'deduction_id' => $earn->id,
                'emp_level' => $emp_level,
                'user_id' => $user_id
            ];

            array_push($tmp_earn,$tmp);
        }

        DB::table('unposted_other_compensations')->insertOrIgnore($tmp_earn);
    }

    public function basicEarnings($employee,$period)
    {   
    
        $earnings=[];
        
        array_push($earnings, (object) [
            'name' => 'Basic Salary (Reg Hours)',
            'hours'=> ($employee->pay_type==1) ? $period->man_hours : $employee->ndays * 8 ,
            'amount' => $employee->basic_pay
        ]);

        if($employee->overtime>0){
            array_push($earnings, (object) [
                'name' => 'OT Regular',
                'hours'=> $employee->overtime,
                'amount' => $employee->overtime_amount
            ]);
        }

        if($employee->sh_ot>0)
        {
            array_push($earnings, (object) [
                'name' => 'OT Special Holiday',
                'hours'=> $employee->sh_ot,
                'amount' => $employee->sh_ot_amount
            ]);
        }

        if($employee->semi_monthly_allowance>0){
            array_push($earnings, (object) [
                'name' => 'Monthly Allowance',
                'hours'=> null,
                'amount' => $employee->semi_monthly_allowance
            ]);
        }

        if($employee->daily_allowance>0){
            array_push($earnings, (object) [
                'name' => 'Daily Allowance',
                'hours'=> null,
                'amount' => $employee->daily_allowance
            ]);
        }

        if($employee->vl_wpay>0 && $employee->pay_type!=2){
            array_push($earnings, (object) [
                'name' => 'VL w/ Pay',
                'hours'=> $employee->vl_wpay,
                'amount' => $employee->vl_wpay_amount,
            ]);
        }

        if($employee->sl_wpay>0 && $employee->pay_type!=2){
            array_push($earnings, (object) [
                'name' => 'SL w/ Pay',
                'hours'=> $employee->sl_wpay,
                'amount' => $employee->sl_wpay_amount,
            ]);
        }

        if($employee->bl_wpay>0 && $employee->pay_type!=2){
            array_push($earnings, (object) [
                'name' => 'Birthday Leave',
                'hours'=> $employee->bl_wpay,
                'amount' => $employee->bl_wpay_amount,
            ]);
        }

        return collect($earnings);
    }

    public function getAbsences($employee,$period_id){
       
        $absences = [];

        if($employee->absences > 0){
            array_push($absences, (object) [
                'name' => 'Tardiness/Absences',
                'hours'=> $employee->absences,
                'amount' => $employee->absences_amount,
            ]);
        }

        return collect($absences);
    }
    

    public function getDeductions($biometric_id,$period_id)
    {   
        $ded_array = [];

        $onetime = DB::table("unposted_onetime_deductions")->select('deduction_type','amount')->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]]);
        $fixed = DB::table("unposted_fixed_deductions")->select('deduction_type','amount')->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]]);
        $install = DB::table("unposted_installments")->select('deduction_type','amount')->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]])
                    ->unionAll($onetime)->unionAll($fixed);
        $deductions = DB::table('deduction_types')
                        ->select('description','deduction_type','amount')
                        ->joinSub($install,'deductions',function($join){
                            $join->on('deductions.deduction_type','=','deduction_types.id');
                        })->orderBy('deduction_type')->get();

        //return $deductions;
        foreach($deductions as $deduction){
            //$ded_array[$deduction->deduction_type] 
            if(array_key_exists($deduction->deduction_type,$ded_array)){
                $ded_array[$deduction->deduction_type] += $deduction->amount;
            }else{
                $ded_array[$deduction->deduction_type] = 0;
                $ded_array[$deduction->deduction_type] += $deduction->amount;
            }
        }

        return $ded_array;

        
    }

    public function getGovLoans($biometric_id,$period_id)
    {
        /*
        SELECT description,amount FROM unposted_loans INNER JOIN loan_types ON deduction_type = loan_types.id
        WHERE period_id = 1 AND biometric_id = 847
        */
        $govLoan = [];
        $loan = DB::table('unposted_loans')->select('id','description','amount')
        ->join('loan_types','deduction_type','=','loan_types.id')
        ->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]])
        ->orderBy('deduction_type')->get();

        foreach($loan as $l)
        {
            if(array_key_exists($l->id,$govLoan)){
                $govLoan[$l->id] += $l->amount;
            }else{
                $govLoan[$l->id] = 0;
                $govLoan[$l->id] += $l->amount;
            }
        }
       
        return $govLoan;
        
        //return $loan;
    }

    public function semiEmployeeNoPayroll($period_id)
    {
        $empInPayroll = $this->model->select('biometric_id')->from('payrollregister_unposted_s')->where('period_id',$period_id);

        $result = $this->model->select('employees.biometric_id','employee_name','div_code','dept_code','job_title_name')
                                ->from('employees')
                                ->join('employee_names_vw','employees.biometric_id','=','employee_names_vw.biometric_id')
                                ->leftJoin('departments','departments.id','=','employees.dept_id')
                                ->leftJoin('divisions','divisions.id','=','employees.division_id')
                                ->leftJoin('job_titles','job_titles.id','=','employees.job_title_id')
                                ->whereNotIn('employees.biometric_id',$empInPayroll)
                                ->where('employees.exit_status',1)
                                ->where('employees.pay_type','!=',3);
                                
        return $result->get();
    }   

    public function getFiledLeaves($biometric_id,$period_id)
    {
        $result = $this->model->select()->from('filed_leaves_vw')->leftJoin('payroll_period',function($join){
            // $join->whereBetween('leave_date',['payroll_period.date_from','payroll_period.date_to']);
                $join->whereRaw('leave_date between payroll_period.date_from and payroll_period.date_to');
        })
        ->where([
            ['biometric_id',$biometric_id],
            ['payroll_period.id',$period_id]
        ])->get();

        return $result;
    }

    public function getColHeaders()
    {   
        //SELECT var_name,col_label FROM payreg_header;
        $result = $this->model->select('var_name','col_label')->from('payreg_header');
        return $result->get();

    }

    public function getDeductionLabel($period)
    {
        //dd($period->id);
        $qry = "SELECT DISTINCT deduction_type FROM (
            SELECT * FROM unposted_fixed_deductions
            UNION ALL 
            SELECT * FROM unposted_installments
            UNION ALL
            SELECT * FROM unposted_onetime_deductions
        ) AS deduction_types where period_id = '".$period->id."';";

        $result = DB::select($qry);
            //dd(collect($deductions)->pluck('deduction_id'))
        
            //dd(collect($result)->pluck('deduction_id'));
        //SELECT id,description FROM deduction_types WHERE id IN ();

        $dedLabel = $this->model->select('id','description')->from('deduction_types')->whereIn('id',collect($result)->pluck('deduction_type'));

        return $dedLabel->get();
    }

    public function getGovLoanLabel($period){
        //SELECT DISTINCT deduction_type FROM unposted_loans WHERE period_id = 2
        $qry = "SELECT DISTINCT deduction_type FROM unposted_loans WHERE period_id = ".$period->id;

        $result = DB::select($qry); //SELECT id,loan_code FROM loan_types WHERE id IN ();
        
        $govLabel = $this->model->select('id','description')->from('loan_types')->whereIn('id',collect($result)->pluck('deduction_type'));

        //dd($govLabel->get());
        return $govLabel->get();
    }

    public function getUsedCompensation($period)
    {
        $qry = "SELECT DISTINCT compensation_type FROM 
        (
           SELECT compensation_type FROM unposted_fixed_compensations WHERE period_id = ".$period->id."
           UNION ALL
           SELECT compensation_type FROM unposted_other_compensations WHERE period_id = ".$period->id."
        ) AS compensation_types ";
        $result = DB::select($qry);
        //return $qry; SELECT id,description FROM compensation_types;
        $compenLabel = $this->model->select('id','description')->from('compensation_types')
                        ->whereIn('id',collect($result)->pluck('compensation_type'));

        return $compenLabel->get();
    }


    public function postNonConfi($period_id)
    {
        $user = Auth::user();
        $tmp = [];
        $result = $this->model->select(DB::raw("payrollregister_unposted_s.*"))
                    ->from('payrollregister_unposted_s')
                    ->join('employees','payrollregister_unposted_s.biometric_id','=','employees.biometric_id')
                    ->where('payrollregister_unposted_s.period_id',$period_id)
                    //->where('emp_level','>=',5)
                    ->where('payrollregister_unposted_s.emp_level','=','non-confi')
                    ->where('user_id','=',$user->id)
                    ->get();
        
        foreach($result  as $line){
            $data = $line->toArray();
            unset($data['line_id']);

            array_push($tmp,$data);
        }

        $tables = [
            'unposted_fixed_deductions' => 'posted_fixed_deductions',
            'unposted_installments' => 'posted_installments',
            'unposted_onetime_deductions' => 'posted_onetime_deductions',
            'unposted_loans' => 'posted_loans'
        ];

        $comp_tables = [
            'unposted_fixed_compensations' => 'posted_fixed_compensations',
            'unposted_other_compensations' => 'posted_other_compensations'
        ];

        DB::beginTransaction();
        
        $insertCount = DB::table('payrollregister_posted_s')->insertOrIgnore($tmp);
        //$insertCount = DB::table('payrollregister_posted_s')->insert($tmp);
       
        if($result->count() == $insertCount){
            foreach($tables as $unposted => $posted){
                $loan_array = [];
                $loan_type = $this->model->select('period_id','employees.biometric_id','deduction_type','amount','deduction_id',$unposted.'.emp_level','user_id')
                    ->from($unposted)
                    ->join('employees',$unposted.'.biometric_id','=','employees.biometric_id')
                    ->join('deduction_types','deduction_types.id','=','deduction_type')
                    ->where('period_id',$period_id)
                    ->where('employees.emp_level','>=',5)
                    ->where('user_id','=',$user->id)
                    ->get();
                
                foreach($loan_type as $loans){
                    $tmploan = $loans->toArray();
                    unset($tmploan['line_id']);
                   
                    array_push($loan_array,$tmploan);
                }
    
                $detailCount = DB::table($posted)->insertOrIgnore($loan_array);
                if($loan_type->count() != $detailCount){
                   
                    DB::rollBack();
                    return array('error'=>'Posting on table '.$unposted.'.');
                    
                }
            }

            foreach($comp_tables as $unposted => $posted){
                $comp_array = [];
                $comp_type = $this->model->select('period_id','employees.biometric_id','compensation_type','amount','deduction_id',$unposted.'.emp_level','user_id')
                ->from($unposted)
                ->join('compensation_types','compensation_types.id','=','compensation_type')
                ->join('employees',$unposted.'.biometric_id','=','employees.biometric_id')
                ->where('period_id',$period_id)
                // ->where('employees.emp_level','>=',5)
                ->where($unposted.'.emp_level','=','non-confi')
                ->where('user_id','=',$user->id)
                ->get();

                foreach($comp_type as $compensation){
                    $tmpcomp = $compensation->toArray();
                    unset($tmpcomp['line_id']);

                    array_push($comp_array,$tmpcomp);
                }

                $detailCount = DB::table($posted)->insertOrIgnore($comp_array);
                if($comp_type->count() != $detailCount){
                    dd($comp_type->count(),$detailCount);
                    DB::rollBack();
                    return array('error'=>'Posting on table '.$unposted.'.');
                    
                }
            }
    
            DB::commit();

            DB::table('posting_info')->insert([
                'period_id' => $period_id,
                'trans_type' => 'non-confi',
                'posted_by' => $user->id,
                'posted_on' => now(),
            ]);

            return array('success'=>'Payroll Period posted successfully.');
        }else{

            
            DB::rollBack();
            return array('error'=>'Posting on table payrollregister_unposted_s.');
            //return response()->json(['error' => 'Posting on table payrollregister_unposted_s.']);
        }
        //dd($result->count(),$insertCount);

        /*

        TRUNCATE posted_fixed_deductions;
        TRUNCATE posted_installments;
        TRUNCATE posted_loans;
        TRUNCATE posted_onetime_deductions;
        TRUNCATE payrollregister_posted_s;
        TRUNCATE posted_fixed_compensations;
        TRUNCATE posted_other_compensations;
        TRUNCATE posting_info;
        


        SELECT period_id,biometric_id,deduction_type,amount,deduction_id FROM unposted_fixed_deductions WHERE period_id = 1;
        SELECT period_id,biometric_id,deduction_type,amount,deduction_id FROM unposted_installments WHERE period_id = 1;
        SELECT period_id,biometric_id,deduction_type,amount,deduction_id FROM unposted_onetime_deductions WHERE period_id = 1;
        SELECT period_id,biometric_id,deduction_type,amount,deduction_id FROM unposted_loans WHERE period_id = 1;
                
        */

    }

    public function getHolidayCounts($biometric_id,$period_id)
    {
        $qry = "SELECT holiday_type FROM holidays INNER JOIN holiday_location ON holidays.id = holiday_id
        INNER JOIN payroll_period ON holiday_date BETWEEN date_from AND date_to
        INNER JOIN employees ON employees.location_id = holiday_location.location_id
        INNER JOIN holiday_types ON holiday_types.id = holiday_type
        WHERE payroll_period.id = $period_id
        AND biometric_id = $biometric_id";

        $result = DB::select($qry);

        return $result;
    }



}


/*

SELECT employees.biometric_id,employee_name FROM employees 
JOIN employee_names_vw ON employees.biometric_id = employee_names_vw.biometric_id
WHERE employees.biometric_id NOT IN (SELECT biometric_id FROM payrollregister_unposted WHERE period_id = 1)
AND employees.exit_status =1;



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