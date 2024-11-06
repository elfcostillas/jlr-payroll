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
        $result = $this->model->select()->from('payrollregister_unposted_weekly')->where('period_id',$period_id)->get()->toArray();
        $tmp_array = [];
        $comp_array = [];
        
        $compensation = $this->model->select('period_id','earnings','retro_pay','deductions','biometric_id','cash_advance','office_account')
                        ->from('unposted_weekly_compensation')->where('period_id',$period_id)->get()->toArray();
        
      
       
        foreach($result as $line){
            unset($line['line_id']);
            echo var_dump($line);
            array_push($tmp_array,$line);
        }

        foreach($compensation as $comp)
        {
            array_push($comp_array,$comp);
        }
       
        $flag = DB::table('payrollregister_posted_weekly')->insertOrIgnore($tmp_array);

        $flag2 = DB::table('posted_weekly_compensation')->insertOrIgnore($comp_array);

        if(!is_object($flag)){
            return array('success'=>'Payroll Period posted successfully.');
        }else{
            return array('error'=>'Error on posting.');
        }
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