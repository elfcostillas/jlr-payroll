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
        $qa = $this->model->select(DB::raw("101 AS id,'Quality Assurance' div_name"));

        $divisions = $this->model->select('id','div_name' )->from('divisions')->union($qa);

        if($filter['div_id']!=0)
        {
            $divisions->where('id',$filter['div_id']);
        }else{

        }
        /*
            SELECT biometric_id,leave_date,leave_type FROM leave_request_header INNER JOIN leave_request_detail ON header_id = header_id 
            WHERE document_status = 'POSTED' AND acknowledge_status ='Approved' AND is_canceled = 'N' 
        */
        /*
        select holiday_date,location_id,holiday_type from holidays inner join holiday_location on holidays.id = holiday_location.holiday_id
        holiday_location on holidays.id = holiday_location.holiday_id
        */

        $holidays = DB::table('holidays')->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                        ->select(DB::raw("holiday_date,location_id,holiday_type"));

        $leaves = $this->model->select('biometric_id','leave_date','leave_type')
                                ->from('leave_request_header')
                                ->join('leave_request_detail','header_id','=','header_id')
                                ->where('document_status','=','POSTED')
                                ->where('acknowledge_status','=','Approved')
                                ->where('is_canceled','=','N')
                                ->whereBetween('leave_date',[$filter['from'],$filter['to']]);

        $div = $divisions->get();

        foreach($div as $d){

            if($d->id != 101){
                $result = $this->model->select(DB::raw("employees.biometric_id,employee_name,COUNT(dtr_date) late_count,SUM((TIME_TO_SEC(edtr.time_in)- TIME_TO_SEC(work_schedules.time_in))/60) AS in_minutes"))
                ->from('edtr')
                ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                ->join('work_schedules','schedule_id','=','work_schedules.id')
                ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr.biometric_id')
                // ->leftJoinSub($leaves,'leaves',function($join){
                //         $join->on('leaves.biometric_id', '=', 'edtr.biometric_id');
                //         $join->on('leaves.leave_date', '=', 'edtr.dtr_date');
                // })
                ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
                //->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)');
                ->where('emp_level','>',2) 
                ->where('pay_type','!=',3) 
                ->where('job_title_id','!=',12)
                ->where('employees.dept_id','!=',5)
                ->leftJoinSub($holidays,'holidays',function($join){
                    $join->on('holidays.location_id','=','employees.location_id');
                    $join->on('edtr.dtr_date','=','holidays.holiday_date');
                })
                // ->whereNull('leave_type')
                ->whereNull('holiday_type')
                ->whereRaw('(
                    (
                    TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)
                    AND TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am) - 900
                    )
                    OR (
                    TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm)
                    AND TIME_TO_SEC(work_schedules.time_in) < TIME_TO_SEC(work_schedules.time_out)
                    )
                )')
                ->where('division_id',$d->id);
            } else {
                $result = $this->model->select(DB::raw("employees.biometric_id,employee_name,COUNT(dtr_date) late_count,SUM((TIME_TO_SEC(edtr.time_in)- TIME_TO_SEC(work_schedules.time_in))/60) AS in_minutes"))
                ->from('edtr')
                ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                ->join('work_schedules','schedule_id','=','work_schedules.id')
                ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr.biometric_id')
                // ->leftJoinSub($leaves,'leaves',function($join){
                //     $join->on('leaves.biometric_id', '=', 'edtr.biometric_id');
                //     $join->on('leaves.leave_date', '=', 'edtr.dtr_date');
                // })
                ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
                //->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)');
                ->where('pay_type','!=',3)
                ->where('emp_level','>',2) 
                ->where('job_title_id','!=',12)
                ->where('employees.dept_id','=',5)
                ->leftJoinSub($holidays,'holidays',function($join){
                    $join->on('holidays.location_id','=','employees.location_id');
                    $join->on('edtr.dtr_date','=','holidays.holiday_date');
                })
                // ->whereNull('leave_type')
                ->whereNull('holiday_type')
                // ->whereNull('leave_type')
                ->whereRaw('(
                    (
                    TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)
                    AND TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am) - 900
                    )
                    OR (
                    TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm)
                    AND TIME_TO_SEC(work_schedules.time_in) < TIME_TO_SEC(work_schedules.time_out)
                    )
                )')
                ->where('division_id',2);
            }
                 
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

    public function summarySG($filter)
    {

        //SELECT id,div_name FROM divisions;
        $qa = $this->model->select(DB::raw("101 AS id,'Quality Assurance' div_name"));

        $divisions = $this->model->select('id','div_name' )->from('divisions')->union($qa);

        if($filter['div_id']!=0)
        {
            $divisions->where('id',$filter['div_id']);
        }else{

        }
        /*
            SELECT biometric_id,leave_date,leave_type FROM leave_request_header INNER JOIN leave_request_detail ON header_id = header_id 
            WHERE document_status = 'POSTED' AND acknowledge_status ='Approved' AND is_canceled = 'N' 
        */
        /*
        select holiday_date,location_id,holiday_type from holidays inner join holiday_location on holidays.id = holiday_location.holiday_id
        holiday_location on holidays.id = holiday_location.holiday_id
        */

        $holidays = DB::table('holidays')->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                        ->select(DB::raw("holiday_date,location_id,holiday_type"));

        $leaves = $this->model->select('biometric_id','leave_date','leave_type')
                                ->from('leave_request_header')
                                ->join('leave_request_detail','header_id','=','header_id')
                                ->where('document_status','=','POSTED')
                                ->where('acknowledge_status','=','Approved')
                                ->where('is_canceled','=','N')
                                ->whereBetween('leave_date',[$filter['from'],$filter['to']]);

        $div = $divisions->get();

        foreach($div as $d){

            if($d->id != 101){
                $result = $this->model->select(DB::raw("employees.biometric_id,employee_name,COUNT(dtr_date) late_count,SUM((TIME_TO_SEC(edtr.time_in)- TIME_TO_SEC(work_schedules.time_in))/60) AS in_minutes"))
                ->from('edtr')
                ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                ->join('work_schedules','schedule_id','=','work_schedules.id')
                ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr.biometric_id')
                // ->leftJoinSub($leaves,'leaves',function($join){
                //         $join->on('leaves.biometric_id', '=', 'edtr.biometric_id');
                //         $join->on('leaves.leave_date', '=', 'edtr.dtr_date');
                // })
                ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
                //->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)');
                ->where('emp_level','>',5) 
                // ->where('pay_type','!=',3) 
                ->where('job_title_id','!=',12)
                ->where('employees.dept_id','!=',5)
                ->leftJoinSub($holidays,'holidays',function($join){
                    $join->on('holidays.location_id','=','employees.location_id');
                    $join->on('edtr.dtr_date','=','holidays.holiday_date');
                })
                // ->whereNull('leave_type')
                ->whereNull('holiday_type')
                ->whereRaw('(
                    (
                    TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)
                    AND TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am) - 900
                    )
                    OR (
                    TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm)
                    AND TIME_TO_SEC(work_schedules.time_in) < TIME_TO_SEC(work_schedules.time_out)
                    )
                )')
                ->where('division_id',$d->id);
            } else {
                $result = $this->model->select(DB::raw("employees.biometric_id,employee_name,COUNT(dtr_date) late_count,SUM((TIME_TO_SEC(edtr.time_in)- TIME_TO_SEC(work_schedules.time_in))/60) AS in_minutes"))
                ->from('edtr')
                ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                ->join('work_schedules','schedule_id','=','work_schedules.id')
                ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr.biometric_id')
                // ->leftJoinSub($leaves,'leaves',function($join){
                //     $join->on('leaves.biometric_id', '=', 'edtr.biometric_id');
                //     $join->on('leaves.leave_date', '=', 'edtr.dtr_date');
                // })
                ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
                //->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)');
                // ->where('pay_type','!=',3)
                ->where('emp_level','>',5) 
                ->where('job_title_id','!=',12)
                ->where('employees.dept_id','=',5)
                ->leftJoinSub($holidays,'holidays',function($join){
                    $join->on('holidays.location_id','=','employees.location_id');
                    $join->on('edtr.dtr_date','=','holidays.holiday_date');
                })
                // ->whereNull('leave_type')
                ->whereNull('holiday_type')
                // ->whereNull('leave_type')
                ->whereRaw('(
                    (
                    TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)
                    AND TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am) - 900
                    )
                    OR (
                    TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm)
                    AND TIME_TO_SEC(work_schedules.time_in) < TIME_TO_SEC(work_schedules.time_out)
                    )
                )')
                ->where('division_id',2);
            }
                 
            if($filter['dept_id']!=0)
            {
                $result->where('dept_id',$filter['dept_id']);
            }
           
            $d->emp = $result->groupBy(DB::raw("employees.biometric_id,lastname,firstname"))
            ->orderBy('late_count','desc')->get();
        }

        return $div;

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

        $holidays = DB::table('holidays')->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
        ->select(DB::raw("holiday_date,location_id,holiday_type"));

        $leaves = $this->model->select('biometric_id','leave_date','leave_type')
        ->from('leave_request_header')
        ->join('leave_request_detail','header_id','=','header_id')
        ->where('document_status','=','POSTED')
        ->where('acknowledge_status','=','Approved')
        ->where('is_canceled','=','N')
        ->whereBetween('leave_date',[$filter['from'],$filter['to']]);

        $result = $this->model->select(DB::raw("employees.biometric_id,employee_name,COUNT(dtr_date) late_count"))
        ->from('edtr')
        ->join('employees','edtr.biometric_id','=','employees.biometric_id')
        ->join('work_schedules','schedule_id','=','work_schedules.id')
        ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr.biometric_id')
        // ->leftJoinSub($leaves,'leaves',function($join){
        //     $join->on('leaves.biometric_id', '=', 'edtr.biometric_id');
        //     $join->on('leaves.leave_date', '=', 'edtr.dtr_date');
        // })
        ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
        //->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)')
        ->where('emp_level','>',2) 
        ->where('pay_type','!=',3) 
        ->where('job_title_id','!=',12)
        
        // ->whereNull('leave_type')
        ->whereRaw('(
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)AND TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am)-900) OR
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) AND TIME_TO_SEC(work_schedules.time_in) < TIME_TO_SEC(work_schedules.time_out) )
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
                    ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                    ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
                    ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
                    ///->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)')
                    ->whereRaw('(
                        (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) AND TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am)-900) OR
                        (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) AND TIME_TO_SEC(work_schedules.time_in) < TIME_TO_SEC(work_schedules.time_out) )
                        )')
                        ->leftJoinSub($holidays,'holidays',function($join){
                            $join->on('holidays.location_id','=','employees.location_id');
                            $join->on('edtr.dtr_date','=','holidays.holiday_date');
                        })
                       
                        ->whereNull('holiday_type')
                    ->where('employees.biometric_id',$e->biometric_id)
                    ->get();

            $e->late_punch = $lates;
        }

        return $emps;

    }

    public function detailedSG($filter)
    {

        $holidays = DB::table('holidays')->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
        ->select(DB::raw("holiday_date,location_id,holiday_type"));

        $leaves = $this->model->select('biometric_id','leave_date','leave_type')
        ->from('leave_request_header')
        ->join('leave_request_detail','header_id','=','header_id')
        ->where('document_status','=','POSTED')
        ->where('acknowledge_status','=','Approved')
        ->where('is_canceled','=','N')
        ->whereBetween('leave_date',[$filter['from'],$filter['to']]);

        $result = $this->model->select(DB::raw("employees.biometric_id,employee_name,COUNT(dtr_date) late_count"))
        ->from('edtr')
        ->join('employees','edtr.biometric_id','=','employees.biometric_id')
        ->join('work_schedules','schedule_id','=','work_schedules.id')
        ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr.biometric_id')
        // ->leftJoinSub($leaves,'leaves',function($join){
        //     $join->on('leaves.biometric_id', '=', 'edtr.biometric_id');
        //     $join->on('leaves.leave_date', '=', 'edtr.dtr_date');
        // })
        ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
        //->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)')
        ->where('emp_level','>',5) 
        // ->where('pay_type','!=',3) 
        ->where('job_title_id','!=',12)
        
        // ->whereNull('leave_type')
        ->whereRaw('(
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)AND TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am)-900) OR
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) AND TIME_TO_SEC(work_schedules.time_in) < TIME_TO_SEC(work_schedules.time_out) )
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
                    ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                    ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
                    ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
                    ///->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)')
                    ->whereRaw('(
                        (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) AND TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am)-900) OR
                        (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) AND TIME_TO_SEC(work_schedules.time_in) < TIME_TO_SEC(work_schedules.time_out) )
                        )')
                        ->leftJoinSub($holidays,'holidays',function($join){
                            $join->on('holidays.location_id','=','employees.location_id');
                            $join->on('edtr.dtr_date','=','holidays.holiday_date');
                        })
                       
                        ->whereNull('holiday_type')
                    ->where('employees.biometric_id',$e->biometric_id)
                    ->get();

            $e->late_punch = $lates;
        }

        return $emps;

    }

    public function getEmployees($year){
        $emp = $this->model->select(DB::raw("biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,'')) AS emp_name"))->from('employees')
                ->where('pay_type','!=',3)
                ->orderBy('lastname','asc')
                ->orderBy('firstname','asc');

        return $emp->get();
    }

    public function buildData($month,$emp,$year)
    {
        $arr = [];
        /*
        $qry = "SELECT biometric_id,MONTH(dtr_date) as m,COUNT(edtr.id) as c FROM edtr 
        LEFT JOIN work_schedules ON schedule_id = work_schedules.id
        left
        WHERE (
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) AND TIME_TO_SEC(edtr.time_in) <= TIME_TO_SEC(work_schedules.out_am))
        OR 
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) AND TIME_TO_SEC(edtr.time_in) <= TIME_TO_SEC(work_schedules.time_out))
            )
        AND 
            YEAR(dtr_date) = $year
        GROUP BY biometric_id,MONTH(dtr_date)";
        */

        $qry = "SELECT edtr.biometric_id,MONTH(dtr_date) AS m,COUNT(edtr.id) AS c FROM edtr 
        LEFT JOIN work_schedules ON schedule_id = work_schedules.id
        LEFT JOIN (
        SELECT biometric_id,leave_date,leave_type FROM leave_request_header INNER JOIN leave_request_detail ON header_id = header_id 
        WHERE document_status = 'POSTED' AND acknowledge_status ='Approved' AND is_canceled = 'N' AND YEAR(leave_date) = $year ) AS l ON edtr.biometric_id = l.biometric_id AND edtr.dtr_date = l.leave_date
        WHERE (
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) AND TIME_TO_SEC(edtr.time_in) <= TIME_TO_SEC(work_schedules.out_am)-900)
        OR 
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) AND TIME_TO_SEC(edtr.time_in) <= TIME_TO_SEC(work_schedules.time_out))
            )
        AND 
            YEAR(dtr_date) = $year
           AND l.leave_type IS NULL
        GROUP BY edtr.biometric_id,MONTH(dtr_date)";

        $data = DB::select($qry);

        foreach($emp as $e)
        {
            foreach($month as $mkey => $mvalue)
            {
                $arr[$e->biometric_id][$mkey] = 0;
            }
        }

        //SELECT biometric_id,tardy_count FROM manual_tardy;

        foreach($data as $log){
            if($year==2023){
                if($log->m!=1){
                    $arr[$log->biometric_id][$log->m] = $log->c;
                }

            }else {
                $arr[$log->biometric_id][$log->m] = $log->c;
            }
        }

        if($year==2023)
        {
            $manual =  DB::select("SELECT biometric_id,tardy_count FROM manual_tardy;");

            foreach($manual as $mtardy)
            {
                $arr[$mtardy->biometric_id][1] = $mtardy->tardy_count;
            }
        }

        return $arr;

    }

    
}

/*

SELECT biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,'')) AS emp_name 
FROM employees WHERE pay_type != 3 ORDER BY lastname,firstname


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