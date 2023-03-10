<?php

namespace App\Mappers\Reports;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TardinessReportMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\Location';
    protected $rules = [
    	
    ];

    public function summary($filter)
    {

        //SELECT id,div_name FROM divisions;

        $divisions = $this->model->select('id','div_name' )->from('divisions');

        if($filter['div_id']!=0)
        {
            $divisions->where('id',$filter['div_id']);
        }else{

        }

        $div = $divisions->get();

        foreach($div as $d){
             $result = $this->model->select(DB::raw("employees.biometric_id,employee_name,COUNT(dtr_date) late_count,SUM((TIME_TO_SEC(edtr.time_in)- TIME_TO_SEC(work_schedules.time_in))/60) AS in_minutes"))
                ->from('edtr')
                ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                ->join('work_schedules','schedule_id','=','work_schedules.id')
                ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr.biometric_id')
                ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
                //->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)');
                ->where('emp_level','>',2) 
                ->where('job_title_id','!=',12)
                ->whereRaw('(
                    (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) && TIME_TO_SEC(edtr.time_in) <= TIME_TO_SEC(work_schedules.out_am)) OR
                    (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) && TIME_TO_SEC(work_schedules.time_in) <= TIME_TO_SEC(work_schedules.time_out) )
                    )')
                ->where('division_id',$d->id);
                 
            if($filter['dept_id']!=0)
            {
                $result->where('dept_id',$filter['dept_id']);
            }

            $d->emp = $result->groupBy(DB::raw("employees.biometric_id,lastname,firstname"))
            ->orderBy('late_count','desc')->get();
        }

        return $div;


        // $result = $this->model->select(DB::raw("employees.biometric_id,employee_name,COUNT(dtr_date) late_count"))
        // ->from('edtr')
        // ->join('employees','edtr.biometric_id','=','employees.biometric_id')
        // ->join('work_schedules','schedule_id','=','work_schedules.id')
        // ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr.biometric_id')
        // ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
        // //->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)');
        // ->whereRaw('(
        //     (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) && TIME_TO_SEC(edtr.time_in) <= TIME_TO_SEC(work_schedules.out_am)) OR
        //     (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm))
        //     )');

        // if($filter['div_id']!=0)
        // {
        //     $result->where('division_id',$filter['div_id']);
        // }

        // if($filter['dept_id']!=0)
        // {
        //     $result->where('dept_id',$filter['dept_id']);
        // }

        // $result = $result->groupBy(DB::raw("employees.biometric_id,lastname,firstname"))
        // ->orderBy('lastname','asc');

       
        // return $result->get();

    }

    public function detailed($filter)
    {
        /*
        $result = $this->model->select(DB::raw("employees.biometric_id,employee_name,edtr.time_in"))
        ->from('edtr')
        ->join('employees','edtr.biometric_id','=','employees.biometric_id')
        ->join('work_schedules','schedule_id','=','work_schedules.id')
        ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr.biometric_id')
        ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
        ->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)');

        if($filter['div_id']!=0)
        {
            $result->where('division_id',$filter['div_id']);
        }

        if($filter['dept_id']!=0)
        {
            $result->where('dept_id',$filter['dept_id']);
        }

        $result = $result->orderBy('lastname','asc');

       
        return $result->get();
        */

        $result = $this->model->select(DB::raw("employees.biometric_id,employee_name,COUNT(dtr_date) late_count"))
        ->from('edtr')
        ->join('employees','edtr.biometric_id','=','employees.biometric_id')
        ->join('work_schedules','schedule_id','=','work_schedules.id')
        ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr.biometric_id')
        ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
        //->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)')
        ->where('emp_level','>',2) 
        ->where('job_title_id','!=',12)
        ->whereRaw('(
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) && TIME_TO_SEC(edtr.time_in) <= TIME_TO_SEC(work_schedules.out_am)) OR
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) && TIME_TO_SEC(work_schedules.time_in) <= TIME_TO_SEC(work_schedules.time_out) )
            )')
        ->groupBy(DB::raw("employees.biometric_id,lastname,firstname"));

        if($filter['div_id']!=0)
        {
            $result->where('division_id',$filter['div_id']);
        }

        if($filter['dept_id']!=0)
        {
            $result->where('dept_id',$filter['dept_id']);
        }

        $result = $result->orderBy('lastname','asc');

        $emps = $result->get();
        
        foreach($emps as $e){
            $lates = $this->model->select(DB::raw("dtr_date, edtr.time_in,ROUND((TIME_TO_SEC(edtr.time_in) - TIME_TO_SEC(work_schedules.time_in))/60,0) AS in_minutes"))
                    ->from('edtr')
                    ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
                    ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
                    ///->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)')
                    ->whereRaw('(
                        (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) && TIME_TO_SEC(edtr.time_in) <= TIME_TO_SEC(work_schedules.out_am)) OR
                        (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm))
                        )')
                    ->where('biometric_id',$e->biometric_id)
                    ->get();

            $e->late_punch = $lates;
        }

        return $emps;

    }

    
}

/*

 $qry = "SELECT dtr_date, edtr.time_in,ROUND((TIME_TO_SEC(edtr.time_in) - TIME_TO_SEC(work_schedules.time_in))/60,0) AS in_minutes  
 FROM edtr LEFT JOIN `work_schedules` ON `schedule_id` = `work_schedules`.`id` 
            WHERE `dtr_date` BETWEEN '2023-02-01' AND '2023-02-28' AND `biometric_id` = 490
            AND TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)";


SELECT employees.biometric_id,lastname,firstname,COUNT(dtr_date) late_count FROM edtr 
INNER JOIN employees ON edtr.biometric_id = employees.biometric_id
INNER JOIN work_schedules ON schedule_id = work_schedules.id
WHERE employees.dept_id = 11 
AND TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)
AND dtr_date BETWEEN '2023-02-01' AND '2023-02-28' 
GROUP BY employees.biometric_id,lastname,firstname
ORDER BY lastname,dtr_date
;

SELECT employees.biometric_id,employee_name,COUNT(dtr_date) late_count FROM edtr 
INNER JOIN employees ON edtr.biometric_id = employees.biometric_id
INNER JOIN work_schedules ON schedule_id = work_schedules.id
INNER JOIN employee_names_vw ON employee_names_vw.biometric_id = edtr.biometric_id
WHERE employees.dept_id = 11 
AND TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)
AND dtr_date BETWEEN '2023-02-01' AND '2023-02-28' 
GROUP BY employees.biometric_id,lastname,firstname
ORDER BY lastname,dtr_date


SELECT employees.biometric_id,employee_name,COUNT(dtr_date) late_count FROM edtr 
INNER JOIN employees ON edtr.biometric_id = employees.biometric_id
INNER JOIN work_schedules ON schedule_id = work_schedules.id
INNER JOIN employee_names_vw ON employee_names_vw.biometric_id = edtr.biometric_id
WHERE employees.dept_id = 11 
AND (
	(TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) && TIME_TO_SEC(edtr.time_in) <= TIME_TO_SEC(work_schedules.out_am)) OR
	(TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm))
    )
AND dtr_date BETWEEN '2023-02-01' AND '2023-02-28' 
GROUP BY employees.biometric_id,lastname,firstname
ORDER BY lastname,dtr_date;

*/