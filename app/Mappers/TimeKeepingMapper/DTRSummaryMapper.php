<?php

namespace App\Mappers\TimeKeepingMapper;

use App\CustomClass\EmployeeDTR;
use App\CustomClass\EmployeeDTR2;
use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DTRSummaryMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\DTRSummary';
    protected $rules = [
      
    ];

    public function periodList()
    {
        $result = DB::table('payroll_period')
                    ->select(DB::raw("id,CONCAT(DATE_FORMAT(date_from,'%m / %d / %Y'),' - ',DATE_FORMAT(date_to,'%m / %d / %Y')) AS period_range"))
                    ->orderBy('id','DESC');

        return $result->get();
    }

    public function deleteAndInsert($id,$emp_level)
    {
        $tmp_array = [];

        

        $result = DB::table('employees')
                    ->select('biometric_id')
                    ->where('exit_status',1)
                    ->whereIn('pay_type',[1,2]);

        if($emp_level=='non-confi'){
            $result = $result->where('emp_level','>=',5);
        }
        else{
            $result = $result->where('emp_level','<',5);
        }

        $flag = DB::table('edtr_totals')
                    ->where('period_id',$id)
                    ->whereNotIn('biometric_id',$result)
                    ->delete();
        
        $dtrTotals = DB::table('edtr_totals')
                        ->select('biometric_id')
                        ->where('period_id',$id);

        $forInsert = DB::table('employees')
                    ->select('biometric_id')
                    ->where('exit_status',1)
                    ->whereNotIn('biometric_id',$dtrTotals)
                    ->whereIn('pay_type',[1,2])
                    ->get();

        foreach($forInsert as $emp){
            array_push($tmp_array,['period_id'=> $id,'biometric_id' => $emp->biometric_id]);
        }

        DB::table('edtr_totals')->insertOrIgnore($tmp_array);

        return $tmp_array;

    }

    public function listEmployees($period_id,$emp_level)
    {   

        //select id,div_name from divisions;
        $divisions = DB::table('divisions')->select('id','div_name')->get();

        foreach($divisions as $div)
        {
            //select * from departments where dept_div_id;
            $departments =  DB::table('departments')->select('id','dept_name')->where('dept_div_id','=',$div->id)->get();

            foreach($departments as $dept)
            {
                $employees = DB::table('employees')->select(DB::raw("trim(
                        concat(
                        ifnull(`employees`.`lastname`, ''),
                        ', ',
                        ifnull(`employees`.`firstname`, ''),
                        ' ',
                        ifnull(`employees`.`suffixname`, ''),
                        ' ',
                        ifnull(`employees`.`middlename`, '')
                        )
                    ) AS `employee_name`,
                    edtr_totals.*"))
                    ->leftJoin('edtr_totals','edtr_totals.biometric_id','=','employees.biometric_id')
                    ->where('exit_status',1)
                    ->whereIn('pay_type',[1,2])
                    ->where('division_id','=',$div->id)
                    ->where('dept_id','=',$dept->id)
                    ->where('period_id',$period_id)
                    ->orderBy('lastname','ASC')
                    ->orderBy('firstname','ASC');

                    if($emp_level=='non-confi'){
                        $employees = $employees->where('emp_level','>=',5)->get();
                    }
                    else{
                        $employees = $employees->where('emp_level','<',5)->get();
                    }
                
                $dept->employees = $employees;
            }

            $div->depts = $departments;
        }

        return $divisions;
    }

    public function employeesToProcess($period_id)
    {
        $query = "select DISTINCT employees.biometric_id from edtr 
                    inner join payroll_period on edtr.dtr_date between payroll_period.date_from and payroll_period.date_to
                    inner join employees on employees.biometric_id = edtr.biometric_id
                    where payroll_period.id = $period_id and pay_type in (1,2)
                    and employees.emp_level >= 5
                    and ((time_in is not null and time_in != '' and time_in != '00:00') or  (time_out is not null and time_out != '' and time_out != '00:00'))
                    and employees.biometric_id != 0";
                    // and employees.biometric_id = 830";
    
        $ids = DB::select(DB::raw($query));

        return $ids;
    }

    public function employeesToProcessConfi($period_id)
    {   
        /*
        $query = "select DISTINCT employees.biometric_id from edtr 
                    inner join payroll_period on edtr.dtr_date between payroll_period.date_from and payroll_period.date_to
                    inner join employees on employees.biometric_id = edtr.biometric_id
                    where payroll_period.id = $period_id and pay_type in (1,2)
                    and employees.emp_level < 5
                    and ((time_in is not null and time_in != '' and time_in != '00:00') and (time_out is not null and time_out != '' and time_out != '00:00'))
                    and employees.biometric_id != 0";
        */

        // $query = "select DISTINCT employees.biometric_id from edtr_totals
        // inner join employees on employees.biometric_id = edtr_totals.biometric_id
        // inner join payroll_period on edtr_totals.period_id = payroll_period.id
        // where payroll_period.id = $period_id and pay_type in (1,2)
        // and employees.emp_level < 5
        // and employees.biometric_id != 0;";
        
        $query = "SELECT DISTINCT employees.biometric_id FROM employees WHERE exit_status = 1 AND emp_level < 5";


                    // and employees.biometric_id = 830";
    
        $ids = DB::select(DB::raw($query));

        return $ids;
    }

    public function processIDS($ids,$period_id)
    {
        $ctr = 0; 

        foreach($ids as $id)
        {
            //dd($id->biometric_id);
            $edtr = new EmployeeDTR($id,$period_id);
            $ctr++;

        }

        return $ctr;
    }

    public function processConfiIDS($ids,$period_id)
    {
        $ctr = 0; 

        foreach($ids as $id)
        {
            //dd($id->biometric_id);
            $edtr = new EmployeeDTR($id,$period_id);
            $ctr++;

        }

        return $ctr;
    }

    public function processConfiIDSV2($ids,$period_id)
    {
        $ctr = 0; 

        foreach($ids as $id)
        {
            //dd($id->biometric_id);
            $edtr = new EmployeeDTR2($id,$period_id);
            $ctr++;

        }

        return $ctr;
    }

}

/*
division_id

dept_id
*/