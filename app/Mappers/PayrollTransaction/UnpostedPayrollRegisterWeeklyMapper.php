<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UnpostedPayrollRegisterWeeklyMapper extends AbstractMapper {
    
    protected $modelClassName = 'App\Models\PayrollTransaction\UnpostedPayrollRegisterWeekly';
    protected $rules = [
    	
    ];

    public function compute($period_id)
    {
        /*
        $result = DB::select("SELECT m.biometric_id,m.period_id,e.basic_salary,SUM(reg_day) AS n_days,SUM(overtime_hrs) n_ot,name_vw.employee_name,IFNULL(earnings,0.00) earnings,IFNULL(deductions,0.00) deductions
        FROM manual_dtr m 
        INNER JOIN manual_dtr_details d ON m.id = d.header_id
        INNER JOIN employee_names_vw AS name_vw ON m.biometric_id = name_vw.biometric_id
        INNER JOIN employees AS e ON m.biometric_id = e.biometric_id
        LEFT JOIN unposted_weekly_compensation AS comp ON m.biometric_id = comp.biometric_id AND m.period_id = comp.period_id
        WHERE m.period_id = $period_id
        GROUP BY m.biometric_id,m.period_id,e.basic_salary,name_vw.employee_name,e.lastname,e.firstname,earnings,deductions
        ORDER BY e.lastname,e.firstname;");
        */
        $result = DB::select("SELECT m.biometric_id,o.id AS period_id,e.basic_salary,
        SUM(ndays) AS n_days,
        SUM(over_time) n_ot,name_vw.employee_name,IFNULL(earnings,0.00) earnings,IFNULL(deductions,0.00) deductions,IFNULL(retro_pay,0.00) retro_pay,
        SUM(IF(IFNULL(sphol_hrs,0)>0,1,0)) sp,
        SUM(IFNULL(sphol_hrs,0)) AS sphol_hrs,
        -- SUM(IF(IFNULL(reghol_hrs,0)>0,1,0)) AS reghol,
        SUM(IFNULL(reghol_pay,0)) AS reghol,
        SUM(IFNULL(reghol_hrs,0)) AS reghol_hrs,
        SUM(IFNULL(sphol_ot,0)) AS sphol_ot,
        SUM(IFNULL(reghol_ot,0)) AS reghol_ot,
        SUM(IFNULL(restday_hrs,0)) AS restday_hrs,
        SUM(IFNULL(restday_ot,0)) AS restday_ot
        FROM edtr m
        INNER JOIN payroll_period_weekly o ON m.dtr_date BETWEEN o.date_from AND o.date_to
        INNER JOIN employee_names_vw AS name_vw ON m.biometric_id = name_vw.biometric_id
        INNER JOIN employees AS e ON m.biometric_id = e.biometric_id
        LEFT JOIN unposted_weekly_compensation AS comp ON m.biometric_id = comp.biometric_id AND o.id = comp.period_id
        WHERE o.id =  $period_id
        AND e.exit_status = 1
        AND e.pay_type = 3
        GROUP BY m.biometric_id,o.id,e.basic_salary,name_vw.employee_name,e.lastname,e.firstname,earnings,deductions
        ORDER BY e.lastname,e.firstname;");

        $tmp_payreg = [];

        foreach($result as $line)
        {
            if($line->n_days>0){
                $hr_rate =  ($line->basic_salary/8);
                $ot_amount = round($line->n_ot * $hr_rate ,2);
                $basic_pay = round($line->basic_salary * $line->n_days,2);
                $sp = round($line->basic_salary * $line->sp,2);
                $sp_hrs = round((($line->basic_salary/8) * $line->sphol_hrs) * 0.3,2);

                $reghol = round($line->basic_salary * $line->reghol,2);
                $sphol_ot_amount =  round((($line->basic_salary/8) * $line->sphol_ot) * 1.69,2);
                
                $rd = round($line->restday_hrs * ($hr_rate * 1.3),2);
                $rd_ot = round($line->restday_ot * ($hr_rate * 1.69),2);

                $reghol_amount = $reghol + round($hr_rate * $line->reghol_hrs,2);
                $reg_ot_amount = round($line->reghol_ot * $hr_rate * 2.6,2);
                //$gross_pay = $ot_amount + $basic_pay + $line->earnings;
                
                $gross_pay = ($sp + $sp_hrs + $sphol_ot_amount) 
                    + ( $reghol + $reghol_amount + $reg_ot_amount ) 
                    + ( $rd + $rd_ot ) 
                    + $basic_pay + $line->earnings + $line->retro_pay;

                array_push($tmp_payreg,[
                    'biometric_id' => $line->biometric_id,
                    'period_id' => $line->period_id,
                    'daily_rate' =>  $line->basic_salary,
                    'days' => $line->n_days,
                    'ot' => $line->n_ot,
                    'ot_amount' => $ot_amount,
                    'basic_pay' => $basic_pay,
                    'earnings' => $line->earnings,
                    'gross_pay' => $gross_pay,
                    'deductions' => $line->deductions,
                    'retro_pay' => $line->retro_pay,
                    'restday' => $line->restday_hrs,
                    'restday_amount' => $rd,
                    'restday_ot' => $line->restday_ot,
                    'restday_ot_amount' => $rd_ot,
                    'sp' => $line->sp,
                    'sp_hrs' =>  $line->sphol_hrs,
                    'sp_amount' => $sp + $sp_hrs,
                    'sp_ot' => $line->sphol_ot,
                    'sp_ot_amount' => $sphol_ot_amount,
                    'reghol' => $line->reghol,
                    'reghol_amount' => $reghol_amount,
                    'reghol_ot' => $line->reghol_ot,
                    'reghol_hrs' => $line->reghol_hrs,
                    'reghol_ot_amount' => $reg_ot_amount,
                    'net_pay' => $gross_pay - $line->deductions

                ]);
            }
            

        }
        
        $flag = DB::table('payrollregister_unposted_weekly')->where('period_id', $period_id)->delete();
       
        if(!is_object($flag)){
            
            DB::table('payrollregister_unposted_weekly')->insertOrIgnore($tmp_payreg);
        }
        

    }

    public function showComputed($period_id)
    {
        $division = $this->model->select('division_id','div_name')->from('employees')
                    ->join('divisions','divisions.id','=','employees.division_id')
                    ->where('exit_status',1)
                    ->where('pay_type',3)
                    ->orderBy('division_id','asc')
                    ->distinct()
                    ->get();
        
        foreach($division as $idiv)
        {
            $department = $this->model->select('departments.id','dept_name')->from('employees')
                ->join('departments','departments.id','=','employees.dept_id')
                ->where('exit_status',1)
                ->where('pay_type',3)
                ->where('employees.division_id',$idiv->division_id)
                ->distinct()
                ->get();

            $idiv->dept = $department;
            
            foreach($department as $dept)
            {
                $employees = DB::select("SELECT name_vw.employee_name,m.* FROM payrollregister_unposted_weekly AS m
                INNER JOIN employee_names_vw AS name_vw ON m.biometric_id = name_vw.biometric_id
                INNER JOIN employees AS e ON m.biometric_id = e.biometric_id
                WHERE m.period_id  = $period_id AND e.division_id = $idiv->division_id AND e.dept_id = $dept->id
                ORDER BY e.lastname,e.firstname");

                $dept->employees = $employees;
            }
        }



        // $result = DB::select("SELECT name_vw.employee_name,m.* FROM payrollregister_unposted_weekly AS m
        // INNER JOIN employee_names_vw AS name_vw ON m.biometric_id = name_vw.biometric_id
        // INNER JOIN employees AS e ON m.biometric_id = e.biometric_id
        // WHERE m.period_id  = $period_id
        // ORDER BY e.lastname,e.firstname");

        return $division;
    }

    public function postPayroll($period_id)
    {
        $user = Auth::user();
        $result = $this->model->select()->from('payrollregister_unposted_weekly')
        ->where('user_id',$user->id)
        ->where('period_id',$period_id)->get()->toArray();
        $tmp_array = [];
        $comp_array = [];
        
        $compensation = $this->model->select('period_id','earnings','retro_pay','deductions','biometric_id')
                        ->from('unposted_weekly_compensation')->where('period_id',$period_id)->get()->toArray();
        
      
        foreach($result as $line){
            unset($line['line_id']);
            array_push($tmp_array,$line);
        }

        foreach($compensation as $comp)
        {
            array_push($comp_array,$comp);
        }
       
        $flag = DB::table('payrollregister_posted_weekly')->insertOrIgnore($tmp_array);

        $flag2 = DB::table('posted_weekly_compensation')->insertOrIgnore($comp_array);

        if(!is_object($flag)){
            DB::table('posting_info')->insert([
                'period_id' => $period_id,
                'trans_type' => 'weekly',
                'posted_by' => $user->id,
                'posted_on' => now(),
            ]);

            return array('success'=>'Payroll Period posted successfully.');
            
        }else{
            return array('error'=>'Error on posting.');
        }
    }

    public function getEmployeeWithDTRW($period_id,$emp_level)
    {

        $user = Auth::user();
        $result = $this->model->select(DB::raw("
                        'weekly' AS emp_level,
                        payroll_period_weekly.id AS period_id,
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
                    ->where('payroll_period_weekly.id','=',$period_id)
                    ->whereIn('pay_type',[3])
                    ->where('exit_status',1)
                    ->whereNotNull('time_in')
                    ->whereNotNull('time_out')
                    ->where('time_in','!=','00:00')
                    ->where('time_out','!=','00:00')
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

        $result = DB::table('payrollregister_unposted_weekly')->insertOrIgnore($blank);

        return $result;
    }

    public function getHolidayCounts($biometric_id,$period_id)
    {
        $qry = "SELECT holiday_type FROM holidays INNER JOIN holiday_location ON holidays.id = holiday_id
        INNER JOIN payroll_period_weekly ON holiday_date BETWEEN date_from AND date_to
        INNER JOIN employees ON employees.location_id = holiday_location.location_id
        INNER JOIN holiday_types ON holiday_types.id = holiday_type
        WHERE payroll_period_weekly.id = $period_id
        AND biometric_id = $biometric_id";

        $result = DB::select($qry);

        return $result;
    }

    public function getDeductions($bio_id,$period)
    {
        $result = $this->model->select()->from('unposted_weekly_compensation')->where('period_id',$period)->where('biometric_id',$bio_id);

        return $result->first();
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

    public function getColHeaders()
    {   
        //SELECT var_name,col_label FROM payreg_header;
        $result = $this->model->select('var_name','col_label')->from('payreg_header');
        return $result->get();

    }
    
    

    public function getEmployees($period) /* Earnings and Deductions here */
    {   
        $user = Auth::user();
        $employees = $this->model->select(DB::raw("employee_names_vw.employee_name,payrollregister_unposted_weekly.*,employees.pay_type,employees.monthly_allowance as mallowance,
        employees.daily_allowance as dallowance,IF(employees.pay_type=1,employees.basic_salary/2,employees.basic_salary) AS basicpay"))
                                ->from("payrollregister_unposted_weekly")
                                ->join("employees",'employees.biometric_id','=','payrollregister_unposted_weekly.biometric_id')
                                ->join("employee_names_vw",'employee_names_vw.biometric_id','=','payrollregister_unposted_weekly.biometric_id')
                                ->where([
                                   
                                    ['payrollregister_unposted_weekly.period_id','=',$period],
                                    ['user_id','=',$user->id],
                                   
                                ])->orderBy('employees.pay_type','DESC')->orderBy('employee_names_vw.employee_name','ASC')->get();
        foreach($employees as $employee)
        {   
            $employee->otherEarnings = $this->otherEarnings($employee->biometric_id,$period);
            $employee->gov_deductions = collect(
                [
                    'SSS Premium' => 0,
                    'SSS WISP' => 0,
                    'PhilHealt Premium' => 0,
                    'PAG IBIG Contri' => 0,
                ]
            );
        
        }
                           
        return $employees;
    }

    public function otherEarnings($biometric_id,$period_id)
    {

        $earning_array = [
            'earnings' => 0,
            'retro_pay' => 0
        ];

        $earnings = DB::table('unposted_weekly_compensation')->select('earnings','retro_pay')
        ->where('period_id','=',$period_id)
        ->where('biometric_id','=',$biometric_id)
        ->first();

        $earning_array = [
            'earnings' => ($earnings!=null) ? $earnings->earnings : 0,
            'retro_pay' => ($earnings!=null) ? $earnings->retro_pay : 0,
        ];
        
        return $earning_array;
       
    }

    public function weeklyEmployeeNoPayroll($period_id)
    {
        $empInPayroll = $this->model->select('biometric_id')->from('payrollregister_unposted_weekly')->where('period_id',$period_id);


        $result = $this->model->select('employees.biometric_id','employee_name','div_code','dept_code','job_title_name')
                                ->from('employees')
                                ->join('employee_names_vw','employees.biometric_id','=','employee_names_vw.biometric_id')
                                ->leftJoin('departments','departments.id','=','employees.dept_id')
                                ->leftJoin('divisions','divisions.id','=','employees.division_id')
                                ->leftJoin('job_titles','job_titles.id','=','employees.job_title_id')
                                ->whereNotIn('employees.biometric_id',$empInPayroll)
                                ->where('employees.exit_status',1)
                                ->where('employees.pay_type','=',3)
                                ->orderBy('employee_name','asc'); 
        return $result->get();
    }   
}

/*





SELECT DISTINCT departments.id,dept_name FROM employees 
INNER JOIN departments ON departments.id = employees.dept_id
WHERE exit_status = 1 AND pay_type = 3 AND employees.division_id = 1 
ORDER BY dept_id; 


SELECT DISTINCT division_id,div_name FROM employees 
INNER JOIN divisions ON divisions.id = employees.division_id
WHERE exit_status = 1 AND pay_type = 3 ORDER BY division_id; 


  +"biometric_id": "491"
  +"basic_salary": "435.00"
  +"n_days": "6"
  +"n_ot": "31.00"
  +"employee_name": "Barangan, Bombette"
  +"earnings": "0.00"
  +"deductions": "0.00"
  */