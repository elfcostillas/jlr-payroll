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

        $result = DB::select("SELECT m.biometric_id,m.period_id,e.basic_salary,SUM(reg_day) AS n_days,SUM(overtime_hrs) n_ot,name_vw.employee_name,IFNULL(earnings,0.00) earnings,IFNULL(deductions,0.00) deductions
        FROM manual_dtr m 
        INNER JOIN manual_dtr_details d ON m.id = d.header_id
        INNER JOIN employee_names_vw AS name_vw ON m.biometric_id = name_vw.biometric_id
        INNER JOIN employees AS e ON m.biometric_id = e.biometric_id
        LEFT JOIN unposted_weekly_compensation AS comp ON m.biometric_id = comp.biometric_id AND m.period_id = comp.period_id
        WHERE m.period_id = $period_id
        GROUP BY m.biometric_id,m.period_id,e.basic_salary,name_vw.employee_name,e.lastname,e.firstname,earnings,deductions
        ORDER BY e.lastname,e.firstname;");

        $tmp_payreg = [];

        foreach($result as $line)
        {
            $hr_rate =  ($line->basic_salary/8);
            $ot_amount = round($line->n_ot * $hr_rate ,2);
            $basic_pay = round($line->basic_salary * $line->n_days,2);
            $gross_pay = $ot_amount + $basic_pay;

            array_push($tmp_payreg,[
                'biometric_id' => $line->biometric_id,
                'period_id' => $line->period_id,
                'daily_rate' => $line->basic_salary,
                'days' => $line->n_days,
                'ot' => $line->n_ot,
                'ot_amount' => $ot_amount,
                'basic_pay' => $basic_pay,
                'earnings' => $line->earnings,
                'gross_pay' => $gross_pay,
                'deductions' => $line->deductions,
                'net_pay' => $gross_pay - $line->deductions ,
            ]);
        }
        
        $flag = DB::table('payrollregister_unposted_weekly')->where('period_id', $period_id)->delete();
        
        if($flag){
            DB::table('payrollregister_unposted_weekly')->insertOrIgnore($tmp_payreg);
        }
        

    }

    public function showComputed($period_id)
    {
        $result = DB::select("SELECT name_vw.employee_name,m.* FROM payrollregister_unposted_weekly AS m
        INNER JOIN employee_names_vw AS name_vw ON m.biometric_id = name_vw.biometric_id
        INNER JOIN employees AS e ON m.biometric_id = e.biometric_id
        WHERE m.period_id  = $period_id
        ORDER BY e.lastname,e.firstname");

        return $result;
    }
}

/*
  +"biometric_id": "491"
  +"basic_salary": "435.00"
  +"n_days": "6"
  +"n_ot": "31.00"
  +"employee_name": "Barangan, Bombette"
  +"earnings": "0.00"
  +"deductions": "0.00"
  */