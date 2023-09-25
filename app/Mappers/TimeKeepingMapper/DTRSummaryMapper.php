<?php

namespace App\Mappers\TimeKeepingMapper;

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

    public function deleteAndInsert($id)
    {
        $tmp_array = [];

        $result = DB::table('employees')
                    ->select('biometric_id')
                    ->where('exit_status',1)
                    ->whereIn('pay_type',[1,2]);

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

    }

    public function listEmployees($period_id)
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
                        ifnull(`jlr_hris`.`employees`.`lastname`, ''),
                        ', ',
                        ifnull(`jlr_hris`.`employees`.`firstname`, ''),
                        ' ',
                        ifnull(`jlr_hris`.`employees`.`suffixname`, ''),
                        ' ',
                        ifnull(`jlr_hris`.`employees`.`middlename`, '')
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
                    ->orderBy('firstname','ASC')
                    ->get();
                
                $dept->employees = $employees;
            }

            $div->depts = $departments;
        }

        return $divisions;
    }

}

/*
division_id

dept_id
*/